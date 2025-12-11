@extends('layouts.app')

@section('title', 'Mon Profil - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Mon profil</h1>
        <p class="text-gray-600">Gérez vos informations personnelles et votre activité</p>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1 mb-8 lg:mb-0">
            <div class="bg-white rounded-xl shadow-soft p-6 sticky top-24">
                <!-- Photo de profil -->
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <img src="{{ $photoUrl }}" 
                             alt="{{ auth()->user()->prenom }}"
                             class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover"
                             id="profile-photo">
                        
                        <!-- Formulaire d'upload de photo -->
                        <form id="photo-upload-form" action="{{ route('profil.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="photo-input" name="photo" accept="image/*">
                        </form>
                        
                        <button type="button" 
                                onclick="document.getElementById('photo-input').click()"
                                class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                            <i class="fas fa-camera text-gray-600"></i>
                        </button>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mt-4">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-benin-100 text-benin-800">
                            {{ auth()->user()->role->nom_role ?? 'Lecteur' }}
                        </span>
                    </div>
                </div>

                <!-- Navigation du profil -->
                <nav class="space-y-1">
                    <a href="{{ route('profil.show') }}" 
                       class="flex items-center px-4 py-3 text-benin-600 bg-benin-50 rounded-lg font-medium">
                        <i class="fas fa-user-circle w-5 h-5 mr-3"></i>
                        Informations personnelles
                    </a>
                    <a href="{{ route('profil.security') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-shield-alt w-5 h-5 mr-3"></i>
                        Sécurité
                    </a>
                    <a href="{{ route('profil.notifications') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-bell w-5 h-5 mr-3"></i>
                        Notifications
                    </a>
                    <a href="{{ route('profil.activity') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-history w-5 h-5 mr-3"></i>
                        Activité
                    </a>
                    <a href="{{ route('favoris.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-heart w-5 h-5 mr-3"></i>
                        Favoris
                    </a>
                    <a href="{{ route('bibliotheque.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:text-benin-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-book w-5 h-5 mr-3"></i>
                        Bibliothèque
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <!-- Informations personnelles -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h2>
                
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
                
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom', auth()->user()->prenom) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                            @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', auth()->user()->nom) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                            @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance', auth()->user()->date_naissance) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                            @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Sexe -->
                        <div>
                            <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                            <select id="sexe" 
                                    name="sexe" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="Homme" {{ old('sexe', auth()->user()->sexe) == 'Homme' ? 'selected' : '' }}>Homme</option>
                                <option value="Femme" {{ old('sexe', auth()->user()->sexe) == 'Femme' ? 'selected' : '' }}>Femme</option>
                                <option value="Autre" {{ old('sexe', auth()->user()->sexe) == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('sexe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Langue -->
                    <div class="mb-6">
                        <label for="id_langue" class="block text-sm font-medium text-gray-700 mb-2">Langue préférée</label>
                        <select id="id_langue" 
                                name="id_langue" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent">
                            <option value="">Sélectionner une langue</option>
                            @foreach($langues as $langue)
                            <option value="{{ $langue->id_langue }}" {{ old('id_langue', auth()->user()->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                {{ $langue->nom_langue }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_langue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Bio -->
                    <div class="mb-6">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio (optionnel)</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent resize-none">{{ old('bio', auth()->user()->bio ?? '') }}</textarea>
                    </div>
                    
                    <!-- Bouton de soumission -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700 transition-colors duration-200 font-semibold">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold mb-2">{{ $stats['contenus_lus'] ?? 0 }}</div>
                            <div class="text-blue-100">Contenus lus</div>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-benin-500 to-beninYellow-500 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold mb-2">{{ $stats['achats'] ?? 0 }}</div>
                            <div class="text-benin-100">Achats effectués</div>
                        </div>
                        <svg class="w-12 h-12 text-benin-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dernières activités -->
            <div class="bg-white rounded-xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Dernières activités</h2>
                    <a href="{{ route('profil.activity') }}" class="text-benin-600 hover:text-benin-700 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse($activites as $activite)
                    <div class="flex items-start p-4 rounded-lg border border-gray-200">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $activite->type == 'view' ? 'bg-blue-100' : ($activite->type == 'like' ? 'bg-red-100' : 'bg-green-100') }} flex items-center justify-center mr-4">
                            @if($activite->type == 'view')
                            <svg class="w-5 h-5 {{ $activite->type == 'view' ? 'text-blue-600' : ($activite->type == 'like' ? 'text-red-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            @elseif($activite->type == 'like')
                            <svg class="w-5 h-5 {{ $activite->type == 'view' ? 'text-blue-600' : ($activite->type == 'like' ? 'text-red-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            @else
                            <svg class="w-5 h-5 {{ $activite->type == 'view' ? 'text-blue-600' : ($activite->type == 'like' ? 'text-red-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <div class="text-gray-900">
                                @if($activite->type == 'view')
                                Vous avez lu "{{ $activite->contenu->titre ?? 'ce contenu' }}"
                                @elseif($activite->type == 'like')
                                Vous avez aimé "{{ $activite->contenu->titre ?? 'ce contenu' }}"
                                @else
                                Vous avez commenté "{{ $activite->contenu->titre ?? 'ce contenu' }}"
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $activite->date->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500">Aucune activité récente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gestion de l'upload de photo
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo-input');
        const photoForm = document.getElementById('photo-upload-form');
        const profilePhoto = document.getElementById('profile-photo');
        
        if (photoInput && photoForm) {
            // Écouter le changement de fichier
            photoInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    
                    // Validation de la taille
                    if (file.size > maxSize) {
                        alert('La photo ne doit pas dépasser 5MB');
                        this.value = '';
                        return;
                    }
                    
                    // Validation du type
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format d\'image non supporté. Utilisez JPEG, PNG, GIF ou WebP.');
                        this.value = '';
                        return;
                    }
                    
                    // Prévisualisation
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        profilePhoto.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                    
                    // Soumettre automatiquement le formulaire
                    photoForm.submit();
                }
            });
        }
        
        // Validation du formulaire de profil
        const profileForm = document.querySelector('form[action*="profil.update"]');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                const prenom = document.getElementById('prenom').value.trim();
                const nom = document.getElementById('nom').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!prenom || !nom || !email) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires');
                    return false;
                }
                
                if (!validateEmail(email)) {
                    e.preventDefault();
                    alert('Veuillez entrer une adresse email valide');
                    return false;
                }
                
                return true;
            });
        }
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>

<style>
    /* Animation de rotation pour le spinner */
    .fa-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Style pour l'image de profil */
    .object-cover {
        object-fit: cover;
    }
</style>
@endpush
@endsection