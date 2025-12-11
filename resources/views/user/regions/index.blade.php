{{-- regions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Régions - CultureHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero -->
    <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
            Découvrez par région
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Explorez la richesse culturelle à travers les différentes régions du Bénin
        </p>
    </div>

    <!-- Regions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($regions as $region)
        <div class="group relative rounded-2xl overflow-hidden shadow-lg hover-lift">
            <div class="h-64 relative">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent z-10"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/60 to-purple-600/60 group-hover:opacity-90 transition-opacity"></div>
                <div class="absolute inset-0 flex items-center justify-center z-20">
                    <h3 class="text-3xl font-display font-bold text-white text-center px-4">{{ $region->nom_region }}</h3>
                </div>
            </div>
            <div class="p-6 bg-white">
                <p class="text-gray-600 mb-4">{{ Str::limit($region->description, 120) }}</p>
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $region->contenus_count ?? 0 }} contenus
                        </div>
                    </div>
                    <a href="{{ route('regions.show', $region) }}" 
                       class="px-4 py-2 bg-cultural-500 text-white rounded-lg hover:bg-cultural-600 transition-colors">
                        Explorer
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection