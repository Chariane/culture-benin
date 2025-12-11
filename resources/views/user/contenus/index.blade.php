@extends('layouts.app')

@section('title', isset($currentType) ? 'Contenus ' . $currentType->nom_contenu . ' - CultureBénin' : 'Explorer les contenus - CultureBénin')

@section('content')
<!-- Hero Section pour la page des contenus -->
<section class="relative overflow-hidden bg-gradient-to-br from-benin-900 via-gray-900 to-beninRed-900 py-16 md:py-24">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-64 h-64 rounded-full bg-benin-500/20 blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-beninYellow-500/20 blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Breadcrumb -->
            @isset($currentType)
            <nav class="flex justify-center mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-benin-300 hover:text-white transition-colors">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                        <a href="{{ route('contenus.index') }}" class="text-gray-300 hover:text-white transition-colors">Contenus</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                        <span class="text-beninYellow-300 font-medium">{{ $currentType->nom_contenu }}s</span>
                    </li>
                </ol>
            </nav>
            @endisset
            
            <h1 class="text-4xl md:text-6xl font-cinzel font-bold text-white mb-6 animate-fade-in">
                @isset($currentType)
                Explorez nos <span class="gradient-text-benin">{{ $currentType->nom_contenu }}s</span>
                @else
                Découvrez notre <span class="gradient-text-benin">Collection</span>
                @endif
            </h1>
            
            <p class="text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
                @isset($currentType)
                Une sélection exclusive de {{ strtolower($currentType->nom_contenu) }}s culturels authentiques du Bénin
                @else
                Parcourez notre bibliothèque de contenus culturels béninois authentiques et vérifiés
                @endif
            </p>
            
            <!-- Quick Stats -->
            <div class="inline-flex items-center space-x-6 mb-8">
                <div class="flex items-center text-gray-300">
                    <i class="fas fa-book-open text-benin-400 mr-2"></i>
                    <span>{{ $contenus->total() }} contenus</span>
                </div>
                <div class="flex items-center text-gray-300">
                    <i class="fas fa-users text-beninYellow-400 mr-2"></i>
                    <span>{{ $stats['auteurs'] ?? 250 }}+ auteurs</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 30L60 26C120 22 240 14 360 14C480 14 600 22 720 26C840 30 960 30 1080 26C1200 22 1320 14 1380 10L1440 6V60H1380C1320 60 1200 60 1080 60C960 60 840 60 720 60C600 60 480 60 360 60C240 60 120 60 60 60H0V30Z" 
                  fill="white" class="dark:fill-gray-900"/>
        </svg>
    </div>
</section>

<!-- Main Content -->
<div class="bg-white dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-8">
            <!-- Sidebar Filters - Modern Design -->
            <div class="lg:col-span-3">
                <div class="sticky top-24 space-y-6">
                    <!-- Mobile Filter Toggle -->
                    <button id="filter-toggle" class="lg:hidden w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-benin-500 to-benin-600 text-white rounded-xl shadow-lg">
                        <span class="font-semibold">Filtres & Options</span>
                        <i class="fas fa-sliders-h text-xl"></i>
                    </button>
                    
                    <!-- Filters Panel -->
                    <div id="filters-panel" class="lg:block hidden bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 space-y-6 animate-slide-down">
                        <!-- Header -->
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-cinzel font-bold text-gray-900 dark:text-white">
                                <i class="fas fa-filter text-benin-500 mr-2"></i>
                                Filtres Avancés
                            </h3>
                            <button onclick="resetFilters()" class="text-sm text-benin-600 dark:text-benin-400 hover:text-benin-700 dark:hover:text-benin-300">
                                <i class="fas fa-redo mr-1"></i>
                                Réinitialiser
                            </button>
                        </div>
                        
                        <!-- Search -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-search text-benin-500 mr-2"></i>
                                Recherche
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       placeholder="Rechercher un contenu..."
                                       value="{{ request('search') }}"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-benin-500 focus:border-transparent transition-all duration-300 dark:text-white">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Type Filter -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-layer-group text-beninYellow-500 mr-2"></i>
                                Type de contenu
                            </label>
                            <div class="space-y-2">
                                @foreach($types as $type)
                                <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 border border-gray-200 dark:border-gray-600">
                                    <input type="radio" 
                                           name="type" 
                                           value="{{ $type->id_type_contenu }}"
                                           {{ request('type') == $type->id_type_contenu ? 'checked' : '' }}
                                           onchange="applyFilters()"
                                           class="hidden peer">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-500 peer-checked:border-benin-500 peer-checked:bg-benin-500 flex items-center justify-center mr-3 transition-all duration-300">
                                        <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-300"></i>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="{{ getTypeIcon($type->nom_contenu) }} text-gray-500 dark:text-gray-400 mr-2"></i>
                                        <span class="text-gray-700 dark:text-gray-300 peer-checked:text-benin-600 dark:peer-checked:text-benin-400 transition-colors duration-300">
                                            {{ $type->nom_contenu }}
                                        </span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Region Filter -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-map-marked-alt text-beninRed-500 mr-2"></i>
                                Région
                            </label>
                            <select id="region" 
                                    name="region" 
                                    onchange="applyFilters()"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-benin-500 focus:border-transparent appearance-none dark:text-white">
                                <option value="">Toutes les régions</option>
                                @foreach($regions as $region)
                                <option value="{{ $region->id_region }}" {{ request('region') == $region->id_region ? 'selected' : '' }}>
                                    {{ $region->nom_region }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Premium Filter -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-crown text-beninYellow-500 mr-2"></i>
                                Type d'accès
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-benin-500 dark:hover:border-benin-500 cursor-pointer transition-all duration-300 {{ request('premium') === '0' ? 'border-benin-500 dark:border-benin-500 bg-benin-50 dark:bg-benin-900/20' : '' }}">
                                    <input type="radio" 
                                           name="premium" 
                                           value="0"
                                           {{ request('premium') === '0' ? 'checked' : '' }}
                                           onchange="applyFilters()"
                                           class="hidden">
                                    <i class="fas fa-unlock text-2xl text-gray-400 mb-2"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Gratuit</span>
                                </label>
                                <label class="flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-beninYellow-500 dark:hover:border-beninYellow-500 cursor-pointer transition-all duration-300 {{ request('premium') === '1' ? 'border-beninYellow-500 dark:border-beninYellow-500 bg-beninYellow-50 dark:bg-beninYellow-900/20' : '' }}">
                                    <input type="radio" 
                                           name="premium" 
                                           value="1"
                                           {{ request('premium') === '1' ? 'checked' : '' }}
                                           onchange="applyFilters()"
                                           class="hidden">
                                    <i class="fas fa-crown text-2xl text-beninYellow-500 mb-2"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Premium</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-sort-amount-down text-purple-500 mr-2"></i>
                                Trier par
                            </label>
                            <select id="sort" 
                                    name="sort" 
                                    onchange="applyFilters()"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-benin-500 focus:border-transparent appearance-none dark:text-white">
                                <option value="date_creation" {{ request('sort') == 'date_creation' ? 'selected' : '' }}>Plus récent</option>
                                <option value="titre" {{ request('sort') == 'titre' ? 'selected' : '' }}>Titre A-Z</option>
                                <option value="popularite" {{ request('sort') == 'popularite' ? 'selected' : '' }}>Plus populaire</option>
                            </select>
                        </div>
                        
                        <!-- Apply Button -->
                        <button onclick="applyFilters()" 
                                class="w-full py-3 bg-gradient-to-r from-benin-500 to-benin-600 text-white rounded-xl font-semibold hover:from-benin-600 hover:to-benin-700 transition-all duration-300 transform hover:scale-[1.02] shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i>
                            Appliquer les filtres
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <div class="lg:col-span-9">
                <!-- Results Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 p-6 bg-gradient-to-r from-gray-50 to-benin-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-sm">
                    <div>
                        <h2 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-2">
                            @isset($currentType)
                            {{ $currentType->nom_contenu }}s Culturels
                            @else
                            Tous les Contenus
                            @endif
                        </h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <span class="flex items-center">
                                <i class="fas fa-layer-group mr-2"></i>
                                {{ $contenus->total() }} résultat{{ $contenus->total() > 1 ? 's' : '' }}
                            </span>
                            @if(request()->hasAny(['search', 'type', 'region', 'langue', 'premium']))
                            <button onclick="resetFilters()" class="text-benin-600 dark:text-benin-400 hover:text-benin-700 dark:hover:text-benin-300 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i>
                                Effacer les filtres
                            </button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2 mt-4 md:mt-0">
                        <button id="grid-view" 
                                class="p-3 rounded-xl bg-gradient-to-r from-benin-500 to-benin-600 text-white shadow-lg transform hover:scale-105 transition-all duration-300"
                                data-tooltip="Vue grille">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="list-view" 
                                class="p-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-105"
                                data-tooltip="Vue liste">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Content Grid/List -->
                <div id="content-container">
                    @if($contenus->count() > 0)
                        <!-- Grid View -->
                        <div id="grid-view-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($contenus as $content)
                            <article class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover-lift border border-gray-200 dark:border-gray-700 overflow-hidden animate-fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                <!-- Premium Badge -->
                                @if($content->premium)
                                <div class="absolute top-4 right-4 z-10">
                                    <span class="px-3 py-1.5 bg-gradient-to-r from-beninYellow-500 to-beninRed-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                                        <i class="fas fa-crown mr-1.5"></i>
                                        PREMIUM
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Image/Media -->
                                <div class="relative h-48 overflow-hidden">
                                    @if($content->medias->first())
                                    <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                                         alt="{{ $content->titre }}"
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                         loading="lazy">
                                    @else
                                    <div class="w-full h-full bg-gradient-to-br from-benin-500/20 to-beninYellow-500/20 dark:from-benin-900/30 dark:to-beninYellow-900/30 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="{{ getTypeIcon($content->type->nom_contenu ?? 'Article') }} text-5xl text-benin-500 dark:text-benin-400"></i>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Type Badge -->
                                    <div class="absolute bottom-4 left-4">
                                        <span class="px-3 py-1.5 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full text-xs font-semibold text-gray-800 dark:text-gray-200 flex items-center shadow-sm">
                                            <i class="{{ getTypeIcon($content->type->nom_contenu ?? 'Article') }} mr-1.5 text-benin-500"></i>
                                            {{ $content->type->nom_contenu ?? 'Article' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Overlay Gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                
                                <!-- Content -->
                                <div class="p-6">
                                    <!-- Date & Stats -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <span class="flex items-center">
                                            <i class="far fa-calendar-alt mr-1.5"></i>
                                            {{ $content->date_creation->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="far fa-eye mr-1.5"></i>
                                            {{ $content->nombre_vues ?? 0 }}
                                        </span>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 class="text-lg font-cinzel font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-benin-600 dark:group-hover:text-benin-400 transition-colors">
                                        <a href="{{ route('contenus.show', $content) }}" class="hover:no-underline">
                                            {{ $content->titre }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Description -->
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-2">
                                        {{ Str::limit(strip_tags($content->texte), 100) }}
                                    </p>
                                    
                                    <!-- Tags -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if($content->region)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-benin-50 dark:bg-benin-900/30 text-benin-700 dark:text-benin-300 text-xs">
                                            <i class="fas fa-map-marker-alt mr-1.5"></i>
                                            {{ $content->region->nom_region }}
                                        </span>
                                        @endif
                                        
                                        @if($content->langue)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs">
                                            <i class="fas fa-language mr-1.5"></i>
                                            {{ $content->langue->nom_langue }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Author & Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <!-- Author -->
                                        <div class="flex items-center">
                                            @if($content->auteur->photo)
                                            <img src="{{ Storage::url($content->auteur->photo) }}" 
                                                 alt="{{ $content->auteur->prenom }}"
                                                 class="w-9 h-9 rounded-full border-2 border-benin-500 mr-3">
                                            @else
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                                {{ strtoupper(substr($content->auteur->prenom, 0, 1)) }}
                                            </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $content->auteur->prenom }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Auteur</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-3">
                                            @auth
                                            <button onclick="toggleFavorite({{ $content->id_contenu }}, this)" 
                                                    class="p-2 {{ $content->is_favorite ? 'text-beninRed-500' : 'text-gray-400 hover:text-beninRed-500' }} transition-colors duration-300 transform hover:scale-110"
                                                    data-tooltip="{{ $content->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                                <i class="{{ $content->is_favorite ? 'fas' : 'far' }} fa-heart text-lg"></i>
                                            </button>
                                            @else
                                            <a href="{{ route('login') }}?redirect={{ urlencode(route('contenus.index')) }}" 
                                               class="p-2 text-gray-400 hover:text-beninRed-500 transition-colors duration-300 transform hover:scale-110"
                                               data-tooltip="Se connecter pour ajouter aux favoris">
                                                <i class="far fa-heart text-lg"></i>
                                            </a>
                                            @endauth
                                            
                                            @if($content->premium)
                                                @auth
                                                    <!-- Vérifier si l'utilisateur a déjà acheté -->
                                                    @php
                                                        $hasPurchased = \App\Models\Paiement::where('id_contenu', $content->id_contenu)
                                                            ->where('id_lecteur', auth()->id())
                                                            ->where('statut', 'success')
                                                            ->exists();
                                                    @endphp
                                                    
                                                    @if($hasPurchased)
                                                        <a href="{{ route('contenus.show', $content) }}" 
                                                           class="p-2 text-gray-400 hover:text-benin-500 transition-colors duration-300 transform hover:scale-110"
                                                           data-tooltip="Lire le contenu">
                                                            <i class="fas fa-arrow-right text-lg"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('paiement.page', $content->id_contenu) }}" 
                                                           class="p-2 text-gray-400 hover:text-beninYellow-500 transition-colors duration-300 transform hover:scale-110"
                                                           data-tooltip="Acheter ce contenu">
                                                            <i class="fas fa-shopping-cart text-lg"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <!-- Utilisateur non connecté - rediriger vers login -->
                                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('contenus.show', $content)) }}" 
                                                       class="p-2 text-gray-400 hover:text-beninYellow-500 transition-colors duration-300 transform hover:scale-110"
                                                       data-tooltip="Se connecter pour acheter">
                                                        <i class="fas fa-lock text-lg"></i>
                                                    </a>
                                                @endauth
                                            @else
                                                <a href="{{ route('contenus.show', $content) }}" 
                                                   class="p-2 text-gray-400 hover:text-benin-500 transition-colors duration-300 transform hover:scale-110"
                                                   data-tooltip="Lire le contenu">
                                                    <i class="fas fa-arrow-right text-lg"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hover Effect -->
                                <div class="absolute inset-0 border-2 border-transparent group-hover:border-benin-500 dark:group-hover:border-benin-400 rounded-2xl transition-all duration-300 pointer-events-none"></div>
                                
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 pointer-events-none"></div>
                            </article>
                            @endforeach
                        </div>
                        
                        <!-- List View (Hidden by default) -->
                        <div id="list-view-content" class="hidden space-y-4">
                            @foreach($contenus as $content)
                            <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover-lift border border-gray-200 dark:border-gray-700 p-6 animate-fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                <div class="flex">
                                    <!-- Media -->
                                    <div class="w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 mr-6">
                                        @if($content->medias->first())
                                        <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                                             alt="{{ $content->titre }}"
                                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        @else
                                        <div class="w-full h-full bg-gradient-to-br from-benin-500/20 to-beninYellow-500/20 flex items-center justify-center">
                                            <i class="{{ getTypeIcon($content->type->nom_contenu ?? 'Article') }} text-3xl text-benin-500"></i>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-grow">
                                        <!-- Header -->
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <!-- Type & Premium -->
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="px-3 py-1 bg-benin-100 dark:bg-benin-900/30 text-benin-700 dark:text-benin-300 rounded-full text-xs font-semibold">
                                                        {{ $content->type->nom_contenu ?? 'Article' }}
                                                    </span>
                                                    @if($content->premium)
                                                    <span class="px-2 py-1 bg-gradient-to-r from-beninYellow-500 to-beninRed-500 text-white rounded-full text-xs font-bold flex items-center">
                                                        <i class="fas fa-crown text-xs mr-1"></i>
                                                        PREMIUM
                                                    </span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Title -->
                                                <h3 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white group-hover:text-benin-600 dark:group-hover:text-benin-400 transition-colors">
                                                    <a href="{{ route('contenus.show', $content) }}">
                                                        {{ $content->titre }}
                                                    </a>
                                                </h3>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center space-x-2">
                                                @auth
                                                <button onclick="toggleFavorite({{ $content->id_contenu }}, this)" 
                                                        class="p-2 {{ $content->is_favorite ? 'text-beninRed-500' : 'text-gray-400 hover:text-beninRed-500' }} transition-colors"
                                                        data-tooltip="{{ $content->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                                    <i class="{{ $content->is_favorite ? 'fas' : 'far' }} fa-heart"></i>
                                                </button>
                                                @else
                                                <a href="{{ route('login') }}?redirect={{ urlencode(route('contenus.index')) }}" 
                                                   class="p-2 text-gray-400 hover:text-beninRed-500 transition-colors"
                                                   data-tooltip="Se connecter pour ajouter aux favoris">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                                @endauth
                                            </div>
                                        </div>
                                        
                                        <!-- Description -->
                                        <p class="text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">
                                            {{ Str::limit(strip_tags($content->texte), 150) }}
                                        </p>
                                        
                                        <!-- Footer -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center">
                                                    <i class="far fa-calendar-alt mr-1.5"></i>
                                                    {{ $content->date_creation->format('d M Y') }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="far fa-eye mr-1.5"></i>
                                                    {{ $content->number_vues ?? 0 }} vues
                                                </span>
                                                @if($content->region)
                                                <span class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-1.5"></i>
                                                    {{ $content->region->nom_region }}
                                                </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Author -->
                                            <div class="flex items-center">
                                                @if($content->auteur->photo)
                                                <img src="{{ Storage::url($content->auteur->photo) }}" 
                                                     alt="{{ $content->auteur->prenom }}"
                                                     class="w-8 h-8 rounded-full border-2 border-benin-500 mr-2">
                                                @else
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center text-white font-semibold text-xs mr-2">
                                                    {{ strtoupper(substr($content->auteur->prenom, 0, 1)) }}
                                                </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $content->auteur->prenom }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-20">
                        <div class="w-48 h-48 mx-auto mb-8 relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-benin-500/10 to-beninYellow-500/10 rounded-full blur-2xl"></div>
                            <div class="relative w-full h-full flex items-center justify-center">
                                <i class="fas fa-search text-8xl text-gray-300 dark:text-gray-600"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                            Aucun contenu trouvé
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-8 max-w-md mx-auto">
                            @if(request()->hasAny(['search', 'type', 'region', 'langue', 'premium']))
                            Essayez de modifier vos critères de recherche ou explorez différentes catégories
                            @else
                            Aucun contenu n'est disponible pour le moment. Revenez plus tard !
                            @endif
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @if(request()->hasAny(['search', 'type', 'region', 'langue', 'premium']))
                            <button onclick="resetFilters()" 
                                    class="px-8 py-3 bg-gradient-to-r from-benin-500 to-benin-600 text-white rounded-xl font-semibold hover:from-benin-600 hover:to-benin-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-redo mr-2"></i>
                                Réinitialiser les filtres
                            </button>
                            @endif
                            <a href="{{ route('contenus.index') }}" 
                               class="px-8 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-home mr-2"></i>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Pagination -->
                @if($contenus->hasPages())
                <div class="mt-12">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        {{ $contenus->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Featured Categories -->
@if(!isset($currentType))
<section class="py-16 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
                Explorer par <span class="gradient-text-benin">Catégorie</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Découvrez notre collection organisée par type de contenu
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($types as $type)
            <a href="{{ route('contenus.by-type', $type->id_type_contenu) }}" 
               class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 text-center hover-lift transition-all duration-300">
                <!-- Background Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-benin-500/5 to-beninYellow-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Icon -->
                <div class="relative w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-benin-500 to-benin-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="{{ getTypeIcon($type->nom_contenu) }} text-white text-2xl"></i>
                </div>
                
                <!-- Content -->
                <h3 class="text-lg font-cinzel font-bold text-gray-900 dark:text-white mb-2 group-hover:text-benin-600 dark:group-hover:text-benin-400 transition-colors">
                    {{ $type->nom_contenu }}s
                </h3>
                
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $type->contenus_count ?? 0 }} contenus
                </p>
                
                <!-- Arrow -->
                <div class="mt-4 text-benin-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fas fa-arrow-right text-lg"></i>
                </div>
                
                <!-- Border Effect -->
                <div class="absolute inset-0 border-2 border-transparent group-hover:border-benin-500 dark:group-hover:border-benin-400 rounded-2xl transition-all duration-300 pointer-events-none"></div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="py-16 bg-gradient-to-r from-benin-900 to-beninRed-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 md:p-12 border border-white/20">
            <div class="text-center">
                <h2 class="text-3xl font-cinzel font-bold text-white mb-4">
                    Restez informé des nouveautés
                </h2>
                <p class="text-gray-200 mb-8 max-w-2xl mx-auto">
                    Recevez les derniers contenus culturels directement dans votre boîte mail
                </p>
                
                <form class="max-w-md mx-auto space-y-4">
                    <div class="relative">
                        <input type="email" 
                               placeholder="Votre adresse email" 
                               class="w-full px-6 py-4 bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl focus:outline-none focus:border-beninYellow-500 transition-all duration-300 backdrop-blur-sm">
                        <button type="submit" 
                                class="absolute right-2 top-2 px-6 py-2 bg-gradient-to-r from-beninYellow-500 to-beninRed-500 text-white rounded-lg font-semibold hover:from-beninYellow-600 hover:to-beninRed-600 transition-all duration-300 transform hover:scale-105">
                            S'abonner
                        </button>
                    </div>
                    <p class="text-gray-300 text-sm">
                        En vous abonnant, vous acceptez notre politique de confidentialité
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Animation for filter panel */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-down {
        animation: slideDown 0.3s ease-out;
    }
    
    /* Custom checkbox/radio styling */
    input[type="radio"]:checked + div {
        border-color: #22c55e;
        background-color: #22c55e;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Custom hover effects */
    .hover-lift {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                    box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #22c55e, #facc15);
        border-radius: 4px;
    }
    
    .dark ::-webkit-scrollbar-track {
        background: #374151;
    }
    
    .dark ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #16a34a, #eab308);
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, 
            #f0f0f0 25%, 
            #e0e0e0 50%, 
            #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    /* Floating animation */
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Pulse animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    // Helper function to get type icon
    function getTypeIcon(typeName) {
        const icons = {
            'Article': 'fas fa-feather-alt',
            'Vidéo': 'fas fa-video',
            'Audio': 'fas fa-music',
            'Image': 'fas fa-images',
            'Document': 'fas fa-file-alt'
        };
        return icons[typeName] || 'fas fa-file-alt';
    }
    
    // Apply filters function
    function applyFilters() {
        const search = document.getElementById('search')?.value || '';
        const type = document.querySelector('input[name="type"]:checked')?.value || '';
        const region = document.getElementById('region')?.value || '';
        const premium = document.querySelector('input[name="premium"]:checked')?.value || '';
        const sort = document.getElementById('sort')?.value || 'date_creation';
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (search) params.set('search', search);
        else params.delete('search');
        
        if (type) params.set('type', type);
        else params.delete('type');
        
        if (region) params.set('region', region);
        else params.delete('region');
        
        if (premium !== '') params.set('premium', premium);
        else params.delete('premium');
        
        if (sort) params.set('sort', sort);
        else params.delete('sort');
        
        params.delete('page'); // Reset to first page
        
        // Show loading state
        showLoading();
        
        // Navigate to new URL
        window.location.href = url.pathname + '?' + params.toString();
    }
    
    // Reset filters function
    function resetFilters() {
        // Show loading state
        showLoading();
        
        // Reset form elements
        document.getElementById('search').value = '';
        document.querySelectorAll('input[name="type"]').forEach(input => input.checked = false);
        document.getElementById('region').value = '';
        document.querySelectorAll('input[name="premium"]').forEach(input => input.checked = false);
        document.getElementById('sort').value = 'date_creation';
        
        // Navigate to base URL
        window.location.href = "{{ route('contenus.index') }}";
    }
    
    // Show loading state
    function showLoading() {
        const container = document.getElementById('content-container');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-20">
                    <div class="w-16 h-16 mx-auto mb-4">
                        <div class="loader-benin"></div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">Chargement des contenus...</p>
                </div>
            `;
        }
    }
    
    // Toggle view mode
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile filter toggle
        const filterToggle = document.getElementById('filter-toggle');
        const filtersPanel = document.getElementById('filters-panel');
        
        if (filterToggle && filtersPanel) {
            filterToggle.addEventListener('click', function() {
                filtersPanel.classList.toggle('hidden');
                const icon = this.querySelector('i');
                if (filtersPanel.classList.contains('hidden')) {
                    icon.className = 'fas fa-sliders-h text-xl';
                } else {
                    icon.className = 'fas fa-times text-xl';
                }
            });
        }
        
        // View mode toggle
        const gridViewBtn = document.getElementById('grid-view');
        const listViewBtn = document.getElementById('list-view');
        const gridViewContent = document.getElementById('grid-view-content');
        const listViewContent = document.getElementById('list-view-content');
        
        if (gridViewBtn && listViewBtn && gridViewContent && listViewContent) {
            // Set initial state
            gridViewContent.classList.remove('hidden');
            listViewContent.classList.add('hidden');
            
            // Grid view click
            gridViewBtn.addEventListener('click', function() {
                gridViewContent.classList.remove('hidden');
                listViewContent.classList.add('hidden');
                
                // Update button styles
                this.className = 'p-3 rounded-xl bg-gradient-to-r from-benin-500 to-benin-600 text-white shadow-lg transform hover:scale-105 transition-all duration-300';
                listViewBtn.className = 'p-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-105';
            });
            
            // List view click
            listViewBtn.addEventListener('click', function() {
                gridViewContent.classList.add('hidden');
                listViewContent.classList.remove('hidden');
                
                // Update button styles
                this.className = 'p-3 rounded-xl bg-gradient-to-r from-benin-500 to-benin-600 text-white shadow-lg transform hover:scale-105 transition-all duration-300';
                gridViewBtn.className = 'p-3 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-105';
            });
        }
        
        // Real-time search with debounce
        const searchInput = document.getElementById('search');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });
        }
        
        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);
        
        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in').forEach(el => {
            observer.observe(el);
        });
        
        // Initialize tooltips
        initTooltips();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    
    // Initialize tooltips
    function initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'fixed z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg opacity-0 transition-opacity duration-200';
                tooltip.textContent = this.getAttribute('data-tooltip');
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
                tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
                
                setTimeout(() => tooltip.classList.add('opacity-100'), 10);
                
                this.addEventListener('mouseleave', () => {
                    tooltip.classList.remove('opacity-100');
                    setTimeout(() => tooltip.remove(), 200);
                });
            });
        });
    }
    
    // Toggle favorite function
    async function toggleFavorite(contenuId, button) {
        // Vérifier si l'utilisateur est connecté
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        
        if (!isLoggedIn) {
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
            return;
        }
        
        try {
            const response = await fetch(`/api/favoris/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id_contenu: contenuId })
            });
            
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
                    return;
                }
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour le bouton spécifique
                const icon = button.querySelector('i');
                const tooltip = button.getAttribute('data-tooltip');
                
                if (data.is_favorite) {
                    // Changer en cœur plein
                    icon.className = 'fas fa-heart text-beninRed-500 text-lg animate-pulse';
                    button.className = button.className.replace('text-gray-400 hover:text-beninRed-500', 'text-beninRed-500');
                    button.setAttribute('data-tooltip', 'Retirer des favoris');
                    
                    // Mettre à jour tous les boutons pour ce contenu (vue grille et liste)
                    document.querySelectorAll(`[onclick*="toggleFavorite(${contenuId}"]`).forEach(btn => {
                        const btnIcon = btn.querySelector('i');
                        btnIcon.className = 'fas fa-heart text-beninRed-500 text-lg';
                        btn.className = btn.className.replace('text-gray-400 hover:text-beninRed-500', 'text-beninRed-500');
                        btn.setAttribute('data-tooltip', 'Retirer des favoris');
                    });
                    
                    setTimeout(() => icon.classList.remove('animate-pulse'), 500);
                    
                    // Ajouter des confettis
                    createConfetti();
                } else {
                    // Changer en cœur vide
                    icon.className = 'far fa-heart text-lg text-gray-400';
                    button.className = button.className.replace('text-beninRed-500', 'text-gray-400 hover:text-beninRed-500');
                    button.setAttribute('data-tooltip', 'Ajouter aux favoris');
                    
                    // Mettre à jour tous les boutons pour ce contenu
                    document.querySelectorAll(`[onclick*="toggleFavorite(${contenuId}"]`).forEach(btn => {
                        const btnIcon = btn.querySelector('i');
                        btnIcon.className = 'far fa-heart text-lg text-gray-400';
                        btn.className = btn.className.replace('text-beninRed-500', 'text-gray-400 hover:text-beninRed-500');
                        btn.setAttribute('data-tooltip', 'Ajouter aux favoris');
                    });
                }
                
                // Mettre à jour le tooltip si nécessaire
                if (tooltip !== button.getAttribute('data-tooltip')) {
                    button.setAttribute('data-tooltip', button.getAttribute('data-tooltip'));
                }
                
                // Afficher une notification
                showNotification(
                    data.message || (data.is_favorite ? 'Ajouté aux favoris ✓' : 'Retiré des favoris'),
                    'success'
                );
            } else {
                showNotification(data.message || 'Une erreur est survenue', 'error');
            }
            
        } catch (error) {
            console.error('Error:', error);
            showNotification('Une erreur est survenue', 'error');
        }
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl transform translate-x-full transition-transform duration-500 backdrop-blur-md`;
        
        const colors = {
            success: 'bg-gradient-to-r from-benin-500 to-benin-600',
            error: 'bg-gradient-to-r from-beninRed-500 to-beninRed-600',
            info: 'bg-gradient-to-r from-blue-500 to-blue-600'
        };
        
        notification.classList.add(colors[type]);
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} text-white text-xl mr-4"></i>
                <div class="text-white font-medium">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-6 text-white/60 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
            notification.classList.add('translate-x-0');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }
    
    // Create confetti effect
    function createConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'fixed inset-0 pointer-events-none z-40';
        document.body.appendChild(confettiContainer);
        
        const colors = ['#22c55e', '#facc15', '#ef4444', '#3b82f6'];
        const confettiCount = 30;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'absolute w-2 h-2 rounded-full';
            confetti.style.left = `${Math.random() * 100}vw`;
            confetti.style.top = '-10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confettiContainer.appendChild(confetti);
            
            // Animation
            const animation = confetti.animate([
                { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                { transform: `translateY(${window.innerHeight}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
            ], {
                duration: 1500 + Math.random() * 1000,
                easing: 'cubic-bezier(0.215, 0.61, 0.355, 1)'
            });
            
            animation.onfinish = () => confetti.remove();
        }
        
        setTimeout(() => confettiContainer.remove(), 3000);
    }
</script>
@endpush