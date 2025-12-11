@extends('admin.layout')

@section('Content')
<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Liste des régions</h3>
                    <a href="{{ route('admin.regions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</a>
                </div>

                <div class="card-body">
                    <table id="regions-table" class="table table-bordered datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Population</th>
                                <th>Superficie</th>
                                <th>Localisation</th>
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
    $('#regions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.regions.data") }}',
        columns: [
            { data: 'id_region' },
            { data: 'nom_region' },
            { data: 'description' },
            { data: 'population' },
            { data: 'superficie' },
            { data: 'localisation' },
            { data: 'actions', orderable: false, searchable: false, className : "text-center" },
        ],
        language: { url: datatablesFrUrl }
    });
});
</script>
@endpush

