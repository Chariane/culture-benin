<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;

class Google2FAController extends Controller
{
    public function enable()
    {
        $google2fa = new Google2FA();
        $user = Auth::user();

        // Si l'utilisateur n'a pas encore de secret, on en génère un
        if (!$user->google2fa_secret) {
            $secretKey = $google2fa->generateSecretKey();

            $user->google2fa_secret = $secretKey;
            $user->save();
        } else {
            $secretKey = $user->google2fa_secret;
        }

        // Génération du QRCode
        $qrCode = $google2fa->getQRCodeInline(
            'Culture App',
            $user->email,
            $secretKey
        );

        return view('auth.google2fa.enable', [
            'secret' => $secretKey,
            'qrCode' => $qrCode
        ]);
    }
}
