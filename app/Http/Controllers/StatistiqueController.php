<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutoEcoleUser;
use App\Models\AutoEcolePaiement;
use App\Models\Session;
use App\Models\Module;
use App\Models\ProgressionLecon;
use App\Models\ResultatQuiz;
use App\Models\CodeCaisse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_utilisateurs' => AutoEcoleUser::count(),
            'utilisateurs_actifs' => AutoEcoleUser::where('validated', true)->count(),
            'utilisateurs_en_attente' => AutoEcoleUser::where('validated', false)->count(),
            'utilisateurs_ce_mois' => AutoEcoleUser::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Statistiques par type de permis
        $permisStats = AutoEcoleUser::select('type_permis', DB::raw('count(*) as total'))
            ->groupBy('type_permis')
            ->get();

        // Statistiques de paiement
        $paiementStats = [
            'montant_total' => AutoEcolePaiement::where('statut', 'valide')->sum('montant'),
            'montant_ce_mois' => AutoEcolePaiement::where('statut', 'valide')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('montant'),
            'paiements_en_attente' => AutoEcolePaiement::where('statut', 'en_attente')->count(),
            'paiement_x_effectues' => AutoEcoleUser::where('paiement_x_effectue', true)->count(),
            'paiement_y_effectues' => AutoEcoleUser::where('paiement_y_effectue', true)->count(),
        ];

        // Répartition des paiements par tranche
        $paiementsParTranche = AutoEcolePaiement::select('tranche', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->where('statut', 'valide')
            ->groupBy('tranche')
            ->get();

        // Statistiques de parrainage
        $parrainageStats = [
            'total_parrains' => AutoEcoleUser::whereHas('filleuls')->count(),
            'total_filleuls' => AutoEcoleUser::whereNotNull('parrain_id')->count(),
            'parrainages_ce_mois' => AutoEcoleUser::whereNotNull('parrain_id')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
        ];

        // Top 5 parrains
        $topParrains = AutoEcoleUser::withCount('filleuls')
            ->having('filleuls_count', '>', 0)
            ->orderBy('filleuls_count', 'desc')
            ->take(5)
            ->get();

        // Statistiques de progression
        $progressionStats = [
            'total_lecons' => ProgressionLecon::count(),
            'lecons_completees' => ProgressionLecon::where('completee', true)->count(),
            'taux_completion' => ProgressionLecon::count() > 0 
                ? round((ProgressionLecon::where('completee', true)->count() / ProgressionLecon::count()) * 100, 2) 
                : 0,
        ];

        // Statistiques de quiz
        $quizStats = [
            'total_tentatives' => ResultatQuiz::count(),
            'quiz_reussis' => ResultatQuiz::where('reussi', true)->count(),
            'taux_reussite' => ResultatQuiz::count() > 0 
                ? round((ResultatQuiz::where('reussi', true)->count() / ResultatQuiz::count()) * 100, 2) 
                : 0,
            'score_moyen' => round(ResultatQuiz::avg('score'), 2),
        ];

        // Statistiques des sessions
        $sessionStats = [
            'sessions_ouvertes' => Session::where('statut', 'ouvert')->count(),
            'sessions_fermees' => Session::where('statut', 'ferme')->count(),
            'prochaine_session' => Session::where('statut', 'ouvert')
                ->where('date_examen', '>', Carbon::now())
                ->orderBy('date_examen', 'asc')
                ->first(),
        ];

        // Codes caisse
        $codeCaisseStats = [
            'codes_generes' => CodeCaisse::count(),
            'codes_utilises' => CodeCaisse::where('utilise', true)->count(),
            'codes_en_attente' => CodeCaisse::where('utilise', false)->count(),
            'montant_codes_utilises' => CodeCaisse::where('utilise', true)->sum('montant'),
        ];

        // Évolution des inscriptions (12 derniers mois)
        $inscriptionsParMois = AutoEcoleUser::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get();

        // Évolution des paiements (12 derniers mois)
        $paiementsParMois = AutoEcolePaiement::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('sum(montant) as montant_total')
            )
            ->where('statut', 'valide')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get();

        // Statistiques par méthode de paiement
        $paiementsParMethode = AutoEcolePaiement::select('methode_paiement', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->where('statut', 'valide')
            ->whereNotNull('methode_paiement')
            ->groupBy('methode_paiement')
            ->get();

        return view('statistiques.dashboard', compact(
            'stats',
            'permisStats',
            'paiementStats',
            'paiementsParTranche',
            'parrainageStats',
            'topParrains',
            'progressionStats',
            'quizStats',
            'sessionStats',
            'codeCaisseStats',
            'inscriptionsParMois',
            'paiementsParMois',
            'paiementsParMethode'
        ));
    }

    // Méthode pour exporter les statistiques en PDF (optionnel)
    public function exportPdf()
    {
        // À implémenter avec une librairie comme DomPDF
    }

    // Méthode pour exporter les statistiques en Excel (optionnel)
    public function exportExcel()
    {
        // À implémenter avec une librairie comme Maatwebsite Excel
    }
}