@extends('admin.layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Liste des Médias</h1>
    <a href="{{ route('admin.medias.create') }}" class="btn btn-success px-4">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive mt-3">
            <table id="medias-table" class="table table-striped table-hover align-middle datatable">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Chemin</th>
                        <th>Type</th>
                        <th class="no-sort text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#medias-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.medias.data') }}",
        language: {
            url: datatablesFrUrl
        },
        columns: [
            { data: 'id_media', name: 'id_media' },
            { data: 'chemin', name: 'chemin' },
            { data: 'type', name: 'type_media.nom_media' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        pageLength: 10,
        responsive: true
    });
});
</script>
@endpush
