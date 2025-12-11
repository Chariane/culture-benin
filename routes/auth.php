<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes invité (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // --- Inscription ---
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // --- Connexion ---
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // --- Reset Password ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    /*
    |--------------------------------------------------------------------------
    | Vérification du code 2FA (TOTP) après login réussi
    |--------------------------------------------------------------------------
    */
    Route::get('/2fa/verify', [TwoFactorController::class, 'verifyForm'])->name('2fa.verify.form');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
});

/*
|--------------------------------------------------------------------------
| Routes après authentification
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- Vérification email ---
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification',
        [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --- Confirmation de mot de passe ---
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // --- Déconnexion ---
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    
    // routes/auth.php

// ... autres routes d'authentification ...

// Routes pour le profil utilisateur

    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\User\ProfileController::class, 'destroy'])->name('profile.destroy');
   /*
    |--------------------------------------------------------------------------
    | Activation du 2FA (QR Code + confirmation)
    |--------------------------------------------------------------------------
    */
    Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])
        ->name('2fa.setup');

    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm'])
        ->name('2fa.confirm');
});
