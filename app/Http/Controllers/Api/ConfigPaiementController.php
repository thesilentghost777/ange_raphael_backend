<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfigPaiement;
use Illuminate\Http\JsonResponse;

class ConfigPaiementController extends Controller
{
    /**
     * Récupérer la configuration de paiement pour les clients
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $config = ConfigPaiement::getConfig();
            
            // Retourner uniquement les données nécessaires pour le client
            return response()->json([
                'success' => true,
                'data' => [
                    'montant_x' => $config->montant_x,
                    'montant_y' => $config->montant_y,
                    'delai_paiement_y' => $config->delai_paiement_y,
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}