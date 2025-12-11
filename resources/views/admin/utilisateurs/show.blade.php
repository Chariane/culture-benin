@extends('admin.layout')

@section('Content')
<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">
                <i class="bi bi-person"></i> Profil de l’utilisateur
            </h5>
        </div>

        <div class="card-body">

            <!-- 🟦 Photo de profil -->
            <div class="text-center mb-4">

                @php
                    $avatar = $utilisateur->sexe === 'Femme' ? 'female.jpg' : 'male.jpg';
                @endphp

                <img src="{{ $utilisateur->photo 
                        ? asset('storage/' . $utilisateur->photo) 
                        : asset($avatar) }}"
                     class="rounded-circle shadow"
                     width="120" height="120" alt="Photo de profil">

                <h4 class="mt-2">{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</h4>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>Nom</th>
                    <td>{{ $utilisateur->nom }}</td>
                </tr>

                <tr>
                    <th>Prénom</th>
                    <td>{{ $utilisateur->prenom }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $utilisateur->email }}</td>
                </tr>

                <tr>
                    <th>Sexe</th>
                    <td>{{ $utilisateur->sexe }}</td>
                </tr>

                <tr>
                    <th>Date de naissance</th>
                    <td>{{ $utilisateur->date_naissance }}</td>
                </tr>

                <tr>
                    <th>Langue</th>
                    <td>{{ $utilisateur->langue->nom_langue }}</td>
                </tr>

                <tr>
                    <th>Rôle</th>
                    <td>{{ $utilisateur->role->nom }}</td>
                </tr>

                <tr>
                    <th>Statut</th>
                    <td>{{ $utilisateur->statut }}</td>
                </tr>

                <tr>
                    <th>Date d’inscription</th>
                    <td>{{ $utilisateur->date_inscription }}</td>
                </tr>

            </table>

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>

            <a href="{{ route('admin.utilisateurs.edit', $utilisateur->id_utilisateur) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>

    </div>

</div>
@endsection
