<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CommentaireController;
use App\Http\Controllers\Admin\ContenuController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\LangueController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\TypeMediaController;
use App\Http\Controllers\Admin\TypeContenuController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ParlerController;
use App\Http\Controllers\Admin\AvisController;

Route::prefix('admin')->name('admin.')
->middleware([
    \Illuminate\Auth\Middleware\Authenticate::class,
    \App\Http\Middleware\IsAdmin::class
])->group(function () {

    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/search', [DashboardController::class, 'search'])
        ->name('search');

    // Routes DataTables
    Route::get('commentaires/data', [CommentaireController::class, 'data'])->name('commentaires.data');
    Route::get('contenus/data', [ContenuController::class, 'data'])->name('contenus.data');
    Route::get('regions/data', [RegionController::class, 'data'])->name('regions.data');
    Route::get('langues/data', [LangueController::class, 'data'])->name('langues.data');
    Route::get('roles/data', [RoleController::class, 'data'])->name('roles.data');
    Route::get('utilisateurs/data', [UtilisateurController::class, 'data'])->name('utilisateurs.data');
    Route::get('type_medias/data', [TypeMediaController::class, 'data'])->name('type_medias.data');
    Route::get('type_contenus/data', [TypeContenuController::class, 'data'])->name('type_contenus.data');
    Route::get('medias/data', [MediaController::class, 'data'])->name('medias.data');

    // IMPORTANT : Parlers data
    Route::get('parlers/data', [ParlerController::class, 'data'])->name('parlers.data');


    /*
    |--------------------------------------------------------------------------
    | RESSOURCES ADMIN NORMALES
    |--------------------------------------------------------------------------
    */
    Route::resource('contenus', ContenuController::class);
    Route::resource('commentaires', CommentaireController::class);
    Route::resource('langues', LangueController::class);
    Route::resource('medias', MediaController::class);
    Route::resource('regions', RegionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('type_contenus', TypeContenuController::class);
    Route::resource('type_medias', TypeMediaController::class);
    Route::delete('/utilisateurs/{id}/delete-photo', 
        [UtilisateurController::class, 'deletePhoto']
    )->name('utilisateurs.deletePhoto');
    Route::resource('utilisateurs', UtilisateurController::class);

    

    /*
    |--------------------------------------------------------------------------
    | ROUTES PARLERS (clé composite → pas de resource)
    |--------------------------------------------------------------------------
    |
    | Clé composite = id_region + id_langue
    | On doit créer les routes manuellement
    |
    */

    Route::get('parlers', [ParlerController::class, 'index'])->name('parlers.index');
    Route::get('parlers/create', [ParlerController::class, 'create'])->name('parlers.create');
    Route::post('parlers', [ParlerController::class, 'store'])->name('parlers.store');

    Route::get('parlers/{id_region}/{id_langue}/edit', [ParlerController::class, 'edit'])
        ->name('parlers.edit');

    Route::put('parlers/{id_region}/{id_langue}', [ParlerController::class, 'update'])
        ->name('parlers.update');

    Route::delete('parlers/{id_region}/{id_langue}', [ParlerController::class, 'destroy'])
        ->name('parlers.destroy');

    // Routes pour les avis
    Route::prefix('avis')->name('avis.')->group(function () {
        Route::get('/', [AvisController::class, 'index'])->name('index');
        Route::delete('/{id}', [AvisController::class, 'destroy'])->name('destroy');
    });

});
