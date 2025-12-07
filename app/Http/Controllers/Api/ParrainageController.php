<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ParrainageService;
use Illuminate\Http\Request;

class ParrainageController extends Controller
{
    private $parrainageService;

    public function __construct(ParrainageService $parrainageService)
    {
        $this->parrainageService = $parrainageService;
    }

    /**
     * Récupérer l'arbre de parrainage de l'utilisateur
     */
    public function monArbre(Request $request)
    {
        try {
            $user = $request->user();
            $arbre = $this->parrainageService->getArbreParrainage($user->id);

            return response()->json([
                'success' => true,
                'code_parrainage' => $user->code_parrainage,
                'niveau' => $arbre['niveau'],
                'bonus' => $arbre['bonus'],
                'filleuls' => $arbre['filleuls']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques de parrainage
     */
    public function statistiques(Request $request)
{
    try {
        $user = $request->user();
        $stats = $this->parrainageService->getStatistiquesParrainage($user->id);

        return response()->json([
            'success' => true,
            'niveau_actuel' => $stats['niveau_actuel'], // ← Correction ici
            'nombre_filleuls_directs' => $stats['nombre_filleuls_directs'],
            'nombre_total_filleuls' => $stats['nombre_total_filleuls'],
            'bonus' => $stats['bonus'], // Déjà correct
            'prochaine_etape' => $stats['prochaine_etape']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Liste des filleuls
     */
    public function mesFilleuls(Request $request)
    {
        try {
            $user = $request->user();
            $filleuls = $this->parrainageService->getFilleuls($user->id);

            return response()->json([
                'success' => true,
                'filleuls' => $filleuls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
