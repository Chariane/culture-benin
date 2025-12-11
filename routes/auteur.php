<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auteur\DashboardController;
use App\Http\Controllers\Auteur\ContenuController;
use App\Http\Controllers\Auteur\MediaController;
use App\Http\Controllers\Auteur\TypeContenuController;
use App\Http\Controllers\Auteur\TypeMediaController;

Route::prefix('auteur')->name('auteur.')
->middleware([
    \Illuminate\Auth\Middleware\Authenticate::class,
    \App\Http\Middleware\IsAuteur::class
])->group(function ()  {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // AJAX DataTable
        Route::get('/contenus/datatable', [ContenuController::class, 'datatable'])->name('contenus.datatable');

        // CRUD
        Route::resource('contenus', ContenuController::class);
        Route::resource('medias', MediaController::class);
        Route::resource('type_contenus', TypeContenuController::class);
        Route::resource('type_medias', TypeMediaController::class);
    });
