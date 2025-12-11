<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            // Si l'utilisateur est connecté ET a validé la 2FA
            if (Auth::guard($guard)->check() && !session()->has('2fa:user:id')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
