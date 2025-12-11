@extends('admin.layout')

@section('Content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">Liste des langues</h3></div>
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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">LANGUES</h3>
                    <a href="{{ route('admin.langues.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </a>
                </div>

                <div class="card-body">
                    <table id="langues-table" class="table table-bordered table-hover datatable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th class="text-center no-sort">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
@push('scripts')
<script>
$(function () {
    $('#langues-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.langues.data") }}',
        columns: [
            { data: 'id_langue' },
            { data: 'code_langue' },
            { data: 'nom_langue' },
            { data: 'description' },
            { data: 'actions', orderable: false, searchable: false, className : "text-center" },
        ],
        language: { url: datatablesFrUrl }
    });
});
</script>
@endpush
