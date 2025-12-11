@extends('layouts.app')

@section('title', 'Activation de la double authentification')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-benin-50 to-beninYellow-50 
            dark:from-gray-900 dark:to-gray-800">
    
    <div class="bg-white/95 backdrop-blur-xl dark:bg-gray-800/95 p-8 md:p-10 rounded-3xl 
                shadow-2xl shadow-benin-500/10 dark:shadow-black/30 
                border border-benin-100 dark:border-gray-700 
                max-w-2xl w-full animate-fadeInUp">

        <!-- En-tête avec logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full gradient-benin-vertical flex items-center justify-center 
                       shadow-lg mx-auto mb-4">
                <i class="fas fa-shield-alt text-white text-3xl"></i>
            </div>
            
            <h1 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white mb-2">
                Double Authentification
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Renforcez la sécurité de votre compte
            </p>
        </div>

        <!-- Étape 1 : Scan du QR Code -->
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 rounded-full bg-benin-500 text-white flex items-center justify-center mr-3">
                    <span class="font-bold">1</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Scannez le QR Code
                </h3>
            </div>
            
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Ouvrez <strong class="text-benin-600 dark:text-benin-400">Google Authenticator</strong> ou 
                <strong class="text-benin-600 dark:text-benin-400">Authy</strong> sur votre mobile et scannez ce QR Code :
            </p>

            <!-- QR Code Container -->
            <div class="flex justify-center mb-8">
                <div class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-lg 
                           border-2 border-benin-100 dark:border-gray-600
                           transform hover:scale-[1.02] transition-transform duration-300">
                    <div class="qr-code-container">
                        {!! $qrCodeUrl !!}
                    </div>
                </div>
            </div>

            <!-- Application suggestions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                   target="_blank"
                   class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 
                          dark:hover:bg-gray-600 transition flex flex-col items-center 
                          border border-gray-200 dark:border-gray-600">
                    <i class="fab fa-google-play text-benin-500 text-xl mb-2"></i>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        Google Play
                    </span>
                </a>
                
                <a href="https://apps.apple.com/app/google-authenticator/id388497605" 
                   target="_blank"
                   class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 
                          dark:hover:bg-gray-600 transition flex flex-col items-center 
                          border border-gray-200 dark:border-gray-600">
                    <i class="fab fa-app-store-ios text-benin-500 text-xl mb-2"></i>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        App Store
                    </span>
                </a>
                
                <a href="https://authy.com/download/" 
                   target="_blank"
                   class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 
                          dark:hover:bg-gray-600 transition flex flex-col items-center 
                          border border-gray-200 dark:border-gray-600">
                    <i class="fas fa-mobile-alt text-benin-500 text-xl mb-2"></i>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        Authy
                    </span>
                </a>
                
                <a href="https://microsoft.com/en-us/security/mobile-authenticator-app" 
                   target="_blank"
                   class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 
                          dark:hover:bg-gray-600 transition flex flex-col items-center 
                          border border-gray-200 dark:border-gray-600">
                    <i class="fab fa-microsoft text-benin-500 text-xl mb-2"></i>
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        Microsoft
                    </span>
                </a>
            </div>
        </div>

        <!-- Étape 2 : Code manuel -->
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 rounded-full bg-benin-500 text-white flex items-center justify-center mr-3">
                    <span class="font-bold">2</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Code manuel (optionnel)
                </h3>
            </div>
            
            <p class="text-gray-600 dark:text-gray-300 mb-4">
                Si vous ne pouvez pas scanner le QR Code, entrez ce code manuellement :
            </p>

            <div class="relative group">
                <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border-2 border-dashed 
                           border-gray-300 dark:border-gray-600 text-center">
                    <code class="font-mono text-2xl tracking-wider font-bold 
                                text-benin-600 dark:text-benin-400 select-all">
                        {{ $secret }}
                    </code>
                    
                    <button type="button" 
                            onclick="copyToClipboard('{{ $secret }}')"
                            class="mt-4 px-4 py-2 bg-benin-50 dark:bg-benin-900/30 
                                   text-benin-600 dark:text-benin-400 rounded-lg 
                                   text-sm font-medium hover:bg-benin-100 
                                   dark:hover:bg-benin-900/50 transition flex items-center 
                                   justify-center gap-2 mx-auto">
                        <i class="far fa-copy"></i>
                        Copier le code
                    </button>
                </div>
                
                <!-- Message de confirmation copie -->
                <div id="copy-success" 
                     class="absolute -top-12 left-1/2 transform -translate-x-1/2 
                            bg-benin-500 text-white px-4 py-2 rounded-lg 
                            text-sm font-medium opacity-0 transition-opacity 
                            duration-300 pointer-events-none">
                    Code copié !
                </div>
            </div>
        </div>

        <!-- Étape 3 : Vérification -->
        <div>
            <div class="flex items-center mb-6">
                <div class="w-8 h-8 rounded-full bg-benin-500 text-white flex items-center justify-center mr-3">
                    <span class="font-bold">3</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Confirmez l'activation
                </h3>
            </div>

            <form action="{{ route('2fa.confirm') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-3 font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-key mr-2 text-benin-500"></i>
                        Entrez le code à 6 chiffres de l'application
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
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                    </div>
                    
                    @error('code')
                        <div class="mt-3 p-3 rounded-xl bg-beninRed-50 border-l-4 border-beninRed-500 
                                   dark:bg-beninRed-900/20 dark:border-beninRed-700">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-beninRed-500 mr-3"></i>
                                <p class="text-beninRed-700 dark:text-beninRed-300">
                                    {{ $message }}
                                </p>
                            </div>
                        </div>
                    @enderror
                    
                    <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Le code change toutes les 30 secondes dans votre application
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-benin-500 to-benin-600 text-white 
                                   py-3.5 rounded-xl text-lg font-semibold shadow-lg
                                   hover:from-benin-600 hover:to-benin-700 hover:shadow-xl 
                                   hover:scale-[1.02] active:scale-95 transition-all 
                                   duration-300 flex items-center justify-center gap-3">
                        <i class="fas fa-shield-check"></i>
                        Activer la protection
                    </button>
                    
                    <a href="{{ route('home') }}" 
                       class="flex-1 px-6 py-3.5 rounded-xl text-lg font-semibold
                              border-2 border-gray-300 dark:border-gray-600
                              text-gray-700 dark:text-gray-300 
                              hover:bg-gray-50 dark:hover:bg-gray-700
                              transition flex items-center justify-center gap-3">
                        <i class="fas fa-times"></i>
                        Plus tard
                    </a>
                </div>

                <!-- Sécurité info -->
                <div class="mt-8 p-4 rounded-xl bg-benin-50 dark:bg-benin-900/20 
                           border border-benin-100 dark:border-benin-800">
                    <div class="flex items-start">
                        <i class="fas fa-lock text-benin-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-1">
                                Pourquoi activer la double authentification ?
                            </h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-benin-500 mr-2 text-xs"></i>
                                    Protection supplémentaire contre les piratages
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-benin-500 mr-2 text-xs"></i>
                                    Sécurise l'accès à vos contenus personnels
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-benin-500 mr-2 text-xs"></i>
                                    Obligatoire pour certaines fonctionnalités avancées
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const successMsg = document.getElementById('copy-success');
        successMsg.style.opacity = '1';
        
        setTimeout(() => {
            successMsg.style.opacity = '0';
        }, 2000);
    }).catch(err => {
        console.error('Erreur lors de la copie : ', err);
    });
}

// Focus sur le champ de code et formater l'input
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.querySelector('input[name="code"]');
    
    if (codeInput) {
        // Formater l'input pour n'accepter que des chiffres
        codeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Auto-focus
        setTimeout(() => {
            codeInput.focus();
        }, 300);
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

/* Style pour le QR Code */
.qr-code-container svg {
    border-radius: 12px;
    padding: 8px;
    background: white;
}

.dark .qr-code-container svg {
    background: #374151; /* gray-700 */
}

/* Style pour le code */
.select-all {
    user-select: all;
    -webkit-user-select: all;
    -moz-user-select: all;
    -ms-user-select: all;
}

/* Gradient pour le logo */
.gradient-benin-vertical {
    background: linear-gradient(to bottom, 
        #16a34a, /* benin-600 */
        #15803d, /* benin-700 */
        #166534  /* benin-800 */
    );
}
</style>
@endsection