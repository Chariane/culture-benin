@extends('admin.layout')

@section('Content')

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Détails de la langue</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.langues.index') }}">Langues</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-info card-outline">

                <div class="card-header">
                    <h3 class="card-title">Informations de la langue</h3>
                </div>

                <div class="card-body">

                    <p><strong>ID :</strong> {{ $langue->id_langue }}</p>
                    <p><strong>Nom :</strong> {{ $langue->nom_langue }}</p>
                    <p><strong>Code :</strong> {{ $langue->code_langue }}</p>
                    <p><strong>Description :</strong> {{ $langue->description }}</p>

                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.langues.index') }}" class="btn btn-secondary">
                        Retour
                    </a>
                </div>

            </div>

        </div>
    </div>

</main>

@endsection
