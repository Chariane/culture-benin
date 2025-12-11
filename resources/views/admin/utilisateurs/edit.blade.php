@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i> Modifier l’utilisateur
            </h5>
        </div>

        <form action="{{ route('admin.utilisateurs.update', $utilisateur->id_utilisateur) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control"
                               value="{{ $utilisateur->nom }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control"
                               value="{{ $utilisateur->prenom }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $utilisateur->email }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sexe</label>
                        <select name="sexe" class="form-select">
                            <option value="">—</option>
                            <option value="Homme" @selected($utilisateur->sexe == 'Homme')>Homme</option>
                            <option value="Femme" @selected($utilisateur->sexe == 'Femme')>Femme</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control"
                               value="{{ $utilisateur->date_naissance }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="id_role" class="form-select">
                            @foreach($roles as $r)
                            <option value="{{ $r->id }}" @selected($utilisateur->id_role == $r->id)>
                                {{ $r->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Langue</label>
                        <select name="id_langue" class="form-select">
                            @foreach($langues as $l)
                            <option value="{{ $l->id_langue }}" @selected($utilisateur->id_langue == $l->id_langue)>
                                {{ $l->nom_langue }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- PHOTO DE PROFIL -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Photo de profil</label>

                        @if ($utilisateur->photo)
                        <div class="mb-2 d-flex align-items-center gap-3">

                            <img src="{{ asset('storage/' . $utilisateur->photo) }}"
                                alt="Photo"
                                class="img-thumbnail shadow-sm"
                                width="120">

                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="if(confirm('Supprimer cette photo ?')) {
                                        document.getElementById('deletePhotoForm').submit();
                                    }">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>

                        </div>
                        @endif

                        <input type="file" name="photo" class="form-control"
                            accept="image/png, image/jpeg, image/jpg, image/webp">

                        <small class="text-muted">
                            Laisser vide pour garder l’ancienne photo.
                        </small>
                    </div>


                </div>

            </div>

            <div class="card-footer text-end">
                <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>

                <button class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
            </div>

        </form>

    </div>

</div>
<!-- Formulaire séparé pour supprimer la photo (hors du grand form !) -->
<form id="deletePhotoForm"
      action="{{ route('admin.utilisateurs.deletePhoto', $utilisateur->id_utilisateur) }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>


@endsection
