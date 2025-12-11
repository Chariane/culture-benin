@extends('admin.layout')

@section('Content')

<style>
    .star-rating input { display:none; }
    .star-rating label {
        font-size: 1.8rem;
        color: #ccc;
        cursor: pointer;
        transition: 0.2s;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f7d106;
    }
</style>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-success text-white">
        <h3 class="m-0">Créer un Commentaire</h3>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.commentaires.store') }}" method="POST">
            @csrf

            {{-- Texte --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Texte</label>
                <textarea name="texte" class="form-control" rows="4">{{ old('texte') }}</textarea>
            </div>

            {{-- Note en étoiles --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Note</label>

                <div class="star-rating d-flex flex-row-reverse justify-content-start">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{$i}}" name="note" value="{{$i}}" {{ old('note') == $i ? 'checked' : '' }}>
                        <label for="star{{$i}}">★</label>
                    @endfor
                </div>
            </div>

            {{-- Date (CORRECTION DÉFINITIVE) --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Date</label>
                <input 
                    type="datetime-local" 
                    name="date" 
                    class="form-control"
                    value="{{ old('date') ? \Carbon\Carbon::parse(old('date'))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                >
            </div>

            {{-- Utilisateur --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Utilisateur</label>
                <select name="id_utilisateur" class="form-select">
                    @foreach($utilisateurs as $user)
                        <option value="{{ $user->id_utilisateur }}">
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
                        <option value="{{ $contenu->id_contenu }}">
                            {{ $contenu->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success px-4">Créer</button>
                <a href="{{ route('admin.commentaires.index') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>

        </form>
    </div>
</div>
@endsection
