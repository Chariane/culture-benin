@extends('auteur.layout')

@section('Content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-3">Modifier le média</h2>

            <form action="{{ route('auteur.medias.update', $media->id_media) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Fichier actuel</label>
                    <div>
                        @if($media->chemin)
                            <a href="{{ Storage::disk('public')->url($media->chemin) }}" target="_blank">
                                {{ basename($media->chemin) }}
                            </a>
                        @else
                            —
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remplacer le fichier (optionnel)</label>
                    <input type="file" name="chemin" class="form-control">
                    <div class="form-text">Laisser vide pour garder l'ancien fichier.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenu</label>
                    <select name="id_contenu" class="form-select" required>
                        @foreach($contenus as $c)
                            <option value="{{ $c->id_contenu }}" {{ $c->id_contenu == $media->id_contenu ? 'selected' : '' }}>
                                {{ $c->titre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type de média</label>
                    <select name="id_type_media" class="form-select" required>
                        @foreach($types as $t)
                            <option value="{{ $t->id_type_media }}" {{ $t->id_type_media == $media->id_type_media ? 'selected' : '' }}>
                                {{ $t->nom_media }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Langue</label>
                    <select name="id_langue" class="form-select" required>
                        @foreach($langues as $l)
                            <option value="{{ $l->id_langue }}" {{ $l->id_langue == $media->id_langue ? 'selected' : '' }}>
                                {{ $l->nom_langue }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('auteur.medias.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
