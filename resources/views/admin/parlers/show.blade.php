@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-eye"></i> Détails — Parler
            </h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Région</th>
                    <td>{{ $parler->region->nom_region }}</td>
                </tr>
                <tr>
                    <th>Langue</th>
                    <td>{{ $parler->langue->nom_langue }}</td>
                </tr>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.parler.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

</div>
@endsection
