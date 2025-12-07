@extends('layouts.admin')

@section('title', $isFrench ? 'Créer un Module' : 'Create Module')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.auto-ecole.cours.modules.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        {{ $isFrench ? 'Nouveau Module' : 'New Module' }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ $isFrench ? 'Créer un module de cours' : 'Create a course module' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Erreurs -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <p class="text-red-800 font-medium mb-2">
                            {{ $isFrench ? 'Erreurs de validation' : 'Validation Errors' }}
                        </p>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-book mr-3"></i>
                    {{ $isFrench ? 'Informations du Module' : 'Module Information' }}
                </h2>
            </div>

            <form action="{{ route('admin.auto-ecole.cours.modules.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Nom du Module' : 'Module Name' }} *
                    </label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="{{ $isFrench ? 'Ex: Introduction au Code de la Route' : 'Ex: Introduction to Traffic Rules' }}">
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Type de Module' : 'Module Type' }} *
                    </label>
                    <select id="type" name="type" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="theorique" {{ old('type') === 'theorique' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Théorique' : 'Theoretical' }}
                        </option>
                        <option value="pratique" {{ old('type') === 'pratique' ? 'selected' : '' }}>
                            {{ $isFrench ? 'Pratique' : 'Practical' }}
                        </option>
                    </select>
                </div>

                <!-- Ordre -->
                <div>
                    <label for="ordre" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Ordre d\'affichage' : 'Display Order' }} *
                    </label>
                    <input type="number" id="ordre" name="ordre" value="{{ old('ordre', 1) }}" min="1" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="1">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $isFrench ? 'Détermine l\'ordre d\'apparition dans la liste' : 'Determines the order of appearance in the list' }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $isFrench ? 'Description' : 'Description' }}
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                              placeholder="{{ $isFrench ? 'Description du module...' : 'Module description...' }}">{{ old('description') }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Créer le Module' : 'Create Module' }}
                    </button>
                    <a href="{{ route('admin.auto-ecole.cours.modules.index') }}"
                       class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
