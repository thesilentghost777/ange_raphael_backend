@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.auto-ecole.sessions.show', $session->id) }}" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-8 py-6">
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier la Session' : 'Edit Session' }}
                    </h2>
                </div>

                <form action="{{ route('admin.auto-ecole.sessions.update', $session->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Nom de la session' : 'Session Name' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $session->nom) }}" required
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
                                <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', $session->date_debut->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('date_debut') border-red-500 @enderror">
                                @error('date_debut')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $isFrench ? 'Date de fin' : 'End Date' }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin', $session->date_fin->format('Y-m-d')) }}" required
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
                            <input type="date" name="date_examen" id="date_examen" value="{{ old('date_examen', $session->date_examen?->format('Y-m-d')) }}"
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
                                <option value="ouvert" {{ old('statut', $session->statut) == 'ouvert' ? 'selected' : '' }}>{{ $isFrench ? 'Ouvert' : 'Open' }}</option>
                                <option value="ferme" {{ old('statut', $session->statut) == 'ferme' ? 'selected' : '' }}>{{ $isFrench ? 'Fermé' : 'Closed' }}</option>
                                <option value="en_cours" {{ old('statut', $session->statut) == 'en_cours' ? 'selected' : '' }}>{{ $isFrench ? 'En cours' : 'In Progress' }}</option>
                                <option value="termine" {{ old('statut', $session->statut) == 'termine' ? 'selected' : '' }}>{{ $isFrench ? 'Terminé' : 'Completed' }}</option>
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
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description', $session->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('admin.auto-ecole.sessions.show', $session->id) }}" 
                           class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center font-semibold">
                            {{ $isFrench ? 'Annuler' : 'Cancel' }}
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition font-semibold shadow-lg">
                            <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Mettre à jour' : 'Update' }}
                        </button>
                    </div>
                </form>

                <!-- Suppression -->
                @if($session->users()->count() == 0)
                <div class="p-8 border-t border-gray-200">
                    <form action="{{ route('admin.auto-ecole.sessions.destroy', $session->id) }}" method="POST" 
                          onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer cette session ?' : 'Are you sure you want to delete this session?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <i class="fas fa-trash mr-2"></i>{{ $isFrench ? 'Supprimer la session' : 'Delete Session' }}
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
