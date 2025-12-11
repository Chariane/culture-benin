@extends('admin.layout')

@section('Content')

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Modifier la langue</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.langues.index') }}">Langues</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h3 class="card-title">Éditer la langue</h3>
                </div>

                <form action="{{ route('admin.langues.update', $langue->id_langue) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom de la langue</label>
                            <input type="text" name="nom_langue" class="form-control"
                                   value="{{ old('nom_langue', $langue->nom_langue) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Code de la langue</label>
                            <input type="text" name="code_langue" class="form-control"
                                   value="{{ old('code_langue', $langue->code_langue) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control">{{ old('description', $langue->description) }}</textarea>
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.langues.index') }}" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-warning text-dark">Mettre à jour</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</main>

@endsection
