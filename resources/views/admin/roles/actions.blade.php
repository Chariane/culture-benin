<?php
// resources/views/admin/roles/actions.blade.php
// Attendu : la variable fournie à la vue s'appelle $r (Role)
?>
<a href="{{ route('admin.roles.show', $r->id) }}"
   class="btn btn-outline-info btn-sm action-icon" title="Voir">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.roles.edit', $r->id) }}"
   class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.roles.destroy', $r->id) }}"
      method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon"
            onclick="return confirm('Supprimer ce rôle ?')"
            title="Supprimer">
        <i class="bi bi-trash"></i>
    </button>
</form>
