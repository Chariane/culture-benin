@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-geo"></i> Détails de la région</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>Nom</th><td>{{ $region->nom_region }}</td></tr>
                <tr><th>Description</th><td>{{ $region->description }}</td></tr>
                <tr><th>Population</th><td>{{ $region->population }}</td></tr>
                <tr><th>Superficie</th><td>{{ $region->superficie }}</td></tr>
                <tr><th>Localisation</th><td>{{ $region->localisation }}</td></tr>
                <tr><th>Création</th><td>{{ $region->created_at }}</td></tr>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <a href="{{ route('admin.regions.edit', $region->id_region) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>

    </div>

</div>
@endsection
