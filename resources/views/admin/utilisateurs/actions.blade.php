<?php
// resources/views/admin/utilisateurs/actions.blade.php
// Attendu : la variable fournie à la vue s'appelle $u (Utilisateur)
?>
<a href="{{ route('admin.utilisateurs.show', $u->id_utilisateur) }}"
   class="btn btn-outline-info btn-sm action-icon" title="Voir">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.utilisateurs.edit', $u->id_utilisateur) }}"
   class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.utilisateurs.destroy', $u->id_utilisateur) }}"
      method="POST"
      style="display:inline-block;"
      onsubmit="return confirmerDoubleSuppression()">
    @csrf
    @method('DELETE')

    <button class="btn btn-outline-danger btn-sm action-icon">
        <i class="bi bi-trash"></i>
    </button>
</form>

@push('scripts')
<script>
function confirmerDoubleSuppression() {
    let msg1 = "⚠️ La suppression de cet utilisateur entraînera la suppression de :\n\n" +
               "• Tous ses contenus\n" +
               "• Tous ses médias\n" +
               "• Tous ses commentaires\n\n" +
               "Êtes-vous sûr de vouloir continuer ?";
    
    if (!confirm(msg1)) {
        return false;
    }

    let msg2 = "❗ Confirmation finale :\n\n" +
               "Êtes-vous VRAIMENT sûr de vouloir supprimer définitivement cet utilisateur ?";
    
    return confirm(msg2);
}
</script>
@endpush
