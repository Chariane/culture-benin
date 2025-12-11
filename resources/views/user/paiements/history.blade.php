@extends('layouts.app')

@section('title', 'Historique des paiements - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Fil d'Ariane -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-700 hover:text-primary-600">
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
                    <a href="{{ route('dashboard') }}" class="ml-1 text-sm text-gray-700 hover:text-primary-600 md:ml-2">Tableau de bord</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm text-gray-500 md:ml-2">Historique des paiements</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">Historique des paiements</h1>
        <p class="text-gray-600">
            Consultez l'historique de tous vos achats de contenus premium
        </p>
    </div>

    <!-- Tableau des paiements -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($purchases->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contenu
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($purchases as $paiement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $paiement->date_paiement->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $paiement->date_paiement->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($paiement->contenu->medias->first())
                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                    <img class="h-10 w-10 rounded-lg object-cover" 
                                         src="{{ Storage::url($paiement->contenu->medias->first()->chemin) }}" 
                                         alt="{{ $paiement->contenu->titre }}">
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('contenus.show', $paiement->contenu) }}" class="hover:text-primary-600">
                                            {{ $paiement->contenu->titre }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $paiement->contenu->type->nom_contenu ?? 'Article' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} XAF
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $paiement->methode_paiement }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'success' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$paiement->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                @if($paiement->statut == 'success')
                                Payé
                                @elseif($paiement->statut == 'pending')
                                En attente
                                @elseif($paiement->statut == 'failed')
                                Échoué
                                @else
                                {{ $paiement->statut }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('contenus.show', $paiement->contenu) }}" 
                               class="text-primary-600 hover:text-primary-900 mr-4">
                                Voir le contenu
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($purchases->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $purchases->links('vendor.pagination.custom') }}
        </div>
        @endif
        
        @else
        <!-- État vide -->
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-6">
                <div class="w-full h-full rounded-full bg-gradient-to-r from-cultural-100 to-cultural-200 flex items-center justify-center">
                    <svg class="w-12 h-12 text-cultural-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun paiement pour le moment</h3>
            <p class="text-gray-500 mb-6">
                Vous n'avez encore effectué aucun achat de contenu premium
            </p>
            <a href="{{ route('contenus.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Explorer les contenus
            </a>
        </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-cultural-50 to-cultural-100 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-cultural-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Dépenses totales</h3>
                    <p class="text-2xl font-bold text-cultural-800">
                        {{ number_format($purchases->where('statut', 'success')->sum('montant'), 0, ',', ' ') }} XAF
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Achats réussis</h3>
                    <p class="text-2xl font-bold text-green-800">
                        {{ $purchases->where('statut', 'success')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Contenus achetés</h3>
                    <p class="text-2xl font-bold text-blue-800">
                        {{ $purchases->unique('id_contenu')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection