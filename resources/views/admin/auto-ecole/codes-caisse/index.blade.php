@extends('layouts.admin')

@section('title', $isFrench ? 'Gestion des Codes Caisse' : 'Cash Code Management')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-ticket-alt text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Codes Caisse' : 'Cash Codes' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Gérer les codes de paiement caisse' : 'Manage cash payment codes' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.auto-ecole.codes-caisse.create') }}" 
                       class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        {{ $isFrench ? 'Nouveau Code' : 'New Code' }}
                    </a>
                    <a href="{{ route('admin.auto-ecole.codes-caisse.export-non-utilises') }}" 
                       class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-file-export mr-2"></i>
                        {{ $isFrench ? 'Exporter CSV' : 'Export CSV' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Total Codes' : 'Total Codes' }}</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Utilisés' : 'Used' }}</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['utilises']) }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Disponibles' : 'Available' }}</p>
                        <p class="text-3xl font-bold text-amber-600 mt-2">{{ number_format($stats['non_utilises']) }}</p>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-full">
                        <i class="fas fa-hourglass-half text-amber-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Expirés' : 'Expired' }}</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($stats['expires']) }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-full">
                        <i class="fas fa-clock text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">{{ $isFrench ? 'Montant Total' : 'Total Amount' }}</p>
                        <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($stats['montant_total']) }} F</p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class="fas fa-money-bill-wave text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.auto-ecole.codes-caisse.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Recherche' : 'Search' }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ $isFrench ? 'Code, nom, téléphone...' : 'Code, name, phone...' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="utilise" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="1" {{ request('utilise') == '1' ? 'selected' : '' }}>{{ $isFrench ? 'Utilisés' : 'Used' }}</option>
                            <option value="0" {{ request('utilise') == '0' ? 'selected' : '' }}>{{ $isFrench ? 'Non utilisés' : 'Not Used' }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $isFrench ? 'Tranche' : 'Installment' }}
                        </label>
                        <select name="tranche" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="x" {{ request('tranche') == 'x' ? 'selected' : '' }}>X</option>
                            <option value="y" {{ request('tranche') == 'y' ? 'selected' : '' }}>Y</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>
                            {{ $isFrench ? 'Filtrer' : 'Filter' }}
                        </button>
                        <a href="{{ route('admin.auto-ecole.codes-caisse.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all duration-200">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-amber-500 to-amber-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Code' : 'Code' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Utilisateur' : 'User' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Montant' : 'Amount' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Tranche' : 'Installment' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Date Création' : 'Created Date' }}
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">
                                {{ $isFrench ? 'Actions' : 'Actions' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($codes as $code)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-blue-600">{{ $code->code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($code->user)
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $code->user->nom_complet }}</p>
                                            <p class="text-sm text-gray-500">{{ $code->user->telephone }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">{{ $isFrench ? 'Non assigné' : 'Unassigned' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900">{{ number_format($code->montant) }} FCFA</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-bold rounded-full {{ $code->tranche == 'x' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ strtoupper($code->tranche) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($code->utilise)
                                        <span class="px-3 py-1 text-sm font-bold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ $isFrench ? 'Utilisé' : 'Used' }}
                                        </span>
                                    @elseif($code->date_expiration && $code->date_expiration < now())
                                        <span class="px-3 py-1 text-sm font-bold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            {{ $isFrench ? 'Expiré' : 'Expired' }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-bold rounded-full bg-amber-100 text-amber-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $isFrench ? 'Disponible' : 'Available' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $code->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.auto-ecole.codes-caisse.show', $code->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$code->utilise)
                                        <button onclick="deleteCode({{ $code->id }})" 
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucun code trouvé' : 'No codes found' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($codes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $codes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function deleteCode(id) {
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
            form.action = `/admin/auto-ecole/codes-caisse/${id}`;
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
@endsection
