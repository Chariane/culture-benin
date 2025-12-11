@extends('layouts.app')

@section('title', 'Contenus de la région : ' . $region->nom_region)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-cinzel font-bold text-gray-900 mb-2">
            Contenus de la région : <span class="text-cultural-600">{{ $region->nom_region }}</span>
        </h1>
        <p class="text-gray-600">
            {{ $contents->total() }} contenus culturels trouvés
        </p>
    </div>

    <!-- Liste des contenus -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($contents as $content)
        <div class="bg-white rounded-xl shadow-soft overflow-hidden hover-lift">
            @if($content->medias->first())
            <div class="h-48 overflow-hidden">
                <img src="{{ Storage::url($content->medias->first()->chemin) }}" 
                     alt="{{ $content->titre }}"
                     class="w-full h-full object-cover">
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="badge badge-cultural">
                        {{ $content->type->nom_contenu ?? 'Article' }}
                    </span>
                    @if($content->premium)
                    <span class="badge badge-premium">
                        <i class="fas fa-crown mr-1"></i> Premium
                    </span>
                    @endif
                </div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    <a href="{{ route('contenus.show', $content) }}" class="hover:text-cultural-600">
                        {{ $content->titre }}
                    </a>
                </h3>
                
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                    {{ Str::limit(strip_tags($content->texte), 120) }}
                </p>
                
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ $content->date_creation->format('d/m/Y') }}
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-eye mr-1"></i>
                        {{ $content->views_count ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-search"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun contenu trouvé</h3>
            <p class="text-gray-600">
                Aucun contenu n'est disponible pour cette région pour le moment.
            </p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($contents->hasPages())
    <div class="mt-8">
        {{ $contents->links() }}
    </div>
    @endif
</div>
@endsection