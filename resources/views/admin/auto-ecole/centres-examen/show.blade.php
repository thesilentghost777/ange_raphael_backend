@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.auto-ecole.centres-examen.index') }}" class="text-blue-600 hover:text-blue-800 transition flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
            </a>
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        {{ $centre->nom }}
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">
                        <i class="fas fa-city mr-2"></i>{{ $centre->ville }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.centres-examen.edit', $centre->id) }}" 
                   class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-900 rounded-lg font-semibold shadow-lg transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>{{ $isFrench ? 'Modifier' : 'Edit' }}
                </a>
            </div>
        </div>

        <!-- Informations du centre -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    {{ $isFrench ? 'Informations' : 'Information' }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">{{ $isFrench ? 'Adresse' : 'Address' }}</label>
                        <p class="text-gray-800 font-semibold">{{ $centre->adresse }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ $isFrench ? 'Ville' : 'City' }}</label>
                        <p class="text-gray-800 font-semibold">{{ $centre->ville }}</p>
                    </div>
                    
                    @if($centre->telephone)
                    <div>
                        <label class="text-sm text-gray-600">{{ $isFrench ? 'Téléphone' : 'Phone' }}</label>
                        <p class="text-gray-800 font-semibold">{{ $centre->telephone }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                        <div class="mt-1">
                            @if($centre->actif)
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                <i class="fas fa-check-circle mr-1"></i>{{ $isFrench ? 'Actif' : 'Active' }}
                            </span>
                            @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-bold">
                                <i class="fas fa-times-circle mr-1"></i>{{ $isFrench ? 'Inactif' : 'Inactive' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    {{ $isFrench ? 'Statistiques' : 'Statistics' }}
                </h3>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-3xl font-bold text-blue-600">{{ $stats['total_inscrits'] }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Total inscrits' : 'Total Registered' }}</div>
                    </div>
                </div>
            </div>
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Ville' : 'City' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Type' : 'Type' }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ $isFrench ? 'Contact' : 'Contact' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($centre->users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $user->nom }} {{ $user->prenom }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->ville }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->type_permis == 'permis_a' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ strtoupper(str_replace('permis_', '', $user->type_permis)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->telephone }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
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
