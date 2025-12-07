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
}
