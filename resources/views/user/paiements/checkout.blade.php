{{-- resources/views/user/paiements/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Redirection vers FedaPay - CultureBénin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-benin-600 to-benin-800 p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                    <i class="fas fa-lock text-benin-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Paiement sécurisé</h2>
                <p class="text-benin-200 mt-2">Redirection vers FedaPay...</p>
            </div>
            
            <!-- Content -->
            <div class="p-8">
                <!-- Transaction Info -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600">Article :</span>
                        <span class="font-semibold text-gray-900">{{ $contenu->titre }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600">Montant :</span>
                        <span class="text-2xl font-bold text-benin-600">
                            {{ number_format($contenu->prix, 0, ',', ' ') }} XOF
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Référence :</span>
                        <span class="font-mono text-sm text-gray-500">{{ $transactionId }}</span>
                    </div>
                </div>
                
                <!-- Loading Animation -->
                <div class="text-center mb-8">
                    <div class="inline-block relative">
                        <div class="w-16 h-16 border-4 border-benin-200 border-t-benin-600 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-arrow-right text-benin-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-gray-600">Ouverture de FedaPay...</p>
                </div>
                
                <!-- Auto-redirect Script -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-4">
                        Si la redirection ne se fait pas automatiquement dans 5 secondes,
                        cliquez sur le bouton ci-dessous.
                    </p>
                    
                    <button onclick="openFedaPay()" 
                            class="w-full bg-gradient-to-r from-benin-500 to-benin-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-benin-600 hover:to-benin-700 transition-all duration-300 mb-3">
                        <i class="fas fa-external-link-alt mr-2"></i> Ouvrir FedaPay
                    </button>
                    
                    <a href="{{ route('contenus.show', $contenu) }}" 
                       class="inline-block text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour au contenu
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 p-4 text-center border-t border-gray-200">
                <div class="flex items-center justify-center space-x-3">
                    <i class="fas fa-shield-alt text-green-500"></i>
                    <span class="text-sm text-gray-600">Paiement 100% sécurisé par</span>
                    <img src="https://fedapay.com/images/logo.png" alt="FedaPay" class="h-5">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ouvrir FedaPay dans une nouvelle fenêtre
    function openFedaPay() {
        const windowFeatures = 'width=500,height=700,scrollbars=yes,resizable=yes,top=100,left=100';
        const fedapayWindow = window.open('{{ $checkoutUrl }}', 'FedaPayCheckout', windowFeatures);
        
        if (fedapayWindow) {
            // Focus sur la nouvelle fenêtre
            fedapayWindow.focus();
            
            // Surveiller la fermeture de la fenêtre
            const checkWindow = setInterval(() => {
                if (fedapayWindow.closed) {
                    clearInterval(checkWindow);
                    // Rediriger vers la page de callback ou recharger
                    window.location.href = '{{ route("contenus.show", $contenu) }}';
                }
            }, 1000);
        } else {
            // Popup bloqué - rediriger dans la même fenêtre
            window.location.href = '{{ $checkoutUrl }}';
        }
    }
    
    // Auto-redirection après 2 secondes
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            openFedaPay();
        }, 2000);
    });
</script>
@endpush