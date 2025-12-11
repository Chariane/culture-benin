@extends('layouts.app')

@section('title', 'Mon Profil | Culture Béninoise')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Mon Profil</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Gérez vos informations personnelles et paramètres de compte
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Carte de profil -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                    <!-- Photo de profil -->
                    <div class="relative mx-auto w-48 h-48 mb-6">
                        <div class="w-full h-full rounded-2xl overflow-hidden border-4 border-white shadow-lg">
                            @if($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" 
                                     alt="{{ $user->nom }} {{ $user->prenom }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjNGY0NmU1Ii8+CjxwYXRoIGQ9Ik0xMDAgMTEwQzExMi42NTQgMTEwIDEyMiAxMDAuNjU0IDEyMiA4OEMxMjIgNzUuMzQ1OCAxMTIuNjU0IDY2IDEwMCA2NkM4Ny4zNDU4IDY2IDc4IDc1LjM0NTggNzggODhDNzggMTAwLjY1NCA4Ny4zNDU4IDExMCAxMDAgMTEwWiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTEwMCAxMjVDNzguODc1IDEyNSA2MiAxMzkuNDQ4IDYyIDE1N0gxMDBIMTM4QzEzOCAxMzkuNDQ4IDEyMS4xMjUgMTI1IDEwMCAxMjVaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K'">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                    <span class="text-white text-5xl font-bold">
                                        {{ strtoupper(substr($user->nom, 0, 1)) }}{{ strtoupper(substr($user->prenom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Badge de statut -->
                        <div class="absolute -bottom-2 -right-2 w-16 h-16 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-user-check text-white text-xl"></i>
                        </div>
                    </div>

                    <!-- Informations de base -->
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $user->prenom }} {{ $user->nom }}
                        </h2>
                        <p class="text-gray-600 mb-3">{{ $user->email }}</p>
                        
                        @if($user->role)
                        <span class="inline-block bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-user-tag mr-1"></i>
                            {{ $user->role->nom }}
                        </span>
                        @endif
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary mb-1">
                                    {{ $user->contenus_count ?? 0 }}
                                </div>
                                <div class="text-xs text-gray-600">Contenus</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-secondary mb-1">
                                    {{ $user->commentaires_count ?? 0 }}
                                </div>
                                <div class="text-xs text-gray-600">Commentaires</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-accent mb-1">
                                    {{ $user->likes_count ?? 0 }}
                                </div>
                                <div class="text-xs text-gray-600">Likes</div>
                            </div>
                        </div>
                    </div>

                    <!-- Date d'inscription -->
                    <div class="text-center text-gray-500 text-sm">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Membre depuis {{ $user->date_inscription ? \Carbon\Carbon::parse($user->date_inscription)->format('d/m/Y') : 'récemment' }}
                    </div>
                </div>
            </div>

            <!-- Formulaire d'édition -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- En-tête du formulaire -->
                    <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fas fa-user-edit"></i>
                            Modifier mes informations
                        </h3>
                    </div>

                    <!-- Messages de succès/erreur -->
                    @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mx-6 mt-6 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-6 mt-6 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                            <div>
                                <p class="text-red-700 font-medium">Veuillez corriger les erreurs suivantes :</p>
                                <ul class="text-red-600 text-sm mt-1">
                                    @foreach($errors->all() as $error)
                                    <li class="flex items-center gap-1">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                        {{ $error }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-primary"></i>
                                    Nom
                                </label>
                                <input type="text" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom', $user->nom) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </div>

                            <!-- Prénom -->
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-primary"></i>
                                    Prénom
                                </label>
                                <input type="text" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="{{ old('prenom', $user->prenom) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-primary"></i>
                                    Adresse email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
                                       required>
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-birthday-cake mr-2 text-primary"></i>
                                    Date de naissance
                                </label>
                                <input type="date" 
                                       id="date_naissance" 
                                       name="date_naissance" 
                                       value="{{ old('date_naissance', $user->date_naissance ? \Carbon\Carbon::parse($user->date_naissance)->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            </div>

                            <!-- Sexe -->
                            <div>
                                <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-venus-mars mr-2 text-primary"></i>
                                    Sexe
                                </label>
                                <select id="sexe" 
                                        name="sexe" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                                    <option value="">Sélectionner...</option>
                                    <option value="M" {{ old('sexe', $user->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('sexe', $user->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>

                            <!-- Langue -->
                            <div class="md:col-span-2">
                                <label for="id_langue" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-language mr-2 text-primary"></i>
                                    Langue préférée
                                </label>
                                <select id="id_langue" 
                                        name="id_langue" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                                    <option value="">Sélectionner une langue...</option>
                                    @foreach($langues as $langue)
                                    <option value="{{ $langue->id_langue }}" 
                                            {{ old('id_langue', $user->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                        {{ $langue->nom_langue }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Photo de profil -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-camera mr-2 text-primary"></i>
                                    Photo de profil
                                </label>
                                
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                                    <!-- Aperçu actuel -->
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-300">
                                            @if($user->photo)
                                                <img src="{{ Storage::url($user->photo) }}" 
                                                     alt="Photo actuelle"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                                    <span class="text-white text-2xl font-bold">
                                                        {{ strtoupper(substr($user->nom, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Upload -->
                                    <div class="flex-1">
                                        <div class="flex items-center justify-center w-full">
                                            <label for="photo" class="cursor-pointer flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                                    <p class="mb-1 text-sm text-gray-500">
                                                        <span class="font-semibold">Cliquez pour uploader</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP (MAX. 2MB)</p>
                                                </div>
                                                <input id="photo" 
                                                       name="photo" 
                                                       type="file" 
                                                       class="hidden" 
                                                       accept="image/*">
                                            </label>
                                        </div>
                                        
                                        <!-- Aperçu de la nouvelle image -->
                                        <div id="photo-preview" class="mt-3 hidden">
                                            <p class="text-sm text-gray-600 mb-2">Nouvelle photo :</p>
                                            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-primary">
                                                <img id="photo-preview-image" 
                                                     class="w-full h-full object-cover" 
                                                     alt="Aperçu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-8 mt-8 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-2"></i>
                                Tous les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires
                            </div>
                            
                            <div class="flex gap-4">
                                <a href="{{ route('home') }}" 
                                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Annuler
                                </a>
                                
                                <button type="submit" 
                                        class="px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-lg font-medium hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Section suppression de compte -->
                <div class="bg-white rounded-2xl shadow-lg mt-8 overflow-hidden border border-red-100">
                    <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4">
                        <h3 class="text-xl font-bold text-red-800 flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            Zone dangereuse
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Supprimer mon compte</h4>
                                <p class="text-gray-600 text-sm max-w-xl">
                                    Une fois votre compte supprimé, toutes vos données seront définitivement effacées.
                                    Cette action est irréversible. Veuillez être certain avant de continuer.
                                </p>
                            </div>
                            
                            <button type="button" 
                                    onclick="confirmDelete()"
                                    class="px-6 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors whitespace-nowrap">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Supprimer le compte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Supprimer votre compte ?</h3>
            <p class="text-gray-600 mb-4">
                Cette action est définitive. Toutes vos données seront perdues.
            </p>
        </div>
        
        <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
            @csrf
            @method('DELETE')
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmez votre mot de passe pour continuer
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500"
                       placeholder="Votre mot de passe actuel"
                       required>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'aperçu de la photo
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const photoPreviewImage = document.getElementById('photo-preview-image');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreviewImage.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Validation du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
            submitButton.disabled = true;
        });
    }
});

function confirmDelete() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('password').value = '';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush