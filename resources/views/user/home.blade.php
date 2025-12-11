@extends('layouts.app')

@section('title', 'CultureBénin - Découvrez la richesse culturelle du Bénin')

@section('content')
<!-- Hero Section avec identité béninoise -->
<section class="relative overflow-hidden bg-gradient-to-br from-benin-900 via-gray-900 to-beninRed-900">
    <!-- Pattern décoratif -->
    <div class="absolute inset-0 hero-pattern opacity-10"></div>
    
    <!-- Éléments décoratifs -->
    <div class="absolute top-10 left-10 w-24 h-24 rounded-full bg-benin-500/10 blur-xl"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-beninYellow-500/10 blur-xl"></div>
    <div class="absolute top-1/2 left-1/3 w-16 h-16 rounded-full bg-beninRed-500/10 blur-xl"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 md:pt-28 md:pb-40">
        <div class="text-center">
            <!-- Badge drapeau béninois -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm mb-8 border border-white/20">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-4 gradient-benin rounded"></div>
                    <span class="text-white text-sm font-medium">Culture Béninoise</span>
                </div>
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-cinzel font-bold text-white mb-6 animate-fade-in">
                <span class="gradient-text-benin">Découvrez</span> la richesse<br>
                <span class="text-beninYellow-300">culturelle du Bénin</span>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-200 mb-10 max-w-3xl mx-auto leading-relaxed">
                Explorez le patrimoine unique du Bénin à travers une collection exclusive 
                de contenus authentiques, de traditions vivantes et d'histoires captivantes.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contenus.index') }}" 
                   class="group btn btn-benin px-10 py-4 text-lg font-semibold shadow-xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-compass mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                    Explorer la collection
                    <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform duration-300"></i>
                </a>
                
                <a href="{{ route('register') }}" 
                   class="group px-10 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white rounded-xl font-semibold hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-3"></i>
                    Rejoindre la communauté
                </a>
            </div>
            
            <!-- Statistiques en temps réel -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2 stat-number" data-target="{{ $stats['contenus'] ?? 1500 }}">{{ $stats['contenus'] ?? '1500+' }}</div>
                    <div class="text-gray-300 text-sm">Contenus culturels</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2 stat-number" data-target="{{ $stats['regions'] ?? 12 }}">{{ $stats['regions'] ?? '12' }}</div>
                    <div class="text-gray-300 text-sm">Régions couvertes</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2 stat-number" data-target="{{ $stats['auteurs'] ?? 250 }}">{{ $stats['auteurs'] ?? '250+' }}</div>
                    <div class="text-gray-300 text-sm">Experts & Auteurs</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2 stat-number" data-target="{{ $stats['utilisateurs'] ?? 5000 }}">{{ $stats['utilisateurs'] ?? '5K+' }}</div>
                    <div class="text-gray-300 text-sm">Membres actifs</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vagues décoratives -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 60L60 52C120 44 240 28 360 28C480 28 600 44 720 52C840 60 960 60 1080 52C1200 44 1320 28 1380 20L1440 12V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V60Z" 
                  fill="url(#benin-wave)" class="wave-animation"/>
            <defs>
                <linearGradient id="benin-wave" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#22c55e;stop-opacity:1" />
                    <stop offset="33%" style="stop-color:#facc15;stop-opacity:1" />
                    <stop offset="66%" style="stop-color:#facc15;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#ef4444;stop-opacity:1" />
                </linearGradient>
            </defs>
        </svg>
    </div>
</section>

<!-- Section des contenus récents -->
<section class="py-16 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-12 h-1 bg-gradient-to-r from-benin-500 to-beninYellow-500 rounded-full"></div>
                    <span class="text-benin-600 dark:text-benin-400 font-semibold text-sm uppercase tracking-wider">À découvrir</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                    Contenus <span class="gradient-text-benin">Récents</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl">
                    Découvrez les dernières publications de nos experts culturels sur le patrimoine béninois
                </p>
            </div>
            
            <a href="{{ route('contenus.index') }}" 
               class="group mt-6 lg:mt-0 inline-flex items-center text-benin-600 dark:text-benin-400 hover:text-benin-700 dark:hover:text-benin-300 font-semibold">
                Voir toute la collection
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform duration-300"></i>
            </a>
        </div>

        <!-- Grille des contenus -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($recentContents as $content)
            <article class="card card-hover group animate-on-scroll">
                <!-- Image avec overlay -->
                <div class="relative h-56 overflow-hidden">
                    @if($content->medias->first())
                    <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                         alt="{{ $content->titre }}"
                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                         loading="lazy">
                    @else
                    <div class="w-full h-full gradient-overlay flex items-center justify-center">
                        <div class="text-center p-6">
                            <i class="fas fa-{{ $content->type->nom_contenu == 'Vidéo' ? 'play-circle' : ($content->type->nom_contenu == 'Audio' ? 'music' : ($content->type->nom_contenu == 'Image' ? 'image' : 'feather-alt')) }} text-5xl text-benin-500 mb-4"></i>
                            <span class="text-benin-800 font-cinzel text-lg">{{ $content->type->nom_contenu ?? 'Contenu' }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Badge type -->
                    <div class="absolute top-4 left-4">
                        <span class="badge badge-benin px-3 py-1.5 text-xs">
                            <i class="fas fa-{{ $content->type->nom_contenu == 'Vidéo' ? 'video' : ($content->type->nom_contenu == 'Audio' ? 'music' : ($content->type->nom_contenu == 'Image' ? 'image' : 'file-alt')) }} mr-1.5"></i>
                            {{ $content->type->nom_contenu ?? 'Article' }}
                        </span>
                    </div>
                    
                    @if($content->premium)
                    <div class="absolute top-4 right-4">
                        <span class="badge badge-premium px-3 py-1.5 text-xs">
                            <i class="fas fa-crown mr-1.5"></i>
                            Premium
                        </span>
                    </div>
                    @endif
                    
                    <!-- Overlay de lecture -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <a href="{{ route('contenus.show', $content) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-lg font-medium hover:bg-gray-100 transition">
                                <i class="fas fa-play-circle mr-2"></i>
                                Consulter
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contenu -->
                <div class="p-6">
                    <!-- Date -->
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ $content->date_creation->format('d F Y') }}
                    </div>
                    
                    <!-- Titre -->
                    <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-benin-600 dark:group-hover:text-benin-400 transition-colors">
                        <a href="{{ route('contenus.show', $content) }}">
                            {{ $content->titre }}
                        </a>
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2 text-sm">
                        {{ Str::limit(strip_tags($content->texte), 120) }}
                    </p>
                    
                    <!-- Auteur -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center">
                            @if($content->auteur->photo)
                            <img src="{{ Storage::url($content->auteur->photo) }}" 
                                 alt="{{ $content->auteur->prenom }}"
                                 class="w-9 h-9 rounded-full border-2 border-benin-500 mr-3">
                            @else
                            <div class="w-9 h-9 rounded-full gradient-benin flex items-center justify-center text-white font-semibold mr-3">
                                {{ strtoupper(substr($content->auteur->prenom, 0, 1)) }}
                            </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">{{ $content->auteur->prenom }} {{ $content->auteur->nom }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Expert culturel</div>
                            </div>
                        </div>
                        
                        <!-- Interactions -->
                        <div class="flex items-center space-x-3">
                            <button onclick="toggleFavorite({{ $content->id_contenu }}, this)" 
                                    class="transition-colors {{ $content->is_favorite ? 'text-beninRed-500' : 'text-gray-400 hover:text-beninRed-500' }}"
                                    data-tooltip="{{ $content->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                <i class="{{ $content->is_favorite ? 'fas fa-heart' : 'far fa-heart' }} text-lg"></i>
                            </button>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                <i class="far fa-eye mr-1.5"></i>
                                <span>{{ $content->nombre_vues ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full">
                <div class="text-center py-16">
                    <div class="w-32 h-32 mx-auto mb-6 text-gray-300 dark:text-gray-700">
                        <i class="fas fa-search text-8xl"></i>
                    </div>
                    <h3 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">Aucun contenu disponible</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        De nouveaux contenus seront bientôt disponibles. Revenez plus tard !
                    </p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section des régions béninoises -->
<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-benin-50 dark:bg-benin-900/30 mb-6">
                <i class="fas fa-map-marker-alt text-benin-600 dark:text-benin-400 mr-2"></i>
                <span class="text-benin-700 dark:text-benin-300 font-medium">Géographie culturelle</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                Explorez par <span class="gradient-text-benin">Région</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Découvrez la diversité culturelle à travers les 12 départements du Bénin
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($popularRegions as $region)
            <a href="{{ route('regions.show', $region) }}" 
               class="group relative rounded-2xl overflow-hidden shadow-lg hover-lift animate-on-scroll">
                <div class="h-64">
                    <!-- Image ou couleur de fond -->
                    <div class="absolute inset-0 bg-gradient-to-br from-benin-600 via-beninYellow-500 to-beninRed-500"></div>
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
                    
                    <!-- Nom de la région -->
                    <div class="absolute inset-0 flex items-center justify-center z-10 p-6">
                        <div class="text-center">
                            <h3 class="text-2xl md:text-3xl font-cinzel font-bold text-white mb-2">{{ $region->nom_region }}</h3>
                            <div class="w-16 h-1 bg-white/50 rounded-full mx-auto"></div>
                        </div>
                    </div>
                    
                    <!-- Badge nombre de contenus -->
                    <div class="absolute top-4 left-4 z-20">
                        <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium">
                            {{ $region->contenus_count ?? 0 }} contenus
                        </span>
                    </div>
                </div>
                
                <!-- Footer avec CTA -->
                <div class="absolute bottom-4 left-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="flex items-center justify-between">
                        <div class="text-white text-sm">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Explorer la région
                        </div>
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/30 transition">
                            <i class="fas fa-arrow-right text-white"></i>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <div class="w-32 h-32 mx-auto mb-6 text-gray-300 dark:text-gray-700">
                        <i class="fas fa-map text-8xl"></i>
                    </div>
                    <h3 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">Régions à venir</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        Les contenus par région seront bientôt disponibles
                    </p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section des valeurs -->
<section class="py-16 bg-gradient-to-br from-benin-50 via-white to-beninYellow-50 dark:from-gray-800 dark:via-gray-900 dark:to-benin-900/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                Notre <span class="gradient-text-benin">Mission</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Préservation, promotion et transmission du patrimoine culturel béninois
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Carte 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-soft hover-lift border border-gray-100 dark:border-gray-700 animate-on-scroll">
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl gradient-benin flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-4 text-center">Préservation</h3>
                <p class="text-gray-600 dark:text-gray-300 text-center">
                    Sauvegarder les traditions, langues et savoir-faire ancestraux du Bénin pour les générations futures
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center text-sm text-benin-600 dark:text-benin-400 font-medium">
                        <i class="fas fa-leaf mr-2"></i>
                        Patrimoine vivant
                    </span>
                </div>
            </div>
            
            <!-- Carte 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-soft hover-lift border border-gray-100 dark:border-gray-700 animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl gradient-benin flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-4 text-center">Promotion</h3>
                <p class="text-gray-600 dark:text-gray-300 text-center">
                    Faire connaître la richesse culturelle béninoise au niveau national et international
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center text-sm text-benin-600 dark:text-benin-400 font-medium">
                        <i class="fas fa-globe-africa mr-2"></i>
                        Rayonnement mondial
                    </span>
                </div>
            </div>
            
            <!-- Carte 3 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-soft hover-lift border border-gray-100 dark:border-gray-700 animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl gradient-benin flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-4 text-center">Transmission</h3>
                <p class="text-gray-600 dark:text-gray-300 text-center">
                    Éduquer et sensibiliser les jeunes générations à l'importance du patrimoine culturel
                </p>
                <div class="mt-6 text-center">
                    <span class="inline-flex items-center text-sm text-benin-600 dark:text-benin-400 font-medium">
                        <i class="fas fa-seedling mr-2"></i>
                        Éducation culturelle
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Laisser un avis -->
<section class="py-20 relative overflow-hidden">
    <!-- Background adapté au mode clair/sombre -->
    <div class="absolute inset-0 bg-gradient-to-br from-benin-900 via-gray-900 to-beninRed-900 dark:from-benin-900 dark:via-gray-900 dark:to-beninRed-900"></div>
    <!-- Pattern décoratif -->
    <div class="absolute inset-0 african-pattern opacity-5 dark:opacity-5"></div>
    
    <!-- Éléments décoratifs -->
    <div class="absolute top-1/4 -left-20 w-64 h-64 rounded-full bg-benin-500/10 dark:bg-benin-500/5 blur-3xl"></div>
    <div class="absolute bottom-1/4 -right-20 w-64 h-64 rounded-full bg-beninYellow-500/10 dark:bg-beninYellow-500/5 blur-3xl"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Badge drapeau -->
            <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/20 dark:bg-white/10 backdrop-blur-sm border border-white/30 dark:border-white/20 mb-8">
                <div class="w-8 h-5 gradient-benin rounded mr-3"></div>
                <span class="text-white font-medium dark:text-white">Votre opinion compte</span>
            </div>
            
            <h2 class="text-3xl md:text-5xl font-cinzel font-bold text-white dark:text-white mb-6">
                Donnez votre <span class="gradient-text-benin">avis</span>
            </h2>
            
            <p class="text-xl text-white dark:text-gray-200 mb-10 max-w-2xl mx-auto">
                Partagez votre expérience avec CultureBénin en quelques mots
            </p>
            
            @auth
            <!-- Formulaire pour les utilisateurs connectés -->
            <form action="{{ route('avis.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-6">
                    <textarea name="message" rows="3" 
                          class="w-full px-6 py-4 bg-white/20 dark:bg-white/10 backdrop-blur-sm border-2 border-white/40 dark:border-white/30 text-white dark:text-white rounded-xl font-medium placeholder-white/70 dark:placeholder-white/50 focus:outline-none focus:border-white/60 dark:focus:border-white/50"
                          placeholder="Dites-nous ce que vous pensez de CultureBénin... (entre 10 et 500 caractères)"
                          required minlength="10" maxlength="500"></textarea>
                    @error('message')
                        <p class="text-red-300 dark:text-red-300 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" 
                      class="group btn btn-benin px-12 py-5 text-lg font-semibold shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-paper-plane mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                    Envoyer mon avis
                </button>
                <p class="text-white/80 dark:text-gray-300 text-sm mt-4">
                    <i class="fas fa-info-circle mr-1"></i> Un seul avis par jour autorisé
                </p>
            </form>
            @else
            <!-- Message pour les non connectés -->
            <div class="mb-8">
                <p class="text-xl text-white dark:text-gray-200 mb-8">
                    Connectez-vous pour nous faire part de votre avis.
                </p>
                <a href="{{ route('login') }}" 
                   class="group btn btn-benin px-12 py-5 text-lg font-semibold shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                    Se connecter
                </a>
                <a href="{{ route('register') }}" 
                   class="group ml-4 px-12 py-5 bg-white/20 dark:bg-white/10 backdrop-blur-sm border-2 border-white/40 dark:border-white/30 text-white dark:text-white rounded-xl font-semibold hover:bg-white hover:text-gray-900 dark:hover:bg-white dark:hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-3"></i>
                    S'inscrire
                </a>
            </div>
            @endauth
            
            <div class="mt-12 flex items-center justify-center space-x-6 text-white/80 dark:text-gray-300 text-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-benin-300 dark:text-benin-400 mr-2"></i>
                    <span>Confidentiel et anonyme</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-beninYellow-300 dark:text-beninYellow-400 mr-2"></i>
                    <span>Amélioration continue</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-beninRed-300 dark:text-beninRed-400 mr-2"></i>
                    <span>Votre avis compte</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section partenaires (optionnelle) -->
<section class="py-12 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400 mb-2">En partenariat avec</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Partenaires fictifs - à remplacer par vos vrais partenaires -->
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <span class="text-sm">Ministère de la Culture</span>
            </div>
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <span class="text-sm">Université d'Abomey-Calavi</span>
            </div>
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-landmark text-xl"></i>
                </div>
                <span class="text-sm">Musée Honmé</span>
            </div>
            <div class="text-center text-gray-400 dark:text-gray-500">
                <div class="w-16 h-16 mx-auto mb-3 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-globe-africa text-xl"></i>
                </div>
                <span class="text-sm">UNESCO Bénin</span>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Animation spécifique pour la page d'accueil */
    .hero-section {
        background: linear-gradient(135deg, 
            rgba(34, 197, 94, 0.1) 0%, 
            rgba(34, 197, 94, 0.05) 25%, 
            rgba(250, 204, 21, 0.05) 50%, 
            rgba(239, 68, 68, 0.1) 75%, 
            rgba(239, 68, 68, 0.05) 100%
        );
    }
    
    /* Animation des statistiques */
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .stat-number {
        animation: countUp 1s ease-out forwards;
    }
    
    /* Animation des cartes au scroll */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .animate-on-scroll.animate-fade-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Style pour les liens régionaux */
    .region-card {
        position: relative;
        overflow: hidden;
    }
    
    .region-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .region-card:hover::before {
        left: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    // Animation des statistiques au scroll
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au scroll pour les éléments
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Animation des statistiques
        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-target')) || 0;
            const current = parseInt(stat.textContent.replace('+', '')) || 0;
            
            if (current < target) {
                let count = current;
                const increment = target / 100;
                const timer = setInterval(() => {
                    count += increment;
                    if (count >= target) {
                        count = target;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(count) + (stat.textContent.includes('+') ? '+' : '');
                }, 20);
            }
        });
    });

    // Fonction pour ajouter aux favoris
    // Fonction pour ajouter aux favoris
function toggleFavorite(contenuId) {
    fetch(`/api/favoris/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_contenu: contenuId })
    })
    .then(response => response.json())
    .then(data => {
        // Mettre à jour l'icône
        const buttons = document.querySelectorAll(`[onclick="toggleFavorite(${contenuId})"]`);
        buttons.forEach(button => {
            const icon = button.querySelector('i');
            if (data.is_favorite) {
                icon.className = 'fas fa-heart text-beninRed-500 text-lg';
            } else {
                icon.className = 'far fa-heart text-lg';
            }
        });
        
        // Notification toast
        showToast(data.is_favorite ? 'Ajouté aux favoris' : 'Retiré des favoris', data.is_favorite ? 'success' : 'info');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Une erreur est survenue', 'error');
    });
}

    // Fonction pour afficher des notifications toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-hard z-50 transform translate-x-full transition-transform duration-300`;
        
        // Couleur selon le type
        if (type === 'success') {
            toast.classList.add('bg-benin-500', 'text-white');
        } else if (type === 'error') {
            toast.classList.add('bg-beninRed-500', 'text-white');
        } else {
            toast.classList.add('bg-gray-800', 'text-white');
        }
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-3"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animation d'entrée
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 10);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endpush