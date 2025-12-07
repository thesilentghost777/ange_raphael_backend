@extends('layouts.admin')

@section('title', $isFrench ? 'Modules de Cours' : 'Course Modules')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-book text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Modules de Cours' : 'Course Modules' }}
                    </h1>
                    <p class="text-gray-600">
                        {{ $isFrench ? 'Gérer les modules théoriques et pratiques' : 'Manage theoretical and practical modules' }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.cours.modules.create') }}" 
                   class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Nouveau Module' : 'New Module' }}
                </a>
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

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($modules as $module)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 overflow-hidden group">
                    <!-- Header -->
                    <div class="bg-gradient-to-r {{ $module->type === 'theorique' ? 'from-blue-500 to-blue-600' : 'from-green-500 to-green-600' }} px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-white text-lg font-bold">{{ $isFrench ? 'Module' : 'Module' }} {{ $module->ordre }}</span>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-white {{ $module->type === 'theorique' ? 'text-blue-600' : 'text-green-600' }}">
                                        {{ $module->type === 'theorique' ? ($isFrench ? 'Théorique' : 'Theory') : ($isFrench ? 'Pratique' : 'Practice') }}
                                    </span>
                                </div>
                                <h3 class="text-white font-bold text-lg line-clamp-2">{{ $module->nom }}</h3>
                            </div>
                            <i class="fas {{ $module->type === 'theorique' ? 'fa-graduation-cap' : 'fa-car' }} text-white text-2xl opacity-75"></i>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        @if($module->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $module->description }}</p>
                        @endif

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <i class="fas fa-book-open text-blue-600 text-xl mb-1"></i>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Chapitres' : 'Chapters' }}</p>
                                <p class="text-xl font-bold text-blue-600">{{ $module->chapitres_count }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <i class="fas fa-tasks text-green-600 text-xl mb-1"></i>
                                <p class="text-sm text-gray-600">{{ $isFrench ? 'Leçons' : 'Lessons' }}</p>
                                <p class="text-xl font-bold text-green-600">{{ $module->lecons_count }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.auto-ecole.cours.chapitres.index', $module->id) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 text-center text-sm">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $isFrench ? 'Voir' : 'View' }}
                            </a>
                            <a href="{{ route('admin.auto-ecole.cours.modules.edit', $module->id) }}" 
                               class="bg-amber-100 text-amber-700 px-4 py-2 rounded-lg font-semibold hover:bg-amber-200 transition-all duration-200">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteModule({{ $module->id }})" 
                                    class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-semibold hover:bg-red-200 transition-all duration-200"
                                    {{ $module->chapitres_count > 0 ? 'disabled title="' . ($isFrench ? 'Module contient des chapitres' : 'Module contains chapters') . '"' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <i class="fas fa-book text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium mb-4">
                            {{ $isFrench ? 'Aucun module créé' : 'No modules created' }}
                        </p>
                        <a href="{{ route('admin.auto-ecole.cours.modules.create') }}" 
                           class="inline-block bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            {{ $isFrench ? 'Créer le premier module' : 'Create first module' }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function deleteModule(id) {
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
            form.action = `/admin/auto-ecole/cours/modules/${id}`;
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
