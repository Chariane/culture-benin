@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="flex justify-center px-4 py-12">
    <div class="w-full max-w-6xl grid md:grid-cols-2 bg-white/90 backdrop-blur-lg 
                rounded-3xl shadow-2xl shadow-benin-500/20 border border-benin-100 
                dark:bg-gray-800/90 dark:border-gray-700 overflow-hidden animate-fadeInUp">

        <!-- IMAGE + MESSAGE -->
        <div class="relative h-72 md:h-auto order-2 md:order-1">
            <div class="absolute inset-0 bg-gradient-to-br from-beninYellow-500/20 to-beninRed-500/20"></div>
            <img src="{{ asset('images/inscription-culture-beninoise.jpg') }}" 
                 class="w-full h-full object-cover"
                 alt="Inscription à la communauté culturelle béninoise">
            
            <!-- Message -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent 
                        flex items-end justify-center pb-10">
                <div class="text-center px-6 animate-slideUp">
                    <h2 class="text-white text-3xl md:text-4xl font-cinzel font-bold mb-4">
                        Rejoignez la communauté<br>culturelle béninoise
                    </h2>
                    <p class="text-gray-200 text-sm md:text-base">
                        Partagez et découvrez notre patrimoine
                    </p>
                </div>
            </div>
        </div>

        <!-- FORMULAIRE -->
        <div class="p-8 md:p-12 order-1 md:order-2">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 rounded-full gradient-benin-vertical flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>
            
            <h2 class="text-3xl font-cinzel font-bold text-center mb-2 text-gray-900 dark:text-white">
                Créer un compte
            </h2>
            <p class="text-center text-gray-600 dark:text-gray-300 mb-8">
                Rejoignez CultureBénin en quelques étapes
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Nom & Prénom -->
                <div class="grid md:grid-cols-2 gap-6">

                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-user mr-2 text-benin-500"></i>
                            Nom
                        </label>
                        <div class="relative">
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                   class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                          bg-white dark:bg-gray-700 dark:border-gray-600
                                          dark:text-white focus:ring-2 focus:ring-benin-500 
                                          focus:border-benin-500 outline-none transition-all
                                          placeholder-gray-400 dark:placeholder-gray-500"
                                   placeholder="Votre nom">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-user-tag"></i>
                            </div>
                        </div>
                        @error('nom')
                            <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-user-circle mr-2 text-benin-500"></i>
                            Prénom
                        </label>
                        <div class="relative">
                            <input type="text" name="prenom" value="{{ old('prenom') }}"
                                   class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                          bg-white dark:bg-gray-700 dark:border-gray-600
                                          dark:text-white focus:ring-2 focus:ring-benin-500 
                                          focus:border-benin-500 outline-none transition-all
                                          placeholder-gray-400 dark:placeholder-gray-500"
                                   placeholder="Votre prénom">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        @error('prenom')
                            <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <!-- Sexe & Langue -->
                <div class="grid md:grid-cols-2 gap-6">

                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-venus-mars mr-2 text-benin-500"></i>
                            Genre
                        </label>
                        <div class="relative">
                            <select name="sexe"
                                class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                       bg-white dark:bg-gray-700 dark:border-gray-600
                                       dark:text-white focus:ring-2 focus:ring-benin-500 
                                       focus:border-benin-500 outline-none transition-all
                                       appearance-none cursor-pointer">
                                <option value="">-- Sélectionnez --</option>
                                <option value="Homme" {{ old('sexe')=='Homme'?'selected':'' }}>Homme</option>
                                <option value="Femme" {{ old('sexe')=='Femme'?'selected':'' }}>Femme</option>
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('sexe')
                            <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-language mr-2 text-benin-500"></i>
                            Langue
                        </label>
                        <div class="relative">
                            <select name="id_langue" required
                                    class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                           bg-white dark:bg-gray-700 dark:border-gray-600
                                           dark:text-white focus:ring-2 focus:ring-benin-500 
                                           focus:border-benin-500 outline-none transition-all
                                           appearance-none cursor-pointer">
                                <option value="">-- Choisissez une langue --</option>
                                @foreach ($langues as $langue)
                                    <option value="{{ $langue->id_langue }}"
                                        {{ old('id_langue') == $langue->id_langue ? 'selected' : '' }}>
                                        {{ $langue->nom_langue }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-language"></i>
                            </div>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        @error('id_langue')
                            <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <!-- Email -->
                <div class="group animate-slideUp">
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-envelope mr-2 text-benin-500"></i>
                        Adresse email
                    </label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}"
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

                <!-- Mot de passe -->
                <div class="grid md:grid-cols-2 gap-6">

                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-lock mr-2 text-benin-500"></i>
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input type="password" name="password"
                                   class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                          bg-white dark:bg-gray-700 dark:border-gray-600
                                          dark:text-white focus:ring-2 focus:ring-benin-500 
                                          focus:border-benin-500 outline-none transition-all
                                          placeholder-gray-400 dark:placeholder-gray-500"
                                   placeholder="Minimum 8 caractères">
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

                    <!-- Confirmation -->
                    <div class="group animate-slideUp">
                        <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-lock mr-2 text-benin-500"></i>
                            Confirmation
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                          bg-white dark:bg-gray-700 dark:border-gray-600
                                          dark:text-white focus:ring-2 focus:ring-benin-500 
                                          focus:border-benin-500 outline-none transition-all
                                          placeholder-gray-400 dark:placeholder-gray-500"
                                   placeholder="Répétez le mot de passe">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <!-- Conditions -->
                <div class="animate-slideUp">
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 
                                dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                        <input type="checkbox" id="conditions" name="conditions" required
                               class="w-5 h-5 rounded border-gray-300 text-benin-500 
                                      focus:ring-benin-500 dark:border-gray-600 
                                      dark:bg-gray-700 mt-1">
                        <label for="conditions" class="text-sm text-gray-700 dark:text-gray-300">
                            J'accepte les 
                            <a href="#" class="text-benin-600 hover:text-benin-700 
                                               dark:text-benin-400 dark:hover:text-benin-300 
                                               hover:underline font-medium">
                                conditions d'utilisation
                            </a> 
                            et la 
                            <a href="#" class="text-benin-600 hover:text-benin-700 
                                               dark:text-benin-400 dark:hover:text-benin-300 
                                               hover:underline font-medium">
                                politique de confidentialité
                            </a> 
                            de CultureBénin
                        </label>
                    </div>
                    @error('conditions')
                        <p class="mt-2 text-sm text-beninRed-600 dark:text-beninRed-400">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Bouton -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-benin-500 to-benin-600 text-white 
                               py-3.5 rounded-xl text-lg font-semibold shadow-lg
                               hover:from-benin-600 hover:to-benin-700 hover:shadow-xl 
                               hover:scale-[1.02] active:scale-95 transition-all 
                               duration-300 animate-slideUp flex items-center justify-center gap-3">
                    <i class="fas fa-user-plus"></i>
                    Créer mon compte
                </button>

                <!-- Login link -->
                <p class="text-center text-gray-600 dark:text-gray-300 animate-slideUp">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" 
                       class="text-benin-600 font-semibold hover:text-benin-700 
                              dark:text-benin-400 dark:hover:text-benin-300 
                              hover:underline transition ml-2 inline-flex items-center gap-1">
                        Se connecter
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </p>

            </form>
        </div>
    </div>
</div>

<!-- Styles pour les animations -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-slideUp {
    animation: slideUp 0.5s ease-out forwards;
}

/* Délais pour les animations successives */
.group:nth-child(1) { animation-delay: 0.1s; }
.group:nth-child(2) { animation-delay: 0.2s; }
.group:nth-child(3) { animation-delay: 0.3s; }
.group:nth-child(4) { animation-delay: 0.4s; }
.group:nth-child(5) { animation-delay: 0.5s; }
.group:nth-child(6) { animation-delay: 0.6s; }

/* Gradient pour le logo */
.gradient-benin-vertical {
    background: linear-gradient(to bottom, 
        #16a34a, /* benin-600 */
        #15803d, /* benin-700 */
        #166534  /* benin-800 */
    );
}

/* Style pour les sélecteurs */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* Mode sombre pour les sélecteurs */
.dark select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}
</style>
@endsection