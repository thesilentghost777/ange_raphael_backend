@extends('layouts.admin')

@section('title', $isFrench ? 'Modifier la Leçon' : 'Edit Lesson')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4 flex-wrap" aria-label="Breadcrumb">
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
                <a href="{{ route('admin.auto-ecole.cours.lecons.index', [$module->id, $chapitre->id]) }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $chapitre->nom }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $isFrench ? 'Modifier' : 'Edit' }}</span>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                <i class="fas fa-edit text-amber-600 mr-3"></i>
                {{ $isFrench ? 'Modifier la Leçon' : 'Edit Lesson' }}
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form action="{{ route('admin.auto-ecole.cours.lecons.update', [$module->id, $chapitre->id, $lecon->id]) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heading text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Titre de la leçon' : 'Lesson title' }} *
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               required
                               value="{{ old('titre', $lecon->titre) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('titre') border-red-500 @enderror">
                        @error('titre')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Ordre et Durée -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ordre" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up text-green-600 mr-2"></i>
                                {{ $isFrench ? 'Ordre' : 'Order' }} *
                            </label>
                            <input type="number" 
                                   name="ordre" 
                                   id="ordre" 
                                   required
                                   min="1"
                                   value="{{ old('ordre', $lecon->ordre) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('ordre') border-red-500 @enderror">
                            @error('ordre')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="duree_minutes" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-purple-600 mr-2"></i>
                                {{ $isFrench ? 'Durée (minutes)' : 'Duration (minutes)' }}
                            </label>
                            <input type="number" 
                                   name="duree_minutes" 
                                   id="duree_minutes" 
                                   min="1"
                                   value="{{ old('duree_minutes', $lecon->duree_minutes) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('duree_minutes') border-red-500 @enderror">
                            @error('duree_minutes')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contenu texte -->
                    <div>
                        <label for="contenu_texte" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Contenu de la leçon' : 'Lesson content' }} *
                        </label>
                        <textarea name="contenu_texte" 
                                  id="contenu_texte" 
                                  required
                                  rows="8"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('contenu_texte') border-red-500 @enderror">{{ old('contenu_texte', $lecon->contenu_texte) }}</textarea>
                        @error('contenu_texte')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Image actuelle -->
                    @if($lecon->image_url)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image text-green-600 mr-2"></i>
                                {{ $isFrench ? 'Image actuelle' : 'Current image' }}
                            </label>
                            <img src="{{ asset('storage/' . $lecon->image_url) }}" 
                                 alt="{{ $lecon->titre }}"
                                 class="w-64 h-40 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                    @endif

                    <!-- Nouvelle image -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-image text-green-600 mr-2"></i>
                            {{ $isFrench ? 'Nouvelle image (optionnelle)' : 'New image (optional)' }}
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 transition-all duration-200 @error('image') border-red-500 @enderror">
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $isFrench ? 'Formats acceptés: JPG, PNG, GIF. Taille max: 5MB' : 'Accepted formats: JPG, PNG, GIF. Max size: 5MB' }}
                        </p>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- URL Vidéo -->
                    <div>
                        <label for="video_url" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-video text-red-600 mr-2"></i>
                            {{ $isFrench ? 'URL de la vidéo (optionnelle)' : 'Video URL (optional)' }}
                        </label>
                        <input type="url" 
                               name="video_url" 
                               id="video_url" 
                               value="{{ old('video_url', $lecon->video_url) }}"
                               placeholder="https://youtube.com/..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('video_url') border-red-500 @enderror">
                        @error('video_url')
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
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                    
                    <a href="{{ route('admin.auto-ecole.cours.lecons.index', [$module->id, $chapitre->id]) }}" 
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
