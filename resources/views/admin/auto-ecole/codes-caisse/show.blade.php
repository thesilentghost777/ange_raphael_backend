@extends('layouts.admin')

@section('title', $isFrench ? 'Détails du Code' : 'Code Details')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
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
                        {{ $isFrench ? 'Détails du Code' : 'Code Details' }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Code Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-8 text-center">
                <p class="text-white text-sm font-medium mb-2">
                    {{ $isFrench ? 'Code Caisse' : 'Cash Code' }}
                </p>
                <p class="text-white text-4xl md:text-5xl font-bold font-mono tracking-wider">
                    {{ $code->code }}
                </p>
                <div class="mt-4">
                    @if($code->utilise)
                        <span class="inline-block px-4 py-2 text-sm font-bold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            {{ $isFrench ? 'Utilisé' : 'Used' }}
                        </span>
                    @elseif($code->date_expiration && $code->date_expiration < now())
                        <span class="inline-block px-4 py-2 text-sm font-bold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>
                            {{ $isFrench ? 'Expiré' : 'Expired' }}
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 text-sm font-bold rounded-full bg-white text-amber-600">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $isFrench ? 'Disponible' : 'Available' }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-xl p-4 border-l-4 border-blue-500">
                        <p class="text-sm text-gray-600 font-medium mb-1">
                            {{ $isFrench ? 'Montant' : 'Amount' }}
                        </p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ number_format($code->montant) }} FCFA
                        </p>
                    </div>

                    <div class="bg-green-50 rounded-xl p-4 border-l-4 border-green-500">
                        <p class="text-sm text-gray-600 font-medium mb-1">
                            {{ $isFrench ? 'Tranche' : 'Installment' }}
                        </p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ strtoupper($code->tranche) }}
                        </p>
                    </div>
                </div>

                <!-- Utilisateur -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Utilisateur' : 'User' }}
                    </h3>
                    @if($code->user)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Nom complet' : 'Full Name' }}</p>
                                    <p class="font-semibold text-gray-900">{{ $code->user->nom_complet }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Email' : 'Email' }}</p>
                                    <p class="font-semibold text-gray-900">{{ $code->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Téléphone' : 'Phone' }}</p>
                                    <p class="font-semibold text-gray-900">{{ $code->user->telephone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Type Permis' : 'License Type' }}</p>
                                    <p class="font-semibold text-gray-900">{{ strtoupper($code->user->type_permis) }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-6 text-center">
                            <i class="fas fa-user-slash text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500 font-medium">
                                {{ $isFrench ? 'Non assigné à un utilisateur' : 'Not assigned to a user' }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Dates -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-calendar text-green-600 mr-2"></i>
                        {{ $isFrench ? 'Dates' : 'Dates' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Création' : 'Created' }}</p>
                            <p class="font-semibold text-gray-900">{{ $code->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($code->date_expiration)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Expiration' : 'Expiration' }}</p>
                                <p class="font-semibold {{ $code->date_expiration < now() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $code->date_expiration->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif

                        @if($code->date_utilisation)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Utilisation' : 'Used On' }}</p>
                                <p class="font-semibold text-gray-900">{{ $code->date_utilisation->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Générateur -->
                @if($code->generateurUser)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user-cog text-purple-600 mr-2"></i>
                            {{ $isFrench ? 'Généré par' : 'Generated By' }}
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="font-semibold text-gray-900">{{ $code->generateurUser->nom }}</p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button onclick="window.print()" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-print mr-2"></i>
                        {{ $isFrench ? 'Imprimer' : 'Print' }}
                    </button>
                    
                    @if(!$code->utilise)
                        <button onclick="deleteCode()" 
                                class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCode() {
    Swal.fire({
        title: '{{ $isFrench ? "Êtes-vous sûr ?" : "Are you sure?" }}',
        text: '{{ $isFrench ? "Cette action est irréversible" : "This action is irreversible" }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '{{ $isFrench ? "Oui, supprimer" : "Yes, delete" }}',
        cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.auto-ecole.codes-caisse.destroy", $code->id) }}';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-gradient-to-r, .shadow-xl {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection
