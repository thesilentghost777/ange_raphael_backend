@extends('layouts.admin')

@section('title', $isFrench ? 'Détails du Paiement' : 'Payment Details')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.paiements.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    <i class="fas fa-wallet mr-1"></i>
                    {{ $isFrench ? 'Paiements' : 'Payments' }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $paiement->transaction_id ?: 'Détails' }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    <i class="fas fa-receipt text-amber-600 mr-3"></i>
                    {{ $isFrench ? 'Détails du Paiement' : 'Payment Details' }}
                </h1>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Statut et montant -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Informations générales' : 'General information' }}
                        </h2>
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full
                            {{ $paiement->statut == 'valide' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $paiement->statut == 'echoue' ? 'bg-red-100 text-red-800' : '' }}">
                            @if($paiement->statut == 'valide')
                                <i class="fas fa-check-circle mr-2"></i> {{ $isFrench ? 'Validé' : 'Validated' }}
                            @elseif($paiement->statut == 'en_attente')
                                <i class="fas fa-clock mr-2"></i> {{ $isFrench ? 'En attente' : 'Pending' }}
                            @else
                                <i class="fas fa-times-circle mr-2"></i> {{ $isFrench ? 'Échoué' : 'Failed' }}
                            @endif
                        </span>
                    </div>

                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-xl p-6 mb-6">
                        <div class="text-center">
                            <p class="text-amber-100 text-sm font-medium mb-2">
                                {{ $isFrench ? 'Montant du paiement' : 'Payment amount' }}
                            </p>
                            <p class="text-4xl font-bold text-white">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'ID Paiement' : 'Payment ID' }}
                            </p>
                            <p class="font-semibold text-gray-900">#{{ $paiement->id }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'Transaction ID' : 'Transaction ID' }}
                            </p>
                            <p class="font-semibold text-gray-900">{{ $paiement->transaction_id ?: 'N/A' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'Type de paiement' : 'Payment type' }}
                            </p>
                            <p class="font-semibold text-gray-900">
                                {{ $paiement->type_paiement == 'en_ligne' ? ($isFrench ? 'En ligne' : 'Online') : ($isFrench ? 'Code caisse' : 'Cash code') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-cut text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'Tranche' : 'Installment' }}
                            </p>
                            <p class="font-semibold text-gray-900">{{ strtoupper($paiement->tranche) }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-credit-card text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'Méthode' : 'Method' }}
                            </p>
                            <p class="font-semibold text-gray-900">
                                @if($paiement->methode_paiement == 'orange_money')
                                    Orange Money
                                @elseif($paiement->methode_paiement == 'mtn_money')
                                    MTN Money
                                @elseif($paiement->methode_paiement == 'card')
                                    {{ $isFrench ? 'Carte bancaire' : 'Bank card' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">
                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                {{ $isFrench ? 'Date du paiement' : 'Payment date' }}
                            </p>
                            <p class="font-semibold text-gray-900">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($paiement->notes)
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-sticky-note text-blue-600 mr-1"></i>
                            {{ $isFrench ? 'Notes' : 'Notes' }}
                        </p>
                        <p class="text-gray-900">{{ $paiement->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Informations utilisateur -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-user text-purple-600 mr-2"></i>
                        {{ $isFrench ? 'Informations utilisateur' : 'User information' }}
                    </h2>

                    @if($paiement->user)
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ strtoupper(substr($paiement->user->nom, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $paiement->user->prenom }} {{ $paiement->user->nom }}
                                </h3>
                                <p class="text-gray-600">{{ $paiement->user->email }}</p>
                                <p class="text-gray-600">
                                    <i class="fas fa-phone text-green-600 mr-1"></i>
                                    {{ $paiement->user->telephone }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Type de permis' : 'License type' }}</p>
                                <p class="font-semibold text-gray-900">{{ strtoupper(str_replace('_', ' ', $paiement->user->type_permis)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Date d\'inscription' : 'Registration date' }}</p>
                                <p class="font-semibold text-gray-900">{{ $paiement->user->date_inscription->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500 italic">{{ $isFrench ? 'Aucun utilisateur associé' : 'No associated user' }}</p>
                    @endif
                </div>
            </div>

            <!-- Historique et actions -->
            <div class="space-y-6">
                <!-- Timestamps -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Horodatage' : 'Timestamps' }}
                    </h2>

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-plus-circle text-green-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Créé le' : 'Created at' }}</p>
                                <p class="font-semibold text-gray-900">{{ $paiement->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="fas fa-edit text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Mis à jour le' : 'Updated at' }}</p>
                                <p class="font-semibold text-gray-900">{{ $paiement->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique des paiements -->
                @if($historiquePaiements->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        {{ $isFrench ? 'Autres paiements' : 'Other payments' }}
                    </h2>

                    <div class="space-y-3">
                        @foreach($historiquePaiements as $historique)
                        <a href="{{ route('admin.paiements.show', $historique->id) }}" 
                           class="block bg-gray-50 hover:bg-amber-50 rounded-lg p-3 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ number_format($historique->montant, 0, ',', ' ') }} FCFA
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $historique->date_paiement->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $historique->statut == 'valide' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $historique->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $historique->statut == 'echoue' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ strtoupper($historique->tranche) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
