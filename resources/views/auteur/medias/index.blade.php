@extends('auteur.layout')

@section('Content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Mes Médias</h2>

        <!-- SEUL bouton création (vers la vraie page) -->
        <a href="{{ route('auteur.medias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Ajouter un média
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-2">
            <table id="mediasTable" class="table table-hover table-striped table-bordered w-100">
                <thead class="table-light">
                    <tr>
                        <th>Fichier</th>
                        <th>Type</th>
                        <th>Contenu</th>
                        <th>Langue</th>
                        <th class="text-center" style="width:130px">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#mediasTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('auteur.medias.index') }}",
        columns: [
            { data: 'chemin', name: 'chemin', orderable: false, searchable: true },
            { data: 'type_media', name: 'typeMedia.nom_media' },
            { data: 'contenu', name: 'contenu.titre' },
            { data: 'langue', name: 'langue.nom_langue' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json" },
        pageLength: 10
    });

    // suppression via AJAX
    $(document).on('submit', '.media-delete-form', function(e) {
        e.preventDefault();
        if (!confirm('Supprimer ce média ?')) return;

        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(resp) {
                table.ajax.reload(null, false);
                alert(resp.message || 'Média supprimé');
            },
            error: function() {
                alert('Erreur lors de la suppression');
            }
        });
    });
});
</script>
@endpush
