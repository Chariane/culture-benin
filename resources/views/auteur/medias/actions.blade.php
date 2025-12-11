<div class="btn-group">
    <a href="{{ route('auteur.medias.show', $row->id_media) }}" class="btn btn-info btn-sm">Voir</a>

    <a href="{{ route('auteur.medias.edit', $row->id_media) }}" class="btn btn-warning btn-sm">Modifier</a>

    <form action="{{ route('auteur.medias.destroy', $row->id_media) }}" method="POST"
          onsubmit="return confirm('Supprimer ce média ?')" style="display:inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm">Supprimer</button>
    </form>
</div>
