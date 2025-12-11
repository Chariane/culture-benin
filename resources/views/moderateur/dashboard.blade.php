@extends('moderateur.layout')

@section('title', 'Dashboard Modérateur')

@section('content')
<div class="main-container">
    <div class="container px-0">

        <!-- Cartes de statistiques -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-warning">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">En attente</small>
                            <div class="h3 fw-bold" id="stat-en-attente">{{ $en_attente }}</div>
                        </div>
                        <div class="text-warning fs-1"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-start border-success">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Validés par moi</small>
                            <div class="h3 fw-bold" id="stat-valides">{{ $valides }}</div>
                        </div>
                        <div class="text-success fs-1"><i class="bi bi-check-circle"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-start border-danger">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Rejetés par moi</small>
                            <div class="h3 fw-bold" id="stat-rejetes">{{ $rejetes }}</div>
                        </div>
                        <div class="text-danger fs-1"><i class="bi bi-x-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenus en attente -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Contenus à valider</h5>
                <small class="text-muted">Cliquez sur "Voir" pour valider ou rejeter</small>
            </div>
            <div class="card-body">
                <div class="table-responsive-wrapper">
                    <table id="enAttenteTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Date création</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Archives -->
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Archive — Validés par moi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-wrapper">
                            <table id="validesTable" class="table table-sm table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date validation</th>
                                        <th>Auteur</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Archive — Rejetés par moi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-wrapper">
                            <table id="mediocresTable" class="table table-sm table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date validation</th>
                                        <th>Auteur</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal de visualisation du contenu -->
<div class="modal fade" id="contenuModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contenuModalTitle">Contenu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <strong>Contenu :</strong>
            <div id="contenuTexte" class="mt-2 p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto;"></div>
        </div>
        <div><strong>Auteur :</strong> <span id="contenuAuteur"></span></div>
        <div><strong>Date création :</strong> <span id="contenuDate"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button id="btnReject" class="btn btn-danger">Marquer Médiocre</button>
        <button id="btnAccept" class="btn btn-success">Marquer Bon</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation des DataTables
    const enAttenteTable = $('#enAttenteTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('moderateur.contenus.en_attente.data') }}",
        columns: [
            { data: 'id_contenu' },
            { data: 'titre' },
            { data: 'auteur' },
            { data: 'date_creation' },
            { data: 'type' },
            { data: 'actions', orderable:false, searchable:false },
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true
    });

    const validesTable = $('#validesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('moderateur.contenus.valides.data') }}",
        columns: [
            { data: 'titre' },
            { data: 'date_validation' },
            { data: 'auteur' },
        ],
        order: [[1, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true
    });

    const mediocresTable = $('#mediocresTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('moderateur.contenus.mediocres.data') }}",
        columns: [
            { data: 'titre' },
            { data: 'date_validation' },
            { data: 'auteur' },
        ],
        order: [[1, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true
    });

    // Fonction pour ouvrir le modal
    window.openContenuModal = function(data) {
        $('#contenuModalTitle').text(data.titre);
        $('#contenuTexte').html(data.texte ? data.texte.replace(/\n/g,'<br>') : '—');
        $('#contenuAuteur').text(data.auteur);
        $('#contenuDate').text(data.date_creation);
        $('#contenuModal').data('contenu-id', data.id_contenu).modal('show');
    };

    // Fonction pour changer le statut d'un contenu
    function changeStatut(id, statut) {
        $.ajax({
            url: "{{ url('moderateur/contenus') }}/" + id + "/change-statut",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                statut: statut
            },
            success: function(resp) {
                if(resp.success) {
                    // Recharger les tables
                    enAttenteTable.ajax.reload();
                    validesTable.ajax.reload();
                    mediocresTable.ajax.reload();
                    
                    // Mettre à jour les statistiques
                    $('#stat-valides').text(resp.stats.valides);
                    $('#stat-rejetes').text(resp.stats.rejetes);
                    $('#stat-en-attente').text(resp.stats.en_attente);
                    
                    // Fermer le modal
                    $('#contenuModal').modal('hide');
                    
                    // Afficher un message de succès
                    alert(resp.message);
                }
            },
            error: function() {
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        });
    }

    // Gestion des boutons du modal
    $('#btnAccept').on('click', function(){
        changeStatut($('#contenuModal').data('contenu-id'), 'Bon');
    });

    $('#btnReject').on('click', function(){
        if(!confirm('Êtes-vous sûr de vouloir marquer ce contenu comme "Médiocre" ? Cette action est irréversible.')) return;
        changeStatut($('#contenuModal').data('contenu-id'), 'Médiocre');
    });
});
</script>
@endpush
@endsection