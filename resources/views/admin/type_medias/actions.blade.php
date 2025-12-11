<?php

?>
<a href="{{ route('admin.type_medias.show', $t->id_type_media) }}" 
   class="btn btn-outline-info btn-sm action-icon" title="Voir">
    <i class="bi bi-eye"></i>
</a>

<a href="{{ route('admin.type_medias.edit', $t->id_type_media) }}" 
   class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
    <i class="bi bi-pencil-square"></i>
</a>

<form action="{{ route('admin.type_medias.destroy', $t->id_type_media) }}" 
      method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button class="btn btn-outline-danger btn-sm action-icon"
            onclick="return confirm('Supprimer ?')"
            title="Supprimer">
        <i class="bi bi-trash"></i>
    </button>
</form> 
