@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Type de Media</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.type_medias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom du type</label>
                <input type="text" name="nom_media" class="form-control" value="{{ old('nom_media') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.type_medias.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
