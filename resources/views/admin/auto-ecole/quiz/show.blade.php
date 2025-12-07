@extends('layouts.admin')

@section('title', $isFrench ? 'Détails du Quiz' : 'Quiz Details')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.auto-ecole.quiz.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    {{ $isFrench ? 'Retour aux quiz' : 'Back to quizzes' }}
                </a>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                <i class="fas fa-question-circle text-amber-600 mr-3"></i>
                {{ $isFrench ? 'Détails du Quiz' : 'Quiz Details' }}
            </h1>
        </div>

        <!-- Question -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white font-bold">
                        #{{ $quiz->ordre }}
                    </span>
                    <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white">
                        <i class="fas fa-layer-group mr-1"></i>
                        {{ $quiz->type }}
                    </span>
                </div>
                <p class="text-white text-xl md:text-2xl font-semibold">
                    {{ $quiz->question }}
                </p>
            </div>

            <div class="p-6">
                <!-- Informations du chapitre -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">
                        <i class="fas fa-book text-blue-600 mr-2"></i>
                        {{ $isFrench ? 'Chapitre' : 'Chapter' }}
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Module' : 'Module' }}</p>
                        <p class="font-semibold text-gray-900 mb-3">{{ $quiz->chapitre->module->nom }}</p>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Chapitre' : 'Chapter' }}</p>
                        <p class="font-semibold text-gray-900">{{ $quiz->chapitre->nom }}</p>
                    </div>
                </div>

                <!-- Options -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-list text-green-600 mr-2"></i>
                        {{ $isFrench ? 'Options de réponse' : 'Answer options' }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($quiz->options as $index => $option)
                            <div class="bg-gray-50 rounded-xl p-4 border-2 {{ $option->est_correcte ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <div class="flex items-start gap-3">
                                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $option->est_correcte ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }} font-bold text-sm">
                                        {{ chr(65 + $index) }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">{{ $option->option_texte }}</p>
                                        @if($option->est_correcte)
                                            <span class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <i class="fas fa-check mr-1"></i>
                                                {{ $isFrench ? 'Réponse correcte' : 'Correct answer' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                        {{ $isFrench ? 'Statistiques du chapitre' : 'Chapter statistics' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4 border-l-4 border-blue-500">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                {{ $isFrench ? 'Tentatives totales' : 'Total attempts' }}
                            </p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $stats['total_tentatives'] }}
                            </p>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4 border-l-4 border-green-500">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                {{ $isFrench ? 'Tentatives réussies' : 'Successful attempts' }}
                            </p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $stats['reponses_correctes'] }}
                            </p>
                        </div>

                        <div class="bg-amber-50 rounded-xl p-4 border-l-4 border-amber-500">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                {{ $isFrench ? 'Taux de réussite' : 'Success rate' }}
                            </p>
                            <p class="text-2xl font-bold text-amber-600">
                                {{ $stats['taux_reussite'] }}%
                            </p>
                        </div>
                    </div>

                    <!-- Statistiques supplémentaires -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-purple-50 rounded-xl p-4 border-l-4 border-purple-500">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                {{ $isFrench ? 'Score moyen' : 'Average score' }}
                            </p>
                            <p class="text-2xl font-bold text-purple-600">
                                {{ $stats['score_moyen'] }}%
                            </p>
                        </div>

                        <div class="bg-indigo-50 rounded-xl p-4 border-l-4 border-indigo-500">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                {{ $isFrench ? 'Options correctes' : 'Correct options' }}
                            </p>
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ $stats['options_correctes'] }} / {{ $stats['nombre_options'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.auto-ecole.quiz.edit', $quiz->id) }}" 
                       class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg text-center">
                        <i class="fas fa-edit mr-2"></i>
                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                    </a>
                    
                    <button onclick="duplicateQuiz()" 
                            class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-copy mr-2"></i>
                        {{ $isFrench ? 'Dupliquer' : 'Duplicate' }}
                    </button>
                    
                    <button onclick="deleteQuiz()" 
                            class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-trash mr-2"></i>
                        {{ $isFrench ? 'Supprimer' : 'Delete' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteQuiz() {
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
            form.action = '{{ route("admin.auto-ecole.quiz.destroy", $quiz->id) }}';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function duplicateQuiz() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.auto-ecole.quiz.duplicate", $quiz->id) }}';
    form.innerHTML = `
        @csrf
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection