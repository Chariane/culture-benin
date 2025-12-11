@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Role</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom du rôle</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
