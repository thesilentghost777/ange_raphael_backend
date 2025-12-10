<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConfigPaiement;
use Illuminate\Http\Request;

class ConfigPaiementController extends Controller
{
    public function index()
    {
        $config = ConfigPaiement::first();

        if (!$config) {
            // Fallback to defaults from your schema
            $config = (object) [
                'montant_x' => 30000,
                'montant_y' => 30000,
                'montant_z' => 15000,
                'delai_paiement_y' => 60,
            ];
            // Optionally log or create the row: ConfigPaiement::create((array) $config);
            return response()->json(['success' => true, 'config' => $config]);
        }

        return response()->json(['success' => true, 'config' => $config]);
    }
}