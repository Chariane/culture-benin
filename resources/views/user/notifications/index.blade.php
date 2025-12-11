@extends('layouts.app')

@section('title', 'Notifications - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Notifications</h1>
                <p class="text-gray-600">Restez informé de vos activités</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($unreadCount > 0)
                <button onclick="markAllAsRead()" 
                        class="flex items-center px-4 py-2 text-gray-700 hover:text-cultural-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tout marquer comme lu
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $unreadCount }}</div>
                    <div class="text-blue-100">Non lues</div>
                </div>
                <svg class="w-12 h-12 text-blue-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-r from-cultural-500 to-cultural-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold mb-2">{{ $notifications->total() }}</div>
                    <div class="text-cultural-100">Total</div>
                </div>
                <svg class="w-12 h-12 text-cultural-200 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('all')" 
                        id="all-tab"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-cultural-500 text-cultural-600">
                    Toutes
                </button>
                <button onclick="showTab('unread')" 
                        id="unread-tab"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Non lues
                </button>
                <button onclick="showTab('system')" 
                        id="system-tab"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Système
                </button>
                <button onclick="showTab('social')" 
                        id="social-tab"
                        class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Social
                </button>
            </nav>
        </div>
    </div>

    <!-- Notifications List -->
    <div id="all-content" class="tab-content active">
        @if($notifications->count() > 0)
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->est_lue ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $notification->type_notification == 'system' ? 'bg-blue-100 text-blue-600' : 
                                   ($notification->type_notification == 'social' ? 'bg-green-100 text-green-600' : 
                                   ($notification->type_notification == 'content' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600')) }}">
                                @if($notification->type_notification == 'system')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @elseif($notification->type_notification == 'social')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                @elseif($notification->type_notification == 'content')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $notification->titre }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <div class="text-sm text-gray-500 mt-2">
                                        {{ $notification->date_creation->diffForHumans() }}
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    @if(!$notification->est_lue)
                                    <button onclick="markAsRead('{{ $notification->id_notification }}')" 
                                            class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="Marquer comme lu">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    @endif
                                    
                                    <button onclick="deleteNotification('{{ $notification->id_notification }}')" 
                                            class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            @if($notification->lien)
                            <div class="mt-4">
                                <a href="{{ $notification->lien }}" 
                                   class="inline-flex items-center text-cultural-600 hover:text-cultural-700 text-sm font-medium">
                                    Voir plus
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-6 text-gray-300">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune notification</h3>
            <p class="text-gray-600">Vous n'avez aucune notification pour le moment</p>
        </div>
        @endif
    </div>

    <!-- Unread Tab Content -->
    <div id="unread-content" class="tab-content hidden">
        <!-- Content will be loaded via AJAX -->
        <div id="unread-list"></div>
    </div>

    <!-- Settings -->
    <div class="mt-8 bg-white rounded-xl shadow-soft p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Préférences de notification</h2>
        
        <form id="notification-preferences">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Types de notifications</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="new_content" {{ $preferences['new_content'] ?? true ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Nouveaux contenus</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="comments" {{ $preferences['comments'] ?? true ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Commentaires</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="likes" {{ $preferences['likes'] ?? true ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Likes</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="messages" {{ $preferences['messages'] ?? true ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Messages</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Modes de réception</h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="email_notifications" {{ $preferences['email_notifications'] ?? true ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Notifications par email</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="push_notifications" {{ $preferences['push_notifications'] ?? true ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Notifications push</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="marketing" {{ $preferences['marketing'] ?? false ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-cultural-600 focus:ring-cultural-500">
                            <span class="ml-2 text-gray-700">Communications marketing</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                    Enregistrer les préférences
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    function showTab(tabName) {
        // Update active tab
        document.querySelectorAll('[id$="-tab"]').forEach(tab => {
            tab.classList.remove('border-cultural-500', 'text-cultural-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
        
        document.getElementById(tabName + '-tab').classList.add('border-cultural-500', 'text-cultural-600');
        document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500');
        
        // Update active content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
            content.classList.add('hidden');
        });
        
        document.getElementById(tabName + '-content').classList.add('active');
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Load unread notifications via AJAX
        if (tabName === 'unread') {
            loadUnreadNotifications();
        }
    }
    
    // Mark as read
    function markAsRead(notificationId) {
        fetch(`/api/notifications/${notificationId}/read`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            // Remove blue background
            const notificationElement = document.querySelector(`[data-notification="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('bg-blue-50');
            }
            
            // Update unread count
            updateUnreadCount();
        });
    }
    
    // Mark all as read
    function markAllAsRead() {
        fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            // Remove blue background from all notifications
            document.querySelectorAll('.bg-blue-50').forEach(el => {
                el.classList.remove('bg-blue-50');
            });
            
            // Update unread count
            updateUnreadCount();
        });
    }
    
    // Delete notification
    function deleteNotification(notificationId) {
        if (confirm('Supprimer cette notification ?')) {
            fetch(`/api/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                // Remove notification element
                const notificationElement = document.querySelector(`[data-notification="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.remove();
                }
                
                // Update unread count
                updateUnreadCount();
            });
        }
    }
    
    // Load unread notifications
    function loadUnreadNotifications() {
        fetch('/api/notifications/unread')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('unread-list');
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-6 text-gray-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune notification non lue</h3>
                        <p class="text-gray-600">Vous avez lu toutes vos notifications</p>
                    </div>
                `;
            } else {
                let html = '<div class="space-y-4">';
                data.forEach(notification => {
                    html += `
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-900">${notification.titre}</h4>
                                <p class="text-gray-600 text-sm mt-1">${notification.message}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="markAsRead('${notification.id}')" class="p-2 text-gray-400 hover:text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <button onclick="deleteNotification('${notification.id}')" class="p-2 text-gray-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            }
        });
    }
    
    // Update unread count
    function updateUnreadCount() {
        fetch('/api/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            // Update badge in navbar
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    }
    
    // Save preferences
    document.getElementById('notification-preferences').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/api/notifications/preferences', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert('Préférences enregistrées avec succès !');
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
@endpush
@endsection