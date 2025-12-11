@extends('admin.layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Parlers</h1>
    <a href="{{ route('admin.parlers.create') }}" class="btn btn-success px-4">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <table id="parlers-table" class="table table-striped table-hover align-middle datatable no-auto">
            <thead class="table-primary">
                <tr>
                    <th>Région</th>
                    <th>Langue</th>
                    <th class="no-sort text-center">Actions</th>
                </tr>
            </thead>
        </table>

    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#parlers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.parlers.data') }}",

        columns: [
            { data: 'region', name: 'region.nom_region' },
            { data: 'langue', name: 'langue.nom_langue' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: "text-center" },
        ],

        language: {
            url: datatablesFrUrl
        },
        pageLength: 10,
    });
});
</script>
@endpush

