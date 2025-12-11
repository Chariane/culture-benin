@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h3>Éditer le Type de Media</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.type_medias.update', $typeMedia->id_type_media) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom du type</label>
                <input 
                    type="text" 
                    name="nom_media" 
                    class="form-control" 
                    value="{{ old('nom_media', $typeMedia->nom_media) }}" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="{{ route('admin.type_medias.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
