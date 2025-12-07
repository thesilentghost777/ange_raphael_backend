@extends('layouts.admin')

@section('title', $isFrench ? 'Gestion des Paiements' : 'Payment Management')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-wallet text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Gestion des Paiements' : 'Payment Management' }}
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{ $isFrench ? 'Suivi et gestion de tous les paiements' : 'Track and manage all payments' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.paiements.statistiques') }}" 
                       class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-chart-line mr-2"></i>
                        {{ $isFrench ? 'Statistiques' : 'Statistics' }}
                    </a>
                    <button onclick="exportPaiements()" 
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-file-export mr-2"></i>
                        {{ $isFrench ? 'Exporter' : 'Export' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8" x-data="{ showFilters: false }">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </h2>
                <button @click="showFilters = !showFilters" 
                        class="text-blue-600 hover:text-blue-800 font-medium">
                    <span x-show="!showFilters">{{ $isFrench ? 'Afficher' : 'Show' }}</span>
                    <span x-show="showFilters">{{ $isFrench ? 'Masquer' : 'Hide' }}</span>
                    <i class="fas fa-chevron-down ml-1" x-bind:class="{ 'rotate-180': showFilters }"></i>
                </button>
            </div>

            <form method="GET" action="{{ route('admin.paiements.index') }}" x-show="showFilters" x-collapse>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Recherche -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Recherche' : 'Search' }}
                        </label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="{{ $isFrench ? 'Transaction, nom, email...' : 'Transaction, name, email...' }}"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="statut" 
                                id="statut"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>
                                {{ $isFrench ? 'En attente' : 'Pending' }}
                            </option>
                            <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Validé' : 'Validated' }}
                            </option>
                            <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Échoué' : 'Failed' }}
                            </option>
                        </select>
                    </div>

                    <!-- Type de paiement -->
                    <div>
                        <label for="type_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Type' : 'Type' }}
                        </label>
                        <select name="type_paiement" 
                                id="type_paiement"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="en_ligne" {{ request('type_paiement') == 'en_ligne' ? 'selected' : '' }}>
                                {{ $isFrench ? 'En ligne' : 'Online' }}
                            </option>
                            <option value="code_caisse" {{ request('type_paiement') == 'code_caisse' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Code caisse' : 'Cash code' }}
                            </option>
                        </select>
                    </div>

                    <!-- Tranche -->
                    <div>
                        <label for="tranche" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Tranche' : 'Installment' }}
                        </label>
                        <select name="tranche" 
                                id="tranche"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="x" {{ request('tranche') == 'x' ? 'selected' : '' }}>X</option>
                            <option value="y" {{ request('tranche') == 'y' ? 'selected' : '' }}>Y</option>
                            <option value="complet" {{ request('tranche') == 'complet' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Complet' : 'Complete' }}
                            </option>
                        </select>
                    </div>

                    <!-- Méthode -->
                    <div>
                        <label for="methode_paiement" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Méthode' : 'Method' }}
                        </label>
                        <select name="methode_paiement" 
                                id="methode_paiement"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="orange_money" {{ request('methode_paiement') == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                            <option value="mtn_money" {{ request('methode_paiement') == 'mtn_money' ? 'selected' : '' }}>MTN Money</option>
                            <option value="card" {{ request('methode_paiement') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                        </select>
                    </div>

                    <!-- Date début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date début' : 'Start date' }}
                        </label>
                        <input type="date" 
                               name="date_debut" 
                               id="date_debut"
                               value="{{ request('date_debut') }}"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>

                    <!-- Date fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Date fin' : 'End date' }}
                        </label>
                        <input type="date" 
                               name="date_fin" 
                               id="date_fin"
                               value="{{ request('date_fin') }}"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all">
                        <i class="fas fa-search mr-2"></i>
                        {{ $isFrench ? 'Rechercher' : 'Search' }}
                    </button>
                    <a href="{{ route('admin.paiements.index') }}" 
                       class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        <i class="fas fa-redo mr-2"></i>
                        {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des paiements -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-amber-600 to-amber-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Transaction' : 'Transaction' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Utilisateur' : 'User' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Montant' : 'Amount' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Type/Tranche' : 'Type/Installment' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Méthode' : 'Method' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Date' : 'Date' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paiements as $paiement)
                        <tr class="hover:bg-amber-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $paiement->transaction_id ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $paiement->user->nom ?? 'N/A' }}</div>
                                    <div class="text-gray-500">{{ $paiement->user->email ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">
                                    {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $paiement->type_paiement == 'en_ligne' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $paiement->type_paiement == 'en_ligne' ? ($isFrench ? 'En ligne' : 'Online') : ($isFrench ? 'Code caisse' : 'Cash code') }}
                                    </span>
                                    <span class="ml-1 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ strtoupper($paiement->tranche) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($paiement->methode_paiement == 'orange_money')
                                        <i class="fas fa-mobile-alt text-orange-600 mr-1"></i> Orange Money
                                    @elseif($paiement->methode_paiement == 'mtn_money')
                                        <i class="fas fa-mobile-alt text-yellow-600 mr-1"></i> MTN Money
                                    @elseif($paiement->methode_paiement == 'card')
                                        <i class="fas fa-credit-card text-blue-600 mr-1"></i> {{ $isFrench ? 'Carte' : 'Card' }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $paiement->statut == 'valide' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $paiement->statut == 'echoue' ? 'bg-red-100 text-red-800' : '' }}">
                                    @if($paiement->statut == 'valide')
                                        <i class="fas fa-check-circle mr-1"></i> {{ $isFrench ? 'Validé' : 'Validated' }}
                                    @elseif($paiement->statut == 'en_attente')
                                        <i class="fas fa-clock mr-1"></i> {{ $isFrench ? 'En attente' : 'Pending' }}
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i> {{ $isFrench ? 'Échoué' : 'Failed' }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.paiements.show', $paiement->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ $isFrench ? 'Détails' : 'Details' }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-semibold">
                                        {{ $isFrench ? 'Aucun paiement trouvé' : 'No payment found' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paiements->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $paiements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function exportPaiements() {
    const url = new URL('{{ route("admin.paiements.export") }}');
    const params = new URLSearchParams(window.location.search);
    window.location.href = url + '?' + params.toString();
}
</script>
@endsection
