{{-- resources/views/user/paiements/payment-page.blade.php --}}
@extends('layouts.app')

@section('title', 'Paiement - ' . $contenu->titre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-cinzel font-bold text-gray-900 dark:text-white mb-3">
                Paiement <span class="text-cultural-600">Sécurisé</span>
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Finalisez votre achat pour accéder à ce contenu premium
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Détails du contenu -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft hover-lift border border-gray-200 dark:border-gray-700">
                    <!-- En-tête -->
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white">
                                Détails de l'achat
                            </h2>
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-premium px-3 py-1.5 text-sm">
                                    <i class="fas fa-crown mr-1.5"></i>
                                    Premium
                                </span>
                                @if($contenu->type)
                                <span class="badge badge-cultural px-3 py-1.5 text-sm">
                                    <i class="fas fa-{{ $contenu->type->nom_contenu == 'Vidéo' ? 'video' : ($contenu->type->nom_contenu == 'Audio' ? 'music' : 'file-alt') }} mr-1.5"></i>
                                    {{ $contenu->type->nom_contenu }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Corps -->
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Image du contenu -->
                            <div class="md:w-1/3">
                                @if($contenu->medias->first())
                                <div class="relative h-48 md:h-40 rounded-xl overflow-hidden">
                                    <img src="{{ Storage::url($contenu->medias->first()->chemin) }}" 
                                         alt="{{ $contenu->titre }}"
                                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>
                                @else
                                <div class="h-48 md:h-40 rounded-xl gradient-overlay flex items-center justify-center">
                                    <div class="text-center p-6">
                                        <i class="fas fa-{{ $contenu->type->nom_contenu == 'Vidéo' ? 'play-circle' : ($contenu->type->nom_contenu == 'Audio' ? 'music' : 'feather-alt') }} text-5xl text-cultural-500 mb-4"></i>
                                        <span class="text-cultural-800 dark:text-cultural-400 font-cinzel text-lg">{{ $contenu->type->nom_contenu ?? 'Contenu' }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Informations -->
                            <div class="md:w-2/3">
                                <h3 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-3">
                                    {{ $contenu->titre }}
                                </h3>
                                
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ Str::limit(strip_tags($contenu->texte), 200) }}
                                </p>

                                <!-- Métadonnées -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-circle mr-3 text-cultural-600"></i>
                                        <span>{{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt mr-3 text-cultural-600"></i>
                                        <span>Publié le {{ $contenu->date_creation->format('d F Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-eye mr-3 text-cultural-600"></i>
                                        <span>{{ $contenu->views_count ?? 0 }} vues</span>
                                    </div>
                                </div>

                                <!-- Badges avantages -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <i class="fas fa-unlock-alt text-green-600 dark:text-green-400 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">Accès immédiat</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Après paiement</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <i class="fas fa-infinity text-blue-600 dark:text-blue-400 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">Accès illimité</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Pour toujours</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Prix total</div>
                                <div class="text-3xl font-bold text-cultural-600">{{ number_format($contenu->prix, 0, ',', ' ') }} XAF</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Soit environ {{ number_format($contenu->prix / 655, 2, ',', ' ') }} €</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Garantie</div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-shield-alt text-green-500"></i>
                                    <span class="font-medium text-gray-900 dark:text-white">Paiement 100% sécurisé</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft hover-lift border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-6">
                                <i class="fas fa-credit-card mr-2"></i>
                                Méthode de paiement
                            </h3>

                            <!-- État de chargement -->
                            <div id="loading" class="text-center py-6" style="display: none;">
                                <div class="w-16 h-16 mx-auto mb-4">
                                    <div class="w-full h-full border-4 border-cultural-600 border-t-transparent rounded-full animate-spin"></div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Préparation du paiement...</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Ne quittez pas cette page</p>
                            </div>

                            <!-- Message d'erreur -->
                            <div id="error-message" class="alert alert-danger rounded-xl mb-4" style="display: none;"></div>

                            <!-- Formulaire de paiement -->
                            <div id="payment-form">
                                <form id="payment-form-submit" action="{{ route('paiement.purchase') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_contenu" value="{{ $contenu->id_contenu }}">

                                    <!-- Options de paiement -->
                                    <div class="mb-6">
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Choisissez votre méthode de paiement</div>
                                        <div class="space-y-3">
                                            <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-cultural-500 dark:hover:border-cultural-500 transition-colors payment-option selected">
                                                <input class="sr-only" type="radio" name="payment_method" value="fedapay" checked>
                                                <div class="flex items-center justify-between w-full">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                                            <i class="fas fa-mobile-alt text-white"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white">Mobile Money / Carte</div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">Via FedaPay</div>
                                                        </div>
                                                    </div>
                                                    <div class="checkmark">
                                                        <i class="fas fa-check-circle text-cultural-600 text-xl"></i>
                                                    </div>
                                                </div>
                                            </label>

                                            <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-cultural-500 dark:hover:border-cultural-500 transition-colors payment-option opacity-50">
                                                <input class="sr-only" type="radio" name="payment_method" value="paypal" disabled>
                                                <div class="flex items-center justify-between w-full">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mr-3">
                                                            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" class="w-8 h-8 object-contain" alt="PayPal">
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-gray-900 dark:text-white">PayPal</div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">Bientôt disponible</div>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded">
                                                        Prochainement
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Bouton de paiement -->
                                    <button type="submit" 
                                            id="pay-button"
                                            class="group w-full btn btn-benin py-4 text-lg font-semibold shadow-lg transform hover:scale-[1.02] transition-all duration-300">
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-lock mr-3"></i>
                                            <span>Payer {{ number_format($contenu->prix, 0, ',', ' ') }} XAF</span>
                                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform duration-300"></i>
                                        </div>
                                    </button>

                                    <!-- Garanties -->
                                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Sécurisé</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-lock text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Chiffré SSL</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">24/7</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-headset text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Support</span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>



                        <!-- Logo FedaPay -->
                        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 rounded-b-2xl">
                            <div class="text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Paiement sécurisé par
                                </p>
                                <div class="flex items-center justify-center space-x-4">
                                    <img src="https://fedapay.com/images/logo.png" 
                                         alt="FedaPay" 
                                         class="h-8 opacity-80 hover:opacity-100 transition-opacity">
                                    <div class="flex space-x-2">
                                        <div class="w-10 h-6 bg-gradient-to-r from-green-500 to-blue-500 rounded"></div>
                                        <div class="w-10 h-6 bg-gradient-to-r from-yellow-500 to-red-500 rounded"></div>
                                        <div class="w-10 h-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retour au contenu -->
        <div class="text-center mt-8">
            <a href="{{ route('contenus.show', $contenu) }}" 
               class="inline-flex items-center text-cultural-600 hover:text-cultural-700 dark:text-cultural-400 dark:hover:text-cultural-300 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au contenu
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .payment-option {
        transition: all 0.3s ease;
    }
    
    .payment-option.selected {
        border-color: #16a34a;
        background-color: rgba(22, 163, 74, 0.05);
    }
    
    .payment-option:hover:not(.selected) {
        border-color: rgba(22, 163, 74, 0.3);
    }
    
    .payment-option input:checked + div .checkmark {
        opacity: 1;
    }
    
    .payment-option .checkmark {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gradient-overlay {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }
    
    .dark .gradient-overlay {
        background: linear-gradient(135deg, #1a2e1c 0%, #0f2012 100%);
    }
    
    .btn-benin {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #4ade80 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3);
    }
    
    .btn-benin:hover {
        box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
        transform: translateY(-2px);
    }
    
    .btn-yellow {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        border: none;
    }
    
    .badge-premium {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
    }
    
    .badge-cultural {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        border: 1px solid #ef4444;
        color: #991b1b;
    }
    
    .dark .alert-danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.2) 0%, rgba(185, 28, 28, 0.3) 100%);
        border-color: #dc2626;
        color: #fca5a5;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form-submit');
    const payButton = document.getElementById('pay-button');
    const loadingDiv = document.getElementById('loading');
    const errorDiv = document.getElementById('error-message');
    const paymentFormDiv = document.getElementById('payment-form');
    const paymentOptions = document.querySelectorAll('.payment-option');
    
    // Gestion de la sélection des options de paiement
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            if (option.classList.contains('opacity-50')) return;
            
            // Désélectionner toutes les options
            paymentOptions.forEach(opt => {
                opt.classList.remove('selected');
                const input = opt.querySelector('input');
                if (input) input.checked = false;
            });
            
            // Sélectionner l'option cliquée
            this.classList.add('selected');
            const input = this.querySelector('input');
            if (input) input.checked = true;
        });
    });
    
    // Soumission du formulaire
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Désactiver le bouton et montrer le chargement
            payButton.disabled = true;
            payButton.innerHTML = `
                <div class="flex items-center justify-center">
                    <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-3"></div>
                    <span>Préparation du paiement...</span>
                </div>
            `;
            
            loadingDiv.style.display = 'block';
            paymentFormDiv.style.display = 'none';
            errorDiv.style.display = 'none';
            
            // Animation de chargement
            setTimeout(() => {
                // Soumettre le formulaire de manière classique
                const hiddenForm = document.createElement('form');
                hiddenForm.method = 'POST';
                hiddenForm.action = form.action;
                hiddenForm.style.display = 'none';
                
                // Ajouter les champs
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                hiddenForm.appendChild(csrfToken);
                
                const contentId = document.createElement('input');
                contentId.type = 'hidden';
                contentId.name = 'id_contenu';
                contentId.value = '{{ $contenu->id_contenu }}';
                hiddenForm.appendChild(contentId);
                
                const paymentMethod = document.createElement('input');
                paymentMethod.type = 'hidden';
                paymentMethod.name = 'payment_method';
                paymentMethod.value = document.querySelector('input[name="payment_method"]:checked').value;
                hiddenForm.appendChild(paymentMethod);
                
                // Soumettre
                document.body.appendChild(hiddenForm);
                hiddenForm.submit();
            }, 500);
        });
    }
    
    // Gestion des erreurs
    @if(session('error'))
    errorDiv.style.display = 'block';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
            <div>
                <strong>Erreur :</strong> {{ session('error') }}
            </div>
        </div>
    `;
    @endif
    
    @if(session('success'))
    // Animation de succès (optionnelle)
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-hard z-50 animate-slide-in';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <strong>Succès :</strong> {{ session('success') }}
            </div>
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
    @endif
});
</script>
@endpush