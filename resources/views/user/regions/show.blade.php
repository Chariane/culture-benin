{{-- /home/nadege/Downloads/culture/resources/views/user/regions/show.blade.php --}}
@extends('layouts.app')

@section('title', $region->nom_region . ' - CultureHub')

@section('styles')
<style>
    .region-hero {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 92, 246, 0.8));
        position: relative;
        overflow: hidden;
    }
    
    .region-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="white" fill-opacity="0.1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,192C672,181,768,139,864,128C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        opacity: 0.3;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .map-container {
        height: 300px;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .language-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
        color: white;
        margin: 0.25rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="region-hero relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="text-center text-white">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Région
            </div>
            
            <h1 class="text-5xl md:text-6xl font-display font-bold mb-6">
                {{ $region->nom_region }}
            </h1>
            
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-8">
                {{ $region->description }}
            </p>
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-12">
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-cultural-600 mb-2">{{ $stats['total_contents'] }}</div>
                    <div class="text-gray-600">Contenus</div>
                </div>
                
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-cultural-600 mb-2">{{ $stats['languages'] }}</div>
                    <div class="text-gray-600">Langues</div>
                </div>
                
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-cultural-600 mb-2">{{ $stats['authors'] }}</div>
                    <div class="text-gray-600">Auteurs</div>
                </div>
                
                <div class="stat-card rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-cultural-600 mb-2">{{ $stats['premium_contents'] }}</div>
                    <div class="text-gray-600">Premium</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2">
            <!-- Languages -->
            @if($region->langues->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Langues parlées</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($region->langues as $langue)
                    <div class="language-badge">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        {{ $langue->nom_langue }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Popular Contents -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Contenus populaires</h2>
                    <a href="{{ route('regions.contents', $region) }}" 
                       class="text-cultural-600 hover:text-cultural-700 font-medium">
                        Voir tout
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($popularContents as $content)
                    <div class="bg-white rounded-xl shadow-soft overflow-hidden hover-lift">
                        <div class="md:flex">
                            <div class="md:w-2/5">
                                <div class="h-48 md:h-full">
                                    @if($content->medias->first())
                                    <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                                         alt="{{ $content->titre }}"
                                         class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gradient-to-r from-cultural-100 to-cultural-200 flex items-center justify-center">
                                        <span class="text-cultural-800 font-display text-4xl">{{ substr($content->titre, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="md:w-3/5 p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium">
                                        {{ $content->type->nom_contenu ?? 'Article' }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                    <a href="{{ route('contenus.show', $content) }}" class="hover:text-cultural-600">
                                        {{ Str::limit($content->titre, 60) }}
                                    </a>
                                </h3>
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $content->date_creation->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Contents -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Contenus récents</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentContents as $content)
                    <div class="bg-white rounded-xl shadow-soft overflow-hidden hover-lift">
                        <div class="p-6">
                            <div class="mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $content->type->nom_contenu == 'Article' ? 'bg-blue-100 text-blue-800' : 
                                       ($content->type->nom_contenu == 'Vidéo' ? 'bg-red-100 text-red-800' : 
                                       ($content->type->nom_contenu == 'Audio' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')) }}">
                                    {{ $content->type->nom_contenu }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('contenus.show', $content) }}" class="hover:text-cultural-600">
                                    {{ $content->titre }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mt-4">
                                <div class="flex items-center">
                                    @if($content->auteur->photo)
                                    <img src="{{ Storage::url($content->auteur->photo) }}" 
                                         alt="{{ $content->auteur->prenom }}"
                                         class="w-6 h-6 rounded-full mr-2">
                                    @else
                                    <div class="w-6 h-6 rounded-full bg-cultural-100 flex items-center justify-center mr-2">
                                        <span class="text-cultural-600 text-xs font-semibold">{{ substr($content->auteur->prenom, 0, 1) }}</span>
                                    </div>
                                    @endif
                                    <span>{{ $content->auteur->prenom }}</span>
                                </div>
                                <span>{{ $content->date_creation->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-1">
            <!-- Region Info -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                
                <div class="space-y-4">
                    @if($region->population)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <div>
                            <div class="font-medium">Population</div>
                            <div class="text-sm text-gray-500">{{ number_format($region->population, 0, ',', ' ') }} habitants</div>
                        </div>
                    </div>
                    @endif
                    
                    @if($region->superficie)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <div>
                            <div class="font-medium">Superficie</div>
                            <div class="text-sm text-gray-500">{{ number_format($region->superficie, 0, ',', ' ') }} km²</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Similar Regions -->
            @if($similarRegions->count() > 0)
            <div class="bg-white rounded-xl shadow-soft p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Régions similaires</h3>
                
                <div class="space-y-4">
                    @foreach($similarRegions as $similar)
                    <a href="{{ route('regions.show', $similar) }}" class="block group">
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ substr($similar->nom_region, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-gray-900 group-hover:text-cultural-600">{{ $similar->nom_region }}</div>
                                <div class="text-sm text-gray-500">{{ $similar->contenus_count }} contenus</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-8">
                <a href="{{ route('regions.contents', $region) }}" 
                   class="block w-full text-center px-6 py-3 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors font-semibold mb-3">
                    Explorer tous les contenus
                </a>
                <a href="{{ route('contenus.index', ['region' => $region->id_region]) }}" 
                   class="block w-full text-center px-6 py-3 border-2 border-cultural-500 text-cultural-500 rounded-lg hover:bg-cultural-50 transition-colors font-semibold">
                    Filtrer par cette région
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Map Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Explorez la région</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Map Placeholder -->
            <div class="lg:col-span-2">
                <div class="map-container bg-white rounded-xl shadow-soft p-4">
                    <div class="w-full h-full bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p class="text-gray-500">Carte interactive de {{ $region->nom_region }}</p>
                            <p class="text-sm text-gray-400 mt-2">(Intégration cartographique à configurer)</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cultural Highlights -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-soft p-6 h-full">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Points culturels</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-cultural-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-cultural-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Patrimoine historique</div>
                                <div class="text-sm text-gray-600 mt-1">Sites et monuments classés</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Traditions vivantes</div>
                                <div class="text-sm text-gray-600 mt-1">Festivals et rituels</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Littérature orale</div>
                                <div class="text-sm text-gray-600 mt-1">Contes et proverbes</div>
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
    // Initialize region stats chart
    document.addEventListener('DOMContentLoaded', function() {
        loadRegionStats();
        
        // Interactive map functionality (placeholder)
        const mapContainer = document.querySelector('.map-container');
        if (mapContainer) {
            mapContainer.addEventListener('click', function() {
                alert('Carte interactive - À intégrer avec Leaflet.js ou Google Maps API');
            });
        }
    });
    
    function loadRegionStats() {
        fetch(`/api/regions/{{ $region->id_region }}/stats`)
        .then(response => response.json())
        .then(data => {
            console.log('Region stats:', data);
            // You can use Chart.js here to visualize the data
        });
    }
</script>
@endpush