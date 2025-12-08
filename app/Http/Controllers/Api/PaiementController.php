<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaiementSimulateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    private $paiementService;

    public function __construct(PaiementSimulateurService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    public function initierPaiement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranche' => 'required|in:x,y,complet',
            'methode_paiement' => 'required|in:orange_money,mtn_money,card',
            'telephone' => 'required_if:methode_paiement,orange_money,mtn_money'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->paiementService->initierPaiementEnLigne(
            $request->user()->id,
            $request->tranche,
            $request->methode_paiement,
            $request->telephone
        );

        return response()->json($result);
    }

    public function payerAvecCodeCaisse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->paiementService->traiterPaiementCodeCaisse(
            $request->code,
            $request->user()->id
        );

        return response()->json($result);
    }

    public function verifierStatut(Request $request, $transactionId)
    {
        Log::info("verifierStatut appelé pour la transaction");
        $result = $this->paiementService->verifierStatutPaiement($transactionId);
        return response()->json($result);
    }

    public function historiquePaiements(Request $request)
    {
        $paiements = $request->user()->paiements()->latest()->get();
        return response()->json(['success' => true, 'paiements' => $paiements]);
    }

    public function monetbilSuccess(Request $request)
    {
        // Récupérer les paramètres de retour de Monetbil
        $transactionId = $request->input('item_ref');
        $status = $request->input('status');
        $paymentRef = $request->input('payment_ref');

        Log::info('Monetbil Success Callback', [
            'transaction_id' => $transactionId,
            'status' => $status,
            'payment_ref' => $paymentRef,
            'all_params' => $request->all()
        ]);

        // Récupérer les détails du paiement
        $paiement = AutoEcolePaiement::where('transaction_id', $transactionId)->first();

        return response()->json([
            'success' => true,
            'message' => 'Paiement effectué avec succès',
            'transaction_id' => $transactionId,
            'payment_ref' => $paymentRef,
            'status' => 'valide',
            'montant' => $paiement->montant ?? null,
            'tranche' => $paiement->tranche ?? null
        ]);
    }

    /**
     * Callback d'échec Monetbil
     * URL: https://votredomaine.com/paiement/monetbil/failed
     */
    public function monetbilFailed(Request $request)
    {
        $transactionId = $request->input('item_ref');
        $status = $request->input('status');

        Log::warning('Monetbil Failed Callback', [
            'transaction_id' => $transactionId,
            'status' => $status,
            'all_params' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Le paiement a échoué',
            'transaction_id' => $transactionId,
            'status' => 'echoue'
        ]);
    }

    /**
     * Notification de Monetbil (Webhook)
     * URL: https://votredomaine.com/paiement/monetbil/notification
     * Cette route traite le paiement automatiquement
     */
    public function monetbilNotification(Request $request)
    {
        try {
            Log::info('Monetbil Notification reçue', $request->all());

            // Récupérer les données de Monetbil
            $transactionId = $request->input('item_ref');
            $status = $request->input('status'); // 'success' ou 'failed'
            $paymentRef = $request->input('payment_ref');
            $amount = $request->input('amount');
            $userId = $request->input('user');

            // Vérifier si la transaction existe
            $paiement = AutoEcolePaiement::where('transaction_id', $transactionId)->first();

            if (!$paiement) {
                Log::error('Transaction introuvable', ['transaction_id' => $transactionId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction introuvable'
                ], 404);
            }

            // Si le paiement est réussi
            if ($status === 'success' || $status === '1') {
                
                $user = AutoEcoleUser::find($paiement->user_id);
                
                if (!$user) {
                    Log::error('Utilisateur introuvable', ['user_id' => $paiement->user_id]);
                    return response()->json(['success' => false], 404);
                }

                // Mettre à jour le statut du paiement
                $paiement->update([
                    'statut' => 'valide',
                    'notes' => 'Paiement Monetbil - Ref: ' . $paymentRef
                ]);

                // Traiter le paiement (débloquer cours, etc.)
                $this->traiterPaiementReussi($paiement, $user);

                Log::info('Paiement Monetbil validé', [
                    'transaction_id' => $transactionId,
                    'user_id' => $user->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement traité avec succès'
                ], 200);
            } 
            
            // Si le paiement a échoué
            else {
                $paiement->update([
                    'statut' => 'echoue',
                    'notes' => 'Échec Monetbil - Status: ' . $status
                ]);

                Log::warning('Paiement Monetbil échoué', [
                    'transaction_id' => $transactionId,
                    'status' => $status
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Paiement échoué'
                ], 200);
            }

        } catch (\Exception $e) {
            Log::error('Erreur notification Monetbil: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur'
            ], 500);
        }
    }

}
