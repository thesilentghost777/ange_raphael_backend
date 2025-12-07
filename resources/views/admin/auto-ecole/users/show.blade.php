@extends('layouts.admin')

@section('title', 'Détails Utilisateur Auto-École')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails de l'utilisateur</h1>
            <p class="text-gray-600 mt-1">Informations complètes du candidat</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.auto-ecole.users.edit', $user->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
            <a href="{{ route('admin.auto-ecole.users.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200">
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations personnelles -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informations personnelles
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom complet</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $user->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="mt-1 text-gray-900">{{ $user->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Prénom</label>
                        <p class="mt-1 text-gray-900">{{ $user->prenom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                        <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') }} ({{ $user->age }} ans)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Lieu de naissance</label>
                        <p class="mt-1 text-gray-900">{{ $user->lieu_naissance }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Sexe</label>
                        <p class="mt-1 text-gray-900">{{ $user->sexe == 'M' ? 'Masculin' : 'Féminin' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">CNI</label>
                        <p class="mt-1 text-gray-900">{{ $user->numero_cni ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Profession</label>
                        <p class="mt-1 text-gray-900">{{ $user->profession ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $user->numero_telephone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-gray-900">{{ $user->email ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Adresse</label>
                        <p class="mt-1 text-gray-900">{{ $user->adresse ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <!-- Formation -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Formation
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type de permis</label>
                        <span class="mt-1 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $user->type_permis == 'permis_a' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ strtoupper(str_replace('_', ' ', $user->type_permis)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Session</label>
                        <p class="mt-1 text-gray-900">{{ $user->session?->nom ?? 'Non assigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Centre d'examen</label>
                        <p class="mt-1 text-gray-900">{{ $user->centreExamen?->nom ?? 'Non assigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jours pratique</label>
                        <p class="mt-1 text-gray-900">
                            @if($user->joursPratique->count() > 0)
                                {{ $user->joursPratique->pluck('jour')->implode(', ') }}
                            @else
                                Non assignés
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Parrainage -->
            @if($user->parrain_id)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Parrainage
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Parrainé par</label>
                        <p class="mt-1 text-gray-900">{{ $user->parrain?->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Filleuls</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $user->filleuls->count() }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Statut</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Validation</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->valide ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $user->valide ? 'Validé' : 'En attente' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Paiement X</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->paiement_x ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->paiement_x ? 'Payé' : 'Non payé' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Paiement Y</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->paiement_y ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->paiement_y ? 'Payé' : 'Non payé' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
    <form action="{{ route('admin.auto-ecole.users.valider', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir changer le statut de validation ?')">
        @csrf
        <button type="submit" class="w-full {{ $user->valide ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
            {{ $user->valide ? 'Invalider' : 'Valider' }}
        </button>
    </form>
</div>
            </div>

            <!-- Dates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informations</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Inscrit le</span>
                        <p class="text-gray-900 font-semibold">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Modifié le</span>
                        <p class="text-gray-900">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    <button onclick="confirmDelete()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                        Supprimer l'utilisateur
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.auto-ecole.users.destroy', $user->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete() {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endsection
