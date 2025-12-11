@extends('layouts.app')

@section('title', 'Sécurité du Compte - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Sécurité du compte</h1>
        <p class="text-gray-600">Gérez votre mot de passe et les paramètres de sécurité</p>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Sidebar -->
        @include('user.profil.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <!-- Changement de mot de passe -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Changer le mot de passe</h2>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-benin-50 border-l-4 border-benin-500 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-benin-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-benin-800 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-red-800 font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Mot de passe actuel -->
                    <div class="mb-6">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe actuel
                        </label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Nouveau mot de passe -->
                    <div class="mb-6">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau mot de passe
                        </label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent @error('new_password') border-red-500 @enderror">
                        @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirmation du nouveau mot de passe -->
                    <div class="mb-6">
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le nouveau mot de passe
                        </label>
                        <input type="password" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                    </div>
                    
                    <!-- Indications pour le mot de passe -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Votre mot de passe doit contenir :</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Au moins 6 caractères
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Des lettres et des chiffres
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Bouton de soumission -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-benin-600 text-white rounded-lg hover:bg-benin-700 transition-colors duration-200 font-semibold">
                            Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Session actuelle -->
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Session actuelle</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">Session en cours</div>
                            <div class="text-sm text-gray-500">Connecté depuis {{ \Carbon\Carbon::now()->subHours(2)->diffForHumans() }}</div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-700">
                                    Pour votre sécurité, vous serez déconnecté automatiquement après 2 heures d'inactivité.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validation du formulaire de mot de passe
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        
        if (!currentPassword || !newPassword || !confirmPassword) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs');
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Le nouveau mot de passe doit contenir au moins 6 caractères');
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Les nouveaux mots de passe ne correspondent pas');
            return false;
        }
        
        return true;
    });
</script>
@endpush