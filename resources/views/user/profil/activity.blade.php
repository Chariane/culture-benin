@extends('layouts.app')

@section('title', 'Activité - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Mon activité</h1>
        <p class="text-gray-600">Historique de vos actions sur la plateforme</p>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Sidebar (identique aux autres pages) -->
        @include('user.profil.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <!-- Onglets -->
            <div class="mb-6">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button onclick="showTab('views')" 
                            id="tab-views"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-lg bg-benin-100 text-benin-700">
                        <i class="fas fa-eye mr-2"></i> Vues
                    </button>
                    <button onclick="showTab('comments')" 
                            id="tab-comments"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-lg text-gray-500 hover:text-gray-700">
                        <i class="fas fa-comment mr-2"></i> Commentaires
                    </button>
                    <button onclick="showTab('likes')" 
                            id="tab-likes"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-lg text-gray-500 hover:text-gray-700">
                        <i class="fas fa-heart mr-2"></i> Likes
                    </button>
                    <button onclick="showTab('purchases')" 
                            id="tab-purchases"
                            class="tab-button px-4 py-2 font-medium text-sm rounded-lg text-gray-500 hover:text-gray-700">
                        <i class="fas fa-shopping-cart mr-2"></i> Achats
                    </button>
                </nav>
            </div>
            
            <!-- Contenu des onglets -->
            <div id="tab-content">
                <!-- Vues -->
                <div id="views-content" class="tab-pane active">
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Contenus récemment consultés</h2>
                        
                        @forelse($views as $view)
                        <div class="flex items-start p-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                <i class="fas fa-eye text-blue-600"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('contenus.show', $view->contenu) }}" 
                                           class="font-medium text-gray-900 hover:text-benin-600">
                                            {{ $view->contenu->titre ?? 'Contenu supprimé' }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Consulté {{ $view->date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-eye-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Aucun contenu consulté récemment</p>
                        </div>
                        @endforelse
                        
                        {{ $views->links() }}
                    </div>
                </div>
                
                <!-- Commentaires -->
                <div id="comments-content" class="tab-pane hidden">
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Commentaires récents</h2>
                        
                        @forelse($comments as $comment)
                        <div class="flex items-start p-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                <i class="fas fa-comment text-green-600"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('contenus.show', $comment->contenu) }}" 
                                           class="font-medium text-gray-900 hover:text-benin-600">
                                            {{ $comment->contenu->titre ?? 'Contenu supprimé' }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Commenté {{ $comment->date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <p class="mt-2 text-gray-700 bg-gray-50 p-3 rounded-lg">
                                    {{ Str::limit($comment->contenu, 200) }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-comment-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Aucun commentaire récent</p>
                        </div>
                        @endforelse
                        
                        {{ $comments->links() }}
                    </div>
                </div>
                
                <!-- Likes -->
                <div id="likes-content" class="tab-pane hidden">
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Contenus aimés</h2>
                        
                        @forelse($likes as $like)
                        <div class="flex items-start p-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                                <i class="fas fa-heart text-red-600"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('contenus.show', $like->contenu) }}" 
                                           class="font-medium text-gray-900 hover:text-benin-600">
                                            {{ $like->contenu->titre ?? 'Contenu supprimé' }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Aimé {{ $like->date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-heart-broken text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Aucun contenu aimé récemment</p>
                        </div>
                        @endforelse
                        
                        {{ $likes->links() }}
                    </div>
                </div>
                
                <!-- Achats -->
                <div id="purchases-content" class="tab-pane hidden">
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Achats récents</h2>
                        
                        @forelse($purchases as $purchase)
                        <div class="flex items-start p-4 border-b border-gray-100 last:border-b-0">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                                <i class="fas fa-shopping-cart text-purple-600"></i>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('contenus.show', $purchase->contenu) }}" 
                                           class="font-medium text-gray-900 hover:text-benin-600">
                                            {{ $purchase->contenu->titre ?? 'Contenu supprimé' }}
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Acheté {{ $purchase->date_paiement->diffForHumans() }} • 
                                            {{ number_format($purchase->montant, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Payé
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Aucun achat récent</p>
                        </div>
                        @endforelse
                        
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(tabName) {
        // Masquer tous les onglets
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
            pane.classList.add('hidden');
        });
        
        // Désactiver tous les boutons d'onglets
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('bg-benin-100', 'text-benin-700');
            button.classList.add('text-gray-500', 'hover:text-gray-700');
        });
        
        // Afficher l'onglet sélectionné
        const activePane = document.getElementById(tabName + '-content');
        const activeButton = document.getElementById('tab-' + tabName);
        
        activePane.classList.remove('hidden');
        activePane.classList.add('active');
        activeButton.classList.remove('text-gray-500', 'hover:text-gray-700');
        activeButton.classList.add('bg-benin-100', 'text-benin-700');
    }
    
    // Afficher l'onglet par défaut
    document.addEventListener('DOMContentLoaded', function() {
        showTab('views');
    });
</script>

<style>
    .tab-pane.active {
        display: block;
    }
</style>
@endpush