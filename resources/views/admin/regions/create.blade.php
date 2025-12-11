@extends('admin.layout')

@section('Content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Ajouter une nouvelle région</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.regions.index') }}">Régions</a></li>
                        <li class="breadcrumb-item active">Créer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row g-4">
                <div class="col-md-6">

                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Formulaire de création</div>
                        </div>

                        <form method="POST" action="{{ route('admin.regions.store') }}">
                            @csrf

                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Nom de la région</label>
                                    <input type="text" name="nom_region" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Population</label>
                                    <input type="number" name="population" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Superficie (km²)</label>
                                    <input type="number" step="0.01" name="superficie" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Localisation</label>
                                    <input type="text" name="localisation" class="form-control">
                                </div>

                            </div>

                            <div class="card-footer">
                                <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

</main>
@endsection
