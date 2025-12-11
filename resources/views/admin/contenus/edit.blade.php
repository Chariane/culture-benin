@extends('admin.layout')

@section('Content')

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-warning text-dark py-3">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square"></i> Éditer le Contenu
        </h4>
    </div>

    <div class="card-body p-4">

        <form action="{{ route('admin.contenus.update', $contenu->id_contenu) }}" method="POST" class="row g-3" id="updateForm" novalidate>
            @csrf
            @method('PUT')

            {{-- Titre --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Titre *</label>
                <input type="text" name="titre" class="form-control shadow-sm" 
                       value="{{ old('titre', $contenu->titre) }}" required>
            </div>

            {{-- Texte --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">Texte *</label>
                <textarea name="texte" class="form-control shadow-sm" rows="4" required>{{ old('texte', $contenu->texte) }}</textarea>
            </div>

            {{-- Statut --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Statut *</label>
                <select name="statut" id="statutSelect" class="form-select shadow-sm" required>
                    <option value="En attente" {{ old('statut', $contenu->statut) == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Bon" {{ old('statut', $contenu->statut) == 'Bon' ? 'selected' : '' }}>Bon</option>
                    <option value="Médiocre" {{ old('statut', $contenu->statut) == 'Médiocre' ? 'selected' : '' }}>Médiocre</option>
                </select>
            </div>

            {{-- Date création --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Date de création *</label>
                <input type="date" name="date_creation" class="form-control shadow-sm"
                       value="{{ old('date_creation', $contenu->date_creation ? \Carbon\Carbon::parse($contenu->date_creation)->format('Y-m-d') : '') }}"
                       required>
            </div>

            {{-- Date validation --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Date de validation</label>
                <input type="date" name="date_validation" id="dateValidationInput"
                       class="form-control shadow-sm"
                       value="{{ old('date_validation', $contenu->date_validation ? \Carbon\Carbon::parse($contenu->date_validation)->format('Y-m-d') : '') }}"
                       {{ $contenu->statut === 'En attente' ? 'disabled' : '' }}>
            </div>

            {{-- Sélection du Modérateur --}}
            <div class="col-md-6" id="moderateurBox"
                 style="{{ $contenu->statut === 'En attente' ? 'display:none;' : '' }}">

                <label class="form-label fw-bold">Modérateur</label>
                <select name="id_moderateur" id="moderateurSelect" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($utilisateurs as $u)
                        <option value="{{ $u->id_utilisateur }}"
                            {{ old('id_moderateur', $contenu->id_moderateur) == $u->id_utilisateur ? 'selected' : '' }}>
                            {{ $u->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Contenu parent --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Contenu parent</label>
                <select name="parent_id" class="form-select shadow-sm">
                    <option value="">Aucun</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id_contenu }}"
                            {{ old('parent_id', $contenu->parent_id) == $p->id_contenu ? 'selected' : '' }}>
                            {{ $p->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Région --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Région *</label>
                <select name="id_region" class="form-select shadow-sm" required>
                    <option value="">— Sélectionner —</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}"
                            {{ old('id_region', $contenu->id_region) == $region->id_region ? 'selected' : '' }}>
                            {{ $region->nom_region }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Langue --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Langue *</label>
                <select name="id_langue" class="form-select shadow-sm" required>
                    <option value="">— Sélectionner —</option>
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}"
                            {{ old('id_langue', $contenu->id_langue) == $langue->id_langue ? 'selected' : '' }}>
                            {{ $langue->nom_langue }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">Type de contenu *</label>
                <select name="id_type_contenu" class="form-select shadow-sm" required>
                    <option value="">— Sélectionner —</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id_type_contenu }}"
                            {{ old('id_type_contenu', $contenu->id_type_contenu) == $type->id_type_contenu ? 'selected' : '' }}>
                            {{ $type->nom_contenu }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Premium --}}
            <input type="hidden" name="premium" value="0">
            
            {{-- Switch Premium --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">Premium</label><br>
                <div class="form-check form-switch">
                    <input type="checkbox" name="premium" id="premiumCheckbox" value="1" 
                           class="form-check-input" {{ old('premium', $contenu->premium) ? 'checked' : '' }}>
                    <label class="form-check-label" for="premiumCheckbox">
                        Contenu premium
                    </label>
                </div>
            </div>

            {{-- Prix --}}
            <div class="col-md-6" id="prixBox" style="{{ $contenu->premium ? '' : 'display:none;' }}">
                <label class="form-label fw-bold">Prix (min 100 FCFA)</label>
                <input type="number" min="100" name="prix" id="prixInput"
                       value="{{ old('prix', $contenu->prix) }}"
                       class="form-control shadow-sm">
            </div>

            {{-- Boutons --}}
            <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.contenus.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                <button type="submit" class="btn btn-warning px-4 text-dark fw-bold">
                    Mettre à jour
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Script chargé');
    
    const form = document.getElementById('updateForm');
    const statutSelect = document.getElementById('statutSelect');
    const dateValInput = document.getElementById('dateValidationInput');
    const modBox = document.getElementById('moderateurBox');
    const premiumCheckbox = document.getElementById('premiumCheckbox');
    const prixBox = document.getElementById('prixBox');

    // Gestion statut
    function updateStatutFields() {
        console.log('Statut changé:', statutSelect.value);
        if (statutSelect.value === "En attente") {
            dateValInput.disabled = true;
            dateValInput.value = "";
            modBox.style.display = "none";
        } else {
            dateValInput.disabled = false;
            modBox.style.display = "block";
            
            // Si date validation est vide, mettre la date d'aujourd'hui
            if (!dateValInput.value) {
                const today = new Date().toISOString().split('T')[0];
                dateValInput.value = today;
            }
        }
    }

    // Gestion premium
    function updatePremiumFields() {
        console.log('Premium changé:', premiumCheckbox.checked);
        if (premiumCheckbox.checked) {
            prixBox.style.display = "block";
        } else {
            prixBox.style.display = "none";
        }
    }

    // Événements
    statutSelect.addEventListener('change', updateStatutFields);
    premiumCheckbox.addEventListener('change', updatePremiumFields);

    // Initialisation
    updateStatutFields();
    updatePremiumFields();

    // Debug simple
    form.addEventListener('submit', function(e) {
        console.log('Form submitted - validation en cours');
        // La validation HTML5 fera le reste
    });
});
</script>

<style>
.form-check-input {
    width: 3em;
    height: 1.5em;
}
</style>

@endsection