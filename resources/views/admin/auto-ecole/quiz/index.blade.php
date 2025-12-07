@extends('layouts.admin')

@section('title', $isFrench ? 'Quiz' : 'Quizzes')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        <i class="fas fa-question-circle text-amber-600 mr-3"></i>
                        {{ $isFrench ? 'Gestion des Quiz' : 'Quiz Management' }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ $isFrench ? 'Créez et gérez les questions des quiz' : 'Create and manage quiz questions' }}
                    </p>
                </div>
                <a href="{{ route('admin.auto-ecole.quiz.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Nouveau Quiz' : 'New Quiz' }}
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

        <!-- Liste des quiz -->
        @if($quiz->count() > 0)
            <div class="space-y-4 mb-8">
                @foreach($quiz as $q)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="flex flex-col md:flex-row">
                            <!-- Indicateur latéral -->
                            <div class="md:w-2 bg-gradient-to-b from-blue-500 to-blue-600"></div>
                            
                            <!-- Contenu -->
                            <div class="flex-1 p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3 mb-3">
                                            <span class="flex-shrink-0 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                                #{{ $q->ordre }}
                                            </span>
                                            <h3 class="text-lg font-bold text-gray-900 flex-1">
                                                {{ $q->question }}
                                            </h3>
                                        </div>

                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $q->chapitre->module->nom }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-book-open mr-1"></i>
                                                {{ $q->chapitre->nom }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-star mr-1"></i>
                                                {{ $q->points }} {{ $isFrench ? 'points' : 'points' }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-list mr-1"></i>
                                                {{ $q->options_count }} {{ $isFrench ? 'options' : 'options' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.auto-ecole.quiz.show', $q->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            {{ $isFrench ? 'Voir' : 'View' }}
                                        </a>
                                        
                                        <a href="{{ route('admin.auto-ecole.quiz.edit', $q->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                                        </a>
                                        
                                        <button onclick="duplicateQuiz({{ $q->id }})" 
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-semibold rounded-lg hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200">
                                            <i class="fas fa-copy mr-2"></i>
                                            {{ $isFrench ? 'Dupliquer' : 'Duplicate' }}
                                        </button>
                                        
                                        <button onclick="deleteQuiz({{ $q->id }})" 
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

            <!-- Pagination -->
            <div class="bg-white rounded-xl shadow-lg p-4">
                {{ $quiz->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-question-circle text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $isFrench ? 'Aucun quiz' : 'No quizzes' }}
                </h3>
                <p class="text-gray-600 mb-6">
                    {{ $isFrench ? 'Commencez par créer votre premier quiz' : 'Start by creating your first quiz' }}
                </p>
                <a href="{{ route('admin.auto-ecole.quiz.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-lg hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    {{ $isFrench ? 'Créer un quiz' : 'Create a quiz' }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function deleteQuiz(id) {
    Swal.fire({
        title: '{{ $isFrench ? "Êtes-vous sûr ?" : "Are you sure?" }}',
        text: '{{ $isFrench ? "Cette action supprimera également toutes les options et résultats associés" : "This will also delete all associated options and results" }}',
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
            form.action = `/admin/auto-ecole/quiz/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function duplicateQuiz(id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/auto-ecole/quiz/${id}/duplicate`;
    form.innerHTML = `
        @csrf
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
