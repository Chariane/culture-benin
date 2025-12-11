@extends('auteur.layout')

@section('Content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-3">Modifier le Contenu</h2>

            <form action="{{ route('auteur.contenus.update', $contenu->id_contenu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Titre</label>
                    <input type="text" name="titre" class="form-control" value="{{ old('titre', $contenu->titre) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Texte</label>
                    <textarea name="texte" class="form-control" rows="6" required>{{ old('texte', $contenu->texte) }}</textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Région</label>
                        <select name="id_region" class="form-select">
                            <option value="">— Aucune —</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id_region }}" {{ old('id_region', $contenu->id_region) == $region->id_region ? 'selected' : '' }}>
                                    {{ $region->nom_region }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Langue</label>
                        <select name="id_langue" class="form-select">
                            <option value="">— Aucune —</option>
                            @foreach($langues as $langue)
                                <option value="{{ $langue->id_langue }}" {{ old('id_langue', $contenu->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                                    {{ $langue->nom_langue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type de contenu</label>
                        <select name="id_type_contenu" class="form-select">
                            <option value="">— Choisir —</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id_type_contenu }}" {{ old('id_type_contenu', $contenu->id_type_contenu) == $type->id_type_contenu ? 'selected' : '' }}>
                                    {{ $type->nom_contenu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contenu d'origine (optionnel)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Aucun —</option>
                            @foreach($contenusOrigine as $c)
                                <option value="{{ $c->id_contenu }}" {{ old('parent_id', $contenu->parent_id) == $c->id_contenu ? 'selected' : '' }}>
                                    {{ $c->titre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- ------------------------ -->
                <!-- 🔥 PREMIUM + PRIX -------- -->
                <!-- ------------------------ -->

                <div class="row g-3 mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Contenu Premium ?</label>
                        <select name="premium" id="premiumSelect" class="form-select">
                            <option value="0" {{ old('premium', $contenu->premium) == 0 ? 'selected' : '' }}>Non (gratuit)</option>
                            <option value="1" {{ old('premium', $contenu->premium) == 1 ? 'selected' : '' }}>Oui (payant)</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="prixGroup" style="display:none;">
                        <label class="form-label">Prix (en FCFA)</label>
                        <input type="number" min="0" name="prix" class="form-control"
                               value="{{ old('prix', $contenu->prix) }}">
                    </div>

                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const premium = document.getElementById('premiumSelect');
                        const prixGroup = document.getElementById('prixGroup');

                        function togglePrix() {
                            prixGroup.style.display = premium.value == "1" ? "block" : "none";
                        }

                        premium.addEventListener('change', togglePrix);
                        togglePrix();
                    });
                </script>

                <div class="alert alert-warning">
                    ⚠️ En modifiant ce contenu, son statut repassera automatiquement à <strong>Médiocre</strong>.
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('auteur.contenus.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
