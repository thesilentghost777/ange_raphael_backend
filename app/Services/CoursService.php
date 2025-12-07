<?php

namespace App\Services;

use App\Models\AutoEcoleUser;
use App\Models\Module;
use App\Models\Chapitre;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\ProgressionLecon;
use App\Models\ResultatQuiz;
use Illuminate\Support\Facades\DB;

class CoursService
{
    /**
     * Récupère la structure complète des cours pour un utilisateur
     */
    public function getStructureCours(int $userId, string $type): array
    {
        $user = AutoEcoleUser::find($userId);

        if (!$user || $user->cours_verrouilles) {
            return [
                'success' => false,
                'message' => 'Accès aux cours verrouillé. Veuillez effectuer le paiement.'
            ];
        }

        $modules = Module::where('type', $type)
            ->with(['chapitres.lecons', 'chapitres.quiz.options'])
            ->orderBy('ordre')
            ->get();

        return [
            'success' => true,
            'modules' => $modules->map(function($module) use ($userId) {
                return $this->formatModule($module, $userId);
            })
        ];
    }

    /**
     * Formate un module avec progression
     */
    private function formatModule(Module $module, int $userId): array
    {
        return [
            'id' => $module->id,
            'nom' => $module->nom,
            'type' => $module->type,
            'description' => $module->description,
            'ordre' => $module->ordre,
            'chapitres' => $module->chapitres->map(function($chapitre) use ($userId) {
                return $this->formatChapitre($chapitre, $userId);
            })
        ];
    }

    /**
     * Formate un chapitre avec progression
     */
    private function formatChapitre(Chapitre $chapitre, int $userId): array
    {
        return [
            'id' => $chapitre->id,
            'nom' => $chapitre->nom,
            'description' => $chapitre->description,
            'ordre' => $chapitre->ordre,
            'lecons' => $chapitre->lecons->map(function($lecon) use ($userId) {
                return $this->formatLecon($lecon, $userId);
            }),
            'quiz_disponible' => $this->quizDisponible($chapitre, $userId),
            'quiz_reussi' => $this->quizReussi($chapitre->id, $userId)
        ];
    }

    /**
     * Formate une leçon avec progression
     */
    private function formatLecon(Lecon $lecon, int $userId): array
    {
        $progression = ProgressionLecon::where('user_id', $userId)
            ->where('lecon_id', $lecon->id)
            ->first();

        return [
            'id' => $lecon->id,
            'titre' => $lecon->titre,
            'contenu_texte' => $lecon->contenu_texte,
            'image_url' => $lecon->image_url,
            'video_url' => $lecon->video_url,
            'ordre' => $lecon->ordre,
            'duree_minutes' => $lecon->duree_minutes,
            'completee' => $progression ? $progression->completee : false,
            'accessible' => $this->leconAccessible($lecon, $userId)
        ];
    }

    /**
     * Vérifie si une leçon est accessible
     */
    private function leconAccessible(Lecon $lecon, int $userId): bool
    {
        // La première leçon de chaque chapitre est toujours accessible
        $premiereLecon = $lecon->chapitre->lecons()->orderBy('ordre')->first();
        if ($lecon->id === $premiereLecon->id) {
            return true;
        }

        // Vérifier si la leçon précédente est complétée
        $leconPrecedente = Lecon::where('chapitre_id', $lecon->chapitre_id)
            ->where('ordre', '<', $lecon->ordre)
            ->orderBy('ordre', 'desc')
            ->first();

        if ($leconPrecedente) {
            return ProgressionLecon::where('user_id', $userId)
                ->where('lecon_id', $leconPrecedente->id)
                ->where('completee', true)
                ->exists();
        }

        return false;
    }

    /**
     * Marque une leçon comme complétée
     */
    public function marquerLeconCompletee(int $userId, int $leconId): array
    {
        try {
            $lecon = Lecon::findOrFail($leconId);

            if (!$this->leconAccessible($lecon, $userId)) {
                return [
                    'success' => false,
                    'message' => 'Cette leçon n\'est pas encore accessible'
                ];
            }

            ProgressionLecon::updateOrCreate(
                [
                    'user_id' => $userId,
                    'lecon_id' => $leconId
                ],
                [
                    'completee' => true,
                    'date_completion' => now()
                ]
            );

            return [
                'success' => true,
                'message' => 'Leçon complétée avec succès'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifie si le quiz d'un chapitre est disponible
     */
    private function quizDisponible(Chapitre $chapitre, int $userId): bool
    {
        // Toutes les leçons doivent être complétées
        $totalLecons = $chapitre->lecons()->count();
        $leconsCompletees = ProgressionLecon::where('user_id', $userId)
            ->whereIn('lecon_id', $chapitre->lecons()->pluck('id'))
            ->where('completee', true)
            ->count();

        return $totalLecons === $leconsCompletees;
    }

    /**
     * Vérifie si le quiz d'un chapitre est réussi
     */
    private function quizReussi(int $chapitreId, int $userId): bool
    {
        return ResultatQuiz::where('user_id', $userId)
            ->where('chapitre_id', $chapitreId)
            ->where('reussi', true)
            ->exists();
    }

    /**
     * Récupère le quiz d'un chapitre
     */
    public function getQuiz(int $userId, int $chapitreId): array
    {
        $user = AutoEcoleUser::find($userId);
        $chapitre = Chapitre::with('quiz.options')->findOrFail($chapitreId);

        if ($user->cours_verrouilles) {
            return [
                'success' => false,
                'message' => 'Accès aux cours verrouillé'
            ];
        }

        if (!$this->quizDisponible($chapitre, $userId)) {
            return [
                'success' => false,
                'message' => 'Vous devez compléter toutes les leçons avant d\'accéder au quiz'
            ];
        }

        // Vérifier si peut retenter
        $dernierResultat = ResultatQuiz::where('user_id', $userId)
            ->where('chapitre_id', $chapitreId)
            ->latest()
            ->first();

        if ($dernierResultat && !$dernierResultat->peut_retenter) {
            return [
                'success' => false,
                'message' => 'Vous ne pouvez pas retenter ce quiz avant le ' . 
                            $dernierResultat->date_prochaine_tentative->format('d/m/Y H:i')
            ];
        }

        return [
            'success' => true,
            'chapitre' => $chapitre->nom,
            'questions' => $chapitre->quiz->map(function($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'type' => $q->type,
                    'options' => $q->options->map(function($opt) {
                        return [
                            'id' => $opt->id,
                            'texte' => $opt->option_texte,
                            // Ne pas envoyer la réponse correcte au client
                        ];
                    })
                ];
            })
        ];
    }

    /**
     * Soumet les réponses d'un quiz
     */
    public function soumettreQuiz(int $userId, int $chapitreId, array $reponses): array
    {
        try {
            DB::beginTransaction();

            $chapitre = Chapitre::with('quiz.options')->findOrFail($chapitreId);
            
            $totalQuestions = $chapitre->quiz->count();
            $bonnesReponses = 0;

            foreach ($reponses as $quizId => $optionId) {
                $quiz = $chapitre->quiz->firstWhere('id', $quizId);
                if ($quiz) {
                    $optionCorrecte = $quiz->options->firstWhere('est_correcte', true);
                    if ($optionCorrecte && $optionCorrecte->id == $optionId) {
                        $bonnesReponses++;
                    }
                }
            }

            $score = ($bonnesReponses / $totalQuestions) * 20;
            $reussi = $score >= 12;

            $resultat = ResultatQuiz::create([
                'user_id' => $userId,
                'chapitre_id' => $chapitreId,
                'score' => $score,
                'reussi' => $reussi,
                'date_tentative' => now(),
                'peut_retenter' => !$reussi,
                'date_prochaine_tentative' => $reussi ? null : now()->addHours(24)
            ]);

            DB::commit();

            return [
                'success' => true,
                'score' => $score,
                'reussi' => $reussi,
                'bonnes_reponses' => $bonnesReponses,
                'total_questions' => $totalQuestions,
                'message' => $reussi ? 
                    'Félicitations ! Vous avez réussi le quiz.' : 
                    'Score insuffisant. Vous pourrez réessayer dans 24h.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupère la progression globale d'un utilisateur
     */
    public function getProgression(int $userId, string $type): array
    {
        $totalLecons = Lecon::whereHas('chapitre.module', function($q) use ($type) {
            $q->where('type', $type);
        })->count();

        $leconsCompletees = ProgressionLecon::where('user_id', $userId)
            ->whereHas('lecon.chapitre.module', function($q) use ($type) {
                $q->where('type', $type);
            })
            ->where('completee', true)
            ->count();

        $totalQuiz = Chapitre::whereHas('module', function($q) use ($type) {
            $q->where('type', $type);
        })->count();

        $quizReussis = ResultatQuiz::where('user_id', $userId)
            ->whereHas('chapitre.module', function($q) use ($type) {
                $q->where('type', $type);
            })
            ->where('reussi', true)
            ->count();

        $pourcentage = $totalLecons > 0 ? 
            (($leconsCompletees / $totalLecons) * 100) : 0;

        return [
            'total_lecons' => $totalLecons,
            'lecons_completees' => $leconsCompletees,
            'total_quiz' => $totalQuiz,
            'quiz_reussis' => $quizReussis,
            'pourcentage_completion' => round($pourcentage, 2)
        ];
    }
}
