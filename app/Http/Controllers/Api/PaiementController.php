<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaiementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\AutoEcolePaiement;
use App\Models\AutoEcoleUser;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    private $paiementService;

    public function __construct(PaiementService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    /**
     * Initier un paiement en ligne
     */
    public function initierPaiement(Request $request)
    {
        Log::info("Initiation de paiement demandée", $request->all());

        $validator = Validator::make($request->all(), [
            'tranche' => 'required|in:x,y,complet',
            'methode_paiement' => 'required|in:orange_money,mtn_money,card',
            'telephone' => 'required_if:methode_paiement,orange_money,mtn_money|regex:/^(237)?6[0-9]{8}$/'
        ], [
            'telephone.required_if' => 'Le numéro de téléphone est requis pour le paiement mobile',
            'telephone.regex' => 'Format de numéro invalide. Le numéro doit commencer par 6 et contenir 9 chiffres (ex: 6XXXXXXXX)'
        ]);

        if ($validator->fails()) {
            Log::warning("Validation échouée lors de l'initiation de paiement", [
                'errors' => $validator->errors()->all(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Normaliser le numéro de téléphone
        $telephone = $request->telephone;
        // Retirer tous les caractères non numériques
        $telephone = preg_replace('/[^0-9]/', '', $telephone);
        
        // Si commence par 237, le retirer temporairement
        if (substr($telephone, 0, 3) === '237') {
            $telephone = substr($telephone, 3);
        }
        
        // Vérifier que c'est bien 9 chiffres commençant par 6
        if (strlen($telephone) !== 9 || substr($telephone, 0, 1) !== '6') {
            return response()->json([
                'success' => false,
                'message' => 'Format de numéro invalide. Le numéro doit commencer par 6 et contenir 9 chiffres'
            ], 422);
        }
        
        // Ajouter le préfixe 237
        $telephone = '237' . $telephone;

        Log::info("Numéro de téléphone normalisé", ['telephone' => $telephone]);

        try {
            $result = $this->paiementService->initierPaiementEnLigne(
                $request->user()->id,
                $request->tranche,
                $request->methode_paiement,
                $telephone
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'initiation du paiement", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'initiation du paiement'
            ], 500);
        }
    }

    /**
     * Payer avec un code caisse
     */
    public function payerAvecCodeCaisse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paiementService->traiterPaiementCodeCaisse(
            $request->code,
            $request->user()->id
        );

        return response()->json($result);
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function verifierStatut(Request $request, $transactionId)
    {
        Log::info("Vérification statut transaction", ['transaction_id' => $transactionId]);

        $result = $this->paiementService->verifierStatutPaiement($transactionId);

        return response()->json($result);
    }

    /**
     * Historique des paiements
     */
    public function historiquePaiements(Request $request)
    {
        $paiements = $request->user()->paiements()->latest()->get();

        return response()->json([
            'success' => true,
            'paiements' => $paiements
        ]);
    }

    /**
     * Callback de succès MoneyFusion (return_url)
     * L'utilisateur est redirigé ici après un paiement réussi
     */
    public function moneyFusionSuccess(Request $request)
    {
        Log::info('MoneyFusion Success Callback', $request->all());

        $token = $request->input('token');
        $transactionId = $request->input('transaction_id');

        if ($token) {
            // Récupérer le paiement via le token
            $paiement = AutoEcolePaiement::where('notes', 'LIKE', "%Token MoneyFusion: {$token}%")->first();
        } elseif ($transactionId) {
            $paiement = AutoEcolePaiement::where('transaction_id', $transactionId)->first();
        } else {
            $paiement = null;
        }

        if ($paiement) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement en cours de traitement',
                'transaction_id' => $paiement->transaction_id,
                'statut' => $paiement->statut,
                'montant' => $paiement->montant,
                'tranche' => $paiement->tranche
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaction non trouvée'
        ], 404);
    }

    /**
     * Webhook MoneyFusion - Traitement automatique des notifications
     * URL: https://ange-raphael.supahuman.site/api/paiement/moneyfusion/webhook
     */
    public function moneyFusionWebhook(Request $request)
    {
        try {
            Log::info('MoneyFusion Webhook reçu', [
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Récupérer les données du webhook
            $event = $request->input('event');
            $tokenPay = $request->input('tokenPay');
            $personalInfo = $request->input('personal_Info', []);
            $montant = $request->input('Montant');
            $numeroTransaction = $request->input('numeroTransaction');
            $statut = $request->input('statut');

            // Extraire les informations personnalisées
            $transactionId = null;
            $userId = null;
            $tranche = null;

            if (!empty($personalInfo) && is_array($personalInfo)) {
                $info = $personalInfo[0];
                $transactionId = $info['transactionId'] ?? null;
                $userId = $info['userId'] ?? null;
                $tranche = $info['tranche'] ?? null;
            }

            Log::info('Données extraites du webhook', [
                'event' => $event,
                'tokenPay' => $tokenPay,
                'transactionId' => $transactionId,
                'userId' => $userId,
                'statut' => $statut
            ]);

            // Récupérer le paiement
            $paiement = null;
            if ($transactionId) {
                $paiement = AutoEcolePaiement::where('transaction_id', $transactionId)->first();
            }

            if (!$paiement && $tokenPay) {
                $paiement = AutoEcolePaiement::where('notes', 'LIKE', "%Token MoneyFusion: {$tokenPay}%")->first();
            }

            if (!$paiement) {
                Log::error('Paiement introuvable dans le webhook', [
                    'transactionId' => $transactionId,
                    'tokenPay' => $tokenPay
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Transaction introuvable'
                ], 404);
            }

            // Traiter selon l'événement
            if ($event === 'payin.session.completed' || $statut === 'paid') {
                
                // Éviter les doublons - vérifier si déjà traité
                if ($paiement->statut === 'valide') {
                    Log::info('Paiement déjà validé - webhook ignoré', [
                        'transaction_id' => $paiement->transaction_id
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Paiement déjà traité'
                    ], 200);
                }

                DB::beginTransaction();
                try {
                    $user = AutoEcoleUser::find($paiement->user_id);

                    if (!$user) {
                        Log::error('Utilisateur introuvable', ['user_id' => $paiement->user_id]);
                        return response()->json(['success' => false], 404);
                    }

                    // Mettre à jour le paiement
                    $paiement->update([
                        'statut' => 'valide',
                        'notes' => $paiement->notes . ' | Webhook: ' . $event . ' | Ref: ' . $numeroTransaction
                    ]);

                    // Traiter le paiement (débloquer cours, etc.)
                    $this->traiterPaiementReussi($paiement, $user);

                    DB::commit();

                    Log::info('Paiement MoneyFusion validé via webhook', [
                        'transaction_id' => $paiement->transaction_id,
                        'user_id' => $user->id,
                        'event' => $event
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Paiement traité avec succès'
                    ], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Erreur traitement paiement webhook: ' . $e->getMessage());
                    throw $e;
                }
            }
            elseif ($event === 'payin.session.cancelled' || $event === 'payin.session.failed') {
                
                $paiement->update([
                    'statut' => 'echoue',
                    'notes' => $paiement->notes . ' | Webhook: ' . $event . ' | Raison: Annulé ou échoué'
                ]);

                Log::warning('Paiement MoneyFusion échoué via webhook', [
                    'transaction_id' => $paiement->transaction_id,
                    'event' => $event
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement échoué enregistré'
                ], 200);
            }
            elseif ($event === 'payin.session.pending') {
                
                Log::info('Paiement en attente - notification pending', [
                    'transaction_id' => $paiement->transaction_id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement en attente'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook reçu'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur webhook MoneyFusion: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur'
            ], 500);
        }
    }

    /**
     * Traite un paiement réussi
     */
    private function traiterPaiementReussi($paiement, $user)
    {
        $tranche = $paiement->tranche;

        if ($tranche === 'x') {
            $user->update([
                'paiement_x_effectue' => true,
                'paiement_x_date' => now()
            ]);

            if ($user->verifierDispenseY()) {
                $user->update(['dispense_y' => true]);
            }

            if ($user->validated) {
                $user->debloquerCours();
            }

        } elseif ($tranche === 'y') {
            $user->update([
                'paiement_y_effectue' => true,
                'paiement_y_date' => now(),
                'cours_verrouilles' => false
            ]);
            
        } elseif ($tranche === 'complet') {
            $user->update([
                'paiement_x_effectue' => true,
                'paiement_x_date' => now(),
                'paiement_y_effectue' => true,
                'paiement_y_date' => now(),
                'cours_verrouilles' => false
            ]);

            if ($user->validated) {
                $user->debloquerCours();
            }
        }

        Log::info('Paiement traité avec succès', [
            'user_id' => $user->id,
            'tranche' => $tranche
        ]);
    }
}