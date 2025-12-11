@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="flex justify-center px-4 py-12">
    <div class="w-full max-w-6xl grid md:grid-cols-2 bg-white/90 backdrop-blur-lg 
                rounded-3xl shadow-2xl shadow-benin-500/20 border border-benin-100 
                dark:bg-gray-800/90 dark:border-gray-700 overflow-hidden animate-fadeInUp">

        <!-- IMAGE + SLOGAN -->
        <div class="relative h-72 md:h-auto order-2 md:order-1">
            <div class="absolute inset-0 bg-gradient-to-br from-benin-500/20 to-beninYellow-500/20"></div>
            <img src="{{ asset('images/connexion-culture-beninoise.jpg') }}" 
                 class="w-full h-full object-cover"
                 alt="Connexion à la culture béninoise">
            
            <!-- Slogan -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent 
                        flex items-end justify-center pb-10">
                <div class="text-center px-6 animate-slideUp">
                    <h2 class="text-white text-3xl md:text-4xl font-cinzel font-bold mb-4">
                        Explorez la richesse<br>culturelle du Bénin
                    </h2>
                    <p class="text-gray-200 text-sm md:text-base">
                        Rejoignez notre communauté de passionnés
                    </p>
                </div>
            </div>
        </div>

        <!-- FORMULAIRE -->
        <div class="p-8 md:p-12 order-1 md:order-2">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 rounded-full gradient-benin-vertical flex items-center justify-center shadow-lg">
                    <i class="fas fa-landmark text-white text-2xl"></i>
                </div>
            </div>
            
            <h2 class="text-3xl font-cinzel font-bold text-center mb-2 text-gray-900 dark:text-white">
                Bienvenue
            </h2>
            <p class="text-center text-gray-600 dark:text-gray-300 mb-8">
                Connectez-vous à votre compte
            </p>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-beninRed-50 border-l-4 border-beninRed-500 
                           dark:bg-beninRed-900/20 dark:border-beninRed-700">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-beninRed-500 mr-3"></i>
                        <p class="text-beninRed-700 dark:text-beninRed-300">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="group animate-slideUp">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-envelope mr-2 text-benin-500"></i>
                        Adresse email
                    </label>
                    <div class="relative">
                        <input type="email" name="email" required
                               class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                      bg-white dark:bg-gray-700 dark:border-gray-600
                                      dark:text-white focus:ring-2 focus:ring-benin-500 
                                      focus:border-benin-500 outline-none transition-all
                                      placeholder-gray-400 dark:placeholder-gray-500"
                               placeholder="exemple@email.com">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="far fa-envelope"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="group animate-slideUp">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-lock mr-2 text-benin-500"></i>
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" name="password" required
                               class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                      bg-white dark:bg-gray-700 dark:border-gray-600
                                      dark:text-white focus:ring-2 focus:ring-benin-500 
                                      focus:border-benin-500 outline-none transition-all
                                      placeholder-gray-400 dark:placeholder-gray-500"
                               placeholder="Votre mot de passe">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between animate-slideUp">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="checkbox" name="remember" id="remember"
                                   class="w-5 h-5 rounded border-gray-300 text-benin-500 
                                          focus:ring-benin-500 dark:border-gray-600 
                                          dark:bg-gray-700">
                        </div>
                        <label for="remember" class="text-gray-700 dark:text-gray-300 text-sm">
                            Se souvenir de moi
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="text-sm text-benin-600 hover:text-benin-700 dark:text-benin-400 
                              dark:hover:text-benin-300 hover:underline transition">
                        Mot de passe oublié ?
                    </a>
                    @endif
                </div>

                <!-- Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-benin-500 to-benin-600 text-white 
                               py-3.5 rounded-xl text-lg font-semibold shadow-lg
                               hover:from-benin-600 hover:to-benin-700 hover:shadow-xl 
                               hover:scale-[1.02] active:scale-95 transition-all 
                               duration-300 animate-slideUp flex items-center justify-center gap-3">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>

            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center animate-slideUp">
                <div class="flex-1 border-t border-gray-300 dark:border-gray-600"></div>
                <span class="px-4 text-gray-500 dark:text-gray-400 text-sm">Ou</span>
                <div class="flex-1 border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <!-- Register link -->
            <p class="text-center text-gray-600 dark:text-gray-300 animate-slideUp">
                Pas encore de compte ?
                <a href="{{ route('register') }}" 
                   class="text-benin-600 font-semibold hover:text-benin-700 
                          dark:text-benin-400 dark:hover:text-benin-300 
                          hover:underline transition ml-2 inline-flex items-center gap-1">
                    Créer un compte
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </p>

        </div>
    </div>
</div>
@endsection