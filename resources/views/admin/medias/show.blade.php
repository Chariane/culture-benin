@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h3>Détails du Média</h3>
    </div>

    <div class="card-body">

        <div class="mb-3">
            <h5 class="fw-bold">ID :</h5>
            <p>{{ $media->id_media }}</p>
        </div>

        <div class="mb-3">
            <h5 class="fw-bold">Fichier :</h5>
            @if($media->chemin)
                <p>
                    <a href="{{ asset('storage/'.$media->chemin) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        Ouvrir le fichier
                    </a>
                </p>
            @else
                <p class="text-muted">Non défini</p>
            @endif
        </div>

        <div class="mb-3">
            <h5 class="fw-bold">Description :</h5>
            <p>{{ $media->description ?? 'Aucune description' }}</p>
        </div>

        <div class="mb-3">
            <h5 class="fw-bold">Type de média :</h5>
            <p>{{ $media->typeMedia->nom_media ?? 'Non défini' }}</p>
        </div>

        <div class="mb-3">
            <h5 class="fw-bold">Contenu associé :</h5>
            <p>{{ $media->contenu->titre ?? 'Aucun contenu lié' }}</p>
        </div>

        <div class="mb-3">
            <h5 class="fw-bold">Langue :</h5>
            <p>{{ $media->langue->nom_langue ?? 'Non défini' }}</p>
        </div>

        <div class="d-flex">
            <a href="{{ route('admin.medias.edit', $media->id_media) }}" class="btn btn-warning me-2">Modifier</a>
            <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Retour</a>
        </div>

    </div>
</div>
@endsection
