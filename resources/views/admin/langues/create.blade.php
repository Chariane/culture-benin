@extends('admin.layout')

@section('Content')

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Ajouter une langue</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Langues</li>
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
                            <div class="card-title">Ajouter une nouvelle langue</div>
                        </div>

                        <form method="POST" action="{{ route('admin.langues.store') }}">
                            @csrf

                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Code de la langue</label>
                                    <input type="text" name="code_langue" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nom de la langue</label>
                                    <input type="text" name="nom_langue" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="reset" class="btn btn-secondary">Annuler</button>
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
