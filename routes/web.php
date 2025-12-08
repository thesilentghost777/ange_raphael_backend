<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GhostController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\AutoEcoleUserController;
use App\Http\Controllers\Admin\CodeCaisseController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\CoursController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ConfigPaiementController;
use App\Http\Controllers\Admin\AdminPaiementController;
use App\Http\Controllers\StatistiqueController;

Route::middleware('auth','track_statistic')->group(function () {
    Route::get('/dashboard', [StatistiqueController::class, 'index'])
        ->name('dashboard');

    Route::post('/lang/fr', [LanguageController::class, 'setFrench'])->name('lang.fr');
    Route::post('/lang/en', [LanguageController::class, 'setEnglish'])->name('lang.en');
   
    Route::get('/', [GhostController::class, 'index'])->name('ghost.index');
    Route::get('/stats', [GhostController::class, 'stats'])->name('ghost.stats');
    Route::get('/logs', [GhostController::class, 'logs'])->name('ghost.logs');
    Route::get('/notifications', [GhostController::class, 'notifications'])->name('ghost.notifications');
    Route::post('/reset-today-stats', [GhostController::class, 'resetTodayStats'])->name('ghost.reset-today-stats');
});

 Route::prefix('/admin/paiements')->name('admin.paiements.')->group(function () {
        Route::get('/', [AdminPaiementController::class, 'index'])->name('index');
        Route::get('/statistiques', [AdminPaiementController::class, 'statistiques'])->name('statistiques');
        Route::get('/export', [AdminPaiementController::class, 'export'])->name('export');
        Route::get('/{id}', [AdminPaiementController::class, 'show'])->name('show');
    });

Route::prefix('admin/auto-ecole')->name('admin.auto-ecole.')->middleware(['auth'])->group(function () {
    
     // Configuration des paiements
    Route::get('/config-paiement', [ConfigPaiementController::class, 'edit'])->name('config-paiement.edit');
    Route::put('/config-paiement', [ConfigPaiementController::class, 'update'])->name('config-paiement.update');
    
     
    
    // ============ UTILISATEURS ============
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AutoEcoleUserController::class, 'index'])->name('index');
        Route::get('/create', [AutoEcoleUserController::class, 'create'])->name('create');
        Route::post('/', [AutoEcoleUserController::class, 'store'])->name('store');
        Route::get('/{id}', [AutoEcoleUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AutoEcoleUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AutoEcoleUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [AutoEcoleUserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/valider', [AutoEcoleUserController::class, 'valider'])->name('valider');
        Route::post('/{id}/invalider', [AutoEcoleUserController::class, 'invalider'])->name('invalider');
        Route::post('/{id}/toggle-actif', [AutoEcoleUserController::class, 'toggleActif'])->name('toggle-actif');
    });

    // ============ CODES CAISSE ============
    Route::prefix('codes-caisse')->name('codes-caisse.')->group(function () {
        Route::get('/', [CodeCaisseController::class, 'index'])->name('index');
        Route::get('/create', [CodeCaisseController::class, 'create'])->name('create');
        Route::post('/', [CodeCaisseController::class, 'store'])->name('store');
        Route::get('/{id}', [CodeCaisseController::class, 'show'])->name('show');
        Route::delete('/{id}', [CodeCaisseController::class, 'destroy'])->name('destroy');
        Route::post('/generer-multiple', [CodeCaisseController::class, 'genererMultiple'])->name('generer-multiple');
        Route::get('/export/non-utilises', [CodeCaisseController::class, 'exportNonUtilises'])->name('export-non-utilises');
    });

    // ============ SESSIONS ============
    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [SessionController::class, 'index'])->name('index');
        Route::get('/create', [SessionController::class, 'create'])->name('create');
        Route::post('/', [SessionController::class, 'store'])->name('store');
        Route::get('/{id}', [SessionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SessionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SessionController::class, 'update'])->name('update');
        Route::delete('/{id}', [SessionController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/changer-statut', [SessionController::class, 'changerStatut'])->name('changer-statut');
    });

    Route::post('sessions/{id}/changer-statut', [SessionController::class, 'changerStatut'])
        ->name('sessions.changer-statut');
        
    // Jours de pratique
    Route::prefix('jours-pratique')->name('jours-pratique.')->group(function () {
        Route::get('/', [SessionController::class, 'joursPratiqueIndex'])->name('index');
        Route::get('create', [SessionController::class, 'joursPratiqueCreate'])->name('create');
        Route::post('/', [SessionController::class, 'joursPratiqueStore'])->name('store');
        Route::get('{id}/edit', [SessionController::class, 'joursPratiqueEdit'])->name('edit');
        Route::put('{id}', [SessionController::class, 'joursPratiqueUpdate'])->name('update');
        Route::delete('{id}', [SessionController::class, 'joursPratiqueDestroy'])->name('destroy');
        Route::post('{id}/toggle-actif', [SessionController::class, 'joursPratiqueToggleActif'])
            ->name('toggle-actif');
    });
    
    // Centres d'examen
    Route::prefix('centres-examen')->name('centres-examen.')->group(function () {
        Route::get('/', [SessionController::class, 'centresExamenIndex'])->name('index');
        Route::get('create', [SessionController::class, 'centresExamenCreate'])->name('create');
        Route::post('/', [SessionController::class, 'centresExamenStore'])->name('store');
        Route::get('{id}', [SessionController::class, 'centresExamenShow'])->name('show');
        Route::get('{id}/edit', [SessionController::class, 'centresExamenEdit'])->name('edit');
        Route::put('{id}', [SessionController::class, 'centresExamenUpdate'])->name('update');
        Route::delete('{id}', [SessionController::class, 'centresExamenDestroy'])->name('destroy');
        Route::post('{id}/toggle-actif', [SessionController::class, 'centresExamenToggleActif'])
            ->name('toggle-actif');
    });

    // ============ COURS (MODULES, CHAPITRES, LEÇONS) ============
    Route::prefix('cours')->name('cours.')->group(function () {
        
        // Modules
        Route::prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [CoursController::class, 'indexModules'])->name('index');
            Route::get('/create', [CoursController::class, 'createModule'])->name('create');
            Route::post('/', [CoursController::class, 'storeModule'])->name('store');
            Route::get('/{id}/edit', [CoursController::class, 'editModule'])->name('edit');
            Route::put('/{id}', [CoursController::class, 'updateModule'])->name('update');
            Route::delete('/{id}', [CoursController::class, 'destroyModule'])->name('destroy');
        });

        // Chapitres
        Route::prefix('modules/{moduleId}/chapitres')->name('chapitres.')->group(function () {
            Route::get('/', [CoursController::class, 'indexChapitres'])->name('index');
            Route::get('/create', [CoursController::class, 'createChapitre'])->name('create');
            Route::post('/', [CoursController::class, 'storeChapitre'])->name('store');
            Route::get('/{id}/edit', [CoursController::class, 'editChapitre'])->name('edit');
            Route::put('/{id}', [CoursController::class, 'updateChapitre'])->name('update');
            Route::delete('/{id}', [CoursController::class, 'destroyChapitre'])->name('destroy');
        });

        // Leçons
        Route::prefix('modules/{moduleId}/chapitres/{chapitreId}/lecons')->name('lecons.')->group(function () {
            Route::get('/', [CoursController::class, 'indexLecons'])->name('index');
            Route::get('/create', [CoursController::class, 'createLecon'])->name('create');
            Route::post('/', [CoursController::class, 'storeLecon'])->name('store');
            Route::get('/{id}/edit', [CoursController::class, 'editLecon'])->name('edit');
            Route::put('/{id}', [CoursController::class, 'updateLecon'])->name('update');
            Route::delete('/{id}', [CoursController::class, 'destroyLecon'])->name('destroy');
        });
    });

    // ============ QUIZ ============
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('index');
        Route::get('/create', [QuizController::class, 'create'])->name('create');
        Route::post('/', [QuizController::class, 'store'])->name('store');
        Route::get('/{id}', [QuizController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [QuizController::class, 'edit'])->name('edit');
        Route::put('/{id}', [QuizController::class, 'update'])->name('update');
        Route::delete('/{id}', [QuizController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/duplicate', [QuizController::class, 'duplicate'])->name('duplicate');
    });
});
require __DIR__.'/auth.php';
