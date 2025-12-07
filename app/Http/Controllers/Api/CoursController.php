<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CoursService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoursController extends Controller
{
    private $coursService;

    public function __construct(CoursService $coursService)
    {
        $this->coursService = $coursService;
    }

    /**
     * Liste des modules et cours (théorique ou pratique)
     */
    public function index(Request $request, $type)
{
    if (!in_array($type, ['theorique', 'pratique'])) {
        return response()->json([
            'success' => false,
            'message' => 'Type invalide. Utilisez "theorique" ou "pratique"'
        ], 400);
    }

    try {
        $user = $request->user();
        
        // Utiliser getStructureCours au lieu de getModulesAvecProgression
        $result = $this->coursService->getStructureCours($user->id, $type);

        // getStructureCours retourne déjà un tableau avec 'success' et 'modules' ou 'message'
        if (!$result['success']) {
            return response()->json($result, 403);
        }

        return response()->json([
            'success' => true,
            'modules' => $result['modules']
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Marquer une leçon comme complétée
     */
    public function completerLecon(Request $request, $id)
{
    try {
        $user = $request->user();
        
        // Utiliser marquerLeconCompletee au lieu de completerLecon
        $result = $this->coursService->marquerLeconCompletee($user->id, $id);
        
        // marquerLeconCompletee retourne un tableau avec 'success' et 'message'
        if (!$result['success']) {
            return response()->json($result, 400);
        }
        
        return response()->json($result);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

  public function getQuiz(Request $request, $id)
{
    try {
        $user = $request->user();
        
        // Utiliser getQuiz au lieu de getQuizPourChapitre
        $result = $this->coursService->getQuiz($user->id, $id);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'chapitre' => $result['chapitre'],
            'questions' => $result['questions']
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Soumettre les réponses à un quiz
 */
public function soumettreQuiz(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'reponses' => 'required|array'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }
    
    try {
        $user = $request->user();
        
        // Utiliser soumettreQuiz (correct) - pas besoin de changer
        $resultat = $this->coursService->soumettreQuiz($user->id, $id, $request->reponses);
        
        if (!$resultat['success']) {
            return response()->json($resultat, 400);
        }
        
        return response()->json([
            'success' => true,
            'score' => $resultat['score'],
            'reussi' => $resultat['reussi'],
            'bonnes_reponses' => $resultat['bonnes_reponses'],
            'total_questions' => $resultat['total_questions'],
            'message' => $resultat['message']
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Progression de l'étudiant (théorique ou pratique)
 */
public function progression(Request $request, $type)
{
    if (!in_array($type, ['theorique', 'pratique'])) {
        return response()->json([
            'success' => false,
            'message' => 'Type invalide. Utilisez "theorique" ou "pratique"'
        ], 400);
    }
    
    try {
        $user = $request->user();
        
        // Utiliser getProgression au lieu de getProgressionGlobale
        $progression = $this->coursService->getProgression($user->id, $type);
        
        return response()->json([
            'success' => true,
            'total_lecons' => $progression['total_lecons'],
            'lecons_completees' => $progression['lecons_completees'],
            'total_quiz' => $progression['total_quiz'],
            'quiz_reussis' => $progression['quiz_reussis'],
            'pourcentage_completion' => $progression['pourcentage_completion']
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
