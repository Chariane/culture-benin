@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h3>Éditer le Type de Contenu</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.type_contenus.update', $typeContenu->id_type_contenu) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom du type</label>
                <input type="text" name="nom_contenu" class="form-control"
                       value="{{ old('nom_contenu', $typeContenu->nom_contenu) }}" required>
            </div>

            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="{{ route('admin.type_contenus.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
