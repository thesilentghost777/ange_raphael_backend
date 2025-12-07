@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.auto-ecole.sessions.index') }}" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        {{ $isFrench ? 'Nouvelle Session' : 'New Session' }}
                    </h2>
                </div>

                <form action="{{ route('admin.auto-ecole.sessions.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Nom de la session' : 'Session Name' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_debut" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Date de début' : 'Start Date' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('date_debut') border-red-500 @enderror">
                                @error('date_debut')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Date de fin' : 'End Date' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('date_fin') border-red-500 @enderror">
                                @error('date_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Date d'examen -->
                        <div>
                            <label for="date_examen" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Date d\'examen' : 'Exam Date' }}
                            </label>
                            <input type="date" name="date_examen" id="date_examen" value="{{ old('date_examen') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('date_examen') border-red-500 @enderror">
                            @error('date_examen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Statut' : 'Status' }} <span class="text-red-500">*</span>
                            </label>
                            <select name="statut" id="statut" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('statut') border-red-500 @enderror">
                                <option value="ouvert" {{ old('statut') == 'ouvert' ? 'selected' : '' }}>{{ $isFrench ? 'Ouvert' : 'Open' }}</option>
                                <option value="ferme" {{ old('statut') == 'ferme' ? 'selected' : '' }}>{{ $isFrench ? 'Fermé' : 'Closed' }}</option>
                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In Progress' }}</option>
                                <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>{{ $isFrench ? 'Terminé' : 'Completed' }}</option>
                            </select>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Description' : 'Description' }}
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('admin.auto-ecole.sessions.index') }}" 
                           class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center font-semibold">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold shadow-lg">
                            <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Enregistrer' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
