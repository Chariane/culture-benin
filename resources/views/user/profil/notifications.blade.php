@extends('layouts.app')

@section('title', 'Notifications - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Notifications</h1>
        <p class="text-gray-600">Gérez vos notifications et préférences</p>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Sidebar (identique aux autres pages) -->
        @include('user.profil.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-soft">
                <!-- En-tête des notifications -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">Toutes les notifications</h2>
                        <div class="flex items-center space-x-4">
                            <button onclick="markAllAsRead()" 
                                    class="text-sm text-benin-600 hover:text-benin-700 font-medium">
                                <i class="fas fa-check-double mr-1"></i> Tout marquer comme lu
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Liste des notifications -->
                <div class="divide-y divide-gray-100">
                    @forelse($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150 
                              {{ is_null($notification->date_lu) ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start">
                            <!-- Icône de notification -->
                            <div class="flex-shrink-0 mr-4">
                                @switch($notification->type)
                                    @case('nouveau_contenu')
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-book text-green-600"></i>
                                        </div>
                                        @break
                                    @case('commentaire')
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-comment text-blue-600"></i>
                                        </div>
                                        @break
                                    @case('like')
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-heart text-red-600"></i>
                                        </div>
                                        @break
                                    @case('achat')
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-shopping-cart text-purple-600"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-bell text-gray-600"></i>
                                        </div>
                                @endswitch
                            </div>
                            
                            <!-- Contenu de la notification -->
                            <div class="flex-grow">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-gray-900 font-medium">
                                            {{ $notification->titre }}
                                        </h3>
                                        <p class="text-gray-600 mt-1">
                                            {{ $notification->contenu }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $notification->date->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @if(is_null($notification->date_lu))
                                        <button onclick="markAsRead('{{ $notification->id_notification }}')" 
                                                class="p-2 text-gray-400 hover:text-benin-600 rounded-full hover:bg-gray-100"
                                                title="Marquer comme lu">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                        @endif
                                        
                                        <button onclick="deleteNotification('{{ $notification->id_notification }}')" 
                                                class="p-2 text-gray-400 hover:text-red-600 rounded-full hover:bg-gray-100"
                                                title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-bell-slash text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucune notification pour le moment</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/lire`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer la classe de non-lu
                const notificationElement = document.querySelector(`[data-notification="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.classList.remove('bg-blue-50');
                }
                
                // Mettre à jour l'icône
                const button = event.target.closest('button');
                if (button) {
                    button.remove();
                }
            }
        });
    }
    
    function markAllAsRead() {
        if (!confirm('Marquer toutes les notifications comme lues ?')) {
            return;
        }
        
        fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer tous les fonds bleus et icônes de marquage
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-blue-50');
                    const markButton = item.querySelector('.mark-read-button');
                    if (markButton) {
                        markButton.remove();
                    }
                });
                
                alert('Toutes les notifications ont été marquées comme lues');
            }
        });
    }
    
    function deleteNotification(notificationId) {
        if (!confirm('Supprimer cette notification ?')) {
            return;
        }
        
        fetch(`/api/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément du DOM
                const notificationElement = document.querySelector(`[data-notification="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.remove();
                }
            }
        });
    }
</script>
@endpush