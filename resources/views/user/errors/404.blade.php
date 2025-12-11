@extends('layouts.app')

@section('title', 'Page non trouvée - CultureHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full text-center">
        <div class="mb-8">
            <div class="text-9xl font-bold text-cultural-100 mb-4">404</div>
            <h1 class="text-3xl font-display font-bold text-gray-900 mb-4">Page non trouvée</h1>
            <p class="text-gray-600 mb-8">
                Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
            </p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Retour à l'accueil
            </a>
            
            <div class="pt-8 border-t border-gray-200">
                <p class="text-gray-500 text-sm">
                    Vous pensez qu'il s'agit d'une erreur ? 
                    <a href="mailto:support@culturehub.com" class="text-primary-600 hover:text-primary-700">Contactez-nous</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection