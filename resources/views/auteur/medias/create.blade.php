@extends('auteur.layout')

@section('Content')
<div class="container mt-4">

    <h2 class="fw-bold mb-3">Ajouter un média</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('auteur.medias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Fichier</label>
                        <input type="file" name="chemin" class="form-control @error('chemin') is-invalid @enderror" required>
                        @error('chemin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">
                            Formats acceptés : image, audio, vidéo, PDF — max 50MB
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contenu lié</label>
                        <select name="id_contenu" class="form-select @error('id_contenu') is-invalid @enderror" required>
                            <option value="">— Choisir un contenu —</option>
                            @foreach($contenus as $c)
                                <option value="{{ $c->id_contenu }}">{{ $c->titre }}</option>
                            @endforeach
                        </select>
                        @error('id_contenu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type de média</label>
                        <select name="id_type_media" class="form-select @error('id_type_media') is-invalid @enderror" required>
                            <option value="">— Choisir —</option>
                            @foreach($types as $t)
                                <option value="{{ $t->id_type_media }}">{{ $t->nom_media }}</option>
                            @endforeach
                        </select>
                        @error('id_type_media') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Langue</label>
                        <select name="id_langue" class="form-select @error('id_langue') is-invalid @enderror" required>
                            <option value="">— Choisir —</option>
                            @foreach($langues as $l)
                                <option value="{{ $l->id_langue }}">{{ $l->nom_langue }}</option>
                            @endforeach
                        </select>
                        @error('id_langue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer</button>
                        <a href="{{ route('auteur.medias.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>
@endsection
