@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-collection"></i> Type de média</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>Nom</th><td>{{ $typeMedia->nom_media }}</td></tr>
                <tr><th>Créé le</th><td>{{ $typeMedia->created_at }}</td></tr>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.type_medias.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <a href="{{ route('admin.type_medias.edit', $typeMedia->id_type_media) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>

    </div>

</div>
@endsection
