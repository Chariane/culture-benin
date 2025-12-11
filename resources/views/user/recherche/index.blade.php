@extends('layouts.app')

@section('title', 'Recherche - CultureHub')

@section('styles')
<style>
    .search-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .search-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="white" fill-opacity="0.1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,192C672,181,768,139,864,128C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        opacity: 0.3;
    }
    
    .filter-group {
        transition: all 0.3s ease;
    }
    
    .filter-group.collapsed {
        max-height: 60px;
        overflow: hidden;
    }
    
    .search-result-card {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .search-result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    
    .highlight {
        background-color: #fef3c7;
        padding: 0 2px;
        border-radius: 3px;
    }
</style>
@endsection

@section('content')
<div class="search-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">
                Explorez la culture béninoise
            </h1>
            <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                Découvrez des milliers de contenus culturels authentiques
            </p>
        </div>
        
        <!-- Search Bar -->
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('recherche.index') }}" method="GET" class="relative">
                <div class="relative">
                    <input type="text" 
                           name="query" 
                           value="{{ request('query') }}"
                           placeholder="Rechercher des contenus, auteurs, régions..."
                           class="w-full px-6 py-4 pl-14 text-lg rounded-xl shadow-lg focus:outline-none focus:ring-3 focus:ring-cultural-500"
                           autocomplete="off"
                           id="search-input">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="submit" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                        Rechercher
                    </button>
                </div>
            </form>
            
            <!-- Quick Suggestions -->
            <div class="mt-6 flex flex-wrap justify-center gap-2">
                @foreach($quickSuggestions as $suggestion)
                <a href="{{ route('recherche.index', ['query' => $suggestion]) }}" 
                   class="px-4 py-2 bg-white/20 text-white rounded-full hover:bg-white/30 transition-colors text-sm">
                    {{ $suggestion }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(request()->has('query'))
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">
                    Résultats pour "{{ request('query') }}"
                </h2>
                <p class="text-gray-600 mt-1">
                    {{ $results->total() }} résultat{{ $results->total() > 1 ? 's' : '' }} trouvé{{ $results->total() > 1 ? 's' : '' }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    Trier par:
                    <select id="search-sort" class="ml-2 border-none focus:ring-0 text-gray-900">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Pertinence</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Plus récent</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularité</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1 mb-8 lg:mb-0">
            <div class="bg-white rounded-xl shadow-soft p-6 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Filtres</h3>
                    @if(request()->hasAny(['type', 'region', 'langue', 'premium', 'date_from']))
                    <a href="{{ route('recherche.index', ['query' => request('query')]) }}" 
                       class="text-sm text-cultural-600 hover:text-cultural-700">
                        Réinitialiser
                    </a>
                    @endif
                </div>
                
                <!-- Type de contenu -->
                <div class="filter-group mb-6">
                    <button onclick="toggleFilterGroup('type')" 
                            class="w-full flex items-center justify-between text-left font-medium text-gray-900 mb-3">
                        <span>Type de contenu</span>
                        <svg id="type-arrow" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="type-filters" class="space-y-2">
                        @foreach($filters['content_types'] as $type)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="type" 
                                   value="{{ $type->id_type_contenu }}"
                                   {{ in_array($type->id_type_contenu, (array)request('type', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500"
                                   onchange="applyFilter()">
                            <span class="ml-2 text-gray-700">{{ $type->nom_contenu }}</span>
                            <span class="ml-auto text-xs text-gray-500">{{ $type->contenus_count ?? 0 }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Région -->
                <div class="filter-group mb-6">
                    <button onclick="toggleFilterGroup('region')" 
                            class="w-full flex items-center justify-between text-left font-medium text-gray-900 mb-3">
                        <span>Région</span>
                        <svg id="region-arrow" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="region-filters" class="space-y-2">
                        @foreach($filters['regions'] as $region)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="region" 
                                   value="{{ $region->id_region }}"
                                   {{ in_array($region->id_region, (array)request('region', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500"
                                   onchange="applyFilter()">
                            <span class="ml-2 text-gray-700">{{ $region->nom_region }}</span>
                            <span class="ml-auto text-xs text-gray-500">{{ $region->contenus_count ?? 0 }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Année -->
                <div class="filter-group mb-6">
                    <button onclick="toggleFilterGroup('year')" 
                            class="w-full flex items-center justify-between text-left font-medium text-gray-900 mb-3">
                        <span>Année</span>
                        <svg id="year-arrow" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="year-filters" class="space-y-2">
                        @foreach($filters['years'] as $year)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="year" 
                                   value="{{ $year }}"
                                   {{ in_array($year, (array)request('year', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500"
                                   onchange="applyFilter()">
                            <span class="ml-2 text-gray-700">{{ $year }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Premium -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="premium" 
                               value="1"
                               {{ request('premium') == '1' ? 'checked' : '' }}
                               class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500"
                               onchange="applyFilter()">
                        <span class="ml-2 text-gray-700">Contenus premium uniquement</span>
                    </label>
                </div>
                
                <!-- Date Range -->
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">Date de publication</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">De</label>
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   onchange="applyFilter()">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">À</label>
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   onchange="applyFilter()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results -->
        <div class="lg:col-span-3">
            <!-- Results Stats -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2 mb-4">
                    @if(request()->has('type') && is_array(request('type')))
                        @foreach(request('type') as $typeId)
                            @php $type = $filters['content_types']->firstWhere('id_type_contenu', $typeId); @endphp
                            @if($type)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                {{ $type->nom_contenu }}
                                <button onclick="removeFilter('type', {{ $typeId }})" class="ml-2 text-blue-600 hover:text-blue-800">
                                    ×
                                </button>
                            </span>
                            @endif
                        @endforeach
                    @endif
                    
                    @if(request()->has('region') && is_array(request('region')))
                        @foreach(request('region') as $regionId)
                            @php $region = $filters['regions']->firstWhere('id_region', $regionId); @endphp
                            @if($region)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                {{ $region->nom_region }}
                                <button onclick="removeFilter('region', {{ $regionId }})" class="ml-2 text-green-600 hover:text-green-800">
                                    ×
                                </button>
                            </span>
                            @endif
                        @endforeach
                    @endif
                    
                    @if(request()->has('premium') && request('premium') == '1')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                        Premium
                        <button onclick="removeFilter('premium', '1')" class="ml-2 text-yellow-600 hover:text-yellow-800">
                            ×
                        </button>
                    </span>
                    @endif
                </div>
            </div>
            
            <!-- Results Grid -->
            @if($results->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                @foreach($results as $result)
                <div class="search-result-card bg-white rounded-xl shadow-soft overflow-hidden">
                    <div class="p-6">
                        <!-- Type Badge -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $result->type->nom_contenu == 'Article' ? 'bg-blue-100 text-blue-800' : 
                                   ($result->type->nom_contenu == 'Vidéo' ? 'bg-red-100 text-red-800' : 
                                   ($result->type->nom_contenu == 'Audio' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')) }}">
                                {{ $result->type->nom_contenu }}
                            </span>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('contenus.show', $result) }}" class="hover:text-cultural-600">
                                {!! highlightText($result->titre, request('query')) !!}
                            </a>
                        </h3>
                        
                        <!-- Excerpt -->
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {!! highlightText(strip_tags($result->texte), request('query')) !!}
                        </p>
                        
                        <!-- Metadata -->
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-4">
                                @if($result->region)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $result->region->nom_region }}
                                </span>
                                @endif
                                
                                @if($result->auteur)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $result->auteur->prenom }}
                                </span>
                                @endif
                            </div>
                            
                            <div class="text-xs">
                                {{ $result->date_creation->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $results->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-600 mb-6">Essayez avec d'autres mots-clés ou modifiez vos filtres</p>
                <a href="{{ route('recherche.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                    Nouvelle recherche
                </a>
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- Search Suggestions -->
    <div class="text-center py-12">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Sujets populaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($popularTopics as $topic)
                <a href="{{ route('recherche.index', ['query' => $topic['query']]) }}" 
                   class="p-6 bg-white rounded-xl shadow-soft hover-lift text-left">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-lg {{ $topic['color'] }} mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $topic['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $topic['title'] }}</h3>
                    </div>
                    <p class="text-sm text-gray-600">{{ $topic['description'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Featured Content -->
    @if($featuredContents->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Contenus en vedette</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredContents as $content)
            <div class="bg-white rounded-xl shadow-soft overflow-hidden hover-lift">
                @include('user.components.content-card', ['content' => $content])
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif
</div>

@push('scripts')
<script>
    // Toggle filter groups
    function toggleFilterGroup(group) {
        const filters = document.getElementById(group + '-filters');
        const arrow = document.getElementById(group + '-arrow');
        
        filters.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
    
    // Apply filters
    function applyFilter() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.location.pathname;
        
        // Add query
        const queryInput = document.createElement('input');
        queryInput.type = 'hidden';
        queryInput.name = 'query';
        queryInput.value = '{{ request("query") }}';
        form.appendChild(queryInput);
        
        // Add filters
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = cb.name;
            input.value = cb.value;
            form.appendChild(input);
        });
        
        // Add date range
        const dateFrom = document.querySelector('input[name="date_from"]');
        const dateTo = document.querySelector('input[name="date_to"]');
        
        if (dateFrom.value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_from';
            input.value = dateFrom.value;
            form.appendChild(input);
        }
        
        if (dateTo.value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_to';
            input.value = dateTo.value;
            form.appendChild(input);
        }
        
        // Add sort
        const sortSelect = document.getElementById('search-sort');
        if (sortSelect) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sort';
            input.value = sortSelect.value;
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Remove filter
    function removeFilter(type, value) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        
        if (type === 'premium') {
            params.delete('premium');
        } else {
            const currentValues = params.getAll(type);
            const newValues = currentValues.filter(v => v != value);
            params.delete(type);
            newValues.forEach(v => params.append(type, v));
        }
        
        window.location.href = url.pathname + '?' + params.toString();
    }
    
    // Auto search suggestions
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                fetchSuggestions(searchInput.value);
            }, 300);
        });
    }
    
    function fetchSuggestions(query) {
        if (query.length < 2) return;
        
        fetch(`/api/recherche/suggestions?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            // Show suggestions dropdown
            showSuggestions(data);
        });
    }
    
    function showSuggestions(data) {
        // Implementation for suggestions dropdown
        console.log('Suggestions:', data);
    }
    
    // Sort change
    const sortSelect = document.getElementById('search-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilter);
    }
</script>
@endpush
@endsection

<?php
// Helper function for highlighting
if (!function_exists('highlightText')) {
    function highlightText($text, $query) {
        if (!$query || strlen($query) < 2) {
            return e($text);
        }
        
        $words = explode(' ', $query);
        $highlighted = $text;
        
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 1) {
                $pattern = '/\b(' . preg_quote($word, '/') . ')\b/i';
                $highlighted = preg_replace($pattern, '<span class="highlight">$1</span>', $highlighted);
            }
        }
        
        return $highlighted ?: e($text);
    }
}
?>