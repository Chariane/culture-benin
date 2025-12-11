@extends('layouts.app')

@section('title', 'Tableau de bord - CultureBénin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-cinzel font-bold text-gray-900 dark:text-white mb-8">
        Tableau de bord
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Statistiques -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-benin-100 dark:bg-benin-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-heart text-benin-600 dark:text-benin-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ auth()->user()->favoris()->count() ?? 0 }}
                    </div>
                    <div class="text-gray-600 dark:text-gray-400">Favoris</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-beninYellow-100 dark:bg-beninYellow-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-eye text-beninYellow-600 dark:text-beninYellow-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ auth()->user()->views()->count() ?? 0 }}
                    </div>
                    <div class="text-gray-600 dark:text-gray-400">Vues</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl bg-beninRed-100 dark:bg-beninRed-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-comment text-beninRed-600 dark:text-beninRed-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ auth()->user()->commentaires()->count() ?? 0 }}
                    </div>
                    <div class="text-gray-600 dark:text-gray-400">Commentaires</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-xl font-cinzel font-bold text-gray-900 dark:text-white mb-4">
            Activités récentes
        </h2>
        <div class="space-y-4">
            <!-- Exemple d'activité -->
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-3">
                        <i class="fas fa-heart text-red-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Vous avez ajouté un favori</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Il y a 2 jours</p>
                    </div>
                </div>
                <a href="#" class="text-benin-500 hover:text-benin-600 dark:text-benin-400">Voir →</a>
            </div>
            <!-- Fin exemple -->
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('historique.index') }}" class="inline-flex items-center px-4 py-2 bg-benin-500 text-white rounded-xl hover:bg-benin-600 transition font-medium">
                Voir tout l'historique
            </a>
        </div>
    </div>
</div>
@endsection