@extends('admin.layout')

@section('Content')

<div class="card shadow-sm mt-4">
    <div class="card-header bg-info text-white">
        <h3 class="m-0">Détails du Commentaire</h3>
    </div>

    <div class="card-body">

        <p><strong>ID :</strong> {{ $commentaire->id_commentaire }}</p>

        <p><strong>Texte :</strong><br>
            <span class="d-block bg-light border p-3 rounded">{{ $commentaire->texte }}</span>
        </p>

        <p><strong>Note :</strong><br>
            @for ($i = 1; $i <= 5; $i++)
                <span class="{{ $i <= $commentaire->note ? 'text-warning' : 'text-secondary' }}" style="font-size:1.6rem;">★</span>
            @endfor
        </p>

        <p><strong>Date :</strong> {{ $commentaire->date }}</p>

        <p><strong>Utilisateur :</strong> {{ $commentaire->utilisateur->nom }}</p>
        <p><strong>Contenu :</strong> {{ $commentaire->contenu->titre }}</p>

        <a href="{{ route('admin.commentaires.index') }}" class="btn btn-secondary px-4">Retour</a>

    </div>
</div>

@endsection
