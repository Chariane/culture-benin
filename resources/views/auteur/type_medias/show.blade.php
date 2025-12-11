@extends('auteur.layout')

@section('Content')
<div class="container mt-4">
    <h2 class="mb-3">Détails du type de média</h2>

    <div class="card p-4 shadow-sm">
        <p><strong>ID :</strong> {{ $type->id_type_media }}</p>
        <p><strong>Nom du média :</strong> {{ $type->nom_media }}</p>
    </div>

    <a href="{{ route('auteur.type_medias.index') }}" class="btn btn-secondary mt-3">
        Retour
    </a>
</div>
@endsection
