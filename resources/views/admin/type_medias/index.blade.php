@extends('admin.layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Types de Médias</h1>
    <a href="{{ route('admin.type_medias.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table id="type-medias-table" class="table table-striped table-hover align-middle">
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

    $('#type-medias-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.type_medias.data') }}",

        columns: [
            { data: 'id_type_media', name: 'id_type_media' },
            { data: 'nom_media', name: 'nom_media' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className:'text-center' }
        ],

        language: { url: datatablesFrUrl },
        pageLength: 10
    });

});
</script>
@endpush
