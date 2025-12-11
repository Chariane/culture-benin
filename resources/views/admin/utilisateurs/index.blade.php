@extends('admin.layout')

@section('Content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Utilisateurs</h1>
    <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-success">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

<div class="table-responsive">

<table id="utilisateurs-table" class="table table-striped table-hover align-middle datatable no-auto">
    <thead class="table-primary">
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Nom(s)</th>
            <th>Prénom(s)</th>
            <th>Email</th>
            <th>Sexe</th>
            <th>Date inscription</th>
            <th>Statut</th>
            <th>Rôle</th>
            <th>Langue</th>
            <th class="text-center no-sort">Actions</th>
        </tr>
    </thead>
</table>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    $('#utilisateurs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.utilisateurs.data') }}",

        columns: [
            { data: 'id_utilisateur', name: 'id_utilisateur' },

            // 🟦 Colonne PHOTO (avatar ou photo uploadée)
            {
                data: 'photo',
                name: 'photo',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {

                    // Par défaut → avatar homme
                    let avatar = "/male.jpg";

                    // Décision selon sexe (en minuscule)
                    if (row.sexe && row.sexe.toLowerCase() === "femme") {
                        avatar = "/female.jpg";
                    }

                    // Si l'utilisateur a une vraie photo enregistrée
                    if (data) {
                        return `
                            <img src="/storage/${data}" 
                                 class="rounded-circle shadow-sm" 
                                 width="40" height="40">
                        `;
                    }

                    // Sinon → avatar par défaut
                    return `
                        <img src="${avatar}" 
                             class="rounded-circle shadow-sm" 
                             width="40" height="40">
                    `;
                }
            },

            { data: 'nom', name: 'nom' },
            { data: 'prenom', name: 'prenom' },
            { data: 'email', name: 'email' },
            { data: 'sexe', name: 'sexe' },
            { data: 'date_inscription', name: 'date_inscription' },
            { data: 'statut', name: 'statut' },
            { data: 'role', name: 'role.nom' },
            { data: 'langue', name: 'langue.nom_langue' },

            {
                data: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],

        // Langue FR
        language: { url: datatablesFrUrl },
        pageLength: 10
    });

});
</script>
@endpush
