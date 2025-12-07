<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\CentreExamen;
use App\Models\JourPratique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    /**
     * Liste des sessions disponibles
     */
    public function index()
    {
        Log::info("Sessions d'examen demandées");
        try {
            $sessions = Session::where('statut', 'ouvert')
                ->orderBy('date_debut', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'sessions' => $sessions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Liste des centres d'examen
     */
    public function centresExamen()
    {
        Log::info("Centres d'examen demandés");
        try {
            $centres = CentreExamen::where('actif', true)
                ->orderBy('nom')
                ->get();

            return response()->json([
                'success' => true,
                'centres' => $centres
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Liste des jours de pratique disponibles
     */
    public function joursPratique()
    {
        Log::info("Jours de pratique demandés");
        try {
            $jours = JourPratique::get();

            return response()->json([
                'success' => true,
                'jours' => $jours
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
