@extends('layouts.app')

@section('title', 'Tableau de bord - CultureHub')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .activity-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-dot {
        position: absolute;
        left: -1.75rem;
        top: 0.25rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: white;
        border: 3px solid #f59e0b;
        z-index: 10;
    }
    
    .quick-action-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .quick-action-card:hover {
        border-color: #f59e0b;
        transform: translateY(-2px);
    }
    
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .progress-ring-circle {
        stroke-linecap: round;
        transition: stroke-dashoffset 0.3s;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">
                    Bonjour, {{ auth()->user()->prenom }} 👋
                </h1>
                <p class="text-gray-600">Voici votre tableau de bord personnel</p>
            </div>
            <div class="text-sm text-gray-500">
                {{ now()->format('l d F Y') }}
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $stats['total_views'] ?? 0 }}</div>
                    <div class="text-blue-100">Contenus lus</div>
                </div>
                <svg class="w-12 h-12 text-blue-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cultural-500 to-cultural-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $stats['total_likes'] ?? 0 }}</div>
                    <div class="text-cultural-100">Likes donnés</div>
                </div>
                <svg class="w-12 h-12 text-cultural-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $stats['total_comments'] ?? 0 }}</div>
                    <div class="text-green-100">Commentaires</div>
                </div>
                <svg class="w-12 h-12 text-green-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $stats['total_favorites'] ?? 0 }}</div>
                    <div class="text-purple-100">Favoris</div>
                </div>
                <svg class="w-12 h-12 text-purple-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2">
            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Accès rapide</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('contenus.index') }}" class="quick-action-card bg-white rounded-xl shadow-soft p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Explorer</div>
                    </a>
                    
                    <a href="{{ route('bibliotheque.index') }}" class="quick-action-card bg-white rounded-xl shadow-soft p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-cultural-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-cultural-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Bibliothèque</div>
                    </a>
                    
                    <a href="{{ route('historique.index') }}" class="quick-action-card bg-white rounded-xl shadow-soft p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Historique</div>
                    </a>
                    
                    <a href="{{ route('profil.show') }}" class="quick-action-card bg-white rounded-xl shadow-soft p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Profil</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Activité récente</h2>
                    <a href="{{ route('historique.index') }}" class="text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <div class="activity-timeline">
                        @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-lg mr-3 
                                            {{ $activity->type == 'view' ? 'bg-blue-100' : 
                                               ($activity->type == 'like' ? 'bg-red-100' : 
                                               ($activity->type == 'comment' ? 'bg-green-100' : 'bg-purple-100')) }}">
                                            @if($activity->type == 'view')
                                            <svg class="w-5 h-5 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                                ($activity->type == 'like' ? 'text-red-600' : 
                                                ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            @elseif($activity->type == 'like')
                                            <svg class="w-5 h-5 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                                ($activity->type == 'like' ? 'text-red-600' : 
                                                ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                            </svg>
                                            @elseif($activity->type == 'comment')
                                            <svg class="w-5 h-5 {{ $activity->type == 'view' ? 'text-blue-600' : 
                                                ($activity->type == 'like' ? 'text-red-600' : 
                                                ($activity->type == 'comment' ? 'text-green-600' : 'text-purple-600')) }}" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                @if($activity->type == 'view')
                                                Vous avez lu "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                                @elseif($activity->type == 'like')
                                                Vous avez aimé "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                                @elseif($activity->type == 'comment')
                                                Vous avez commenté "{{ $activity->contenu->titre ?? 'un contenu' }}"
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $activity->date->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    @if($activity->contenu)
                                    <a href="{{ route('contenus.show', $activity->contenu) }}" 
                                       class="text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                                        Voir
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">Aucune activité récente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Continue Reading -->
            @if($continueReading->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Continuer votre lecture</h2>
                <div class="bg-white rounded-xl shadow-soft overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($continueReading as $reading)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden mr-4">
                                    @if($reading->contenu->medias->first())
                                    <img src="{{ Storage::url($reading->contenu->medias->first()->chemin) }}" 
                                         alt="{{ $reading->contenu->titre }}"
                                         class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full bg-gradient-to-r from-cultural-100 to-cultural-200 flex items-center justify-center">
                                        <span class="text-cultural-800 font-display text-lg">{{ substr($reading->contenu->titre, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 mb-1">
                                                <a href="{{ route('contenus.show', $reading->contenu) }}" class="hover:text-cultural-600">
                                                    {{ $reading->contenu->titre }}
                                                </a>
                                            </h3>
                                            <div class="text-sm text-gray-500">
                                                {{ $reading->contenu->type->nom_contenu ?? 'Contenu' }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-cultural-600 mb-1">{{ $reading->progression ?? 0 }}%</div>
                                            <div class="text-xs text-gray-500">Progression</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-cultural-500 h-2 rounded-full" style="width: {{ $reading->progression ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="text-sm text-gray-500">
                                            Dernière lecture : {{ $reading->derniere_lecture->diffForHumans() }}
                                        </div>
                                        <a href="{{ route('lecture.read', $reading->contenu) }}" 
                                           class="px-4 py-2 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors text-sm font-medium">
                                            Continuer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-1">
            <!-- Reading Goals -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Objectifs de lecture</h3>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-medium text-gray-700">Contenus ce mois</div>
                            <div class="text-sm text-gray-500">{{ $goals['current_month'] ?? 0 }}/{{ $goals['monthly_goal'] ?? 10 }}</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-cultural-500 h-2 rounded-full" 
                                 style="width: {{ min(100, (($goals['current_month'] ?? 0) / ($goals['monthly_goal'] ?? 10)) * 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-medium text-gray-700">Minutes cette semaine</div>
                            <div class="text-sm text-gray-500">{{ $goals['weekly_minutes'] ?? 0 }}/{{ $goals['weekly_goal'] ?? 180 }} min</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" 
                                 style="width: {{ min(100, (($goals['weekly_minutes'] ?? 0) / ($goals['weekly_goal'] ?? 180)) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <button onclick="setReadingGoals()" 
                        class="w-full mt-6 px-4 py-2 border border-cultural-500 text-cultural-500 rounded-lg hover:bg-cultural-50 transition-colors text-sm font-medium">
                    Définir des objectifs
                </button>
            </div>

            <!-- Recommendations -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommandations</h3>
                
                <div class="space-y-4">
                    @foreach($recommendations as $content)
                    <a href="{{ route('contenus.show', $content) }}" class="block group">
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden">
                                @if($content->medias->first())
                                <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                                     alt="{{ $content->titre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                @else
                                <div class="w-full h-full bg-gradient-to-r from-cultural-100 to-cultural-200 flex items-center justify-center">
                                    <span class="text-cultural-800 font-display text-lg">{{ substr($content->titre, 0, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-gray-900 group-hover:text-cultural-600 line-clamp-2">{{ $content->titre }}</div>
                                <div class="text-sm text-gray-500">{{ $content->type->nom_contenu ?? 'Contenu' }}</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <a href="{{ route('contenus.index') }}" 
                   class="block w-full text-center mt-4 px-4 py-2 text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                    Voir plus de recommandations
                </a>
            </div>

            <!-- Notifications -->
            <div class="bg-white rounded-xl shadow-soft p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    <a href="{{ route('notifications.index') }}" class="text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                    <div class="p-3 rounded-lg {{ !$notification->est_lue ? 'bg-blue-50' : 'bg-gray-50' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-full {{ $notification->type_notification == 'content' ? 'bg-blue-100' : 'bg-green-100' }} flex items-center justify-center">
                                    @if($notification->type_notification == 'content')
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="text-sm font-medium text-gray-900">{{ $notification->titre }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $notification->date_creation->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($notifications->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Aucune notification</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set reading goals
    function setReadingGoals() {
        const monthlyGoal = prompt('Objectif mensuel (nombre de contenus) :', '{{ $goals["monthly_goal"] ?? 10 }}');
        const weeklyGoal = prompt('Objectif hebdomadaire (minutes) :', '{{ $goals["weekly_goal"] ?? 180 }}');
        
        if (monthlyGoal && weeklyGoal) {
            fetch('/api/dashboard/goals', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    monthly_goal: parseInt(monthlyGoal),
                    weekly_goal: parseInt(weeklyGoal)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    window.location.reload();
                }
            });
        }
    }
    
    // Mark notification as read
    function markNotificationAsRead(notificationId) {
        fetch(`/api/notifications/${notificationId}/read`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
    
    // Initialize charts
    document.addEventListener('DOMContentLoaded', function() {
        // You can add Chart.js here for visualizations
        console.log('Dashboard loaded');
        
        // Mark notifications as read when viewed
        document.querySelectorAll('.bg-blue-50').forEach(notification => {
            // This would require notification IDs in the HTML
        });
    });
</script>
@endpush
@endsection