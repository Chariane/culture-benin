<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affichage du formulaire de connexion
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Traitement de la connexion (email / mot de passe)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Identifiants incorrects.'),
            ]);
        }

        $request->session()->regenerate();

        /** @var Utilisateur $user */
        $user = Auth::user();

        // --- Tous les utilisateurs doivent passer par 2FA ---
        if (! $user->google2fa_secret) {
            session(['2fa:user:id' => $user->id_utilisateur]);
            return redirect()->route('2fa.setup');
        }

        // --- Si l'utilisateur a déjà un secret 2FA ---
        session(['2fa:user:id' => $user->id_utilisateur]);
        Auth::logout();

        return redirect()->route('2fa.verify.form');
    }

    /**
     * Gestion après authentification 2FA réussie
     */
    public function handle2FASuccess(Request $request)
    {
        // Vérifier s'il y a un contenu à rediriger
        if ($request->session()->has('redirect_content_id')) {
            $contentId = $request->session()->get('redirect_content_id');
            $request->session()->forget('redirect_content_id');
            return redirect()->route('contenus.show', $contentId);
        }
        
        // Vérifier s'il y a une URL de redirection stockée
        if ($request->session()->has('redirect_after_login')) {
            $url = $request->session()->get('redirect_after_login');
            $request->session()->forget('redirect_after_login');
            return redirect($url);
        }
        
        return redirect()->intended(route('home'));
    }

    /**
     * Déconnexion
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}