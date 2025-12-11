<?php
// resources/views/admin/commentaires/actions.blade.php
?>
<a href="{{ route('admin.commentaires.show', $c->id_commentaire) }}" class="btn btn-outline-info btn-sm action-icon">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.commentaires.edit', $c->id_commentaire) }}" class="btn btn-outline-warning btn-sm action-icon">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.commentaires.destroy', $c->id_commentaire) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon" onclick="return confirm('Supprimer ?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
