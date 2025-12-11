@extends('admin.layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Rôles</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table id="roles-table" class="table table-striped table-hover align-middle datatable no-auto">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.roles.data') }}",

        columns: [
            { data: 'id', name: 'id' },
            { data: 'nom', name: 'nom' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],

        language: { url: datatablesFrUrl },
        pageLength: 10
    });

});
</script>
@endpush
