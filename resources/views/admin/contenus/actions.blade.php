<?php
// resources/views/admin/commentaires/actions.blade.php
?>
<a href="{{ route('admin.contenus.show', $c->id_contenu) }}" 
   class="btn btn-outline-info btn-sm action-icon" title="Voir">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.contenus.edit', $c->id_contenu) }}" 
   class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.contenus.destroy', $c->id_contenu) }}" 
      method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon"
            onclick="return confirm('Supprimer ?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
