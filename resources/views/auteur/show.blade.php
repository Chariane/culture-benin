@extends('layouts.app')

@section('title', $auteur->prenom . ' ' . $auteur->nom . ' - CultureBénin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- En-tête du profil -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden mb-12">
        <div class="relative">
            <!-- Bannière -->
            <div class="h-48 bg-gradient-to-r from-benin-500 to-benin-700"></div>
            
            <!-- Photo de profil et informations -->
            <div class="relative px-8 pb-8">
                <div class="flex flex-col md:flex-row items-center md:items-end -mt-20 mb-6">
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
                    
                    <img src="{{ $photoUrl }}" 
                         alt="{{ $auteur->nom }}"
                         class="w-40 h-40 rounded-full border-6 border-white dark:border-gray-800 
                                object-cover shadow-lg">
                    
                    <!-- Informations -->
                    <div class="md:ml-8 mt-4 md:mt-0 text-center md:text-left">
                        <h1 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white">
                            {{ $auteur->prenom }} {{ $auteur->nom }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-2">
                            @if($auteur->langue)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-language mr-2"></i>
                                    {{ $auteur->langue->nom_langue }}
                                </div>
                            @endif
                            
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Membre depuis {{ $auteur->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="text-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50">
                        <div class="text-3xl font-bold text-benin-600 dark:text-benin-400">
                            {{ $auteur->contenus->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Contenus publiés
                        </div>
                    </div>
                    
                    <div class="text-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50">
                        <div class="text-3xl font-bold text-beninYellow-600 dark:text-beninYellow-400">
                            {{ $auteur->contenus->where('type_contenu_id', 1)->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Articles
                        </div>
                    </div>
                    
                    <div class="text-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50">
                        <div class="text-3xl font-bold text-beninRed-600 dark:text-beninRed-400">
                            {{ $auteur->contenus->where('type_contenu_id', 2)->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Photos
                        </div>
                    </div>
                    
                    <div class="text-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $auteur->contenus->where('type_contenu_id', 3)->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Vidéos
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenus de l'auteur -->
    <div>
        <h2 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white mb-6">
            Ses publications
        </h2>
        
        @if($contenus->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($contenus as $contenu)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg 
                               hover:shadow-xl transition-shadow duration-300 overflow-hidden
                               border border-gray-200 dark:border-gray-700">
                        <!-- Image du contenu -->
                        <div class="h-48 overflow-hidden">
                            @if($contenu->type_contenu_id == 2) <!-- Photo -->
                                <img src="{{ asset('storage/' . $contenu->chemin) }}" 
                                     alt="{{ $contenu->titre }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @elseif($contenu->type_contenu_id == 3) <!-- Vidéo -->
                                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                    <i class="fas fa-play-circle text-white text-5xl"></i>
                                </div>
                            @else <!-- Article -->
                                <div class="w-full h-full bg-gradient-to-r from-benin-500 to-benin-600 
                                           flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white text-5xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Informations du contenu -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                             {{ $contenu->type_contenu_id == 1 ? 'bg-benin-100 text-benin-800 dark:bg-benin-900/30 dark:text-benin-400' : 
                                                ($contenu->type_contenu_id == 2 ? 'bg-beninYellow-100 text-beninYellow-800 dark:bg-beninYellow-900/30 dark:text-beninYellow-400' : 
                                                'bg-beninRed-100 text-beninRed-800 dark:bg-beninRed-900/30 dark:text-beninRed-400') }}">
                                    {{ $contenu->typeContenu->nom_contenu }}
                                </span>
                                
                                @if($contenu->region)
                                    <span class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $contenu->region->nom_region }}
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ $contenu->titre }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($contenu->description), 120) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $contenu->created_at->format('d/m/Y') }}
                                </span>
                                
                                <a href="{{ route('contenus.show', $contenu) }}" 
                                   class="text-benin-600 hover:text-benin-700 dark:text-benin-400 
                                          dark:hover:text-benin-300 font-medium text-sm">
                                    Lire la suite
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $contenus->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 text-gray-400">
                    <i class="fas fa-box-open text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Aucune publication
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Cet auteur n'a pas encore publié de contenu.
                </p>
            </div>
        @endif
    </div>
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
@endsection