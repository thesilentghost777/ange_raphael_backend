@extends('layouts.admin')

@section('title', $isFrench ? 'Nouveau Chapitre' : 'New Chapter')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.auto-ecole.cours.modules.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $isFrench ? 'Modules' : 'Modules' }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <a href="{{ route('admin.auto-ecole.cours.chapitres.index', $module->id) }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $module->nom }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $isFrench ? 'Nouveau' : 'New' }}</span>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                <i class="fas fa-plus-circle text-amber-600 mr-3"></i>
                {{ $isFrench ? 'Nouveau Chapitre' : 'New Chapter' }}
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form action="{{ route('admin.auto-ecole.cours.chapitres.store', $module->id) }}" method="POST" class="p-6 md:p-8">
                @csrf

                <div class="space-y-6">
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heading text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Nom du chapitre' : 'Chapter name' }} *
                        </label>
                        <input type="text" 
                               name="nom" 
                               id="nom" 
                               required
                               value="{{ old('nom') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('nom') border-red-500 @enderror"
                               placeholder="{{ $isFrench ? 'Ex: Introduction au code de la route' : 'Ex: Introduction to traffic code' }}">
                        @error('nom')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Ordre -->
                    <div>
                        <label for="ordre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-up text-green-600 mr-2"></i>
                            {{ $isFrench ? 'Ordre d\'affichage' : 'Display order' }} *
                        </label>
                        <input type="number" 
                               name="ordre" 
                               id="ordre" 
                               required
                               min="1"
                               value="{{ old('ordre', 1) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('ordre') border-red-500 @enderror">
                        @error('ordre')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-purple-600 mr-2"></i>
                            {{ $isFrench ? 'Description' : 'Description' }}
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('description') border-red-500 @enderror"
                                  placeholder="{{ $isFrench ? 'Description optionnelle du chapitre...' : 'Optional chapter description...' }}">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Créer le chapitre' : 'Create chapter' }}
                    </button>
                    
                    <a href="{{ route('admin.auto-ecole.cours.chapitres.index', $module->id) }}" 
                       class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
