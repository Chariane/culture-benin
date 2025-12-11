@extends('admin.layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Types de Contenus</h1>
    <a href="{{ route('admin.type_contenus.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table id="type-contenus-table" class="table table-striped table-hover align-middle datatable no-auto">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th class="text-center no-sort">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
$(document).ready(function () {

    $('#type-contenus-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.type_contenus.data') }}",

        columns: [
            { data: 'id_type_contenu', name: 'id_type_contenu' },
            { data: 'nom_contenu', name: 'nom_contenu' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className:'text-center' }
        ],

        pageLength: 10,
        language: { url: datatablesFrUrl }
    });

});
</script>

@endsection
