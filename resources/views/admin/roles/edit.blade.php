@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h3>Éditer le Role</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nom du rôle</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $role->nom) }}" required>
            </div>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
