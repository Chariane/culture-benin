<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Moderateur\DashboardController;
use App\Http\Controllers\Moderateur\ContenuController;

Route::prefix('moderateur')->name('moderateur.')
->middleware([
    \Illuminate\Auth\Middleware\Authenticate::class,
    \App\Http\Middleware\IsModerateur::class
])->group(function ()  {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Data pour DataTables
    Route::get('/contenus/en-attente/data', [ContenuController::class, 'enAttenteData'])
        ->name('contenus.en_attente.data');

    Route::get('/contenus/valides/data', [ContenuController::class, 'validesData'])
        ->name('contenus.valides.data');

    Route::get('/contenus/mediocres/data', [ContenuController::class, 'mediocresData'])
        ->name('contenus.mediocres.data');

    // Voir contenu et changer statut
    Route::get('/contenus/{contenu}', [ContenuController::class, 'show'])
        ->name('contenus.show');

    Route::post('/contenus/{contenu}/change-statut', [ContenuController::class, 'changeStatut'])
        ->name('contenus.change_statut');

    Route::get('/contenus/en-attente', [ContenuController::class, 'indexEnAttente'])
        ->name('contenus.en_attente');


});

