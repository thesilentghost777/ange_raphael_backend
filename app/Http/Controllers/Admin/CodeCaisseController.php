<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeCaisse;
use App\Models\AutoEcoleUser;
use App\Models\ConfigPaiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CodeCaisseController extends Controller
{
    public function index(Request $request)
    {
        $query = CodeCaisse::with(['user', 'generateurUser']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('nom', 'LIKE', "%{$search}%")
                         ->orWhere('prenoms', 'LIKE', "%{$search}%")
                         ->orWhere('numero_telephone', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('utilise')) {
            $query->where('utilise', $request->utilise);
        }

        if ($request->filled('tranche')) {
            $query->where('tranche', $request->tranche);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
        }

        $codes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => CodeCaisse::count(),
            'utilises' => CodeCaisse::where('utilise', true)->count(),
            'non_utilises' => CodeCaisse::where('utilise', false)->count(),
            'expires' => CodeCaisse::where('date_expiration', '<', now())
                ->where('utilise', false)
                ->count(),
            'montant_total' => CodeCaisse::where('utilise', true)->sum('montant'),
            'complets' => CodeCaisse::where('tranche', 'complet')->count()
        ];

        return view('admin.auto-ecole.codes-caisse.index', compact('codes', 'stats'));
    }

    public function create()
    {
        $users = AutoEcoleUser::orderBy('nom')
            ->get();
        
        $config = ConfigPaiement::first();
        
        return view('admin.auto-ecole.codes-caisse.create', compact('users', 'config'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:auto_ecole_users,id',
            'tranche' => 'required|in:x,y,complet',
            'montant' => 'required|numeric|min:0',
            'date_expiration' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $code = CodeCaisse::create([
            'code' => CodeCaisse::genererCode(),
            'user_id' => $request->user_id,
            'montant' => $request->montant,
            'tranche' => $request->tranche,
            'date_expiration' => $request->date_expiration,
            'genere_par' => Auth::id()
        ]);

        return redirect()->route('admin.auto-ecole.codes-caisse.show', $code->id)
            ->with('success', 'Code caisse généré avec succès');
    }

    public function show($id)
    {
        $code = CodeCaisse::with(['user', 'generateurUser'])->findOrFail($id);
        return view('admin.auto-ecole.codes-caisse.show', compact('code'));
    }

    public function genererMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tranche' => 'required|in:x,y,complet',
            'montant' => 'required|numeric|min:0',
            'quantite' => 'required|integer|min:1|max:100',
            'date_expiration' => 'nullable|date|after:today'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $codesGeneres = [];

        for ($i = 0; $i < $request->quantite; $i++) {
            $codesGeneres[] = CodeCaisse::create([
                'code' => CodeCaisse::genererCode(),
                'montant' => $request->montant,
                'tranche' => $request->tranche,
                'date_expiration' => $request->date_expiration,
                'genere_par' => Auth::id()
            ]);
        }

        return redirect()->route('admin.auto-ecole.codes-caisse.index')
            ->with('success', count($codesGeneres) . ' codes générés avec succès');
    }

    public function destroy($id)
    {
        $code = CodeCaisse::findOrFail($id);

        if ($code->utilise) {
            return redirect()->back()->with('error', 'Impossible de supprimer un code déjà utilisé');
        }

        $code->delete();

        return redirect()->route('admin.auto-ecole.codes-caisse.index')
            ->with('success', 'Code supprimé avec succès');
    }

    public function exportNonUtilises()
    {
        $codes = CodeCaisse::where('utilise', false)
            ->where(function($q) {
                $q->whereNull('date_expiration')
                  ->orWhere('date_expiration', '>', now());
            })
            ->get();

        $csv = "Code,Montant,Tranche,Date Expiration,Date Création\n";

        foreach ($codes as $code) {
            $trancheLabel = $code->tranche === 'complet' ? 'COMPLET' : strtoupper($code->tranche);
            
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $code->code,
                $code->montant,
                $trancheLabel,
                $code->date_expiration ? $code->date_expiration->format('Y-m-d') : 'N/A',
                $code->created_at->format('Y-m-d H:i')
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="codes_caisse_' . now()->format('Y-m-d') . '.csv"');
    }
}