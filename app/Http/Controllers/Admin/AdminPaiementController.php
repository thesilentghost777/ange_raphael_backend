<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoEcolePaiement;
use App\Models\AutoEcoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaiementController extends Controller
{
    /**
     * Affiche la liste des paiements
     */
    public function index(Request $request)
    {
        $query = AutoEcolePaiement::with('user');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        if ($request->filled('tranche')) {
            $query->where('tranche', $request->tranche);
        }

        if ($request->filled('methode_paiement')) {
            $query->where('methode_paiement', $request->methode_paiement);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);

        return view('admin.paiements.index', compact('paiements'));
    }

    /**
     * Affiche les détails d'un paiement
     */
    public function show($id)
    {
        $paiement = AutoEcolePaiement::with('user')->findOrFail($id);
        
        // Historique des paiements de l'utilisateur
        $historiquePaiements = AutoEcolePaiement::where('user_id', $paiement->user_id)
            ->where('id', '!=', $id)
            ->orderBy('date_paiement', 'desc')
            ->limit(10)
            ->get();

        return view('admin.paiements.show', compact('paiement', 'historiquePaiements'));
    }

    /**
     * Affiche les statistiques des paiements
     */
    public function statistiques(Request $request)
    {
        // Période par défaut : ce mois-ci
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));

        // Statistiques globales
        $statsGlobales = [
            'total_paiements' => AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])->count(),
            'montant_total' => AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                ->where('statut', 'valide')
                ->sum('montant'),
            'paiements_valides' => AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                ->where('statut', 'valide')
                ->count(),
            'paiements_en_attente' => AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                ->where('statut', 'en_attente')
                ->count(),
            'paiements_echoues' => AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
                ->where('statut', 'echoue')
                ->count(),
        ];

        // Répartition par statut
        $parStatut = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->select('statut', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->groupBy('statut')
            ->get();

        // Répartition par type de paiement
        $parTypePaiement = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->select('type_paiement', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->groupBy('type_paiement')
            ->get();

        // Répartition par tranche
        $parTranche = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->select('tranche', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->groupBy('tranche')
            ->get();

        // Répartition par méthode de paiement
        $parMethodePaiement = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->whereNotNull('methode_paiement')
            ->select('methode_paiement', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->groupBy('methode_paiement')
            ->get();

        // Évolution des paiements par jour
        $evolutionParJour = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->select(
                DB::raw('DATE(date_paiement) as date'),
                DB::raw('count(*) as total'),
                DB::raw('sum(montant) as montant_total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 10 des utilisateurs par montant payé
        $topUtilisateurs = AutoEcolePaiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->where('statut', 'valide')
            ->select('user_id', DB::raw('sum(montant) as montant_total'), DB::raw('count(*) as nb_paiements'))
            ->groupBy('user_id')
            ->orderBy('montant_total', 'desc')
            ->limit(10)
            ->with('user')
            ->get();

        return view('admin.paiements.statistiques', compact(
            'statsGlobales',
            'parStatut',
            'parTypePaiement',
            'parTranche',
            'parMethodePaiement',
            'evolutionParJour',
            'topUtilisateurs',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * Exporte les paiements en CSV
     */
    public function export(Request $request)
    {
        $query = AutoEcolePaiement::with('user');

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        if ($request->filled('tranche')) {
            $query->where('tranche', $request->tranche);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->get();

        $filename = 'paiements_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($paiements) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Transaction ID',
                'Utilisateur',
                'Email',
                'Montant',
                'Type Paiement',
                'Tranche',
                'Méthode',
                'Statut',
                'Date Paiement',
                'Notes'
            ]);

            // Données
            foreach ($paiements as $paiement) {
                fputcsv($file, [
                    $paiement->id,
                    $paiement->transaction_id,
                    $paiement->user->name ?? 'N/A',
                    $paiement->user->email ?? 'N/A',
                    $paiement->montant,
                    $paiement->type_paiement,
                    $paiement->tranche,
                    $paiement->methode_paiement ?? 'N/A',
                    $paiement->statut,
                    $paiement->date_paiement->format('Y-m-d H:i:s'),
                    $paiement->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}