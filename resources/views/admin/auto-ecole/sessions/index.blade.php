@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        {{ $isFrench ? 'Gestion des Sessions' : 'Sessions Management' }}
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">
                        {{ $isFrench ? 'Auto-École Ange Raphaël' : 'Ange Raphaël Driving School' }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.sessions.create') }}" 
                   class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-900 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ $isFrench ? 'Nouvelle Session' : 'New Session' }}
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.auto-ecole.sessions.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Statut' : 'Status' }}
                    </label>
                    <select name="statut" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        <option value="ouvert" {{ request('statut') == 'ouvert' ? 'selected' : '' }}>{{ $isFrench ? 'Ouvert' : 'Open' }}</option>
                        <option value="ferme" {{ request('statut') == 'ferme' ? 'selected' : '' }}>{{ $isFrench ? 'Fermé' : 'Closed' }}</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In Progress' }}</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>{{ $isFrench ? 'Terminé' : 'Completed' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Année' : 'Year' }}
                    </label>
                    <input type="number" name="annee" value="{{ request('annee') }}" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="{{ $isFrench ? 'Ex: 2024' : 'Ex: 2024' }}">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-200">
                        <i class="fas fa-filter mr-2"></i>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des sessions -->
        <div class="grid grid-cols-1 gap-6">
            @forelse($sessions as $session)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-calendar text-white text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $session->nom }}</h3>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                                            {{ $session->date_debut->format('d/m/Y') }} - {{ $session->date_fin->format('d/m/Y') }}
                                        </span>
                                        @if($session->date_examen)
                                        <span class="flex items-center">
                                            <i class="fas fa-graduation-cap text-yellow-500 mr-2"></i>
                                            {{ $isFrench ? 'Examen:' : 'Exam:' }} {{ $session->date_examen->format('d/m/Y') }}
                                        </span>
                                        @endif
                                        <span class="flex items-center">
                                            <i class="fas fa-users text-green-500 mr-2"></i>
                                            {{ $session->users_count }} {{ $isFrench ? 'inscrits' : 'registered' }}
                                        </span>
                                    </div>
                                    @if($session->description)
                                    <p class="text-sm text-gray-600">{{ $session->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-3 items-end">
                            @php
                                $statutColors = [
                                    'ouvert' => 'bg-green-100 text-green-800',
                                    'ferme' => 'bg-red-100 text-red-800',
                                    'en_cours' => 'bg-blue-100 text-blue-800',
                                    'termine' => 'bg-gray-100 text-gray-800'
                                ];
                                $statutLabels = [
                                    'ouvert' => $isFrench ? 'Ouvert' : 'Open',
                                    'ferme' => $isFrench ? 'Fermé' : 'Closed',
                                    'en_cours' => $isFrench ? 'En cours' : 'In Progress',
                                    'termine' => $isFrench ? 'Terminé' : 'Completed'
                                ];
                            @endphp
                            <span class="px-4 py-2 rounded-full text-sm font-bold {{ $statutColors[$session->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statutLabels[$session->statut] ?? $session->statut }}
                            </span>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('admin.auto-ecole.sessions.show', $session->id) }}" 
                                   class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.auto-ecole.sessions.edit', $session->id) }}" 
                                   class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">{{ $isFrench ? 'Aucune session trouvée' : 'No sessions found' }}</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
