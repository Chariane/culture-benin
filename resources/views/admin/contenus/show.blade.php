@extends('admin.layout')

@section('Content')

{{-- ===========================
    🔵 CONTENU PRINCIPAL
=========================== --}}
<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h4 class="mb-0">
            <i class="bi bi-file-earmark-text"></i> {{ $contenu->titre }}
        </h4>
    </div>

    <div class="card-body p-4">

        {{-- STATUT --}}
        <p><strong>Statut :</strong>
            <span class="badge {{ $contenu->statut == 'Bon' ? 'bg-success' : 'bg-danger' }}">
                {{ $contenu->statut }}
            </span>
        </p>

        <p><strong>Date de création :</strong> {{ $contenu->date_creation }}</p>

        {{-- PREMIUM + PRIX --}}
        <div class="mt-3">
            <h5 class="fw-bold">Premium :</h5>

            @if($contenu->premium)
                <span class="badge bg-primary px-3 py-2" style="font-size: 1rem;">
                    ⭐ Premium
                </span>

                <p class="mt-2 mb-0">
                    <strong>Prix :</strong>
                    <span class="text-success fw-bold">{{ number_format($contenu->prix, 0, ',', ' ') }} FCFA</span>
                </p>
            @else
                <span class="badge bg-secondary px-3 py-2" style="font-size: 1rem;">
                    Standard
                </span>
            @endif
        </div>

        <hr>

        {{-- TEXTE --}}
        <p class="fw-bold mb-1">Texte :</p>

        <div class="p-3 bg-light rounded shadow-sm">
            {!! nl2br(e($contenu->texte)) !!}
        </div>

        <a href="{{ route('admin.contenus.index') }}" class="btn btn-secondary mt-4">
            Retour à la liste
        </a>

    </div>
</div>

{{-- ===========================
    🟢 COMMENTAIRES
=========================== --}}
<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-success text-white py-3">
        <h4 class="mb-0">
            <i class="bi bi-chat-left-text"></i>
            Commentaires associés ({{ $contenu->commentaires->count() }})
        </h4>
    </div>

    <div class="card-body p-4">

        @forelse($contenu->commentaires as $commentaire)

            <div class="p-3 mb-3 bg-white border rounded shadow-sm">
                <p class="fw-bold mb-1">
                    <i class="bi bi-person-circle"></i>
                    {{ $commentaire->utilisateur->nom }} {{ $commentaire->utilisateur->prenom }}
                    <small class="text-muted">— {{ $commentaire->date }}</small>
                </p>

                {{-- NOTATION --}}
                <p class="text-warning mb-2" style="font-size:1.3rem;">
                    {!! str_repeat('★', $commentaire->note) !!}
                    {!! str_repeat('☆', 5 - $commentaire->note) !!}
                </p>

                <p class="mb-0 text-secondary">{{ $commentaire->texte }}</p>
            </div>

        @empty
            <p class="text-muted fst-italic">Aucun commentaire pour ce contenu.</p>
        @endforelse

    </div>
</div>




{{-- ===========================
    🟣 MÉDIAS ASSOCIÉS
=========================== --}}
<h3 class="fw-bold mt-4">📸 Médias associés</h3>

@if($contenu->medias->count() == 0)
    <p class="text-muted">Aucun média trouvé.</p>
@else

<style>
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}
.media-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: transform .2s;
}
.media-card:hover {
    transform: scale(1.04);
}
.media-thumb {
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 8px;
}
.video-container video {
    width: 100%;
    max-height: 170px;
    border-radius: 8px;
}
.pdf-box {
    background: #f0f0f0;
    text-align: center;
    padding: 40px 10px;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
}
</style>

<div class="media-grid">
    @foreach ($contenu->medias as $media)

        <div class="media-card">

            @php
                $path = asset('storage/' . $media->chemin);
                $ext = strtolower(pathinfo($media->chemin, PATHINFO_EXTENSION));
            @endphp

            {{-- IMAGES --}}
            @if(in_array($ext, ['jpg','jpeg','png','gif']))
                <img src="{{ $path }}" class="media-thumb" alt="media">

            {{-- VIDEOS --}}
            @elseif(in_array($ext, ['mp4','avi','mov']))
                <div class="video-container">
                    <video controls>
                        <source src="{{ $path }}" type="video/mp4">
                    </video>
                </div>

            {{-- AUDIOS --}}
            @elseif(in_array($ext, ['mp3','wav']))
                <audio controls class="w-100 mt-3">
                    <source src="{{ $path }}" type="audio/mp3">
                </audio>

            {{-- PDF OU AUTRES --}}
            @else
                <div class="pdf-box">
                    📄 {{ strtoupper($ext) }}
                </div>
            @endif

            <div class="mt-2 text-center">
                <a href="{{ $path }}" target="_blank"
                   class="btn btn-sm btn-outline-primary">
                    Ouvrir
                </a>
            </div>

        </div>

    @endforeach
</div>

@endif

@endsection
