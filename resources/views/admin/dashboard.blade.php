@extends('admin.layout')

@section('Content')
@php
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
@endphp
<div class="container mt-4">

    <h1 class="fw-bold mb-4"><i class="bi bi-speedometer2"></i> Tableau de Bord</h1>

    {{-- === STATISTIQUES GÉNÉRALES === --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 bg-primary text-white rounded-3">
            <h6 class="mb-1">Contenus</h6>
            <h2 class="m-0">{{ $totalContenus }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 bg-success text-white rounded-3">
            <h6 class="mb-1">Commentaires</h6>
            <h2 class="m-0">{{ $totalCommentaires }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 bg-warning text-dark rounded-3">
            <h6 class="mb-1">Utilisateurs</h6>
            <h2 class="m-0">{{ $totalUtilisateurs }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 bg-info text-white rounded-3">
            <h6 class="mb-1">Avis</h6>
            <h2 class="m-0">{{ $totalAvis }}</h2>
        </div>
    </div>
</div>

    {{-- === STATS SUPPLÉMENTAIRES === --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="mb-1">Types médias</h6>
                <p class="h4 m-0">{{ $totalTypeMedias }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="mb-1">Types contenus</h6>
                <p class="h4 m-0">{{ $totalTypeContenus }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="mb-1">Régions</h6>
                <p class="h4 m-0">{{ $totalRegions }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6 class="mb-1">Langues</h6>
                <p class="h4 m-0">{{ $totalLangues }}</p>
            </div>
        </div>
    </div>

    {{-- === GRAPHIQUES === --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold">Répartition des contenus par statut</h5>
                <canvas id="chartStatut" height="200"></canvas>
                @if($contenusParStatut->isEmpty())
                    <p class="small text-muted mt-2">Aucun contenu pour l'instant.</p>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold">Notes moyennes (top contenus)</h5>
                <canvas id="chartNotes" height="200"></canvas>
                @if($notes->isEmpty())
                    <p class="small text-muted mt-2">Aucune note disponible.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- === Répartition par région / langue / type média === --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6>Contenus par région</h6>
                <ul class="list-group list-group-flush">
                    @forelse($contenusParRegion as $region => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $region }}
                            <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucune donnée.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6>Contenus par langue</h6>
                <ul class="list-group list-group-flush">
                    @forelse($contenusParLangue as $lang => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $lang }}
                            <span class="badge bg-success rounded-pill">{{ $count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucune donnée.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6>Médias par type</h6>
                <ul class="list-group list-group-flush">
                    @forelse($mediasParType as $type => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $type }}
                            <span class="badge bg-danger rounded-pill">{{ $count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucune donnée.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- === Derniers contenus / commentaires / médias / utilisateurs === --}}
    <div class="row gy-4">
        <div class="col-lg-6">

            {{-- Derniers contenus --}}
            <div class="card shadow-sm mb-3 p-3">
                <h5 class="fw-bold">📌 Derniers contenus</h5>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($lastContenus as $c)
                            <tr>
                                <td>{{ $c->id_contenu }}</td>
                                <td>{{ $c->titre }}</td>
                                <td>{{ $c->statut ?? '-' }}</td>
                                <td>{{ $c->date_creation ?? optional($c->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Aucun contenu.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Derniers commentaires --}}
            <div class="card shadow-sm mb-3 p-3">
                <h5 class="fw-bold">💬 Derniers commentaires</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Utilisateur</th>
                                <th>Commentaire</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($lastCommentaires as $com)
                            <tr>
                                <td>{{ $com->utilisateur->nom ?? 'Inconnu' }}</td>
                                <td class="text-truncate" style="max-width:300px;">{{ Str::limit($com->texte, 100) }}</td>
                                <td>{{ $com->note ?? '-' }}</td>
                                <td>{{ optional($com->date ?? $com->created_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Aucun commentaire.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-lg-6">

            {{-- Derniers médias --}}
            <div class="card shadow-sm mb-3 p-3">
                <h5 class="fw-bold">🖼️ Derniers médias</h5>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Chemin</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($lastMedias as $m)
                            <tr>
                                <td>{{ $m->id_media }}</td>

                                <td class="text-truncate" style="max-width:200px;">
                                    {{ $m->chemin }}
                                </td>

                                <td>
                                    {{ $m->typeMedia->nom_media ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Aucun média.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Derniers utilisateurs --}}
            <div class="card shadow-sm mb-3 p-3">
                <h5 class="fw-bold">👥 Derniers utilisateurs</h5>
                <ul class="list-group list-group-flush">
                    @forelse($lastUtilisateurs as $u)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $u->nom ?? '—' }}</strong><br>
                                <small class="text-muted">{{ $u->email ?? '' }}</small>
                            </div>
                            <span class="text-muted small">{{ optional($u->created_at)->format('Y-m-d') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucun utilisateur.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>

    {{-- === TABLEAU DES AUTEURS === --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Liste des Auteurs</h5>
                    <span class="badge bg-light text-dark">{{ $auteurs->count() }} auteurs</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Auteur</th>
                                    <th>Email</th>
                                    <th class="text-center">Abonnés</th>
                                    <th class="text-center">Contenus</th>
                                    <th class="text-center">Premium</th>
                                    <th>Date d'inscription</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auteurs as $index => $auteur)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if($auteur->photo)
                                                    <img src="{{ Storage::url($auteur->photo) }}" 
                                                         alt="{{ $auteur->prenom }}" 
                                                         class="rounded-circle" 
                                                         width="40" 
                                                         height="40">
                                                @else
                                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($auteur->prenom, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <strong>{{ $auteur->prenom }} {{ $auteur->nom }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $auteur->email }}" class="text-decoration-none">
                                            {{ $auteur->email }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill fs-6">
                                            {{ $auteur->abonnes_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill fs-6">
                                            {{ $auteur->contenus_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark rounded-pill fs-6">
                                            {{ $auteur->contenus_premium_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            {{ $auteur->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="#" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir le profil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Voir ses contenus">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="mailto:{{ $auteur->email }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Envoyer un email">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user-slash fa-2x mb-3"></i>
                                            <p class="mb-0">Aucun auteur trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                Total abonnés : <strong>{{ $auteurs->sum('abonnes_count') }}</strong>
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-file-alt me-1"></i>
                                Total contenus : <strong>{{ $auteurs->sum('contenus_count') }}</strong>
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-crown me-1"></i>
                                Total premium : <strong>{{ $auteurs->sum('contenus_premium_count') }}</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === TABLEAU DES MODÉRATEURS === --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i> Liste des Modérateurs</h5>
                <span class="badge bg-light text-dark">{{ $moderateurs->count() }} modérateurs</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Modérateur</th>
                                <th>Email</th>
                                <th class="text-center">Contenus validés</th>
                                <th class="text-center">Total modérés</th>
                                <th>Performance</th>
                                <th>Date d'inscription</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($moderateurs as $index => $moderateur)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if($moderateur->photo)
                                                <img src="{{ Storage::url($moderateur->photo) }}" 
                                                     alt="{{ $moderateur->prenom }}" 
                                                     class="rounded-circle" 
                                                     width="40" 
                                                     height="40">
                                            @else
                                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($moderateur->prenom, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <strong>{{ $moderateur->prenom }} {{ $moderateur->nom }}</strong>
                                            @if($moderateur->id_utilisateur == auth()->id())
                                                <span class="badge bg-primary ms-2">Vous</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $moderateur->email }}" class="text-decoration-none">
                                        {{ $moderateur->email }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill fs-6">
                                        {{ $moderateur->contenus_valides_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill fs-6">
                                        {{ $moderateur->contenus_moderes_total ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar 
                                                    @if($moderateur->performance >= 80) bg-success
                                                    @elseif($moderateur->performance >= 50) bg-warning
                                                    @else bg-danger
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $moderateur->performance }}%;" 
                                                    aria-valuenow="{{ $moderateur->performance }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small class="text-muted">{{ $moderateur->performance }}%</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $moderateur->created_at->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.utilisateurs.show', $moderateur->id_utilisateur) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.contenus.index', ['moderateur' => $moderateur->id_utilisateur]) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Contenus modérés">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                        <a href="mailto:{{ $moderateur->email }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Envoyer un email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-user-shield fa-2x mb-3"></i>
                                        <p class="mb-0">Aucun modérateur trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>
                            Total contenus validés : <strong>{{ $moderateurs->sum('contenus_valides_count') }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">
                            <i class="fas fa-chart-line me-1"></i>
                            Performance moyenne : 
                            <strong>{{ $performanceMoyenne }}%</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- === DERNIERS AVIS === --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-comment-alt me-2"></i> Derniers Avis</h5>
                <a href="{{ route('admin.avis.index') }}" class="btn btn-sm btn-light">
                    Voir tous les avis <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Utilisateur</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lastAvis as $index => $avis)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            @if($avis->utilisateur && $avis->utilisateur->photo)
                                                <img src="{{ Storage::url($avis->utilisateur->photo) }}" 
                                                     alt="{{ $avis->utilisateur->prenom }}" 
                                                     class="rounded-circle" 
                                                     width="40" 
                                                     height="40">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            @if($avis->utilisateur)
                                                <strong>{{ $avis->utilisateur->prenom }} {{ $avis->utilisateur->nom }}</strong><br>
                                                <small class="text-muted">{{ $avis->utilisateur->email }}</small>
                                            @else
                                                <strong>Utilisateur inconnu</strong>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $avis->message }}">
                                        {{ $avis->message }}
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        @php
                                            // Gestion sécurisée de la date
                                            $date = null;
                                            if ($avis->date) {
                                                try {
                                                    if ($avis->date instanceof \Carbon\Carbon) {
                                                        $date = $avis->date;
                                                    } else {
                                                        $date = \Carbon\Carbon::parse($avis->date);
                                                    }
                                                } catch (Exception $e) {
                                                    $date = null;
                                                }
                                            }
                                        @endphp
                                        {{ $date ? $date->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('admin.avis.destroy', $avis->id_avis) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Supprimer l'avis">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-comment-slash fa-2x mb-3"></i>
                                        <p class="mb-0">Aucun avis trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light text-center">
                <a href="{{ route('admin.avis.index') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-list me-1"></i> Voir tous les avis ({{ $totalAvis }})
                </a>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    /* Styles pour les tableaux */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    /* Cards améliorées */
    .card-header {
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0,0,0,.125);
        padding: 0.75rem 1.5rem;
    }
    
    /* Boutons d'action */
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow-x: auto;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 2px;
        }
    }
</style>

{{-- === Chart.js === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // statuts
    (function(){
        const dataStatutLabels = {!! json_encode($contenusParStatut->keys()->all()) !!};
        const dataStatut = {!! json_encode($contenusParStatut->values()->all()) !!};
        const ctx = document.getElementById('chartStatut');
        if(ctx && dataStatut.length){
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: dataStatutLabels,
                    datasets: [{ data: dataStatut }]
                },
                options: { responsive: true }
            });
        }
    })();

    // notes
    (function(){
        const labels = {!! json_encode($notes->keys()->all()) !!};
        const dataVals = {!! json_encode($notes->values()->all()) !!};
        const ctx2 = document.getElementById('chartNotes');
        if(ctx2 && dataVals.length){
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Note moyenne',
                        data: dataVals,
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true, suggestedMax: 5 } } }
            });
        }
    })();
</script>

@endsection