@extends('layouts.admin')

@section('title', $isFrench ? 'Leçons' : 'Lessons')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header avec breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ route('admin.auto-ecole.cours.modules.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $isFrench ? 'Modules' : 'Modules' }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <a href="{{ route('admin.auto-ecole.cours.chapitres.index', $module->id) }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $module->nom }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $chapitre->nom }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-file-alt text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Leçons' : 'Lessons' }}
                    </h1>
                    <p class="text-gray-600 mt-2">{{ $chapitre->nom }}</p>
                </div>
                <a href="{{ route('admin.auto-ecole.cours.lecons.create', [$module->id, $chapitre->id]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Nouvelle Leçon' : 'New Lesson' }}
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

        <!-- Liste des leçons -->
        @if($chapitre->lecons->count() > 0)
            <div class="space-y-4">
                @foreach($chapitre->lecons as $lecon)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col md:flex-row">
                            <!-- Image -->
                            <div class="md:w-48 h-48 md:h-auto bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center relative overflow-hidden">
                                @if($lecon->image_url)
                                    <img src="{{ asset('storage/' . $lecon->image_url) }}" 
                                         alt="{{ $lecon->titre }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-file-alt text-white text-6xl opacity-50"></i>
                                @endif
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                    <span class="text-blue-600 font-bold text-sm">#{{ $lecon->ordre }}</span>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1 p-6">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                                            {{ $lecon->titre }}
                                        </h3>
                                        
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit(strip_tags($lecon->contenu_texte), 150) }}
                                        </p>

                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if($lecon->duree_minutes)
                                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $lecon->duree_minutes }} min
                                                </span>
                                            @endif
                                            
                                            @if($lecon->video_url)
                                                <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                                    <i class="fas fa-video mr-1"></i>
                                                    {{ $isFrench ? 'Vidéo' : 'Video' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.auto-ecole.cours.lecons.edit', [$module->id, $chapitre->id, $lecon->id]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        
                                        <button onclick="deleteLecon({{ $lecon->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                            <i class="fas fa-trash mr-2"></i>
                                            {{ $isFrench ? 'Supprimer' : 'Delete' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $isFrench ? 'Aucune leçon' : 'No lessons' }}
                </h3>
                <p class="text-gray-600 mb-6">
                    {{ $isFrench ? 'Commencez par créer votre première leçon' : 'Start by creating your first lesson' }}
                </p>
                <a href="{{ route('admin.auto-ecole.cours.lecons.create', [$module->id, $chapitre->id]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Créer une leçon' : 'Create a lesson' }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function deleteLecon(id) {
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
            form.action = `/admin/auto-ecole/cours/modules/{{ $module->id }}/chapitres/{{ $chapitre->id }}/lecons/${id}`;
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
