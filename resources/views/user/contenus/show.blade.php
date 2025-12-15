@extends('layouts.app')

@section('title', $contenu->titre . ' - CultureBénin')

@section('styles')
<style>
    .content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
    .content-body p {
        margin-bottom: 1rem;
        line-height: 1.8;
    }
    .content-body h2 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1f2937;
    }
    .content-body h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #374151;
    }
    .content-body ul, .content-body ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .content-body li {
        margin-bottom: 0.5rem;
    }
    .content-body blockquote {
        border-left: 4px solid #facc15;
        padding-left: 1rem;
        font-style: italic;
        color: #6b7280;
        margin: 1.5rem 0;
    }
    .premium-blur {
        filter: blur(5px);
        pointer-events: none;
        user-select: none;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Fil d'Ariane -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-700 hover:text-benin-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Accueil
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('contenus.index') }}" class="ml-1 text-sm text-gray-700 hover:text-benin-600 md:ml-2">Contenus</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm text-gray-500 md:ml-2 truncate max-w-xs">{{ Str::limit($contenu->titre, 50) }}</span>
                </div>
            </li>
        </ol>
    </nav>

<!-- Messages d'alerte -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-xl">
        <i class="fas fa-info-circle mr-2"></i>
        {{ session('info') }}
    </div>
    @endif

    <!-- Contenu Principal -->
    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <!-- En-tête -->
            <div class="mb-8">
                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-benin-100 text-benin-800 text-sm font-medium rounded-full">
                        {{ $contenu->type->nom_contenu ?? 'Article' }}
                    </span>
                    @if($contenu->premium)
                    <span class="px-3 py-1 bg-gradient-to-r from-beninYellow-500 to-beninRed-500 text-white text-sm font-medium rounded-full">
                        <i class="fas fa-crown mr-1"></i> Premium
                    </span>
                    @endif
                    @if($contenu->region)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $contenu->region->nom_region }}
                    </span>
                    @endif
                </div>
                
                <!-- Titre -->
                <h1 class="text-3xl md:text-4xl font-cinzel font-bold text-gray-900 mb-4">{{ $contenu->titre }}</h1>
                
                <!-- Métadonnées -->
                <div class="flex flex-wrap items-center gap-4 text-gray-600 mb-6">
                    <!-- Auteur avec bouton s'abonner -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            @if($contenu->auteur->photo)
                            <img src="{{ Storage::url($contenu->auteur->photo) }}" 
                                alt="{{ $contenu->auteur->prenom }}"
                                class="w-10 h-10 rounded-full mr-3 border-2 border-benin-500">
                            @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center mr-3">
                                <span class="text-white text-lg font-semibold">{{ substr($contenu->auteur->prenom, 0, 1) }}</span>
                            </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}</div>
                                <div class="text-sm text-gray-500">Auteur</div>
                            </div>
                        </div>
                        
                        <!-- Bouton S'abonner -->
                        @auth
                            @if(auth()->id() != $contenu->auteur->id_utilisateur)
                                <button onclick="toggleSubscribe({{ $contenu->auteur->id_utilisateur }})" 
                                        id="subscribe-btn-header"
                                        class="flex items-center space-x-2 px-4 py-2 {{ $isSubscribed ? 'bg-gray-100 text-gray-700 border border-gray-300' : 'bg-benin-600 text-white' }} rounded-lg hover:opacity-90 transition-opacity duration-300">
                                    <i class="fas {{ $isSubscribed ? 'fa-check' : 'fa-user-plus' }} mr-2"></i>
                                    <span>{{ $isSubscribed ? 'Abonné' : "S'abonner" }}</span>
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" 
                            class="flex items-center space-x-2 px-4 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700 transition-colors">
                                <i class="fas fa-user-plus mr-2"></i>
                                <span>S'abonner</span>
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Date et vues -->
                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- Date -->
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                            {{ $contenu->date_creation->format('d F Y') }}
                        </div>
                        
                        <!-- Vues -->
                        <div class="flex items-center">
                            <i class="far fa-eye mr-2 text-gray-400"></i>
                            {{ $contenu->nombre_vues ?? 0 }} vues
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Principale -->
            @if($contenu->medias->where('typeMedia.nom_media', 'image')->first())
            <div class="mb-8 rounded-2xl overflow-hidden">
                <img src="{{ Storage::url($contenu->medias->where('typeMedia.nom_media', 'image')->first()->chemin) }}" 
                     alt="{{ $contenu->titre }}"
                     class="w-full h-auto max-h-[500px] object-cover">
                @if($contenu->medias->where('typeMedia.nom_media', 'image')->first()->description)
                <p class="text-center text-gray-500 text-sm mt-2 italic">
                    {{ $contenu->medias->where('typeMedia.nom_media', 'image')->first()->description }}
                </p>
                @endif
            </div>
            @endif

            <!-- Vérification d'accès -->
            @php
                $hasAccess = false;
                $hasPurchased = false;
                
                if (!$contenu->premium) {
                    $hasAccess = true;
                } elseif (auth()->check()) {
                    $purchase = \App\Models\Paiement::where('id_contenu', $contenu->id_contenu)
                        ->where('id_lecteur', auth()->id())
                        ->where('statut', 'success')
                        ->first();
                    
                    $hasPurchased = $purchase ? true : false;
                    $hasAccess = $hasPurchased;
                }
                
                // Vérifier les likes et favoris
                $hasLiked = false;
                $hasFavorited = false;
                $likesCount = $contenu->likes()->count();
                
                if (auth()->check()) {
                    $hasLiked = $contenu->likes()->where('id_utilisateur', auth()->id())->exists();
                    $hasFavorited = \App\Models\Favori::where('id_utilisateur', auth()->id())
                        ->where('id_contenu', $contenu->id_contenu)
                        ->exists();
                }
            @endphp

            <!-- Contenu Premium - Bloc d'achat -->
            @if($contenu->premium && !$hasAccess)
                <!-- Bloc d'achat pour les contenus premium non achetés -->
                <div class="bg-gradient-to-r from-benin-50 to-beninYellow-50 border border-benin-200 rounded-2xl p-8 mb-8 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-r from-beninYellow-500 to-beninRed-500 flex items-center justify-center">
                        <i class="fas fa-crown text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Contenu Premium</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Ce contenu est réservé aux membres premium. Accédez à l'intégralité du contenu en l'achetant.
                    </p>
                    <div class="bg-white rounded-xl p-6 max-w-md mx-auto mb-6 shadow-lg">
                        <div class="text-4xl font-bold text-benin-600 mb-2">{{ number_format($contenu->prix, 0, ',', ' ') }} XOF</div>
                        <div class="text-gray-600 mb-6">Accès permanent</div>
                        @auth
                        <a href="{{ route('paiement.page', $contenu->id_contenu) }}" 
                           class="w-full bg-gradient-to-r from-benin-500 to-benin-600 text-white py-3 px-6 rounded-lg hover:from-benin-600 hover:to-benin-700 transition-colors duration-200 font-semibold text-lg inline-flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i> Acheter maintenant
                        </a>
                        @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" 
                               class="block w-full bg-primary-600 text-white py-3 px-6 rounded-lg hover:bg-primary-700 transition-colors duration-200 font-semibold text-lg text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Se connecter pour acheter
                            </a>
                            <p class="text-sm text-gray-500">
                                Vous n'avez pas de compte ? 
                                <a href="{{ route('register') }}" class="text-benin-600 hover:text-benin-700 font-medium">S'inscrire gratuitement</a>
                            </p>
                        </div>
                        @endauth
                    </div>
                </div>
                
                <!-- Aperçu limité -->
                <div class="content-body mb-8 relative">
                    <div class="premium-blur">
                        {!! Str::limit($contenu->texte, 500) !!}
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-white via-white/50 to-transparent flex items-center justify-center">
                        <div class="text-center bg-white/80 backdrop-blur-sm p-6 rounded-xl shadow-lg">
                            <i class="fas fa-lock text-4xl text-beninYellow-500 mb-4"></i>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Contenu Bloqué</h4>
                            <p class="text-gray-600 mb-4">Achetez ce contenu pour débloquer l'intégralité</p>
                            <a href="{{ route('paiement.page', $contenu->id_contenu) }}" 
                               class="bg-gradient-to-r from-benin-500 to-benin-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-benin-600 hover:to-benin-700 transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-unlock-alt mr-2"></i> Débloquer pour {{ number_format($contenu->prix, 0, ',', ' ') }} XOF
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Contenu complet (gratuit ou premium acheté) -->
                <div class="content-body mb-8">
                    {!! $contenu->texte !!}
                </div>
            @endif

            <!-- Galerie de médias -->
            @if($contenu->medias->count() > 1)
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Galerie</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($contenu->medias->skip(1) as $media)
                    <div class="rounded-lg overflow-hidden border border-gray-200 hover:border-benin-500 transition-colors duration-300">
                        <img src="{{ Storage::url($media->chemin) }}" 
                             alt="{{ $media->description ?? 'Média' }}"
                             class="w-full h-40 object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                             onclick="openMediaModal('{{ Storage::url($media->chemin) }}', '{{ $media->description ?? '' }}')">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8 p-6 bg-gray-50 rounded-2xl">
                <!-- Boutons d'interaction -->
                <div class="flex items-center space-x-4">
                    <!-- Like -->
                    @auth
                    <button onclick="toggleLike({{ $contenu->id_contenu }})" 
                            class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i id="like-icon-{{ $contenu->id_contenu }}" 
                           class="{{ $hasLiked ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-400' }}"></i>
                        <span id="like-count-{{ $contenu->id_contenu }}">{{ $likesCount }}</span>
                    </button>
                    @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" 
                       class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="far fa-heart text-gray-400"></i>
                        <span>{{ $likesCount }}</span>
                    </a>
                    @endauth
                    
                    <!-- Favori -->
                    @auth
                    <button onclick="toggleFavorite({{ $contenu->id_contenu }})" 
                            class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i id="favorite-icon-{{ $contenu->id_contenu }}" 
                           class="{{ $hasFavorited ? 'fas fa-bookmark text-yellow-500' : 'far fa-bookmark text-gray-400' }}"></i>
                        <span>Favori</span>
                    </button>
                    @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" 
                       class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="far fa-bookmark text-gray-400"></i>
                        <span>Favori</span>
                    </a>
                    @endauth
                    
                    <!-- Partager -->
                    <button onclick="shareContent()" 
                            class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-share-alt text-gray-400"></i>
                        <span>Partager</span>
                    </button>
                </div>
                
                <!-- Acheter (si premium et pas encore acheté) -->
                @if($contenu->premium)
                    @auth
                        @if($hasPurchased)
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">
                                <i class="fas fa-check-circle mr-2"></i> Déjà acheté
                            </span>
                        @else
                            <a href="{{ route('paiement.page', $contenu->id_contenu) }}" 
                               class="px-6 py-2 bg-gradient-to-r from-beninYellow-500 to-beninRed-500 text-white rounded-lg hover:from-beninYellow-600 hover:to-beninRed-600 transition-colors duration-200 font-semibold">
                                <i class="fas fa-shopping-cart mr-2"></i> Acheter {{ number_format($contenu->prix, 0, ',', ' ') }} XOF
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('contenus.show', $contenu)) }}" 
                           class="px-6 py-2 bg-gradient-to-r from-benin-500 to-benin-600 text-white rounded-lg hover:from-benin-600 hover:to-benin-700 transition-colors duration-200 font-semibold">
                            <i class="fas fa-lock mr-2"></i> Se connecter pour acheter
                        </a>
                    @endauth
                @endif
            </div>

            <!-- Commentaires -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Commentaires (<span id="comment-count">{{ $contenu->commentaires->count() }}</span>)</h3>
                
                <!-- Formulaire de commentaire -->
                @auth
                <div class="mb-8">
                    <form action="{{ route('commentaires.store') }}" method="POST" id="comment-form">
                        @csrf
                        <input type="hidden" name="id_contenu" value="{{ $contenu->id_contenu }}">
                        <div class="mb-4">
                            <textarea name="texte" 
                                     id="comment-text"
                                     rows="4" 
                                     placeholder="Partagez votre avis sur ce contenu..."
                                     class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent resize-none"></textarea>
                            <div id="comment-text-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Notation (optionnelle) -->
                                <div class="flex items-center">
                                    <span class="text-gray-700 mr-3">Note (optionnel) :</span>
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" 
                                                onclick="setRating({{ $i }})"
                                                class="text-2xl focus:outline-none hover:text-yellow-400">
                                            <span id="star-{{ $i }}" class="text-gray-300">★</span>
                                        </button>
                                        @endfor
                                        <button type="button" 
                                                onclick="clearRating()"
                                                class="text-sm text-gray-500 hover:text-gray-700 ml-3">
                                            <i class="fas fa-times mr-1"></i> Effacer
                                        </button>
                                    </div>
                                    <input type="hidden" name="note" id="note">
                                    <div id="note-error" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>
                                
                            </div>
                            <button type="submit" 
                                    id="submit-comment"
                                    class="px-6 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700 transition-colors duration-200 font-semibold">
                                <i class="fas fa-paper-plane mr-2"></i> Publier
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-6 text-center mb-8">
                    <i class="fas fa-comment text-3xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-4">Connectez-vous pour laisser un commentaire</p>
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" 
                       class="inline-flex items-center px-6 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </a>
                </div>
                @endauth
                
                <!-- Liste des commentaires -->
                <div class="space-y-6" id="comments-list">
                    @foreach($contenu->commentaires->sortByDesc('date') as $commentaire)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300 comment-item" id="comment-{{ $commentaire->id_commentaire }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                @if($commentaire->utilisateur->photo)
                                <img src="{{ Storage::url($commentaire->utilisateur->photo) }}" 
                                     alt="{{ $commentaire->utilisateur->prenom }}"
                                     class="w-10 h-10 rounded-full mr-3 border-2 border-benin-500">
                                @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center mr-3">
                                    <span class="text-white font-semibold">{{ substr($commentaire->utilisateur->prenom, 0, 1) }}</span>
                                </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $commentaire->utilisateur->prenom }} {{ $commentaire->utilisateur->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $commentaire->date->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($commentaire->note)
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-lg {{ $i <= $commentaire->note ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                @endif
                                
                                <!-- Actions du commentaire -->
                                @auth
                                @if($commentaire->id_utilisateur == Auth::id())
                                <div class="flex space-x-2">
                                    <button onclick="editComment({{ $commentaire->id_commentaire }})" 
                                            class="text-gray-400 hover:text-blue-500 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteComment({{ $commentaire->id_commentaire }})" 
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                                @endauth
                            </div>
                        </div>
                        <div class="text-gray-700 comment-text">
                            {{ $commentaire->texte }}
                        </div>
                        
                        <!-- Formulaire d'édition (caché par défaut) -->
                        <div class="edit-comment-form hidden mt-4">
                            <form class="space-y-4">
                                @csrf
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent resize-none" 
                                          rows="3"
                                          name="texte">{{ $commentaire->texte }}</textarea>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" 
                                            onclick="cancelEdit({{ $commentaire->id_commentaire }})"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                        Annuler
                                    </button>
                                    <button type="button" 
                                            onclick="updateComment({{ $commentaire->id_commentaire }})"
                                            class="px-4 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700">
                                        Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- À propos de l'auteur -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h3 class="text-lg font-cinzel font-semibold text-gray-900 mb-4">À propos de l'auteur</h3>
                <div class="flex items-center mb-4">
                    @if($contenu->auteur->photo)
                    <img src="{{ Storage::url($contenu->auteur->photo) }}" 
                        alt="{{ $contenu->auteur->prenom }}"
                        class="w-16 h-16 rounded-full mr-4 border-2 border-benin-500">
                    @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center mr-4">
                        <span class="text-white text-2xl font-semibold">{{ substr($contenu->auteur->prenom, 0, 1) }}</span>
                    </div>
                    @endif
                    <div>
                        <div class="font-bold text-gray-900">{{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}</div>
                        <div class="text-sm text-gray-600">{{ $contenu->auteur->role->nom_role ?? 'Auteur' }}</div>
                    </div>
                </div>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="text-center">
                        <div class="font-bold text-gray-900">{{ $contenu->auteur->contenus->count() }}</div>
                        <div class="text-sm text-gray-600">Contenus</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-gray-900">{{ $contenu->auteur->abonnes->count() }}</div>
                        <div class="text-sm text-gray-600">Abonnés</div>
                    </div>
                </div>
                @auth
                @if(auth()->id() != $contenu->auteur->id_utilisateur)
                <button onclick="toggleSubscribe({{ $contenu->auteur->id_utilisateur }})" 
                        id="subscribe-btn"
                        class="w-full py-2 px-4 {{ $isSubscribed ? 'bg-gray-200 text-gray-700' : 'bg-benin-600 text-white' }} rounded-lg font-medium hover:opacity-90 transition-opacity duration-300">
                    <i class="fas {{ $isSubscribed ? 'fa-check' : 'fa-user-plus' }} mr-2"></i>
                    {{ $isSubscribed ? 'Abonné' : "S'abonner" }}
                </button>
                @endif
                @else
                <a href="{{ route('login') }}" 
                class="block w-full text-center py-2 px-4 bg-benin-600 text-white rounded-lg font-medium hover:bg-benin-700 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i> S'abonner
                </a>
                @endauth
            </div>

            <!-- Contenus similaires -->
            @if($similarContents && $similarContents->count() > 0)
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h3 class="text-lg font-cinzel font-semibold text-gray-900 mb-4">Contenus similaires</h3>
                <div class="space-y-4">
                    @foreach($similarContents as $similar)
                    <a href="{{ route('contenus.show', $similar) }}" class="block group">
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-300">
                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                @if($similar->medias->first())
                                <img src="{{ Storage::url($similar->medias->first()->chemin) }}" 
                                     alt="{{ $similar->titre }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                <div class="w-full h-full bg-gradient-to-r from-benin-100 to-beninYellow-100 flex items-center justify-center">
                                    <i class="{{ getTypeIcon($similar->type->nom_contenu ?? 'Article') }} text-benin-500"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-gray-900 group-hover:text-benin-600 line-clamp-2">{{ $similar->titre }}</div>
                                <div class="text-sm text-gray-500">{{ $similar->date_creation->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour les médias -->
<div id="media-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden backdrop-blur-sm">
    <div class="relative max-w-4xl max-h-[90vh] mx-4">
        <button onclick="closeMediaModal()" 
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors">
            <i class="fas fa-times text-3xl"></i>
        </button>
        <img id="modal-image" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl" src="" alt="">
        <div id="modal-caption" class="text-white text-center mt-4 text-lg"></div>
    </div>
</div>

@push('scripts')
<script>
    // Fonction pour obtenir l'icône du type
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
    // Script de débogage
console.log('=== DEBUG INFOS ===');
console.log('Contenu ID:', {{ $contenu->id_contenu }});
console.log('User logged in:', {{ auth()->check() ? 'true' : 'false' }});
console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);
console.log('Current URL:', window.location.href);

// Testez la route API
fetch('/api/likes/check/{{ $contenu->id_contenu }}', {
    headers: {
        'Accept': 'application/json'
    }
})
.then(response => {
    console.log('Check like - Status:', response.status);
    return response.json();
})
.then(data => {
    console.log('Check like - Response:', data);
})
.catch(error => {
    console.error('Check like - Error:', error);
});
    // Gestion des likes
    function toggleLike(contenuId) {
        const icon = document.getElementById(`like-icon-${contenuId}`);
        // Animation de chargement
        const originalClass = icon.className;
        icon.className = 'fas fa-spinner fa-spin text-benin-500';

        fetch(`/api/likes/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_contenu: contenuId })
        })
        .then(async response => {
            if (!response.ok) {
                const status = response.status;
                if (status === 419) throw new Error("Erreur CSRF (Session expirée). Rafraîchissez la page.");
                if (status === 401) throw new Error("Vous n'êtes plus connecté.");
                if (status === 500) throw new Error("Erreur serveur interne.");
                throw new Error(`Erreur HTTP: ${status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Réponse serveur invalide (HTML reçu au lieu de JSON). Peut-être une redirection ?");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const count = document.getElementById(`like-count-${contenuId}`);
                
                if (data.liked) {
                    icon.className = 'fas fa-heart text-red-500';
                    icon.classList.add('animate-pulse');
                    setTimeout(() => icon.classList.remove('animate-pulse'), 500);
                    showNotification('Contenu liké', 'success');
                } else {
                    icon.className = 'far fa-heart text-gray-400';
                    showNotification('Like retiré', 'info');
                }
                
                count.textContent = data.total_likes;
            } else {
                icon.className = originalClass; // Restaurer en cas d'échec logique
                showNotification(data.message || 'Erreur lors de l\'ajout du like', 'error');
            }
        })
        .catch(error => {
            console.error('Like Error:', error);
            icon.className = originalClass; // Restaurer en cas d'erreur
            showNotification(error.message || 'Une erreur est survenue', 'error');
        });
    }
    
    // Gestion des favoris
    function toggleFavorite(contenuId) {
        const icon = document.getElementById(`favorite-icon-${contenuId}`);
        const isCurrentlyFavorite = icon.classList.contains('fas');
        
        // Indicateur de chargement
        icon.className = 'fas fa-spinner fa-spin text-gray-400';
        
        fetch(`{{ route('favoris.toggle') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_contenu: contenuId })
        })
        .then(async response => {
            if (!response.ok) {
                if (response.status === 419) throw new Error("Session expirée (CSRF). Rafraîchissez.");
                if (response.status === 401) throw new Error("Connectez-vous à nouveau.");
                throw new Error(`Erreur serveur: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Réponse invalide (HTML).");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.is_favorite) {
                    icon.className = 'fas fa-bookmark text-yellow-500';
                    icon.classList.add('animate-bounce');
                    setTimeout(() => icon.classList.remove('animate-bounce'), 500);
                    showNotification(data.message, 'success');
                } else {
                    icon.className = 'far fa-bookmark text-gray-400';
                    showNotification(data.message, 'info');
                }
            } else {
                throw new Error(data.message || 'Erreur inconnue');
            }
        })
        .catch(error => {
            console.error('Favorite Error:', error);
            // Restauration état visuel
            icon.className = isCurrentlyFavorite ? 'fas fa-bookmark text-yellow-500' : 'far fa-bookmark text-gray-400';
            showNotification(error.message, 'error');
        });
    }
    
    // Gestion des abonnements
    function toggleSubscribe(auteurId) {
    const buttonHeader = document.getElementById('subscribe-btn-header');
    const buttonSidebar = document.getElementById('subscribe-btn');
    
    // Utiliser l'en-tête comme référence principale
    const button = buttonHeader || buttonSidebar;
    
    if (button.textContent.trim().includes('Abonné')) {
        // Désabonner
        fetch(`/api/abonnements/${auteurId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Mettre à jour le bouton d'en-tête
                if (buttonHeader) {
                    buttonHeader.innerHTML = '<i class="fas fa-user-plus mr-2"></i> S\'abonner';
                    buttonHeader.className = 'flex items-center space-x-2 px-4 py-2 bg-benin-600 text-white rounded-lg hover:opacity-90 transition-opacity duration-300';
                }
                
                // Mettre à jour le bouton de sidebar
                if (buttonSidebar) {
                    buttonSidebar.innerHTML = '<i class="fas fa-user-plus mr-2"></i> S\'abonner';
                    buttonSidebar.className = 'w-full py-2 px-4 bg-benin-600 text-white rounded-lg font-medium hover:opacity-90 transition-opacity duration-300';
                }
                
                showNotification('Abonnement annulé', 'info');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    } else {
        // S'abonner
        fetch('/api/abonnements', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id_auteur: auteurId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Mettre à jour le bouton d'en-tête
                if (buttonHeader) {
                    buttonHeader.innerHTML = '<i class="fas fa-check mr-2"></i> Abonné';
                    buttonHeader.className = 'flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:opacity-90 transition-opacity duration-300';
                }
                
                // Mettre à jour le bouton de sidebar
                if (buttonSidebar) {
                    buttonSidebar.innerHTML = '<i class="fas fa-check mr-2"></i> Abonné';
                    buttonSidebar.className = 'w-full py-2 px-4 bg-gray-200 text-gray-700 rounded-lg font-medium hover:opacity-90 transition-opacity duration-300';
                }
                
                showNotification('Abonnement réussi !', 'success');
            } else if (data.error) {
                showNotification(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    }
}
    
    // Partage
    function shareContent() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $contenu->titre }}',
                text: 'Découvrez ce contenu culturel sur CultureBénin',
                url: window.location.href
            })
            .then(() => showNotification('Contenu partagé !', 'success'))
            .catch(error => {
                console.error('Error sharing:', error);
                copyToClipboard();
            });
        } else {
            copyToClipboard();
        }
    }
    
    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => showNotification('Lien copié dans le presse-papier !', 'success'))
            .catch(error => {
                console.error('Copy failed:', error);
                showNotification('Erreur lors de la copie', 'error');
            });
    }
    
    // Notation
    let currentRating = 0;
    function setRating(rating) {
        currentRating = rating;
        document.getElementById('note').value = rating;
        
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById(`star-${i}`);
            if (i <= rating) {
                star.className = 'text-yellow-400';
            } else {
                star.className = 'text-gray-300';
            }
        }
    }
    
    function clearRating() {
        currentRating = 0;
        document.getElementById('note').value = '';
        for (let i = 1; i <= 5; i++) {
            document.getElementById(`star-${i}`).className = 'text-gray-300';
        }
    }
    
    // Modal pour les médias
    function openMediaModal(src, caption) {
        document.getElementById('modal-image').src = src;
        document.getElementById('modal-caption').textContent = caption || '';
        document.getElementById('media-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMediaModal() {
        document.getElementById('media-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Fermer le modal avec ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMediaModal();
        }
    });
    
    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('media-modal').addEventListener('click', (e) => {
        if (e.target.id === 'media-modal') {
            closeMediaModal();
        }
    });
    
    // Notification
    function showNotification(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl transform translate-x-full transition-transform duration-500 backdrop-blur-sm ${colors[type]}`;
        
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
        }, 3000);
    }

    // ============================================
    // GESTION AJAX DES COMMENTAIRES
    // ============================================

    // Gestion de la soumission du formulaire de commentaire
    document.addEventListener('DOMContentLoaded', function() {
        const commentForm = document.getElementById('comment-form');
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submit-comment');
                const originalText = submitBtn.innerHTML;
                
                // Désactiver le bouton pendant la soumission
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Publication...';
                
                // Masquer les erreurs précédentes
                document.getElementById('comment-text-error').classList.add('hidden');
                document.getElementById('note-error').classList.add('hidden');
                
                const formData = new FormData(this);
                
                fetch('{{ route("commentaires.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        // Afficher les erreurs de validation
                        for (const field in data.errors) {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        }
                        showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
                    } else if (data.success || data.message) {
                        // Afficher le message de succès
                        showNotification(data.message || 'Commentaire ajouté avec succès', 'success');
                        
                        // Réinitialiser le formulaire
                        commentForm.reset();
                        clearRating();
                        
                        // Ajouter le nouveau commentaire à la liste
                        if (data.comment) {
                            addCommentToDOM(data.comment);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Une erreur est survenue lors de la publication', 'error');
                })
                .finally(() => {
                    // Réactiver le bouton
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    });

    // Fonction pour ajouter un commentaire au DOM
    function addCommentToDOM(comment) {
        const commentsList = document.getElementById('comments-list');
        
        // Formater la date
        const now = new Date();
        const formattedDate = now.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const commentHTML = `
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300 comment-item" id="comment-${comment.id_commentaire}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        ${comment.utilisateur.photo ? 
                            `<img src="/storage/${comment.utilisateur.photo}" 
                                  alt="${comment.utilisateur.prenom}"
                                  class="w-10 h-10 rounded-full mr-3 border-2 border-benin-500">` :
                            `<div class="w-10 h-10 rounded-full bg-gradient-to-r from-benin-500 to-beninYellow-500 flex items-center justify-center mr-3">
                                <span class="text-white font-semibold">${comment.utilisateur.prenom.charAt(0)}</span>
                            </div>`
                        }
                        <div>
                            <div class="font-semibold text-gray-900">${comment.utilisateur.prenom} ${comment.utilisateur.nom}</div>
                            <div class="text-sm text-gray-500">À l'instant</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        ${comment.note ? `
                            <div class="flex items-center">
                                ${Array.from({length: 5}, (_, i) => `
                                    <i class="fas fa-star text-lg ${i < comment.note ? 'text-yellow-400' : 'text-gray-300'}"></i>
                                `).join('')}
                            </div>
                        ` : ''}
                        
                        ${comment.id_utilisateur === {{ auth()->id() ?? 0 }} ? `
                            <div class="flex space-x-2">
                                <button onclick="editComment(${comment.id_commentaire})" 
                                        class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteComment(${comment.id_commentaire})" 
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="text-gray-700 comment-text">
                    ${comment.texte}
                </div>
                
                <!-- Formulaire d'édition (caché par défaut) -->
                <div class="edit-comment-form hidden mt-4">
                    <form class="space-y-4">
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-benin-500 focus:border-transparent resize-none" 
                                  rows="3"
                                  name="texte">${comment.texte}</textarea>
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                    onclick="cancelEdit(${comment.id_commentaire})"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="button" 
                                    onclick="updateComment(${comment.id_commentaire})"
                                    class="px-4 py-2 bg-benin-600 text-white rounded-lg hover:bg-benin-700">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        // Ajouter le commentaire en haut de la liste
        if (commentsList.children.length === 0) {
            commentsList.innerHTML = commentHTML;
        } else {
            commentsList.insertAdjacentHTML('afterbegin', commentHTML);
        }
        
        // Mettre à jour le compteur de commentaires
        updateCommentCount();
    }

    // Fonction pour mettre à jour le compteur de commentaires
    function updateCommentCount() {
        const commentItems = document.querySelectorAll('.comment-item');
        const commentCountElement = document.getElementById('comment-count');
        if (commentCountElement) {
            commentCountElement.textContent = commentItems.length;
        }
    }

    // Fonction pour éditer un commentaire
    function editComment(commentId) {
        const commentItem = document.getElementById(`comment-${commentId}`);
        const commentText = commentItem.querySelector('.comment-text');
        const editForm = commentItem.querySelector('.edit-comment-form');
        
        commentText.classList.add('hidden');
        editForm.classList.remove('hidden');
    }

    // Fonction pour annuler l'édition
    function cancelEdit(commentId) {
        const commentItem = document.getElementById(`comment-${commentId}`);
        const commentText = commentItem.querySelector('.comment-text');
        const editForm = commentItem.querySelector('.edit-comment-form');
        
        commentText.classList.remove('hidden');
        editForm.classList.add('hidden');
    }

    // Fonction pour mettre à jour un commentaire
    function updateComment(commentId) {
        const commentItem = document.getElementById(`comment-${commentId}`);
        const textarea = commentItem.querySelector('.edit-comment-form textarea');
        const newText = textarea.value;
        
        fetch(`/commentaires/${commentId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                texte: newText,
                _method: 'PUT'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                showNotification(data.message, 'success');
                
                // Mettre à jour le texte du commentaire
                const commentText = commentItem.querySelector('.comment-text');
                commentText.textContent = newText;
                
                // Revenir à l'affichage normal
                cancelEdit(commentId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la mise à jour', 'error');
        });
    }

    // Fonction pour supprimer un commentaire
    function deleteComment(commentId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            fetch(`/commentaires/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showNotification(data.message, 'success');
                    
                    // Supprimer l'élément du DOM
                    const commentItem = document.getElementById(`comment-${commentId}`);
                    commentItem.remove();
                    
                    // Mettre à jour le compteur
                    updateCommentCount();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur lors de la suppression', 'error');
            });
        }
    }
    
    // Vérifier l'état des likes et favoris au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const contenuId = {{ $contenu->id_contenu }};
        
        // Si l'utilisateur est connecté, vérifier l'état initial du like
        @if(auth()->check())
        fetch(`/api/likes/check/${contenuId}`)
            .then(response => response.json())
            .then(data => {
                const icon = document.getElementById(`like-icon-${contenuId}`);
                const count = document.getElementById(`like-count-${contenuId}`);
                
                if (icon) {
                    if (data.liked) {
                        icon.className = 'fas fa-heart text-red-500';
                    } else {
                        icon.className = 'far fa-heart text-gray-400';
                    }
                }
                
                if (count) {
                    count.textContent = data.total_likes;
                }
            })
            .catch(error => console.error('Error checking like:', error));
        @endif
    });
</script>
@endpush
@endsection