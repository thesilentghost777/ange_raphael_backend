<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AutoEcoleUser;
use App\Services\ParrainageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AutoEcoleAuthController extends Controller
{
    private $parrainageService;

    public function __construct(ParrainageService $parrainageService)
    {
        $this->parrainageService = $parrainageService;
    }

    public function inscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:auto_ecole_users,email',
            'telephone' => 'required|string|unique:auto_ecole_users,telephone',
            'password' => 'required|string|min:6',
            'type_permis' => 'required|in:permis_a,permis_b',
            'code_parrainage' => 'nullable|string',
            'session_id' => 'required|exists:sessions1,id',
            'centre_examen_id' => 'required|exists:centres_examen,id',
            'jours_pratique' => 'required|array'
        ]);
        Log::info("données d'inscription reçues: " . json_encode($request->all()));
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = AutoEcoleUser::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'password' => Hash::make($request->password),
                'type_permis' => $request->type_permis,
                'code_parrainage' => (new AutoEcoleUser)->genererCodeParrainage(),
                'session_id' => $request->session_id,
                'centre_examen_id' => $request->centre_examen_id,
                'date_inscription' => now()
            ]);

            // Attribuer parrain
            $codeParrainage = $request->code_parrainage ?? 'AR-cfpam-2025';
            $this->parrainageService->attribuerParrain($user, $codeParrainage);

            // Attribuer jours pratique
            foreach ($request->jours_pratique as $jourId) {
                \App\Models\UserJourPratique::create([
                    'user_id' => $user->id,
                    'jour_pratique_id' => $jourId
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function connexion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = AutoEcoleUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function profil(Request $request)
    {
        return response()->json(['success' => true, 'user' => $request->user()]);
    }
}
