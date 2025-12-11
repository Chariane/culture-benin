@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Utilisateur</h3>
    </div>
    <div class="card-body">

        {{-- Affichage des erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.utilisateurs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nom(s)</label>
                <input type="text" class="form-control" name="nom" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom(s)</label>
                <input type="text" class="form-control" name="prenom" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="mot_de_passe" required />
            </div>

            <div class="mb-3">
                <legend class="col-form-label pt-0">Sexe</legend>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexe" value="Homme" checked required>
                    <label class="form-check-label">Homme</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexe" value="Femme" required>
                    <label class="form-check-label">Femme</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Date naissance</label>
                <input type="date" class="form-control" name="date_naissance" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" class="form-control" name="photo" accept="image/*" />
            </div>

            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <select name="id_role" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Langue</label>
                <select name="id_langue" class="form-select" required>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}">{{ $langue->nom_langue }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
