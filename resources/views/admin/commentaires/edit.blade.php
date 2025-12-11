@extends('admin.layout')

@section('Content')

<style>
    .star-rating input { display:none; }
    .star-rating label {
        font-size: 1.8rem;
        color: #ccc;
        cursor: pointer;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f7d106;
    }
</style>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-warning">
        <h3 class="m-0">Éditer le Commentaire</h3>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.commentaires.update', $commentaire->id_commentaire) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Texte --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Texte</label>
                <textarea name="texte" class="form-control" rows="4">{{ old('texte', $commentaire->texte) }}</textarea>
            </div>

            {{-- Note --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Note</label>
                <div class="star-rating d-flex flex-row-reverse justify-content-start">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{$i}}" name="note" value="{{$i}}" {{ $commentaire->note == $i ? 'checked' : '' }}>
                        <label for="star{{$i}}">★</label>
                    @endfor
                </div>
            </div>

            {{-- Date --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Date</label>
                <input type="datetime-local" name="date" class="form-control" value="{{ old('date', $commentaire->date) }}">
            </div>

            {{-- Utilisateur --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Utilisateur</label>
                <select name="id_utilisateur" class="form-select">
                    @foreach($utilisateurs as $user)
                        <option value="{{ $user->id_utilisateur }}" {{ $user->id_utilisateur == $commentaire->id_utilisateur ? 'selected' : '' }}>
                            {{ $user->nom }} {{ $user->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Contenu --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Contenu</label>
                <select name="id_contenu" class="form-select">
                    @foreach($contenus as $contenu)
                        <option value="{{ $contenu->id_contenu }}" {{ $contenu->id_contenu == $commentaire->id_contenu ? 'selected' : '' }}>
                            {{ $contenu->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-warning px-4">Mettre à jour</button>
            <a href="{{ route('admin.commentaires.index') }}" class="btn btn-secondary px-4">Annuler</a>

        </form>

    </div>
</div>

@endsection
