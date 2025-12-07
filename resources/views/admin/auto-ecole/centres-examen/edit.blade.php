@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.auto-ecole.centres-examen.show', $centre->id) }}" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-8 py-6">
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier le Centre d\'Examen' : 'Edit Exam Center' }}
                    </h2>
                </div>

                <form action="{{ route('admin.auto-ecole.centres-examen.update', $centre->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Nom du centre' : 'Center Name' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $centre->nom) }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label for="adresse" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Adresse' : 'Address' }} <span class="text-red-500">*</span>
                            </label>
                            <textarea name="adresse" id="adresse" rows="3" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('adresse') border-red-500 @enderror">{{ old('adresse', $centre->adresse) }}</textarea>
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ville -->
                        <div>
                            <label for="ville" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Ville' : 'City' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $centre->ville) }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('ville') border-red-500 @enderror">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Téléphone' : 'Phone' }}
                            </label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $centre->telephone) }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actif -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="actif" value="1" {{ old('actif', $centre->actif) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Centre actif' : 'Active center' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('admin.auto-ecole.centres-examen.show', $centre->id) }}" 
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
                @if($centre->users()->count() == 0)
                <div class="p-8 border-t border-gray-200">
                    <form action="{{ route('admin.auto-ecole.centres-examen.destroy', $centre->id) }}" method="POST" 
                          onsubmit="return confirm('{{ $isFrench ? 'Êtes-vous sûr de vouloir supprimer ce centre ?' : 'Are you sure you want to delete this center?' }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            <i class="fas fa-trash mr-2"></i>{{ $isFrench ? 'Supprimer' : 'Delete' }}
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
