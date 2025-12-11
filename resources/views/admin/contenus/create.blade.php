@extends('admin.layout')

@section('Content')

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-success text-white py-3">
        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Créer un Contenu</h4>
    </div>

    <div class="card-body p-4">

        {{-- Affichage erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.contenus.store') }}" method="POST" class="row g-3" id="contenuCreateForm">
            @csrf

            <div class="col-md-6">
                <label class="form-label fw-bold">Titre</label>
                <input type="text" name="titre" class="form-control shadow-sm" value="{{ old('titre') }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">Texte</label>
                <textarea name="texte" class="form-control shadow-sm" rows="4">{{ old('texte') }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Statut</label>
                <select name="statut" class="form-select shadow-sm" required>
                    <option value="Bon" {{ old('statut') == 'Bon' ? 'selected' : '' }}>Bon</option>
                    <option value="Médiocre" {{ old('statut') == 'Médiocre' ? 'selected' : '' }}>Médiocre</option>
                    <option value="En attente" {{ old('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de création</label>
                <input type="date" name="date_creation" class="form-control shadow-sm" value="{{ old('date_creation') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Date de validation</label>
                <input type="date" name="date_validation" class="form-control shadow-sm" value="{{ old('date_validation') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Contenu parent</label>
                <select name="parent_id" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id_contenu }}" {{ old('parent_id') == $p->id_contenu ? 'selected' : '' }}>
                            {{ $p->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Région</label>
                <select name="id_region" class="form-select shadow-sm" required>
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}" {{ old('id_region') == $region->id_region ? 'selected' : '' }}>
                            {{ $region->nom_region }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Langue</label>
                <select name="id_langue" class="form-select shadow-sm" required>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}" {{ old('id_langue') == $langue->id_langue ? 'selected' : '' }}>
                            {{ $langue->nom_langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Type de contenu</label>
                <select name="id_type_contenu" class="form-select shadow-sm" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id_type_contenu }}" {{ old('id_type_contenu') == $type->id_type_contenu ? 'selected' : '' }}>
                            {{ $type->nom_contenu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Auteur</label>
                <select name="id_auteur" class="form-select shadow-sm" required>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}" {{ old('id_auteur') == $u->id_utilisateur ? 'selected' : '' }}>
                            {{ $u->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Modérateur</label>
                <select name="id_moderateur" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}" {{ old('id_moderateur') == $u->id_utilisateur ? 'selected' : '' }}>
                            {{ $u->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- IMPORTANT: hidden input ensures a value (0) is sent when checkbox unchecked --}}
            <input type="hidden" name="premium" value="0">

            {{-- PREMIUM SWITCH --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">Premium</label><br>

                <label class="switch">
                    <input type="checkbox" name="premium" id="premiumToggle" value="1" {{ old('premium') ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            {{-- PRIX (visible seulement si premium coché) --}}
            <div class="col-md-4" id="prixField" style="{{ old('premium') ? 'display:block;' : 'display:none;' }}">
                <label class="form-label fw-bold">Prix du contenu (min 100 FCFA)</label>
                <input type="number" name="prix" id="prixInput" min="100" class="form-control shadow-sm"
                       placeholder="Ex : 100" value="{{ old('prix') }}" {{ old('premium') ? 'required' : '' }}>
            </div>

            <style>
                .switch {
                    position: relative;
                    display: inline-block;
                    width: 50px;
                    height: 26px;
                }
                .switch input { display: none; }
                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background-color: #ccc;
                    transition: .4s;
                    border-radius: 34px;
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 20px;
                    width: 20px;
                    left: 3px;
                    bottom: 3px;
                    background-color: white;
                    transition: .4s;
                    border-radius: 50%;
                }
                input:checked + .slider {
                    background-color: #0d6efd;
                }
                input:checked + .slider:before {
                    transform: translateX(24px);
                }
            </style>

            {{-- SCRIPT POUR AFFICHER / MASQUER LE PRIX ET TOGGLER required --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const toggle = document.getElementById('premiumToggle');
                    const priceDiv = document.getElementById('prixField');
                    const priceInput = document.getElementById('prixInput');

                    function updatePriceVisibility() {
                        if (toggle.checked) {
                            priceDiv.style.display = 'block';
                            if (priceInput) priceInput.setAttribute('required', 'required');
                        } else {
                            priceDiv.style.display = 'none';
                            if (priceInput) priceInput.removeAttribute('required');
                        }
                    }

                    toggle.addEventListener('change', updatePriceVisibility);

                    // initial state
                    updatePriceVisibility();
                });
            </script>

            <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.contenus.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                <button class="btn btn-success px-4">Créer</button>
            </div>

        </form>
    </div>
</div>

@endsection
