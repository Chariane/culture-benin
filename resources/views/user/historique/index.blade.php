@extends('layouts.app')

@section('title', 'Historique - CultureHub')

@section('styles')
<style>
    .timeline-item {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 2rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        top: 0;
        bottom: -2rem;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-dot {
        position: absolute;
        left: 1.25rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #f59e0b;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Mon historique</h1>
                <p class="text-gray-600">Suivez votre activité et vos découvertes</p>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="clearHistory()" 
                        class="flex items-center px-4 py-2 text-gray-700 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Effacer l'historique
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_views'] ?? 0 }}</div>
                    <div class="text-gray-600">Contenus visionnés</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_likes'] ?? 0 }}</div>
                    <div class="text-gray-600">Likes donnés</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_comments'] ?? 0 }}</div>
                    <div class="text-gray-600">Commentaires</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['reading_time']['minutes'] ?? 0 }} min</div>
                    <div class="text-gray-600">Temps de lecture</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select id="period" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cultural-500">
                    <option value="all">Toutes périodes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type d'activité</label>
                <select id="activity-type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cultural-500">
                    <option value="all">Toutes les activités</option>
                    <option value="view">Lectures</option>
                    <option value="like">Likes</option>
                    <option value="comment">Commentaires</option>
                    <option value="purchase">Achats</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                <select id="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cultural-500">
                    <option value="date_desc">Plus récent</option>
                    <option value="date_asc">Plus ancien</option>
                    <option value="popularity">Plus populaire</option>
                </select>
            </div>
            
            <div class="self-end">
                <button onclick="applyHistoryFilters()" 
                        class="px-6 py-2 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                    Appliquer
                </button>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-xl shadow-soft p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Activités récentes</h2>
        
        <div class="space-y-8">
            @foreach($activities as $activity)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                
                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg mr-4 
                                {{ $activity->type == 'view' ? 'bg-blue-100' : 
                                   ($activity->type == 'like' ? 'bg-red-100' : 
                                   ($activity->type == 'comment' ? 'bg-green-100' : 'bg-purple-100')) }}">
                                @if($activity->type == 'view')
                                <svg class="w-6 h-6 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                    ($activity->type == 'like' ? 'text-red-600' : 
                                    ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                @elseif($activity->type == 'like')
                                <svg class="w-6 h-6 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                    ($activity->type == 'like' ? 'text-red-600' : 
                                    ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                </svg>
                                @elseif($activity->type == 'comment')
                                <svg class="w-6 h-6 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                    ($activity->type == 'like' ? 'text-red-600' : 
                                    ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                @else
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">
                                    @if($activity->type == 'view')
                                    Vous avez lu "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                    @elseif($activity->type == 'like')
                                    Vous avez aimé "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                    @elseif($activity->type == 'comment')
                                    Vous avez commenté "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                    @else
                                    Vous avez acheté "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $activity->date->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        @if($activity->contenu)
                        <a href="{{ route('contenus.show', $activity->contenu) }}" 
                           class="text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                            Voir le contenu
                        </a>
                        @endif
                    </div>
                    
                    @if($activity->contenu)
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        @if($activity->contenu->region)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $activity->contenu->region->nom_region }}
                        </span>
                        @endif
                        
                        @if($activity->contenu->auteur)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $activity->contenu->auteur->prenom }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            
            @if($activities->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune activité récente</h3>
                <p class="text-gray-600 mb-6">Explorez les contenus pour commencer à remplir votre historique</p>
                <a href="{{ route('contenus.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                    Explorer les contenus
                </a>
            </div>
            @endif
        </div>
        
        @if($activities->hasPages())
        <div class="mt-8">
            {{ $activities->links() }}
        </div>
        @endif
    </div>

    <!-- Recommendations -->
    @if($recommendations->count() > 0)
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Basé sur votre historique</h2>
            <a href="{{ route('contenus.index') }}" class="text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                Voir plus
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($recommendations as $content)
            <div class="bg-white rounded-xl shadow-soft overflow-hidden hover-lift">
                @include('user.components.content-card', ['content' => $content])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Apply filters
    function applyHistoryFilters() {
        const period = document.getElementById('period').value;
        const type = document.getElementById('activity-type').value;
        const sort = document.getElementById('sort').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (period !== 'all') params.set('period', period);
        else params.delete('period');
        
        if (type !== 'all') params.set('type', type);
        else params.delete('type');
        
        if (sort !== 'date_desc') params.set('sort', sort);
        else params.delete('sort');
        
        params.delete('page');
        
        window.location.href = url.pathname + '?' + params.toString();
    }
    
    // Clear history
    function clearHistory() {
        if (confirm('Êtes-vous sûr de vouloir effacer tout votre historique ? Cette action est irréversible.')) {
            fetch('/api/historique/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                window.location.reload();
            });
        }
    }
    
    // Auto-apply filters on change
    ['period', 'activity-type', 'sort'].forEach(id => {
        document.getElementById(id).addEventListener('change', applyHistoryFilters);
    });
</script>
@endpush
@endsection