<div class="d-flex justify-content-center gap-2">

    <a href="{{ route('admin.parlers.edit', ['id_region' => $p->id_region, 'id_langue' => $p->id_langue]) }}"
       class="btn btn-warning btn-sm action-icon">
        <i class="bi bi-pencil-square"></i>
    </a>

    <form action="{{ route('admin.parlers.destroy', ['id_region' => $p->id_region, 'id_langue' => $p->id_langue]) }}"
          method="POST"
          onsubmit="return confirm('Supprimer cette association ?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm action-icon">
            <i class="bi bi-trash"></i>
        </button>
    </form>

</div>
