@extends('auteur.layout')

@section('Content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Détails du média</h3>
                <div>
                    <a href="{{ route('auteur.medias.edit', $media->id_media) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <a href="{{ route('auteur.medias.index') }}" class="btn btn-sm btn-secondary">Retour</a>
                </div>
            </div>

            <p><strong>ID :</strong> {{ $media->id_media }}</p>
            <p><strong>Fichier :</strong>
                @if($media->chemin)
                    <a href="{{ Storage::disk('public')->url($media->chemin) }}" target="_blank">
                        {{ basename($media->chemin) }}
                    </a>
                @else
                    —
                @endif
            </p>

            <p><strong>Type :</strong> {{ $media->typeMedia->nom_media ?? '—' }}</p>
            <p><strong>Contenu :</strong> {{ $media->contenu->titre ?? '—' }}</p>
            <p><strong>Langue :</strong> {{ $media->langue->nom_langue ?? '—' }}</p>
        </div>
    </div>
</div>
@endsection
