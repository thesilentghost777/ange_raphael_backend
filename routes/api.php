<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AutoEcoleAuthController;
use App\Http\Controllers\Api\PaiementController;
use App\Http\Controllers\Api\CoursController;
use App\Http\Controllers\Api\ParrainageController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ConfigPaiementController;

Route::get('/test', function() {
    return response()->json(['message' => 'Ange Raphael fonctionne correctement']);
});
// Routes publiques
Route::post('/inscription', [AutoEcoleAuthController::class, 'inscription']);
Route::post('/connexion', [AutoEcoleAuthController::class, 'connexion']);
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/centres-examen', [SessionController::class, 'centresExamen']);
Route::get('/jours-pratique', [SessionController::class, 'joursPratique']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Profil
    Route::get('/profil', [AutoEcoleAuthController::class, 'profil']);
    Route::get('/config-paiement', [ConfigPaiementController::class, 'index'])
        ->name('api.config-paiement.index');
    Route::get('/acces/cours', [PaiementController::class, 'verifierAccesCours']);
    // Paiements
    Route::post('/paiement/initier', [PaiementController::class, 'initierPaiement']);
    Route::post('/paiement/code-caisse', [PaiementController::class, 'payerAvecCodeCaisse']);
    Route::get('/paiement/statut/{transactionId}', [PaiementController::class, 'verifierStatut']);
    Route::get('/paiements/historique', [PaiementController::class, 'historiquePaiements']);
    // Route pour la page de succès après paiement Monetbil
    Route::get('/paiement/monetbil/success', [PaiementController::class, 'monetbilSuccess'])
        ->name('paiement.monetbil.success');

    // Route pour la page d'échec après paiement Monetbil
    Route::get('/paiement/monetbil/failed', [PaiementController::class, 'monetbilFailed'])
        ->name('paiement.monetbil.failed');

    // Route pour la notification de Monetbil (webhook) - Choisir GET ou POST selon config
    Route::post('/paiement/monetbil/notification', [PaiementController::class, 'monetbilNotification'])
        ->name('paiement.monetbil.notification');

    // Cours
    Route::get('/cours/{type}', [CoursController::class, 'index']); // type: theorique|pratique
    Route::post('/cours/lecon/{id}/completer', [CoursController::class, 'completerLecon']);
    Route::get('/cours/chapitre/{id}/quiz', [CoursController::class, 'getQuiz']);
    Route::post('/cours/chapitre/{id}/quiz', [CoursController::class, 'soumettreQuiz']);
    Route::get('/cours/progression/{type}', [CoursController::class, 'progression']);
    
    // Parrainage
    Route::get('/parrainage/mon-arbre', [ParrainageController::class, 'monArbre']);
    Route::get('/parrainage/statistiques', [ParrainageController::class, 'statistiques']);
    Route::get('/parrainage/filleuls', [ParrainageController::class, 'mesFilleuls']);

    
});
