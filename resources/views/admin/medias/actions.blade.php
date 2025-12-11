<div class="d-flex justify-content-center gap-1">

    <!-- Voir -->
    <a href="{{ route('admin.medias.show', $media->id_media) }}"
       class="btn btn-outline-info btn-sm action-icon" title="Voir">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Modifier -->
    <a href="{{ route('admin.medias.edit', $media->id_media) }}"
       class="btn btn-outline-warning btn-sm action-icon" title="Modifier">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- Supprimer -->
    <form action="{{ route('admin.medias.destroy', $media->id_media) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger btn-sm action-icon"
                onclick="return confirm('Supprimer ce média ?');"
                title="Supprimer">
            <i class="bi bi-trash"></i>
        </button>
    </form>

</div>
