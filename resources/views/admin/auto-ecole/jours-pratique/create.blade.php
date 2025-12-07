@extends('layouts.admin')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.auto-ecole.jours-pratique.index') }}" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h2 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        {{ $isFrench ? 'Nouveau Jour de Pratique' : 'New Practice Day' }}
                    </h2>
                </div>

                <form action="{{ route('admin.auto-ecole.jours-pratique.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Jour -->
                        <div>
                            <label for="jour" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Jour de la semaine' : 'Day of Week' }} <span class="text-red-500">*</span>
                            </label>
                            <select name="jour" id="jour" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('jour') border-red-500 @enderror">
                                <option value="">{{ $isFrench ? 'Sélectionner un jour' : 'Select a day' }}</option>
                                <option value="lundi" {{ old('jour') == 'lundi' ? 'selected' : '' }}>{{ $isFrench ? 'Lundi' : 'Monday' }}</option>
                                <option value="mardi" {{ old('jour') == 'mardi' ? 'selected' : '' }}>{{ $isFrench ? 'Mardi' : 'Tuesday' }}</option>
                                <option value="mercredi" {{ old('jour') == 'mercredi' ? 'selected' : '' }}>{{ $isFrench ? 'Mercredi' : 'Wednesday' }}</option>
                                <option value="jeudi" {{ old('jour') == 'jeudi' ? 'selected' : '' }}>{{ $isFrench ? 'Jeudi' : 'Thursday' }}</option>
                                <option value="vendredi" {{ old('jour') == 'vendredi' ? 'selected' : '' }}>{{ $isFrench ? 'Vendredi' : 'Friday' }}</option>
                                <option value="samedi" {{ old('jour') == 'samedi' ? 'selected' : '' }}>{{ $isFrench ? 'Samedi' : 'Saturday' }}</option>
                                <option value="dimanche" {{ old('jour') == 'dimanche' ? 'selected' : '' }}>{{ $isFrench ? 'Dimanche' : 'Sunday' }}</option>
                            </select>
                            @error('jour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Heure -->
                        <div>
                            <label for="heure" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Heure' : 'Time' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="heure" id="heure" value="{{ old('heure') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('heure') border-red-500 @enderror">
                            @error('heure')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Zone -->
                        <div>
                            <label for="zone" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $isFrench ? 'Zone' : 'Zone' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="zone" id="zone" value="{{ old('zone') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('zone') border-red-500 @enderror"
                                placeholder="{{ $isFrench ? 'Ex: Centre-ville, Zone Nord...' : 'Ex: City Center, North Zone...' }}">
                            @error('zone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actif -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="actif" value="1" checked 
                                       class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm font-semibold text-gray-700">
                                    {{ $isFrench ? 'Jour actif' : 'Active day' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 mt-8">
                        <a href="{{ route('admin.auto-ecole.jours-pratique.index') }}" 
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
