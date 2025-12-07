@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.auto-ecole.centres-examen.index') }}" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        {{ $isFrench ? 'Nouveau Centre d\'Examen' : 'New Exam Center' }}
                    </h2>
                </div>

                <form action="{{ route('admin.auto-ecole.centres-examen.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Nom du centre' : 'Center Name' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
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
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ville -->
                        <div>
                            <label for="ville" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Ville' : 'City' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required
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
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('telephone') border-red-500 @enderror">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actif -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="actif" value="1" checked 
                                       class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Centre actif' : 'Active center' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('admin.auto-ecole.centres-examen.index') }}" 
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
