@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i> Modifier la région
            </h5>
        </div>

        <form action="{{ route('admin.regions.update', $region->id_region) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Nom de la région</label>
                    <input type="text" name="nom_region" class="form-control" value="{{ $region->nom_region }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ $region->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Population</label>
                    <input type="number" name="population" class="form-control" value="{{ $region->population }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Superficie</label>
                    <input type="text" name="superficie" class="form-control" value="{{ $region->superficie }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Localisation</label>
                    <input type="text" name="localisation" class="form-control" value="{{ $region->localisation }}">
                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>

                <button class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
