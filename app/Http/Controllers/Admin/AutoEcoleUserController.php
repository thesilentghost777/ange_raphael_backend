<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoEcoleUser;
use App\Models\Session;
use App\Models\CentreExamen;
use App\Services\ParrainageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AutoEcoleUserController extends Controller
{
    private $parrainageService;

    public function __construct(ParrainageService $parrainageService)
    {
        $this->parrainageService = $parrainageService;
    }

    public function index(Request $request)
    {
        $query = AutoEcoleUser::with(['session', 'centreExamen', 'parrain']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenoms', 'LIKE', "%{$search}%")
                  ->orWhere('numero_telephone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type_permis')) {
            $query->where('type_permis', $request->type_permis);
        }

        if ($request->filled('valide')) {
            $query->where('valide', $request->valide);
        }

        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        if ($request->filled('paiement_x')) {
            $query->where('paiement_x', $request->paiement_x);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $sessions = Session::orderBy('date_debut', 'desc')->get();

        return view('admin.auto-ecole.users.index', compact('users', 'sessions'));
    }

    public function show($id)
    {
        $user = AutoEcoleUser::with([
            'session', 
            'centreExamen', 
            'parrain', 
            'filleuls',
            'paiements',
            'progressionLecons',
            'resultatsQuiz'
        ])->findOrFail($id);

        $arbreParrainage = $this->parrainageService->getArbreParrainage($id);
        $statistiquesParrainage = $this->parrainageService->getStatistiquesParrainage($id);

        return view('admin.auto-ecole.users.show', compact('user', 'arbreParrainage', 'statistiquesParrainage'));
    }

    public function create()
    {
        $sessions = Session::ouvertes()->get();
        $centresExamen = CentreExamen::actifs()->get();

        return view('admin.auto-ecole.users.create', compact('sessions', 'centresExamen'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:20|unique:auto_ecole_users',
            'email' => 'nullable|email|unique:auto_ecole_users',
            'age' => 'required|integer|min:18',
            'localisation' => 'required|string|max:255',
            'type_permis' => 'required|in:permis_a,permis_b',
            'session_id' => 'nullable|exists:sessions,id',
            'centre_examen_id' => 'nullable|exists:centres_examen,id',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = AutoEcoleUser::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'numero_telephone' => $request->numero_telephone,
            'email' => $request->email,
            'age' => $request->age,
            'localisation' => $request->localisation,
            'type_permis' => $request->type_permis,
            'session_id' => $request->session_id,
            'centre_examen_id' => $request->centre_examen_id,
            'password' => Hash::make($request->password),
            'valide' => true,
            'date_validation' => now()
        ]);

        $user->genererCodeParrainage();

        return redirect()->route('admin.auto-ecole.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    public function edit($id)
    {
        $user = AutoEcoleUser::findOrFail($id);
        $sessions = Session::orderBy('date_debut', 'desc')->get();
        $centresExamen = CentreExamen::actifs()->get();

        return view('admin.auto-ecole.users.edit', compact('user', 'sessions', 'centresExamen'));
    }

    public function update(Request $request, $id)
    {
        $user = AutoEcoleUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:20|unique:auto_ecole_users,numero_telephone,' . $id,
            'email' => 'nullable|email|unique:auto_ecole_users,email,' . $id,
            'age' => 'required|integer|min:18',
            'localisation' => 'required|string|max:255',
            'type_permis' => 'required|in:permis_a,permis_b',
            'session_id' => 'nullable|exists:sessions,id',
            'centre_examen_id' => 'nullable|exists:centres_examen,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update($request->except(['password']));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.auto-ecole.users.show', $user->id)
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy($id)
    {
        $user = AutoEcoleUser::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.auto-ecole.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    public function valider($id)
    {
        $user = AutoEcoleUser::findOrFail($id);
        $user->update([
            'valide' => true,
            'date_validation' => now()
        ]);

        // Débloquer cours si paiement X effectué
        if ($user->paiement_x) {
            $user->debloquerCours();
        }

        return redirect()->back()->with('success', 'Utilisateur validé avec succès');
    }

    public function invalider($id)
    {
        $user = AutoEcoleUser::findOrFail($id);
        $user->update([
            'valide' => false,
            'date_validation' => null,
            'cours_debloques' => false
        ]);

        return redirect()->back()->with('success', 'Validation révoquée avec succès');
    }

    public function toggleActif($id)
    {
        $user = AutoEcoleUser::findOrFail($id);
        $user->update(['actif' => !$user->actif]);

        return redirect()->back()->with('success', 'Statut modifié avec succès');
    }
}
