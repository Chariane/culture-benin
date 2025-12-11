@extends('auteur.layout')

@section('Content')
<div class="container mt-4">
    <h2 class="mb-4">Liste des types de contenus</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Ajouter un type
    </button>

    <table class="table table-bordered" id="typesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@include('auteur.type_contenus.create') <!-- 🔥 AJOUT IMPORTANT -->

@push('scripts')
<script>
$(function () {
    $('#typesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('auteur.type_contenus.index') }}",
        columns: [
            { data: 'id_type_contenu', name: 'id_type_contenu' },
            { data: 'nom_contenu', name: 'nom_contenu' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json",
            error: function(xhr, status, error) {
                console.log("Erreur AJAX :", xhr.responseText);
            },
        }
    });
});
</script>
@endpush