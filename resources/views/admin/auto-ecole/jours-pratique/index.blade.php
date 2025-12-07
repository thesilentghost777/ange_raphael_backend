@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-clock mr-3"></i>
                        {{ $isFrench ? 'Jours de Pratique' : 'Practice Days' }}
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">
                        {{ $isFrench ? 'Gestion des horaires de pratique' : 'Practice Schedule Management' }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.jours-pratique.create') }}" 
                   class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-900 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ $isFrench ? 'Nouveau Jour' : 'New Day' }}
                </a>
            </div>
        </div>

        <!-- Grille des jours -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jours as $jour)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 overflow-hidden {{ !$jour->actif ? 'opacity-60' : '' }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 capitalize">
                                {{ $isFrench ? $jour->jour : ucfirst($jour->jour) }}
                            </h3>
                            <div class="flex items-center text-blue-600 font-semibold mt-2">
                                <i class="fas fa-clock mr-2"></i>
                                {{ \Carbon\Carbon::parse($jour->heure)->format('H:i') }}
                            </div>
                        </div>
                        <div>
                            @if($jour->actif)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                {{ $isFrench ? 'Actif' : 'Active' }}
                            </span>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">
                                {{ $isFrench ? 'Inactif' : 'Inactive' }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-yellow-500"></i>
                        <span>{{ $jour->zone }}</span>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <form action="{{ route('admin.auto-ecole.jours-pratique.toggle-actif', $jour->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 {{ $jour->actif ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-all duration-200 text-sm">
                                <i class="fas fa-{{ $jour->actif ? 'pause' : 'play' }} mr-1"></i>
                                {{ $jour->actif ? ($isFrench ? 'Désactiver' : 'Deactivate') : ($isFrench ? 'Activer' : 'Activate') }}
                            </button>
                        </form>
                        <a href="{{ route('admin.auto-ecole.jours-pratique.edit', $jour->id) }}" 
                           class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-clock text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">{{ $isFrench ? 'Aucun jour de pratique configuré' : 'No practice days configured' }}</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($jours->hasPages())
        <div class="mt-6">
            {{ $jours->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
