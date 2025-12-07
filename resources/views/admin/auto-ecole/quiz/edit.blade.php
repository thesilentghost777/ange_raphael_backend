@extends('layouts.admin')

@section('title', $isFrench ? 'Modifier le Quiz' : 'Edit Quiz')

@section('admin-content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm mb-4" aria-label="Breadcrumb">
                <a href="{{ route('admin.auto-ecole.quiz.index') }}" 
                   class="text-gray-600 hover:text-amber-600 transition-colors">
                    {{ $isFrench ? 'Quiz' : 'Quizzes' }}
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-amber-600 font-semibold">{{ $isFrench ? 'Modifier' : 'Edit' }}</span>
            </nav>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                <i class="fas fa-edit text-amber-600 mr-3"></i>
                {{ $isFrench ? 'Modifier le Quiz' : 'Edit Quiz' }}
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden" x-data="quizForm()">
            <form action="{{ route('admin.auto-ecole.quiz.update', $quiz->id) }}" method="POST" class="p-6 md:p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Chapitre -->
                    <div>
                        <label for="chapitre_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-book text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Chapitre' : 'Chapter' }} *
                        </label>
                        <select name="chapitre_id" 
                                id="chapitre_id" 
                                required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('chapitre_id') border-red-500 @enderror">
                            <option value="">{{ $isFrench ? 'Sélectionnez un chapitre' : 'Select a chapter' }}</option>
                            @foreach($chapitres as $chapitre)
                                <option value="{{ $chapitre->id }}" {{ old('chapitre_id', $quiz->chapitre_id) == $chapitre->id ? 'selected' : '' }}>
                                    {{ $chapitre->module->nom }} - {{ $chapitre->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('chapitre_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Question -->
                    <div>
                        <label for="question" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-question text-purple-600 mr-2"></i>
                            {{ $isFrench ? 'Question' : 'Question' }} *
                        </label>
                        <textarea name="question" 
                                  id="question" 
                                  required
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('question') border-red-500 @enderror">{{ old('question', $quiz->question) }}</textarea>
                        @error('question')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Ordre et Points -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ordre" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up text-green-600 mr-2"></i>
                                {{ $isFrench ? 'Ordre' : 'Order' }} *
                            </label>
                            <input type="number" 
                                   name="ordre" 
                                   id="ordre" 
                                   required
                                   min="1"
                                   value="{{ old('ordre', $quiz->ordre) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('ordre') border-red-500 @enderror">
                            @error('ordre')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="points" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-star text-amber-600 mr-2"></i>
                                {{ $isFrench ? 'Points' : 'Points' }} *
                            </label>
                            <input type="number" 
                                   name="points" 
                                   id="points" 
                                   required
                                   min="1"
                                   value="{{ old('points', $quiz->points) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200 @error('points') border-red-500 @enderror">
                            @error('points')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Options -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-4">
                            <i class="fas fa-list text-blue-600 mr-2"></i>
                            {{ $isFrench ? 'Options de réponse' : 'Answer options' }} *
                        </label>

                        <div class="space-y-4" id="options-container">
                            <template x-for="(option, index) in options" :key="index">
                                <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" 
                                               :name="`options[${index}][est_correct]`"
                                               value="1"
                                               x-model="option.est_correct"
                                               class="mt-1 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        
                                        <div class="flex-1">
                                            <input type="text" 
                                                   :name="`options[${index}][texte]`"
                                                   x-model="option.texte"
                                                   required
                                                   :placeholder="'{{ $isFrench ? 'Option' : 'Option' }} ' + (index + 1)"
                                                   class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all duration-200">
                                            <input type="hidden" :name="`options[${index}][est_correct]`" value="0" x-show="!option.est_correct">
                                        </div>

                                        <button type="button" 
                                                @click="removeOption(index)"
                                                x-show="options.length > 2"
                                                class="text-red-600 hover:text-red-800 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 ml-8">
                                        {{ $isFrench ? 'Cochez si cette option est correcte' : 'Check if this option is correct' }}
                                    </p>
                                </div>
                            </template>
                        </div>

                        <button type="button" 
                                @click="addOption"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            {{ $isFrench ? 'Ajouter une option' : 'Add option' }}
                        </button>

                        @error('options')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                    
                    <a href="{{ route('admin.auto-ecole.quiz.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function quizForm() {
    return {
        options: @json($quiz->options->map(fn($opt) => ['texte' => $opt->texte, 'est_correct' => $opt->est_correct])),
        addOption() {
            this.options.push({ texte: '', est_correct: false });
        },
        removeOption(index) {
            if (this.options.length > 2) {
                this.options.splice(index, 1);
            }
        }
    }
}
</script>
@endsection
