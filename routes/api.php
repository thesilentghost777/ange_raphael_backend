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
Route::post('/deconnexion', [AutoEcoleAuthController::class, 'deconnexion']);
Route::post('/inscription', [AutoEcoleAuthController::class, 'inscription']);
Route::post('/connexion', [AutoEcoleAuthController::class, 'connexion']);
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/centres-examen', [SessionController::class, 'centresExamen']);
Route::get('/jours-pratique', [SessionController::class, 'joursPratique']);

Route::get('/paiement/monetbil/success', [PaiementController::class, 'moneyFusionSuccess'])
        ->name('paiement.monetbil.success');
Route::post('/paiement/moneyfusion/webhook', [PaiementController::class, 'moneyFusionWebhook']);
    
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
