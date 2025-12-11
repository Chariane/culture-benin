@extends('layouts.app')

@section('title', 'Vérification double authentification')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-benin-50 to-beninYellow-50 
            dark:from-gray-900 dark:to-gray-800">
    
    <div class="bg-white/95 backdrop-blur-xl dark:bg-gray-800/95 p-8 md:p-10 rounded-3xl 
                shadow-2xl shadow-benin-500/10 dark:shadow-black/30 
                border border-benin-100 dark:border-gray-700 
                max-w-md w-full animate-fadeInUp">

        <!-- En-tête avec logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full gradient-benin-vertical flex items-center justify-center 
                       shadow-lg mx-auto mb-4">
                <i class="fas fa-shield-check text-white text-2xl"></i>
            </div>
            
            <h1 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-2">
                Vérification de sécurité
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm">
                Pour votre sécurité, veuillez confirmer votre identité
            </p>
        </div>

        <!-- Message de contexte -->
        <div class="mb-8 p-4 rounded-xl bg-benin-50 dark:bg-benin-900/20 
                   border border-benin-100 dark:border-benin-800">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-benin-500 mt-0.5 mr-3"></i>
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Ouvrez votre application d'authentification et entrez le code à 6 chiffres affiché.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('2fa.verify.post') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block mb-3 font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-mobile-alt mr-2 text-benin-500"></i>
                    Code de vérification
                </label>
                
                <div class="relative">
                    <input type="text" 
                           name="code" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           class="w-full px-4 pl-12 py-3.5 rounded-xl border border-gray-300 
                                  bg-white dark:bg-gray-700 dark:border-gray-600
                                  dark:text-white focus:ring-2 focus:ring-benin-500 
                                  focus:border-benin-500 outline-none transition-all
                                  placeholder-gray-400 dark:placeholder-gray-500
                                  text-center text-2xl font-mono tracking-widest"
                           placeholder="000000"
                           required
                           autocomplete="one-time-code"
                           autofocus>
                    
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                
                @error('code')
                    <div class="mt-3 p-3 rounded-xl bg-beninRed-50 border-l-4 border-beninRed-500 
                               dark:bg-beninRed-900/20 dark:border-beninRed-700">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-beninRed-500 mr-3"></i>
                            <p class="text-beninRed-700 dark:text-beninRed-300 text-sm">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                @enderror
                
                @if(session('error'))
                    <div class="mt-3 p-3 rounded-xl bg-beninRed-50 border-l-4 border-beninRed-500 
                               dark:bg-beninRed-900/20 dark:border-beninRed-700">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-beninRed-500 mr-3"></i>
                            <p class="text-beninRed-700 dark:text-beninRed-300 text-sm">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                @endif
                
                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center">
                    <i class="fas fa-clock mr-1"></i>
                    Le code change toutes les 30 secondes
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-benin-500 to-benin-600 text-white 
                               py-3.5 rounded-xl text-lg font-semibold shadow-lg
                               hover:from-benin-600 hover:to-benin-700 hover:shadow-xl 
                               hover:scale-[1.02] active:scale-95 transition-all 
                               duration-300 flex items-center justify-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    Vérifier et continuer
                </button>
                
                <!-- Liens utiles -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex flex-wrap justify-center gap-4 text-sm">
                        <a href="{{ route('2fa.setup') }}" 
                           class="text-benin-600 hover:text-benin-700 
                                  dark:text-benin-400 dark:hover:text-benin-300 
                                  hover:underline transition flex items-center gap-1">
                            <i class="fas fa-cog"></i>
                            Configurer 2FA
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="text-gray-600 hover:text-gray-800 
                                  dark:text-gray-400 dark:hover:text-gray-300 
                                  hover:underline transition flex items-center gap-1">
                            <i class="fas fa-sign-in-alt"></i>
                            Retour à la connexion
                        </a>
                        
                        <a href="{{ route('password.request') }}" 
                           class="text-beninRed-600 hover:text-beninRed-700 
                                  dark:text-beninRed-400 dark:hover:text-beninRed-300 
                                  hover:underline transition flex items-center gap-1">
                            <i class="fas fa-question-circle"></i>
                            Aide
                        </a>
                    </div>
                </div>
                
                <!-- Information de secours -->
                <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 
                           border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-life-ring text-gray-500 dark:text-gray-400 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Problème avec l'authentification ?
                            </h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Contactez notre support à 
                                <a href="mailto:support@culturebenin.bj" 
                                   class="text-benin-600 hover:text-benin-700 
                                          dark:text-benin-400 dark:hover:text-benin-300 
                                          hover:underline font-medium">
                                    support@culturebenin.bj
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.querySelector('input[name="code"]');
    
    if (codeInput) {
        // Formater l'input pour n'accepter que des chiffres
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-soumettre quand 6 chiffres sont entrés
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
        
        // Auto-focus
        setTimeout(() => {
            codeInput.focus();
        }, 300);
        
        // Ajouter un placeholder dynamique
        let placeholder = '';
        const placeholderInterval = setInterval(() => {
            const randomCode = Math.floor(100000 + Math.random() * 900000);
            placeholder = randomCode.toString();
            codeInput.placeholder = placeholder;
        }, 3000);
        
        // Nettoyer l'intervalle quand on quitte la page
        window.addEventListener('beforeunload', () => {
            clearInterval(placeholderInterval);
        });
    }
});
</script>

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

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Style pour l'input code */
input[type="text"]::-webkit-inner-spin-button,
input[type="text"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="text"] {
    -moz-appearance: textfield;
}

/* Gradient pour le logo */
.gradient-benin-vertical {
    background: linear-gradient(to bottom, 
        #16a34a, /* benin-600 */
        #15803d, /* benin-700 */
        #166534  /* benin-800 */
    );
}

/* Animation de pulsation pour le champ de code */
input:focus {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
    }
}
</style>
@endsection