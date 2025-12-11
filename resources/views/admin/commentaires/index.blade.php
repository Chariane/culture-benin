@extends('admin.layout')

@section('Content')

<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
    <h1 class="fw-bold">Commentaires</h1>
    <a href="{{ route('admin.commentaires.create') }}" class="btn btn-success px-4">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table id="commentaires-table" class="table table-striped table-hover align-middle datatable">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Texte</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Contenu</th>
                    <th class="text-center no-sort">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection
@push('scripts')
        <script>
        $(document).ready(function () {

            $('#commentaires-table').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('admin.commentaires.data') }}",

                columns: [
                    { data: 'id_commentaire', name: 'id_commentaire' },
                    { data: 'texte', name: 'texte' },
                    { data: 'note', name: 'note' },
                    { data: 'date', name: 'date' },
                    { data: 'utilisateur', name: 'utilisateur.nom', defaultContent: '' },
                    { data: 'contenu', name: 'contenu.titre', defaultContent: '' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
                ],

                pageLength: 10,
                language: { url: datatablesFrUrl }
            });

        });
        </script>
@endpush
    
