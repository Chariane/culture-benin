@extends('auteur.layout')

@section('Content')
<div class="container mt-4">
    <h2>Détails du type</h2>

    <div class="card p-3">
        <p><strong>ID :</strong> {{ $type->id_type_contenu }}</p>
        <p><strong>Nom :</strong> {{ $type->nom_contenu }}</p>
    </div>

    <a href="{{ route('auteur.type_contenus.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
