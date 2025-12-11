@extends('layouts.app')

@section('title', 'Auteurs - CultureBénin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec statistiques -->
    <div class="mb-10">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                Nos Auteurs
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                Rencontrez les passionnés qui partagent et préservent la culture béninoise à travers leurs écrits, photos et vidéos.
            </p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-benin-100 dark:bg-benin-900/30 flex items-center justify-center mr-4">
                        <i class="fas fa-users text-benin-600 dark:text-benin-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_auteurs'] }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Auteurs inscrits</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-beninYellow-100 dark:bg-beninYellow-900/30 flex items-center justify-center mr-4">
                        <i class="fas fa-file-alt text-beninYellow-600 dark:text-beninYellow-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_contenus'] }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Contenus publiés</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-beninRed-100 dark:bg-beninRed-900/30 flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-beninRed-600 dark:text-beninRed-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['auteurs_actifs'] }}</div>
                        <div class="text-gray-600 dark:text-gray-400">Auteurs actifs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="mb-8">
        <form action="{{ route('auteurs.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Recherche -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Rechercher un auteur par nom, prénom ou email..." 
                               class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-300 
                                      dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                      focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <!-- Filtre langue -->
                <div class="w-full lg:w-48">
                    <select name="langue" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Toutes les langues</option>
                        @foreach($languesDisponibles as $id => $nom)
                            <option value="{{ $id }}" {{ request('langue') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre sexe -->
                <div class="w-full lg:w-48">
                    <select name="sexe" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Tous les genres</option>
                        <option value="Homme" {{ request('sexe') == 'Homme' ? 'selected' : '' }}>Homme</option>
                        <option value="Femme" {{ request('sexe') == 'Femme' ? 'selected' : '' }}>Femme</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex gap-3">
                    <button type="submit" 
                            class="px-6 py-3.5 bg-benin-500 text-white rounded-xl hover:bg-benin-600 
                                   transition font-medium flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                    
                    <a href="{{ route('auteurs.index') }}" 
                       class="px-6 py-3.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                              rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium 
                              flex items-center gap-2">
                        <i class="fas fa-redo"></i>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des auteurs -->
    @if($auteurs->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($auteurs as $auteur)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg 
                           hover:shadow-xl transition-all duration-300 overflow-hidden
                           border border-gray-200 dark:border-gray-700 
                           transform hover:-translate-y-1">
                    
                    <!-- En-tête avec photo -->
                    <div class="relative h-40 bg-gradient-to-r from-benin-400 to-benin-600">
                        @php
                            $photoUrl = null;
                            if ($auteur->photo && file_exists(public_path('storage/photos/' . basename($auteur->photo)))) {
                                $photoUrl = asset('storage/photos/' . basename($auteur->photo));
                            } else {
                                if ($auteur->sexe === 'Homme') {
                                    $photoUrl = asset('male.jpg');
                                } elseif ($auteur->sexe === 'Femme') {
                                    $photoUrl = asset('female.jpg');
                                } else {
                                    $photoUrl = asset('images/default-avatar.png');
                                }
                            }
                        @endphp
                        
                        <img src="{{ $photoUrl }}" 
                             alt="{{ $auteur->nom }}"
                             class="w-28 h-28 rounded-full border-4 border-white dark:border-gray-800 
                                    absolute -bottom-14 left-1/2 transform -translate-x-1/2 object-cover shadow-lg">
                    </div>

                    <!-- Informations de l'auteur -->
                    <div class="pt-16 pb-6 px-6 text-center">
                        <h3 class="text-xl font-cinzel font-semibold text-gray-900 dark:text-white mb-1">
                            {{ $auteur->prenom }} {{ $auteur->nom }}
                        </h3>
                        
                        @if($auteur->langue)
                            <div class="flex items-center justify-center text-gray-600 dark:text-gray-400 text-sm mb-3">
                                <i class="fas fa-language mr-2"></i>
                                {{ $auteur->langue->nom_langue }}
                            </div>
                        @endif

                        <!-- Statistiques -->
                        <div class="flex justify-center items-center space-x-4 mb-4">
                            <div class="text-center">
                                <div class="text-xl font-bold text-benin-600 dark:text-benin-400">
                                    {{ $auteur->contenus_count }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Publications
                                </div>
                            </div>
                            
                            <div class="h-8 w-px bg-gray-300 dark:bg-gray-600"></div>
                            
                            <div class="text-center">
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-{{ $auteur->sexe === 'Homme' ? 'male' : 'female' }} mr-1"></i>
                                    {{ $auteur->sexe }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Genre
                                </div>
                            </div>
                        </div>

                        <!-- Date d'inscription -->
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                            <i class="far fa-calendar-alt mr-1"></i>
                            Depuis {{ $auteur->created_at->format('m/Y') }}
                        </div>

                        <!-- Bouton de profil -->
                        <a href="{{ route('auteurs.show', $auteur) }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl 
                                  bg-benin-50 dark:bg-benin-900/30 text-benin-600 dark:text-benin-400 
                                  hover:bg-benin-100 dark:hover:bg-benin-900/50 transition 
                                  font-medium text-sm group">
                            <i class="fas fa-eye mr-2 group-hover:mr-3 transition-all"></i>
                            Voir le profil
                            <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 
                               group-hover:ml-3 transition-all"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $auteurs->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-32 h-32 mx-auto mb-6 text-gray-400">
                <i class="fas fa-user-slash text-8xl"></i>
            </div>
            <h3 class="text-2xl font-cinzel font-semibold text-gray-700 dark:text-gray-300 mb-3">
                Aucun auteur trouvé
            </h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
                Aucun auteur ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
            </p>
            <a href="{{ route('auteurs.index') }}" 
               class="inline-flex items-center px-6 py-3.5 bg-benin-500 text-white rounded-xl 
                      hover:bg-benin-600 transition font-medium gap-2">
                <i class="fas fa-redo"></i>
                Réinitialiser la recherche
            </a>
        </div>
    @endif
</div>

<style>
    /* Style pour la pagination */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        gap: 4px;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .pagination li a:hover:not(.disabled) {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    
    .pagination li.active span {
        background: linear-gradient(to right, #16a34a, #22c55e);
        border-color: #16a34a;
        color: white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
    }
    
    .pagination li.disabled span {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    
    /* Mode sombre */
    .dark .pagination li a,
    .dark .pagination li span {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
    }
    
    .dark .pagination li a:hover:not(.disabled) {
        background: #4b5563;
        border-color: #6b7280;
    }
    
    .dark .pagination li.active span {
        background: linear-gradient(to right, #16a34a, #22c55e);
        border-color: #16a34a;
        color: white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    
    .dark .pagination li.disabled span {
        background: #374151;
        border-color: #4b5563;
        color: #6b7280;
    }
</style>

<script>
// Script pour la recherche en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const filterForm = document.querySelector('form');
    
    // Auto-soumission après 500ms sans frappe
    let searchTimeout;
    searchInput?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
    
    // Soumission au changement des sélecteurs
    document.querySelectorAll('select[name], input[name]').forEach(element => {
        if (element.name !== 'search') {
            element.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });
});
</script>
@endsection