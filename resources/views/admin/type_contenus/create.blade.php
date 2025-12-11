@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Type de Contenu</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.type_contenus.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom du type</label>
                <input type="text" name="nom_contenu" class="form-control" value="{{ old('nom_contenu') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.type_contenus.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
