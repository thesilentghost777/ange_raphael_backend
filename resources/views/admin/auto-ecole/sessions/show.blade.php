@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.auto-ecole.sessions.index') }}" class="text-blue-600 hover:text-blue-800 transition flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
            </a>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        {{ $session->nom }}
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">
                        {{ $session->date_debut->format('d/m/Y') }} - {{ $session->date_fin->format('d/m/Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.auto-ecole.sessions.edit', $session->id) }}" 
                       class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-900 rounded-lg font-semibold shadow-lg transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>{{ $isFrench ? 'Modifier' : 'Edit' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['total_inscrits'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Inscrits' : 'Registered' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="text-3xl font-bold text-green-600">{{ $stats['valides'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Validés' : 'Validated' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['paiement_x_complet'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Paiement X' : 'Payment X' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['paiement_y_complet'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Paiement Y' : 'Payment Y' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="text-3xl font-bold text-red-600">{{ $stats['permis_a'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Permis A' : 'License A' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['permis_b'] }}</div>
                <div class="text-sm text-gray-600">{{ $isFrench ? 'Permis B' : 'License B' }}</div>
            </div>
        </div>

        <!-- Changement de statut -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-sync-alt text-blue-500 mr-2"></i>
                {{ $isFrench ? 'Changer le statut' : 'Change Status' }}
            </h3>
            <form method="POST" action="{{ route('admin.auto-ecole.sessions.changer-statut', $session->id) }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <select name="statut" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="ouvert" {{ $session->statut == 'ouvert' ? 'selected' : '' }}>{{ $isFrench ? 'Ouvert' : 'Open' }}</option>
                    <option value="ferme" {{ $session->statut == 'ferme' ? 'selected' : '' }}>{{ $isFrench ? 'Fermé' : 'Closed' }}</option>
                    <option value="en_cours" {{ $session->statut == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In Progress' }}</option>
                    <option value="termine" {{ $session->statut == 'termine' ? 'selected' : '' }}>{{ $isFrench ? 'Terminé' : 'Completed' }}</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-200">
                    {{ $isFrench ? 'Modifier' : 'Update' }}
                </button>
            </form>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-users mr-2"></i>
                    {{ $isFrench ? 'Utilisateurs inscrits' : 'Registered Users' }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Nom' : 'Name' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Type' : 'Type' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Validé' : 'Validated' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Paiement X' : 'Payment X' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Paiement Y' : 'Payment Y' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($session->users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $user->nom }} {{ $user->prenom }}</div>
                                <div class="text-sm text-gray-500">{{ $user->telephone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->type_permis == 'permis_a' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ strtoupper(str_replace('permis_', '', $user->type_permis)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->valide)
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                @else
                                <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->paiement_x)
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                @else
                                <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->paiement_y)
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                @else
                                <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                {{ $isFrench ? 'Aucun utilisateur inscrit' : 'No registered users' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
