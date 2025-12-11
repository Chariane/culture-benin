@extends('auteur.layout')

@section('Content')
<div class="container-fluid"> {{-- élargi au maximum --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Mes Contenus</h2>
        <a href="{{ route('auteur.contenus.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nouveau Contenu
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table id="contenusTable" class="table table-striped table-bordered w-100" style="width: 100% !important; table-layout: auto;">
                <thead>
                    <tr>
                        <th style="min-width: 150px">Titre</th>
                        <th style="min-width: 250px">Extrait</th>
                        <th style="min-width: 150px">Contenu origine</th>
                        <th style="min-width: 150px">Région</th>
                        <th style="min-width: 150px">Langue</th>

                        <th style="min-width: 120px">Premium ?</th>
                        <th style="min-width: 120px">Prix</th>

                        <th style="min-width: 160px">Date création</th>
                        <th style="min-width: 150px" class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#contenusTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true, // 🔥 permet l’extension horizontale
        autoWidth: false, // ne réduit pas automatiquement
        ajax: "{{ route('auteur.contenus.datatable') }}",
        columns: [
            { data: 'titre', name: 'titre' },
            { data: 'texte', name: 'texte', orderable: false, searchable: true },
            { data: 'origine', name: 'parent.titre', orderable: false, searchable: false },
            { data: 'region', name: 'region.nom_region' },
            { data: 'langue', name: 'langue.nom_langue' },

            { data: 'premium', name: 'premium' },
            { data: 'prix', name: 'prix' },

            { data: 'date_creation', name: 'date_creation' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: { 
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json" 
        },
        pageLength: 10
    });
});
</script>
@endpush
