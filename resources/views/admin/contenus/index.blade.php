@extends('admin.layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Liste des Contenus</h1>
    <a href="{{ route('admin.contenus.create') }}" class="btn btn-success px-4">
        <i class="fa fa-plus"></i> Nouveau
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <table id="contenus-table" class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Date création</th>
                    <th>Contenu d'origine</th>
                    <th>Date validation</th>
                    <th>Langue</th>
                    <th>Région</th>
                    <th>Auteur</th>
                    <th>Modérateur</th>
                    <th>Type</th>

                    <!-- 🟦 Premium -->
                    <th>Premium</th>

                    <!-- 🟧 Prix -->
                    <th>Prix</th>

                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>

    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#contenus-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.contenus.data') }}",

        columns: [
            { data: 'id_contenu', name: 'id_contenu' },
            { data: 'titre', name: 'titre' },
            { data: 'date_creation', name: 'date_creation' },

            { data: 'parent', name: 'parent', defaultContent: '-' },

            { data: 'date_validation', name: 'date_validation' },

            { data: 'langue', name: 'langue' },
            { data: 'region', name: 'region' },

            { data: 'auteur', name: 'auteur' },
            { data: 'moderateur', name: 'moderateur' },

            { data: 'type', name: 'type' },

            // Badge Premium
            { data: 'premium', name: 'premium', orderable: false, searchable: false },

            // Prix
            { data: 'prix', name: 'prix' },

            { data: 'statut', name: 'statut' },

            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/fr-FR.json"
        }
    });
});
</script>
@endpush
