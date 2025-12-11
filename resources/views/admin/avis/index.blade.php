@extends('admin.layout')

@section('Content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="fas fa-comment-alt me-2"></i> Gestion des Avis</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Retour au tableau de bord
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Avis</h5>
            <div class="badge bg-light text-dark fs-6">
                <span id="total-count">0</span> avis
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="avis-table" class="table table-hover table-striped w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Utilisateur</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Les données seront chargées via DataTable AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialisation de DataTable
    var table = $('#avis-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.avis.index') }}",
            type: 'GET'
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '5%'
            },
            {
                data: 'utilisateur_info',
                name: 'utilisateur_info',
                orderable: false,
                searchable: false
            },
            {
                data: 'message_short',
                name: 'message',
                orderable: true,
                searchable: true
            },
            {
                data: 'date_formatted',
                name: 'date',
                orderable: true,
                searchable: false,
                width: '15%'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '10%'
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
        },
        order: [[3, 'desc']], // Trier par date décroissante par défaut
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]],
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
        drawCallback: function(settings) {
            // Mettre à jour le compteur
            var api = this.api();
            $('#total-count').text(api.rows().count());
        },
        initComplete: function() {
            // Ajouter un bouton de suppression multiple si nécessaire
            $('.dataTables_length label').addClass('form-label');
            $('.dataTables_filter label').addClass('form-label');
        }
    });

    // Gestion de la suppression avec confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var confirmMessage = form.data('confirm') || 'Êtes-vous sûr ?';
        
        if (confirm(confirmMessage)) {
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Rafraîchir la table
                        table.draw();
                        // Afficher un message de succès
                        toastr.success(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Une erreur est survenue lors de la suppression.');
                }
            });
        }
    });

    // Ajouter un champ de recherche personnalisé pour le message
    $('#avis-table_filter').addClass('d-flex justify-content-end');
    $('#avis-table_filter input').addClass('form-control form-control-sm w-auto ms-2');
    
    // Styling des boutons de pagination
    $('.dataTables_paginate .paginate_button').addClass('btn btn-sm');
});
</script>
@endsection

@section('styles')
<style>
    #avis-table_wrapper {
        padding: 0;
    }
    
    #avis-table {
        border-collapse: collapse !important;
    }
    
    #avis-table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    #avis-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
    }
    
    .dataTables_length select {
        width: auto;
        display: inline-block;
    }
    
    .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
    
    /* Style pour les boutons de pagination */
    .dataTables_paginate .paginate_button {
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .dataTables_paginate .paginate_button.current {
        background: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        border-color: #dee2e6 !important;
    }
    
    /* Style pour le message dans le tableau */
    .text-truncate {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_filter {
            float: none !important;
            text-align: left !important;
            margin-top: 10px;
        }
        
        .dataTables_wrapper .dataTables_length {
            float: none !important;
        }
    }
</style>
@endsection