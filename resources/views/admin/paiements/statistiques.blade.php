@extends('layouts.admin')

@section('title', $isFrench ? 'Statistiques des Paiements' : 'Payment Statistics')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-chart-line text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Statistiques des Paiements' : 'Payment Statistics' }}
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{ $isFrench ? 'Analyse détaillée des paiements' : 'Detailed payment analysis' }}
                    </p>
                </div>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <!-- Filtres de période -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('admin.paiements.statistiques') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="date_debut" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i>
                        {{ $isFrench ? 'Date début' : 'Start date' }}
                    </label>
                    <input type="date" 
                           name="date_debut" 
                           id="date_debut"
                           value="{{ $dateDebut }}"
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label for="date_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i>
                        {{ $isFrench ? 'Date fin' : 'End date' }}
                    </label>
                    <input type="date" 
                           name="date_fin" 
                           id="date_fin"
                           value="{{ $dateFin }}"
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                </div>

                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all">
                    <i class="fas fa-sync-alt mr-2"></i>
                    {{ $isFrench ? 'Actualiser' : 'Refresh' }}
                </button>
            </form>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total paiements -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-up text-white/60"></i>
                </div>
                <h3 class="text-sm font-medium mb-1 text-blue-100">{{ $isFrench ? 'Total paiements' : 'Total payments' }}</h3>
                <p class="text-3xl font-bold">{{ number_format($statsGlobales['total_paiements']) }}</p>
            </div>

            <!-- Montant total -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-coins text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-up text-white/60"></i>
                </div>
                <h3 class="text-sm font-medium mb-1 text-green-100">{{ $isFrench ? 'Montant total' : 'Total amount' }}</h3>
                <p class="text-3xl font-bold">{{ number_format($statsGlobales['montant_total'], 0, ',', ' ') }}</p>
                <p class="text-xs text-green-100 mt-1">FCFA</p>
            </div>

            <!-- Paiements validés -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <i class="fas fa-check text-white/60"></i>
                </div>
                <h3 class="text-sm font-medium mb-1 text-amber-100">{{ $isFrench ? 'Validés' : 'Validated' }}</h3>
                <p class="text-3xl font-bold">{{ number_format($statsGlobales['paiements_valides']) }}</p>
            </div>

            <!-- Paiements en attente -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <i class="fas fa-hourglass-half text-white/60"></i>
                </div>
                <h3 class="text-sm font-medium mb-1 text-purple-100">{{ $isFrench ? 'En attente' : 'Pending' }}</h3>
                <p class="text-3xl font-bold">{{ number_format($statsGlobales['paiements_en_attente']) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Répartition par statut -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    {{ $isFrench ? 'Répartition par statut' : 'Distribution by status' }}
                </h2>
                <div class="space-y-4">
                    @foreach($parStatut as $stat)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">
                                @if($stat->statut == 'valide')
                                    <i class="fas fa-check-circle text-green-600 mr-1"></i> {{ $isFrench ? 'Validé' : 'Validated' }}
                                @elseif($stat->statut == 'en_attente')
                                    <i class="fas fa-clock text-yellow-600 mr-1"></i> {{ $isFrench ? 'En attente' : 'Pending' }}
                                @else
                                    <i class="fas fa-times-circle text-red-600 mr-1"></i> {{ $isFrench ? 'Échoué' : 'Failed' }}
                                @endif
                            </span>
                            <span class="text-sm text-gray-600">{{ $stat->total }} ({{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full 
                                {{ $stat->statut == 'valide' ? 'bg-green-600' : '' }}
                                {{ $stat->statut == 'en_attente' ? 'bg-yellow-600' : '' }}
                                {{ $stat->statut == 'echoue' ? 'bg-red-600' : '' }}"
                                style="width: {{ ($stat->total / $statsGlobales['total_paiements']) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Répartition par type -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                    {{ $isFrench ? 'Répartition par type' : 'Distribution by type' }}
                </h2>
                <div class="space-y-4">
                    @foreach($parTypePaiement as $stat)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">
                                @if($stat->type_paiement == 'en_ligne')
                                    <i class="fas fa-globe text-blue-600 mr-1"></i> {{ $isFrench ? 'En ligne' : 'Online' }}
                                @else
                                    <i class="fas fa-ticket-alt text-purple-600 mr-1"></i> {{ $isFrench ? 'Code caisse' : 'Cash code' }}
                                @endif
                            </span>
                            <span class="text-sm text-gray-600">{{ $stat->total }} ({{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $stat->type_paiement == 'en_ligne' ? 'bg-blue-600' : 'bg-purple-600' }}"
                                style="width: {{ ($stat->montant_total / $statsGlobales['montant_total']) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Répartition par tranche -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-cut text-green-600 mr-2"></i>
                    {{ $isFrench ? 'Répartition par tranche' : 'Distribution by installment' }}
                </h2>
                <div class="space-y-4">
                    @foreach($parTranche as $stat)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ strtoupper($stat->tranche) }}</p>
                                <p class="text-sm text-gray-600">{{ $stat->total }} {{ $isFrench ? 'paiements' : 'payments' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">{{ number_format($stat->montant_total, 0, ',', ' ') }}</p>
                                <p class="text-sm text-gray-600">FCFA</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Répartition par méthode -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-credit-card text-amber-600 mr-2"></i>
                    {{ $isFrench ? 'Répartition par méthode' : 'Distribution by method' }}
                </h2>
                <div class="space-y-4">
                    @foreach($parMethodePaiement as $stat)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    @if($stat->methode_paiement == 'orange_money')
                                        <i class="fas fa-mobile-alt text-orange-600 mr-1"></i> Orange Money
                                    @elseif($stat->methode_paiement == 'mtn_money')
                                        <i class="fas fa-mobile-alt text-yellow-600 mr-1"></i> MTN Money
                                    @else
                                        <i class="fas fa-credit-card text-blue-600 mr-1"></i> {{ $isFrench ? 'Carte bancaire' : 'Bank card' }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-600">{{ $stat->total }} {{ $isFrench ? 'paiements' : 'payments' }}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900">{{ number_format($stat->montant_total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Évolution par jour -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-chart-area text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Évolution des paiements' : 'Payment evolution' }}
            </h2>
            <div class="overflow-x-auto">
                <div class="min-w-full" style="height: 300px;">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top utilisateurs -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-trophy text-amber-600 mr-2"></i>
                {{ $isFrench ? 'Top 10 utilisateurs' : 'Top 10 users' }}
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ $isFrench ? 'Rang' : 'Rank' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ $isFrench ? 'Utilisateur' : 'User' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ $isFrench ? 'Nb paiements' : 'Payments count' }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ $isFrench ? 'Montant total' : 'Total amount' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topUtilisateurs as $index => $top)
                        <tr class="hover:bg-amber-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-2xl font-bold
                                    {{ $index == 0 ? 'text-yellow-500' : '' }}
                                    {{ $index == 1 ? 'text-gray-400' : '' }}
                                    {{ $index == 2 ? 'text-orange-600' : '' }}
                                    {{ $index > 2 ? 'text-gray-600' : '' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $top->user->nom ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $top->user->email ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $top->nb_paiements }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    {{ number_format($top->montant_total, 0, ',', ' ') }} FCFA
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    
    const evolutionData = @json($evolutionParJour);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: evolutionData.map(d => d.date),
            datasets: [{
                label: '{{ $isFrench ? "Montant (FCFA)" : "Amount (FCFA)" }}',
                data: evolutionData.map(d => d.montant_total),
                borderColor: 'rgb(217, 119, 6)',
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FCFA';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
