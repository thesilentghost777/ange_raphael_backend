@extends('layouts.admin')

@section('title', 'Tableau de Bord - Statistiques')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord - Statistiques</h1>
        <p class="text-gray-600 mt-1">Aperçu complet des performances de la plateforme</p>
    </div>

    <!-- Statistiques Générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Utilisateurs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_utilisateurs']) }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Utilisateurs Actifs -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Utilisateurs Actifs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['utilisateurs_actifs']) }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Attente Validation -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">En Attente Validation</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['utilisateurs_en_attente']) }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inscriptions ce mois -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-indigo-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inscriptions ce mois</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($stats['utilisateurs_ce_mois']) }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <svg class="w-8 h-8 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Financières -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenus Total -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($paiementStats['montant_total']) }} FCFA</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenus ce mois -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenus ce mois</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($paiementStats['montant_ce_mois']) }} FCFA</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Paiements en Attente -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Paiements en Attente</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($paiementStats['paiements_en_attente']) }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des types de permis -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition par Type de Permis</h3>
            </div>
            <div class="h-80">
                <canvas id="permisChart"></canvas>
            </div>
        </div>

        <!-- Graphique des paiements par tranche -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Paiements par Tranche</h3>
            </div>
            <div class="h-80">
                <canvas id="paiementTrancheChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques de Parrainage -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Top 5 Parrains</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filleuls</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($topParrains as $index => $parrain)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $parrain->nom }} {{ $parrain->prenom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $parrain->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $parrain->filleuls_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $parrain->code_parrainage }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Aucun parrain pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Statistiques Parrainage</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Total Parrains</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($parrainageStats['total_parrains']) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Filleuls</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($parrainageStats['total_filleuls']) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Parrainages ce mois</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($parrainageStats['parrainages_ce_mois']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de Progression et Quiz -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Progression des Cours -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Progression des Cours</h3>
            <div class="mb-4">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Taux de Complétion</span>
                    <span class="text-sm font-bold text-gray-900">{{ $progressionStats['taux_completion'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progressionStats['taux_completion'] }}%"></div>
                </div>
            </div>
            <p class="text-sm text-gray-600">
                <span class="font-semibold">Leçons complétées:</span> 
                {{ number_format($progressionStats['lecons_completees']) }} / {{ number_format($progressionStats['total_lecons']) }}
            </p>
        </div>

        <!-- Résultats des Quiz -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Résultats des Quiz</h3>
            <div class="mb-4">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Taux de Réussite</span>
                    <span class="text-sm font-bold text-gray-900">{{ $quizStats['taux_reussite'] }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $quizStats['taux_reussite'] }}%"></div>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Quiz réussis:</span> 
                    {{ number_format($quizStats['quiz_reussis']) }} / {{ number_format($quizStats['total_tentatives']) }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">Score moyen:</span> 
                    <span class="font-bold">{{ $quizStats['score_moyen'] }}%</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Évolution des Inscriptions (12 derniers mois)</h3>
        </div>
        <div class="h-96">
            <canvas id="inscriptionsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Graphique des types de permis
const permisData = {
    labels: [@foreach($permisStats as $stat) '{{ ucfirst($stat->type_permis) }}', @endforeach],
    datasets: [{
        data: [@foreach($permisStats as $stat) {{ $stat->total }}, @endforeach],
        backgroundColor: ['#3B82F6', '#10B981', '#6366F1'],
        borderWidth: 1,
    }]
};

new Chart(document.getElementById('permisChart'), {
    type: 'pie',
    data: permisData,
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
});

// Graphique des paiements par tranche
const paiementTrancheData = {
    labels: [@foreach($paiementsParTranche as $paiement) '{{ ucfirst($paiement->tranche) }}', @endforeach],
    datasets: [{
        label: 'Montant (FCFA)',
        data: [@foreach($paiementsParTranche as $paiement) {{ $paiement->montant_total }}, @endforeach],
        backgroundColor: ['#3B82F6', '#10B981', '#6366F1'],
        borderWidth: 1,
    }]
};

new Chart(document.getElementById('paiementTrancheChart'), {
    type: 'bar',
    data: paiementTrancheData,
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat().format(value) + ' FCFA';
                    }
                }
            }
        }
    }
});

// Graphique d'évolution des inscriptions
const inscriptionsData = {
    labels: [@foreach($inscriptionsParMois as $inscription) '{{ $inscription->mois }}/{{ $inscription->annee }}', @endforeach],
    datasets: [{
        label: 'Inscriptions',
        data: [@foreach($inscriptionsParMois as $inscription) {{ $inscription->total }}, @endforeach],
        borderColor: '#3B82F6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.3,
        fill: true
    }]
};

new Chart(document.getElementById('inscriptionsChart'), {
    type: 'line',
    data: inscriptionsData,
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection