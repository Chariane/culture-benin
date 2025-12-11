<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\Utilisateur;

class TwoFactorController extends Controller
{
    /**
     * 1) Affiche la page de setup (QR) — utilise l'ID stocké en session
     *    L'utilisateur doit être identifié via la session '2fa:user:id'
     */
    public function setup(Request $request)
    {
        $userId = session('2fa:user:id');

        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée, reconnectez-vous.']);
        }

        $user = Utilisateur::find($userId);
        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        // si déjà activé, on redirige
        if ($user->google2fa_secret) {
            return redirect()->route('admin.dashboard');
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        // Stocker temporairement le secret avant confirmation
        session(['2fa:secret' => $secret]);

        // Générer PNG inline (n'oublie pas bacon/bacon-qr-code installé)
        $qrCodeUrl = $google2fa->getQRCodeInline(
            'CultureApp',
            $user->email,
            $secret
        );

        return view('auth.2fa-setup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * 2) Confirme l'activation 2FA (après scan et saisie du code TOTP)
     */
    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('2fa:user:id');
        $secret = session('2fa:secret');

        if (! $userId || ! $secret) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée, recommencez.']);
        }

        $user = Utilisateur::find($userId);
        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        $google2fa = new Google2FA();
        if (! $google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        // Sauvegarder le secret en base
        $user->google2fa_secret = $secret;
        $user->save();

        // nettoyage des sessions temporaires
        session()->forget('2fa:secret');
        session()->forget('2fa:user:id');

        // login définitif de l'utilisateur (utiliser la clé primaire personnalisée)
        Auth::loginUsingId($user->id_utilisateur);

        return redirect()->route('home')->with('success', 'Double authentification activée.');
    }

    /**
     * 3) Formulaire où l'utilisateur entre son code après la saisie du mot de passe
     */
    public function verifyForm()
    {
        // s'assurer qu'on a bien un user en attente
        if (! session('2fa:user:id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa-verify');
    }

    /**
     * 4) Vérifie le code TOTP et connecte l'utilisateur
     */
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('2fa:user:id');
        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée, reconnectez-vous.']);
        }

        $user = Utilisateur::find($userId);
        if (! $user || ! $user->google2fa_secret) {
            return redirect()->route('login')->withErrors(['email' => 'Utilisateur introuvable ou 2FA non configuré.']);
        }

        $google2fa = new Google2FA();
        if (! $google2fa->verifyKey($user->google2fa_secret, $request->code)) {
            return back()->withErrors(['code' => 'Code incorrect.']);
        }

        // Connexion définitive
        session()->forget('2fa:user:id');
        Auth::loginUsingId($user->id_utilisateur);

        return redirect()->route('home');
    }
}
