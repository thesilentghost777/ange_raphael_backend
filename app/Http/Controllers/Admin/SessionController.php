<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\JourPratique;
use App\Models\CentreExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    // ==================== GESTION DES SESSIONS ====================
    
    public function index(Request $request)
    {
        $query = Session::withCount('users');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('annee')) {
            $query->whereYear('date_debut', $request->annee);
        }

        $sessions = $query->orderBy('date_debut', 'desc')->paginate(15);

        return view('admin.auto-ecole.sessions.index', compact('sessions'));
    }

    public function show($id)
    {
        $session = Session::with(['users' => function($q) {
            $q->orderBy('nom');
        }])->findOrFail($id);

        // Statistiques de la session
        $stats = [
            'total_inscrits' => $session->users->count(),
            'valides' => $session->users->where('valide', true)->count(),
            'paiement_x_complet' => $session->users->where('paiement_x', true)->count(),
            'paiement_y_complet' => $session->users->where('paiement_y', true)->count(),
            'permis_a' => $session->users->where('type_permis', 'permis_a')->count(),
            'permis_b' => $session->users->where('type_permis', 'permis_b')->count(),
        ];

        return view('admin.auto-ecole.sessions.show', compact('session', 'stats'));
    }

    public function create()
    {
        return view('admin.auto-ecole.sessions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_examen' => 'nullable|date|after:date_debut',
            'statut' => 'required|in:ouvert,ferme,en_cours,termine',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $session = Session::create($request->all());

        return redirect()->route('admin.auto-ecole.sessions.show', $session->id)
            ->with('success', 'Session créée avec succès');
    }

    public function edit($id)
    {
        $session = Session::findOrFail($id);
        return view('admin.auto-ecole.sessions.edit', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_examen' => 'nullable|date|after:date_debut',
            'statut' => 'required|in:ouvert,ferme,en_cours,termine',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $session->update($request->all());

        return redirect()->route('admin.auto-ecole.sessions.show', $session->id)
            ->with('success', 'Session mise à jour avec succès');
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);

        if ($session->users()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer une session avec des utilisateurs inscrits');
        }

        $session->delete();

        return redirect()->route('admin.auto-ecole.sessions.index')
            ->with('success', 'Session supprimée avec succès');
    }

    public function changerStatut(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:ouvert,ferme,en_cours,termine'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $session->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut modifié avec succès');
    }

    // ==================== GESTION DES JOURS DE PRATIQUE ====================
    
    public function joursPratiqueIndex()
    {
        $jours = JourPratique::orderBy('jour')
            ->orderBy('heure')
            ->paginate(15);

        return view('admin.auto-ecole.jours-pratique.index', compact('jours'));
    }

    public function joursPratiqueCreate()
    {
        return view('admin.auto-ecole.jours-pratique.create');
    }

    public function joursPratiqueStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure' => 'required|date_format:H:i',
            'zone' => 'required|string|max:255',
            'actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        JourPratique::create($request->all());

        return redirect()->route('admin.auto-ecole.jours-pratique.index')
            ->with('success', 'Jour de pratique créé avec succès');
    }

    public function joursPratiqueEdit($id)
    {
        $jour = JourPratique::findOrFail($id);
        return view('admin.auto-ecole.jours-pratique.edit', compact('jour'));
    }

    public function joursPratiqueUpdate(Request $request, $id)
    {
        $jour = JourPratique::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jour' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'heure' => 'required|date_format:H:i',
            'zone' => 'required|string|max:255',
            'actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jour->update($request->all());

        return redirect()->route('admin.auto-ecole.jours-pratique.index')
            ->with('success', 'Jour de pratique mis à jour avec succès');
    }

    public function joursPratiqueDestroy($id)
    {
        $jour = JourPratique::findOrFail($id);

        if ($jour->userJours()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un jour de pratique avec des utilisateurs inscrits');
        }

        $jour->delete();

        return redirect()->route('admin.auto-ecole.jours-pratique.index')
            ->with('success', 'Jour de pratique supprimé avec succès');
    }

    public function joursPratiqueToggleActif($id)
    {
        $jour = JourPratique::findOrFail($id);
        $jour->update(['actif' => !$jour->actif]);

        $message = $jour->actif ? 'Jour de pratique activé' : 'Jour de pratique désactivé';
        return redirect()->back()->with('success', $message);
    }

    // ==================== GESTION DES CENTRES D'EXAMEN ====================
    
    public function centresExamenIndex()
    {
        $centres = CentreExamen::withCount('users')
            ->orderBy('ville')
            ->orderBy('nom')
            ->paginate(15);

        return view('admin.auto-ecole.centres-examen.index', compact('centres'));
    }

    public function centresExamenShow($id)
    {
        $centre = CentreExamen::with(['users' => function($q) {
            $q->orderBy('nom');
        }])->findOrFail($id);

        $stats = [
            'total_inscrits' => $centre->users->count(),
            'par_ville' => $centre->users->groupBy('ville')->map->count()
        ];

        return view('admin.auto-ecole.centres-examen.show', compact('centre', 'stats'));
    }

    public function centresExamenCreate()
    {
        return view('admin.auto-ecole.centres-examen.create');
    }

    public function centresExamenStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $centre = CentreExamen::create($request->all());

        return redirect()->route('admin.auto-ecole.centres-examen.show', $centre->id)
            ->with('success', 'Centre d\'examen créé avec succès');
    }

    public function centresExamenEdit($id)
    {
        $centre = CentreExamen::findOrFail($id);
        return view('admin.auto-ecole.centres-examen.edit', compact('centre'));
    }

    public function centresExamenUpdate(Request $request, $id)
    {
        $centre = CentreExamen::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'actif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $centre->update($request->all());

        return redirect()->route('admin.auto-ecole.centres-examen.show', $centre->id)
            ->with('success', 'Centre d\'examen mis à jour avec succès');
    }

    public function centresExamenDestroy($id)
    {
        $centre = CentreExamen::findOrFail($id);

        if ($centre->users()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un centre d\'examen avec des utilisateurs inscrits');
        }

        $centre->delete();

        return redirect()->route('admin.auto-ecole.centres-examen.index')
            ->with('success', 'Centre d\'examen supprimé avec succès');
    }

    public function centresExamenToggleActif($id)
    {
        $centre = CentreExamen::findOrFail($id);
        $centre->update(['actif' => !$centre->actif]);

        $message = $centre->actif ? 'Centre d\'examen activé' : 'Centre d\'examen désactivé';
        return redirect()->back()->with('success', $message);
    }
}