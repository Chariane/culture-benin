<?php
// resources/views/admin/regions/actions.blade.php
?>
<a href="{{ route('admin.regions.show', $r->id_region) }}" class="btn btn-outline-info btn-sm action-icon">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.regions.edit', $r->id_region) }}" class="btn btn-outline-warning btn-sm action-icon">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.regions.destroy', $r->id_region) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon" onclick="return confirm('Supprimer ?')">
        <i class="bi bi-trash"></i>
    </button>
</form>
