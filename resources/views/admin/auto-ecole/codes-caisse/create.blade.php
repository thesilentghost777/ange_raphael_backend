@extends('layouts.admin')

@section('title', $isFrench ? 'Générer des Codes Caisse' : 'Generate Cash Codes')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('admin.auto-ecole.codes-caisse.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-ticket-alt text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Générer des Codes Caisse' : 'Generate Cash Codes' }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ $isFrench ? 'Créer des codes de paiement pour les utilisateurs' : 'Create payment codes for users' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ mode: 'single' }">
            <!-- Code Unique -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2" :class="mode === 'single' ? 'border-blue-500' : 'border-gray-200'">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 cursor-pointer" @click="mode = 'single'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-user mr-3"></i>
                            {{ $isFrench ? 'Code Unique' : 'Single Code' }}
                        </h2>
                        <div class="text-white" x-show="mode === 'single'">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.auto-ecole.codes-caisse.store') }}" method="POST" class="p-6 space-y-6" x-show="mode === 'single'">
                    @csrf

                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Utilisateur' : 'User' }} *
                        </label>
                        <select id="user_id" name="user_id" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="">{{ $isFrench ? 'Sélectionner un utilisateur' : 'Select a user' }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nom_complet }} - {{ $user->telephone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tranche" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Type de Paiement' : 'Payment Type' }} *
                        </label>
                        <select id="tranche" name="tranche" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <option value="x" {{ old('tranche') == 'x' ? 'selected' : '' }}>
                                Tranche X ({{ number_format($config->montant_x ?? 0) }} FCFA)
                            </option>
                            <option value="y" {{ old('tranche') == 'y' ? 'selected' : '' }}>
                                Tranche Y ({{ number_format($config->montant_y ?? 0) }} FCFA)
                            </option>
                            <option value="complet" {{ old('tranche') == 'complet' ? 'selected' : '' }}>
                                Paiement Complet ({{ number_format(($config->montant_x ?? 0) + ($config->montant_y ?? 0)) }} FCFA)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="montant" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }} *
                        </label>
                        <input type="number" id="montant" name="montant" value="{{ old('montant') }}" 
                               step="0.01" min="0" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="50000">
                    </div>

                    <div>
                        <label for="date_expiration" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Date d\'expiration' : 'Expiration Date' }}
                        </label>
                        <input type="date" id="date_expiration" name="date_expiration" value="{{ old('date_expiration') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $isFrench ? 'Optionnel - Laisser vide pour aucune expiration' : 'Optional - Leave empty for no expiration' }}
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Générer le Code' : 'Generate Code' }}
                    </button>
                </form>
            </div>

            <!-- Codes Multiples -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2" :class="mode === 'multiple' ? 'border-green-500' : 'border-gray-200'">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 cursor-pointer" @click="mode = 'multiple'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            {{ $isFrench ? 'Codes Multiples' : 'Multiple Codes' }}
                        </h2>
                        <div class="text-white" x-show="mode === 'multiple'">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.auto-ecole.codes-caisse.generer-multiple') }}" method="POST" class="p-6 space-y-6" x-show="mode === 'multiple'">
                    @csrf

                    <div>
                        <label for="tranche_multiple" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Type de Paiement' : 'Payment Type' }} *
                        </label>
                        <select id="tranche_multiple" name="tranche" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                            <option value="x">Tranche X ({{ number_format($config->montant_x ?? 0) }} FCFA)</option>
                            <option value="y">Tranche Y ({{ number_format($config->montant_y ?? 0) }} FCFA)</option>
                            <option value="complet">Paiement Complet ({{ number_format(($config->montant_x ?? 0) + ($config->montant_y ?? 0)) }} FCFA)</option>
                        </select>
                    </div>

                    <div>
                        <label for="montant_multiple" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Montant (FCFA)' : 'Amount (FCFA)' }} *
                        </label>
                        <input type="number" id="montant_multiple" name="montant" 
                               step="0.01" min="0" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="50000">
                    </div>

                    <div>
                        <label for="quantite" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Quantité' : 'Quantity' }} *
                        </label>
                        <input type="number" id="quantite" name="quantite" 
                               min="1" max="100" required
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="10">
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $isFrench ? 'Maximum 100 codes à la fois' : 'Maximum 100 codes at once' }}
                        </p>
                    </div>

                    <div>
                        <label for="date_expiration_multiple" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Date d\'expiration' : 'Expiration Date' }}
                        </label>
                        <input type="date" id="date_expiration_multiple" name="date_expiration" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $isFrench ? 'Optionnel - Laisser vide pour aucune expiration' : 'Optional - Leave empty for no expiration' }}
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus-circle mr-2"></i>
                        {{ $isFrench ? 'Générer les Codes' : 'Generate Codes' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-sm">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-2xl mr-4 mt-1"></i>
                <div>
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">
                        {{ $isFrench ? 'Informations' : 'Information' }}
                    </h4>
                    <ul class="space-y-2 text-blue-800">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Un code unique est assigné à un utilisateur spécifique' : 'A single code is assigned to a specific user' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Les codes multiples ne sont pas assignés et peuvent être distribués librement' : 'Multiple codes are not assigned and can be distributed freely' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Un paiement "Complet" couvre les deux tranches (X + Y) en une seule fois' : 'A "Complete" payment covers both installments (X + Y) at once' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Les codes expirés ne peuvent plus être utilisés' : 'Expired codes can no longer be used' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Montants de configuration
const montantX = {{ $config->montant_x ?? 0 }};
const montantY = {{ $config->montant_y ?? 0 }};
const montantComplet = montantX + montantY;

// Auto-fill montant selon tranche - Code unique
document.getElementById('tranche').addEventListener('change', function() {
    const montantInput = document.getElementById('montant');
    if (this.value === 'x') {
        montantInput.value = montantX;
    } else if (this.value === 'y') {
        montantInput.value = montantY;
    } else if (this.value === 'complet') {
        montantInput.value = montantComplet;
    }
});

// Auto-fill montant selon tranche - Codes multiples
document.getElementById('tranche_multiple').addEventListener('change', function() {
    const montantInput = document.getElementById('montant_multiple');
    if (this.value === 'x') {
        montantInput.value = montantX;
    } else if (this.value === 'y') {
        montantInput.value = montantY;
    } else if (this.value === 'complet') {
        montantInput.value = montantComplet;
    }
});
</script>
@endsection