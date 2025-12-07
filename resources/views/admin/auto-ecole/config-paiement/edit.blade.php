@extends('layouts.admin')

@section('title', $isFrench ? 'Configuration des Paiements' : 'Payment Configuration')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-cog text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Configuration des Paiements' : 'Payment Configuration' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Paramétrer les montants et délais de paiement' : 'Configure payment amounts and deadlines' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

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
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    {{ $isFrench ? 'Montants des Tranches' : 'Payment Amounts' }}
                </h2>
            </div>

            <form action="{{ route('admin.auto-ecole.config-paiement.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Montant X -->
                <div>
                    <label for="montant_x" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-circle text-blue-500 mr-2"></i>
                        {{ $isFrench ? 'Montant Tranche X (FCFA)' : 'Amount X (FCFA)' }}
                    </label>
                    <input 
                        type="number" 
                        id="montant_x" 
                        name="montant_x" 
                        value="{{ old('montant_x', $config->montant_x ?? 0) }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="50000">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $isFrench ? 'Première tranche de paiement obligatoire' : 'First mandatory payment installment' }}
                    </p>
                </div>

                <!-- Montant Y -->
                <div>
                    <label for="montant_y" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-circle text-green-500 mr-2"></i>
                        {{ $isFrench ? 'Montant Tranche Y (FCFA)' : 'Amount Y (FCFA)' }}
                    </label>
                    <input 
                        type="number" 
                        id="montant_y" 
                        name="montant_y" 
                        value="{{ old('montant_y', $config->montant_y ?? 0) }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                        placeholder="30000">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $isFrench ? 'Deuxième tranche de paiement (peut être dispensée)' : 'Second payment installment (can be waived)' }}
                    </p>
                </div>

                <!-- Montant Z -->
                <div>
                    <label for="montant_z" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-circle text-amber-500 mr-2"></i>
                        {{ $isFrench ? 'Montant Tranche Z (FCFA)' : 'Amount Z (FCFA)' }}
                    </label>
                    <input 
                        type="number" 
                        id="montant_z" 
                        name="montant_z" 
                        value="{{ old('montant_z', $config->montant_z ?? 0) }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                        placeholder="20000">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $isFrench ? 'Troisième tranche de paiement' : 'Third payment installment' }}
                    </p>
                </div>

                <!-- Délai Paiement Y -->
                <div>
                    <label for="delai_paiement_y" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-red-500 mr-2"></i>
                        {{ $isFrench ? 'Délai pour Paiement Y (jours)' : 'Deadline for Payment Y (days)' }}
                    </label>
                    <input 
                        type="number" 
                        id="delai_paiement_y" 
                        name="delai_paiement_y" 
                        value="{{ old('delai_paiement_y', $config->delai_paiement_y ?? 60) }}"
                        min="1"
                        max="365"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                        placeholder="60">
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $isFrench ? 'Nombre de jours après le paiement X pour effectuer le paiement Y' : 'Number of days after payment X to make payment Y' }}
                    </p>
                </div>

                <!-- Résumé -->
                <div class="bg-gradient-to-r from-blue-50 to-amber-50 rounded-xl p-6 border-2 border-blue-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-calculator text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Montant Total' : 'Total Amount' }}
                    </h3>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-bold text-blue-600" id="montant-total">0</span>
                        <span class="text-xl text-gray-600">FCFA</span>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                    <a 
                        href="{{ route('dashboard') }}"
                        class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-sm">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-2xl mr-4 mt-1"></i>
                <div>
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">
                        {{ $isFrench ? 'Informations Importantes' : 'Important Information' }}
                    </h4>
                    <ul class="space-y-2 text-blue-800">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Le paiement X est obligatoire pour débloquer l\'accès aux cours' : 'Payment X is mandatory to unlock course access' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Le paiement Y peut être dispensé si l\'utilisateur atteint le niveau N1 de parrainage avant le délai' : 'Payment Y can be waived if the user reaches sponsorship level N1 before the deadline' }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                            <span>{{ $isFrench ? 'Les montants configurés ici s\'appliquent à tous les nouveaux paiements' : 'The amounts configured here apply to all new payments' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const montantX = document.getElementById('montant_x');
    const montantY = document.getElementById('montant_y');
    const montantZ = document.getElementById('montant_z');
    const montantTotal = document.getElementById('montant-total');

    function calculerTotal() {
        const x = parseFloat(montantX.value) || 0;
        const y = parseFloat(montantY.value) || 0;
        const z = parseFloat(montantZ.value) || 0;
        const total = x + y + z;
        montantTotal.textContent = total.toLocaleString('fr-FR');
    }

    montantX.addEventListener('input', calculerTotal);
    montantY.addEventListener('input', calculerTotal);
    montantZ.addEventListener('input', calculerTotal);

    calculerTotal();
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection
