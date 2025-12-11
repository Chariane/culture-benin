@extends('layouts.app')

@section('title', $auteur->prenom . ' ' . $auteur->nom . ' - CultureBénin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Bannière et photo de profil -->
    <div class="relative mb-12">
        <!-- Bannière -->
        <div class="h-64 rounded-3xl bg-gradient-to-r from-benin-500 via-benin-600 to-benin-700 
                    overflow-hidden shadow-xl">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
        
        <!-- Photo de profil et informations principales -->
        <div class="relative -mt-32 px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center lg:items-end gap-8">
                <!-- Photo -->
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
                
                <div class="relative">
                    <img src="{{ $photoUrl }}" 
                         alt="{{ $auteur->nom }}"
                         class="w-48 h-48 lg:w-56 lg:h-56 rounded-2xl border-6 border-white 
                                dark:border-gray-800 object-cover shadow-2xl">
                    
                    <!-- Badge Auteur -->
                    <div class="absolute -top-3 -right-3 bg-benin-500 text-white px-4 py-1.5 
                                rounded-full text-xs font-bold shadow-lg">
                        <i class="fas fa-pen-fancy mr-1"></i>
                        Auteur
                    </div>
                </div>
                
                <!-- Informations -->
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-4xl lg:text-5xl font-cinzel font-bold text-gray-900 dark:text-white mb-3">
                        {{ $auteur->prenom }} <span class="text-benin-600">{{ $auteur->nom }}</span>
                    </h1>
                    
                    <!-- Métadonnées -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-4">
                        @if($auteur->langue)
                            <div class="flex items-center text-gray-700 dark:text-gray-300 bg-gray-100 
                                        dark:bg-gray-700 px-4 py-2 rounded-xl">
                                <i class="fas fa-language text-benin-500 mr-2"></i>
                                {{ $auteur->langue->nom_langue }}
                            </div>
                        @endif
                        
                        <div class="flex items-center text-gray-700 dark:text-gray-300 bg-gray-100 
                                    dark:bg-gray-700 px-4 py-2 rounded-xl">
                            <i class="fas fa-{{ $auteur->sexe === 'Homme' ? 'male' : 'female' }} text-benin-500 mr-2"></i>
                            {{ $auteur->sexe }}
                        </div>
                        
                        <div class="flex items-center text-gray-700 dark:text-gray-300 bg-gray-100 
                                    dark:bg-gray-700 px-4 py-2 rounded-xl">
                            <i class="fas fa-calendar-alt text-benin-500 mr-2"></i>
                            Membre depuis {{ $auteur->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-gray-600 dark:text-gray-400 max-w-3xl">
                        Passionné(e) de culture béninoise, {{ $auteur->prenom }} partage sa passion à travers 
                        {{ $stats['total_contenus'] }} publications de qualité sur notre plateforme.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 rounded-xl bg-benin-100 dark:bg-benin-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt text-benin-600 dark:text-benin-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_contenus'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Publications totales</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 rounded-xl bg-beninYellow-100 dark:bg-beninYellow-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-newspaper text-beninYellow-600 dark:text-beninYellow-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['articles'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Articles</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 rounded-xl bg-beninRed-100 dark:bg-beninRed-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-camera text-beninRed-600 dark:text-beninRed-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['photos'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Photos</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-video text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['videos'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Vidéos</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres pour les contenus -->
    <div class="mb-8">
        <form action="{{ route('auteurs.show', $auteur) }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Filtre type de contenu -->
                <div class="flex-1">
                    <select name="type" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Tous les types de contenus</option>
                        @foreach($typesAuteur as $id => $nom)
                            <option value="{{ $id }}" {{ request('type') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre région -->
                <div class="flex-1">
                    <select name="region" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Toutes les régions</option>
                        @foreach($regionsAuteur as $id => $nom)
                            <option value="{{ $id }}" {{ request('region') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre date -->
                <div class="w-full lg:w-48">
                    <select name="date" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Trier par date</option>
                        <option value="recent" {{ request('date') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                        <option value="ancien" {{ request('date') == 'ancien' ? 'selected' : '' }}>Plus anciens</option>
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
                    
                    <a href="{{ route('auteurs.show', $auteur) }}" 
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

    <!-- Publications -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white">
                Publications
            </h2>
            <span class="text-gray-600 dark:text-gray-400">
                {{ $contenus->total() }} publication(s)
            </span>
        </div>
        
        @if($contenus->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($contenus as $contenu)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg 
                               hover:shadow-xl transition-all duration-300 overflow-hidden
                               border border-gray-200 dark:border-gray-700 
                               transform hover:-translate-y-1">
                        
                        <!-- Image/icône -->
                        <div class="h-48 overflow-hidden relative">
                            @if($contenu->type_contenu_id == 2 && $contenu->chemin)
                                <img src="{{ asset('storage/' . $contenu->chemin) }}" 
                                     alt="{{ $contenu->titre }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center
                                            {{ $contenu->type_contenu_id == 1 ? 'bg-gradient-to-r from-benin-500 to-benin-600' : 
                                               ($contenu->type_contenu_id == 2 ? 'bg-gradient-to-r from-beninYellow-500 to-beninYellow-600' : 
                                               'bg-gradient-to-r from-beninRed-500 to-beninRed-600') }}">
                                    <i class="fas {{ $contenu->type_contenu_id == 1 ? 'fa-newspaper' : 
                                                     ($contenu->type_contenu_id == 2 ? 'fa-camera' : 'fa-video') }} 
                                               text-white text-5xl"></i>
                                </div>
                            @endif
                            
                            <!-- Badge type -->
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold 
                                          {{ $contenu->type_contenu_id == 1 ? 'bg-benin-500 text-white' : 
                                             ($contenu->type_contenu_id == 2 ? 'bg-beninYellow-500 text-white' : 
                                             'bg-beninRed-500 text-white') }}">
                                {{ $contenu->typeContenu->nom_contenu }}
                            </span>
                        </div>
                        
                        <!-- Informations -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $contenu->titre }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                @if($contenu->description)
                                    {{ Str::limit(strip_tags($contenu->description), 100) }}
                                @else
                                    Aucune description disponible.
                                @endif
                            </p>
                            
                            <!-- Métadonnées -->
                            <div class="flex items-center justify-between mb-4">
                                @if($contenu->region)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-benin-500"></i>
                                        {{ $contenu->region->nom_region }}
                                    </div>
                                @endif
                                
                                <div class="text-gray-500 dark:text-gray-400 text-sm">
                                    <i class="far fa-calendar mr-1.5"></i>
                                    {{ $contenu->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <!-- Bouton -->
                            <a href="{{ route('contenus.show', $contenu) }}" 
                               class="block w-full text-center px-4 py-2.5 rounded-xl 
                                      bg-benin-50 dark:bg-benin-900/30 text-benin-600 dark:text-benin-400 
                                      hover:bg-benin-100 dark:hover:bg-benin-900/50 transition 
                                      font-medium text-sm">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Voir la publication
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $contenus->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 text-gray-400">
                    <i class="fas fa-box-open text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Aucune publication trouvée
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Cet auteur n'a pas encore publié de contenu ou aucun contenu ne correspond à vos filtres.
                </p>
                <a href="{{ route('auteurs.index') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-benin-500 text-white rounded-xl 
                          hover:bg-benin-600 transition font-medium gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux auteurs
                </a>
            </div>
        @endif
    </div>

    <!-- Popular Content -->
    @if(isset($contenusPopulaires) && $contenusPopulaires->count() > 0)
        <div class="mb-12">
            <h2 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white mb-6">
                Publications populaires
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($contenusPopulaires as $contenu)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden
                               border border-gray-200 dark:border-gray-700">
                        <div class="h-40 relative">
                            @if($contenu->type_contenu_id == 2 && $contenu->chemin)
                                <img src="{{ asset('storage/' . $contenu->chemin) }}" 
                                     alt="{{ $contenu->titre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center
                                            {{ $contenu->type_contenu_id == 1 ? 'bg-gradient-to-r from-benin-500 to-benin-600' : 
                                               ($contenu->type_contenu_id == 2 ? 'bg-gradient-to-r from-beninYellow-500 to-beninYellow-600' : 
                                               'bg-gradient-to-r from-beninRed-500 to-beninRed-600') }}">
                                    <i class="fas {{ $contenu->type_contenu_id == 1 ? 'fa-newspaper' : 
                                                     ($contenu->type_contenu_id == 2 ? 'fa-camera' : 'fa-video') }} 
                                               text-white text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $contenu->titre }}
                            </h4>
                            <a href="{{ route('contenus.show', $contenu) }}" 
                               class="text-benin-500 hover:text-benin-600 dark:text-benin-400 
                                      dark:hover:text-benin-300 text-sm font-medium">
                                Consulter →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
// Script pour les filtres
document.addEventListener('DOMContentLoaded', function() {
    // Auto-soumission des filtres
    document.querySelectorAll('select[name]').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Animation des cartes au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observer les cartes de contenu
    document.querySelectorAll('.grid > div').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endsection