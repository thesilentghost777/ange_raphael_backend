@extends('layouts.admin')

@section('title', $isFrench ? 'Chapitres' : 'Chapters')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header avec breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.auto-ecole.cours.modules.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    <i class="fas fa-book mr-1"></i>
                    {{ $isFrench ? 'Modules' : 'Modules' }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $module->nom }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-book-open text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Chapitres' : 'Chapters' }}
                    </h1>
                    <p class="text-gray-600 mt-2">{{ $module->nom }}</p>
                </div>
                <a href="{{ route('admin.auto-ecole.cours.chapitres.create', $module->id) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Nouveau Chapitre' : 'New Chapter' }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Liste des chapitres -->
        @if($module->chapitres->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($module->chapitres as $chapitre)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white font-bold text-sm">
                                        #{{ $chapitre->ordre }}
                                    </span>
                                    <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-white text-sm">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        {{ $chapitre->lecons_count }} {{ $isFrench ? 'leçons' : 'lessons' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                {{ $chapitre->nom }}
                            </h3>
                            
                            @if($chapitre->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ $chapitre->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap gap-2 mt-4">
                                <a href="{{ route('admin.auto-ecole.cours.lecons.index', [$module->id, $chapitre->id]) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    {{ $isFrench ? 'Leçons' : 'Lessons' }}
                                </a>
                                
                                <a href="{{ route('admin.auto-ecole.cours.chapitres.edit', [$module->id, $chapitre->id]) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button onclick="deleteChapitre({{ $chapitre->id }})" 
                                        class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-book-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $isFrench ? 'Aucun chapitre' : 'No chapters' }}
                </h3>
                <p class="text-gray-600 mb-6">
                    {{ $isFrench ? 'Commencez par créer votre premier chapitre' : 'Start by creating your first chapter' }}
                </p>
                <a href="{{ route('admin.auto-ecole.cours.chapitres.create', $module->id) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Créer un chapitre' : 'Create a chapter' }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function deleteChapitre(id) {
    Swal.fire({
        title: '{{ $isFrench ? "Êtes-vous sûr ?" : "Are you sure?" }}',
        text: '{{ $isFrench ? "Cette action supprimera également toutes les leçons associées" : "This will also delete all associated lessons" }}',
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
            form.action = `/admin/auto-ecole/cours/modules/{{ $module->id }}/chapitres/${id}`;
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
