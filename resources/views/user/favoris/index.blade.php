@extends('layouts.app')

@section('title', 'Mes Favoris - CultureBénin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white mb-2">
            Mes Favoris
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Retrouvez ici tous les contenus que vous avez aimés.
        </p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-heart text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Total favoris</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-benin-100 dark:bg-benin-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt text-benin-600 dark:text-benin-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['par_type']['articles'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Articles</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-beninYellow-100 dark:bg-beninYellow-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-camera text-beninYellow-600 dark:text-beninYellow-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['par_type']['photos'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Photos</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-beninRed-100 dark:bg-beninRed-900/30 
                            flex items-center justify-center mr-4">
                    <i class="fas fa-video text-beninRed-600 dark:text-beninRed-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['par_type']['videos'] }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Vidéos</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="mb-8">
        <form action="{{ route('favoris.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Filtre type de contenu -->
                <div class="flex-1">
                    <select name="type" 
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-300 
                                   dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                   focus:ring-2 focus:ring-benin-500 focus:border-benin-500 outline-none">
                        <option value="">Tous les types de contenus</option>
                        @foreach($typesDisponibles as $type)
                            <option value="{{ $type->id_type_contenu }}" {{ request('type') == $type->id_type_contenu ? 'selected' : '' }}>
                                {{ $type->nom_contenu }}
                            </option>
                        @endforeach
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
                    
                    <a href="{{ route('favoris.index') }}" 
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

    <!-- Liste des favoris -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-cinzel font-bold text-gray-900 dark:text-white">
                Contenus favoris
            </h2>
            <span class="text-gray-600 dark:text-gray-400">
                {{ $favoris->total() }} élément(s)
            </span>
        </div>
        
        @if($favoris->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favoris as $favori)
                    @php
                        $contenu = $favori->contenu;
                    @endphp
                    
                    @if($contenu)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg 
                               hover:shadow-xl transition-all duration-300 overflow-hidden
                               border border-gray-200 dark:border-gray-700 
                               transform hover:-translate-y-1">
                        
                        <!-- Image/icône -->
                        <div class="h-48 overflow-hidden relative">
                            @if($contenu->type_contenu_id == 2 && $contenu->medias->count() > 0)
                                <img src="{{ asset('storage/' . $contenu->medias->first()->chemin) }}" 
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
                                {{ $contenu->typeContenu ? $contenu->typeContenu->nom_contenu : 'Non spécifié' }}
                            </span>
                            
                            <!-- Date d'ajout -->
                            <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs 
                                          bg-gray-800/70 text-white">
                                <i class="far fa-clock mr-1"></i>
                                {{ $favori->date_ajout->format('d/m/Y') }}
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
                            
                            <!-- Auteur -->
                            @if($contenu->auteur)
                            <div class="flex items-center mb-4">
                                @php
                                    $photoAuteur = null;
                                    if ($contenu->auteur->photo && file_exists(public_path('storage/photos/' . basename($contenu->auteur->photo)))) {
                                        $photoAuteur = asset('storage/photos/' . basename($contenu->auteur->photo));
                                    } else {
                                        if ($contenu->auteur->sexe === 'Homme') {
                                            $photoAuteur = asset('male.jpg');
                                        } elseif ($contenu->auteur->sexe === 'Femme') {
                                            $photoAuteur = asset('female.jpg');
                                        }
                                    }
                                @endphp
                                
                                @if($photoAuteur)
                                <img src="{{ $photoAuteur }}" 
                                     alt="{{ $contenu->auteur->nom }}"
                                     class="w-8 h-8 rounded-full mr-2 border border-gray-300 dark:border-gray-600">
                                @else
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 
                                            flex items-center justify-center mr-2 text-gray-600 dark:text-gray-300 
                                            text-xs font-semibold">
                                    {{ strtoupper(substr($contenu->auteur->nom, 0, 1)) }}
                                </div>
                                @endif
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $contenu->auteur->prenom }} {{ $contenu->auteur->nom }}
                                </div>
                            </div>
                            @endif
                            
                            <!-- Métadonnées et actions -->
                            <div class="flex items-center justify-between">
                                @if($contenu->region)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                                        <i class="fas fa-map-marker-alt mr-1.5 text-benin-500"></i>
                                        {{ $contenu->region->nom_region }}
                                    </div>
                                @endif
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('contenus.show', $contenu) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-benin-500 text-white text-sm 
                                              hover:bg-benin-600 transition font-medium">
                                        Consulter
                                    </a>
                                    
                                    <button onclick="toggleFavori({{ $contenu->id_contenu }})"
                                            class="px-3 py-1.5 rounded-lg bg-red-100 dark:bg-red-900/30 
                                                   text-red-600 dark:text-red-400 text-sm 
                                                   hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $favoris->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 text-gray-400">
                    <i class="fas fa-heart text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Aucun favori pour le moment
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Vous n'avez encore ajouté aucun contenu à vos favoris.
                </p>
                <a href="{{ route('contenus.index') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-benin-500 text-white rounded-xl 
                          hover:bg-benin-600 transition font-medium gap-2">
                    <i class="fas fa-compass"></i>
                    Explorer les contenus
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Fonction pour retirer des favoris
function toggleFavori(contenuId) {
    if (!confirm('Voulez-vous vraiment retirer ce contenu de vos favoris ?')) {
        return;
    }
    
    fetch('{{ route("favoris.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_contenu: contenuId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'removed') {
                // Actualiser la page
                window.location.reload();
            }
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la suppression du favori');
    });
}

// Auto-soumission des filtres
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name]').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>

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