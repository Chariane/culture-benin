@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h3>Modifier le Média</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.medias.update', $media->id_media) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-bold">Fichier actuel</label>
                @if($media->chemin)
                    <p>
                        <a href="{{ asset('storage/'.$media->chemin) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            Voir le fichier
                        </a>
                    </p>
                @else
                    <p class="text-muted">Aucun fichier enregistré</p>
                @endif

                <label class="form-label fw-bold">Nouveau fichier</label>
                <input type="file" name="chemin" class="form-control">
                <small class="text-muted">Laisse vide pour conserver le fichier existant</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3">
                    {{ old('description', $media->description) }}
                </textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type de média</label>
                <select name="id_type_media" class="form-select">
                    @foreach($typeMedias as $type)
                        <option value="{{ $type->id_type_media }}"
                            {{ $type->id_type_media == $media->id_type_media ? 'selected' : '' }}>
                            {{ $type->nom_media }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Langue</label>
                <select name="id_langue" class="form-select">
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}"
                            {{ $langue->id_langue == $media->id_langue ? 'selected' : '' }}>
                            {{ $langue->nom_langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Contenu associé</label>
                <select name="id_contenu" class="form-select">
                    @foreach($contenus as $contenu)
                        <option value="{{ $contenu->id_contenu }}"
                            {{ $contenu->id_contenu == $media->id_contenu ? 'selected' : '' }}>
                            {{ $contenu->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex">
                <button type="submit" class="btn btn-warning me-2">Mettre à jour</button>
                <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Annuler</a>
            </div>

        </form>
    </div>
</div>
@endsection
