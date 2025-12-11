<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('auteur.type_contenus.store') }}">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Créer un type de contenu</h5>
        </div>

        <div class="modal-body">
            <label>Nom du type</label>
            <input type="text" name="nom_contenu" class="form-control" required>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            <button class="btn btn-success">Enregistrer</button>
        </div>
    </form>
  </div>
</div>
