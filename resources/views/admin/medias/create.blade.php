@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Média</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.medias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                <label class="form-label fw-bold">Fichier</label>
                <input type="file" name="chemin" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type de média</label>
                <select name="id_type_media" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($typeMedias as $type)
                        <option value="{{ $type->id_type_media }}"
                            {{ old('id_type_media') == $type->id_type_media ? 'selected' : '' }}>
                            {{ $type->nom_media }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Langue</label>
                <select name="id_langue" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}"
                            {{ old('id_langue') == $langue->id_langue ? 'selected' : '' }}>
                            {{ $langue->nom_langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Contenu associé</label>
                <select name="id_contenu" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    @foreach($contenus as $contenu)
                        <option value="{{ $contenu->id_contenu }}"
                            {{ old('id_contenu') == $contenu->id_contenu ? 'selected' : '' }}>
                            {{ $contenu->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex">
                <button type="submit" class="btn btn-success me-2">Créer</button>
                <a href="{{ route('admin.medias.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
