@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-building mr-3"></i>
                        {{ $isFrench ? 'Centres d\'Examen' : 'Exam Centers' }}
                    </h1>
                    <p class="text-blue-100 text-sm sm:text-base">
                        {{ $isFrench ? 'Gestion des centres d\'examen' : 'Exam Centers Management' }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.centres-examen.create') }}" 
                   class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-900 rounded-lg font-semibold shadow-lg transition-all duration-200 flex items-center whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ $isFrench ? 'Nouveau Centre' : 'New Center' }}
                </a>
            </div>
        </div>

        <!-- Grille des centres -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($centres as $centre)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 overflow-hidden {{ !$centre->actif ? 'opacity-60' : '' }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $centre->nom }}</h3>
                            <div class="flex items-start text-gray-600 text-sm mb-2">
                                <i class="fas fa-map-marker-alt text-yellow-500 mr-2 mt-1"></i>
                                <span>{{ $centre->adresse }}</span>
                            </div>
                            <div class="flex items-center text-blue-600 font-semibold">
                                <i class="fas fa-city mr-2"></i>
                                {{ $centre->ville }}
                            </div>
                            @if($centre->telephone)
                            <div class="flex items-center text-gray-600 text-sm mt-2">
                                <i class="fas fa-phone mr-2"></i>
                                {{ $centre->telephone }}
                            </div>
                            @endif
                        </div>
                        <div>
                            @if($centre->actif)
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

                    <div class="flex items-center text-gray-600 mb-4 bg-gray-50 rounded-lg p-3">
                        <i class="fas fa-users text-blue-500 mr-2"></i>
                        <span class="font-semibold">{{ $centre->users_count }} {{ $isFrench ? 'inscrits' : 'registered' }}</span>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.auto-ecole.centres-examen.show', $centre->id) }}" 
                           class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200 text-center text-sm">
                            <i class="fas fa-eye mr-1"></i>
                            {{ $isFrench ? 'Voir' : 'View' }}
                        </a>
                        <form action="{{ route('admin.auto-ecole.centres-examen.toggle-actif', $centre->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 {{ $centre->actif ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-all duration-200 text-sm">
                                <i class="fas fa-{{ $centre->actif ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.auto-ecole.centres-examen.edit', $centre->id) }}" 
                           class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">{{ $isFrench ? 'Aucun centre d\'examen configuré' : 'No exam centers configured' }}</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($centres->hasPages())
        <div class="mt-6">
            {{ $centres->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
