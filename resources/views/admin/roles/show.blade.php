@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Détails du rôle</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>Nom du rôle</th><td>{{ $role->nom }}</td></tr>
                <tr><th>Créé le</th><td>{{ $role->created_at }}</td></tr>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>

    </div>

</div>
@endsection
