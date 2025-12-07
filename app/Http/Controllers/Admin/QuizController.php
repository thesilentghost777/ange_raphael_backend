<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapitre;
use App\Models\Quiz;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $quiz = Quiz::with(['chapitre.module'])
            ->withCount('options')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.auto-ecole.quiz.index', compact('quiz'));
    }

    public function create()
    {
        $chapitres = Chapitre::with('module')->orderBy('ordre')->get();
        return view('admin.auto-ecole.quiz.create', compact('chapitres'));
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'chapitre_id' => 'required|exists:chapitres,id',
        'question' => 'required|string',
        'ordre' => 'required|integer|min:1',
        'points' => 'required|integer|min:1',
        'options' => 'required|array|min:2',
        'options.*.texte' => 'required|string',
        'options.*.est_correct' => 'nullable' // Changé de 'required|boolean' à 'nullable'
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Vérifier qu'il y a au moins une bonne réponse
    $hasCorrectAnswer = collect($request->options)->contains(function($option) {
        return isset($option['est_correct']) && $option['est_correct'] == '1';
    });

    if (!$hasCorrectAnswer) {
        return redirect()->back()
            ->with('error', 'Il doit y avoir au moins une réponse correcte')
            ->withInput();
    }

    DB::beginTransaction();
    try {
        $quiz = Quiz::create([
            'chapitre_id' => $request->chapitre_id,
            'question' => $request->question,
            'type' => 'qcm', // Ajout du type manquant
            'ordre' => $request->ordre,
            'points' => $request->points ?? 1 // Gérer si points n'existe pas dans la table
        ]);

        foreach ($request->options as $index => $option) {
            QuizOption::create([
                'quiz_id' => $quiz->id,
                'option_texte' => $option['texte'], // ✅ Correspond à la migration
                'est_correcte' => isset($option['est_correct']) && $option['est_correct'] == '1', // ✅ Correspond à la migration
                'ordre' => $index + 1 // Ajout de l'ordre manquant
            ]);
        }

        DB::commit();
        return redirect()->route('admin.auto-ecole.quiz.show', $quiz->id)
            ->with('success', 'Quiz créé avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Erreur lors de la création du quiz: ' . $e->getMessage())
            ->withInput();
    }
}
    public function show($id)
{
    $quiz = Quiz::with(['chapitre.module', 'options'])->findOrFail($id);
    
    // Statistiques basées sur votre structure de base de données
    // Les résultats sont liés au chapitre, pas au quiz individuel
    $resultatsParChapitre = \App\Models\ResultatQuiz::where('chapitre_id', $quiz->chapitre_id)->get();
    
    $stats = [
        'total_tentatives' => $resultatsParChapitre->count(),
        'tentatives_reussies' => $resultatsParChapitre->where('reussi', true)->count(),
        'reponses_correctes' => $resultatsParChapitre->where('reussi', true)->count(), // Ajouté pour la vue
        'taux_reussite' => $resultatsParChapitre->count() > 0 
            ? round(($resultatsParChapitre->where('reussi', true)->count() / $resultatsParChapitre->count()) * 100, 2)
            : 0,
        'score_moyen' => $resultatsParChapitre->count() > 0
            ? round($resultatsParChapitre->avg('score'), 2)
            : 0,
        'nombre_options' => $quiz->options->count(),
        'options_correctes' => $quiz->options->where('est_correcte', true)->count()
    ];
    
    return view('admin.auto-ecole.quiz.show', compact('quiz', 'stats'));
}

    public function edit($id)
    {
        $quiz = Quiz::with('options')->findOrFail($id);
        $chapitres = Chapitre::with('module')->orderBy('ordre')->get();
        return view('admin.auto-ecole.quiz.edit', compact('quiz', 'chapitres'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'chapitre_id' => 'required|exists:chapitres,id',
            'question' => 'required|string',
            'ordre' => 'required|integer|min:1',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.texte' => 'required|string',
            'options.*.est_correct' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Vérifier qu'il y a au moins une bonne réponse
        $hasCorrectAnswer = collect($request->options)->contains('est_correct', true);
        if (!$hasCorrectAnswer) {
            return redirect()->back()
                ->with('error', 'Il doit y avoir au moins une réponse correcte')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $quiz->update([
                'chapitre_id' => $request->chapitre_id,
                'question' => $request->question,
                'ordre' => $request->ordre,
                'points' => $request->points
            ]);

            // Supprimer les anciennes options
            $quiz->options()->delete();

            // Créer les nouvelles options
            foreach ($request->options as $option) {
                QuizOption::create([
                    'quiz_id' => $quiz->id,
                    'texte' => $option['texte'],
                    'est_correct' => $option['est_correct']
                ]);
            }

            DB::commit();

            return redirect()->route('admin.auto-ecole.quiz.show', $quiz->id)
                ->with('success', 'Quiz mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du quiz: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
{
    $quiz = Quiz::findOrFail($id);
    
    DB::beginTransaction();
    try {
        // Supprimer uniquement les options associées au quiz
        // Les résultats sont liés au chapitre, pas au quiz individuel
        // Donc pas besoin de les supprimer ici
        $quiz->options()->delete();
        
        // Supprimer le quiz
        $quiz->delete();
        
        DB::commit();
        
        return redirect()->route('admin.auto-ecole.quiz.index')
            ->with('success', 'Quiz supprimé avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Erreur lors de la suppression du quiz: ' . $e->getMessage());
    }
}

    public function duplicate($id)
{
    $quiz = Quiz::with('options')->findOrFail($id);
    
    DB::beginTransaction();
    try {
        // Créer le nouveau quiz
        $newQuiz = Quiz::create([
            'chapitre_id' => $quiz->chapitre_id,
            'question' => $quiz->question . ' (Copie)',
            'type' => $quiz->type, // ✅ Ajouté (obligatoire dans la migration)
            'ordre' => Quiz::where('chapitre_id', $quiz->chapitre_id)->max('ordre') + 1
        ]);
        
        // Dupliquer les options avec les bons noms de colonnes
        foreach ($quiz->options as $option) {
            QuizOption::create([
                'quiz_id' => $newQuiz->id,
                'option_texte' => $option->option_texte, // ✅ Corrigé: texte → option_texte
                'est_correcte' => $option->est_correcte, // ✅ Corrigé: est_correct → est_correcte
                'ordre' => $option->ordre // ✅ Ajouté (obligatoire dans la migration)
            ]);
        }
        
        DB::commit();
        
        return redirect()->route('admin.auto-ecole.quiz.edit', $newQuiz->id)
            ->with('success', 'Quiz dupliqué avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Erreur lors de la duplication du quiz: ' . $e->getMessage());
    }
}
}
