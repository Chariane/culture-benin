@extends('auteur.layout')

@section('Content')
<div class="container">
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h2 class="fw-bold mb-0">{{ $contenu->titre }}</h2>
                <div class="text-end">
                    <small class="text-muted">Créé le : {{ $contenu->date_creation }}</small>
                </div>
            </div>

            <p class="mt-3"><strong>Type :</strong> {{ optional($contenu->type)->nom_contenu ?? '—' }}</p>

            <p>
                <strong>Statut :</strong>
                @if($contenu->statut === 'Bon')
                    <span class="badge bg-success">Bon</span>
                @elseif($contenu->statut === 'Médiocre')
                    <span class="badge bg-danger">Médiocre</span>
                @else
                    <span class="badge bg-danger">En attente</span>
                @endif
            </p>

            <p><strong>Région :</strong> {{ optional($contenu->region)->nom_region ?? '—' }}</p>
            <p><strong>Langue :</strong> {{ optional($contenu->langue)->nom_langue ?? '—' }}</p>

            {{-- 🔥 NOUVEAUX CHAMPS PREMIUM & PRIX --}}
            <p>
                <strong>Accès :</strong>
                @if($contenu->premium)
                    <span class="badge bg-warning text-dark">Premium</span>
                @else
                    <span class="badge bg-secondary">Gratuit</span>
                @endif
            </p>

            <p>
                <strong>Prix :</strong>
                @if($contenu->premium)
                    {{ $contenu->prix }} F CFA
                @else
                    —
                @endif
            </p>

            <hr>

            <h5>Contenu</h5>
            <p class="mt-3">{!! nl2br(e($contenu->texte)) !!}</p>

            <hr>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('auteur.contenus.edit', $contenu->id_contenu) }}" class="btn btn-warning">Modifier</a>

                <form method="POST" action="{{ route('auteur.contenus.destroy', $contenu->id_contenu) }}"
                      onsubmit="return confirm('Supprimer ce contenu ?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Supprimer</button>
                </form>

                <a href="{{ route('auteur.contenus.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Commentaires</h5>
            @if($contenu->commentaires->isEmpty())
                <p class="text-muted mb-0">Aucun commentaire pour ce contenu.</p>
            @else
                <ul class="list-group">
                    @foreach($contenu->commentaires as $commentaire)
                        <li class="list-group-item">
                            <strong>{{ $commentaire->nom }}</strong> : {{ $commentaire->texte }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
