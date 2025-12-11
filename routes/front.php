<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ContenuController;
use App\Http\Controllers\User\LectureController;
use App\Http\Controllers\User\CommentaireController;
use App\Http\Controllers\User\LikeController;
use App\Http\Controllers\User\ProfilController;
use App\Http\Controllers\User\FavoriController;
use App\Http\Controllers\User\PaiementController;
use App\Http\Controllers\User\AbonnementController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\RechercheController;
use App\Http\Controllers\User\HistoriqueController;
use App\Http\Controllers\User\BibliothequeController;
use App\Http\Controllers\User\RegionController;
use App\Http\Controllers\User\AuteurController;
use App\Http\Controllers\User\AvisController;


// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/regions', [RegionController::class, 'index'])->name('regions.index');
Route::get('/regions/{region}', [RegionController::class, 'show'])->name('regions.show');
Route::get('/regions/{region}/contents', [RegionController::class, 'contents'])->name('regions.contents');
Route::get('/auteurs', [AuteurController::class, 'index'])->name('auteurs.index');
Route::get('/auteurs/{auteur}', [AuteurController::class, 'show'])->name('auteurs.show');
Route::get('/contenus', [ContenuController::class, 'index'])->name('contenus.index');
Route::get('/contenus/{contenu}', [ContenuController::class, 'show'])->name('contenus.show');
Route::get('/contenus/type/{type}', [ContenuController::class, 'byType'])->name('contenus.by-type');

// Callback FedaPay (accessible sans auth - FedaPay appelle cette URL)
Route::match(['get', 'post'], '/paiement/callback', [PaiementController::class, 'callback'])->name('paiement.callback');
Route::post('/paiement/webhook', [PaiementController::class, 'webhook'])->name('paiement.webhook');

// Routes protégées
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Lecture
    Route::get('/lecture/{contenu}', [LectureController::class, 'read'])->name('lecture.read');
    Route::post('/lecture/{contenu}/complete', [LectureController::class, 'markAsCompleted'])->name('lecture.complete');
    
    // Commentaires
    Route::post('/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    Route::put('/commentaires/{commentaire}', [CommentaireController::class, 'update'])->name('commentaires.update');
    Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
    
        
    // Favoris
    Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');
    
    // Profil
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/profil/security', [ProfilController::class, 'security'])->name('profil.security');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('/profil/activity', [ProfilController::class, 'activity'])->name('profil.activity');
    Route::get('/profil/notifications', [ProfilController::class, 'notifications'])->name('profil.notifications');
    Route::post('/profil/photo', [ProfilController::class, 'uploadPhoto'])->name('profil.photo');
    
    // Abonnements
    Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements.index');
    Route::post('/abonnements', [AbonnementController::class, 'subscribe'])->name('abonnements.subscribe');
    Route::delete('/abonnements/{auteur}', [AbonnementController::class, 'unsubscribe'])->name('abonnements.unsubscribe');
    
    // Historique
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique.index');
    
    // Bibliothèque
    Route::get('/bibliotheque', [BibliothequeController::class, 'index'])->name('bibliotheque.index');
    
    //Avis
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    
    // Paiements - CORRECTION DES ROUTES
    Route::get('/paiement/contenu/{id}', [PaiementController::class, 'showPaymentPage'])->name('paiement.page');
    Route::get('/paiement/direct/{id}', [PaiementController::class, 'directPayment'])->name('paiement.direct');
    Route::post('/paiement/process', [PaiementController::class, 'purchase'])->name('paiement.purchase');
    Route::get('/paiement/historique', [PaiementController::class, 'purchaseHistory'])->name('paiement.history');
    
    // Recherche
    Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.index');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{notification}/lire', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// Routes API pour les fonctionnalités AJAX
Route::middleware('auth')->prefix('api')->group(function () {
    Route::post('/likes/toggle', [LikeController::class, 'toggle']);
    Route::post('/favoris/toggle', [FavoriController::class, 'toggle']);
    Route::post('/abonnements', [AbonnementController::class, 'subscribe']);
    Route::delete('/abonnements/{auteur}', [AbonnementController::class, 'unsubscribe']);
    Route::post('/profil/photo', [ProfilController::class, 'uploadPhoto']);
    Route::get('/bibliotheque/collection/{collection}', [BibliothequeController::class, 'getCollection'])->name('bibliotheque.collection');
    Route::post('/bibliotheque/collections', [BibliothequeController::class, 'createCollection']);
    Route::put('/bibliotheque/collections/{collection}', [BibliothequeController::class, 'updateCollection']);
    Route::delete('/bibliotheque/collections/{collection}', [BibliothequeController::class, 'deleteCollection']);
});

// Erreurs
Route::fallback(function () {
    return view('user.errors.404');
});