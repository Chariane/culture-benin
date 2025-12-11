<?php
// resources/views/admin/langues/actions.blade.php
?>
<a href="{{ route('admin.langues.show', $l->id_langue) }}" class="btn btn-outline-info btn-sm action-icon">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.langues.edit', $l->id_langue) }}" class="btn btn-outline-warning btn-sm action-icon">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.langues.destroy', $l->id_langue) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon" onclick="return confirm('Supprimer ?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
