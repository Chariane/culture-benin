@extends('auteur.layout')

@section('Content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-3">Créer un Contenu</h2>

            <form action="{{ route('auteur.contenus.store') }}" method="POST" id="createForm" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Titre *</label>
                    <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" 
                           value="{{ old('titre') }}" required>
                    @error('titre') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Texte *</label>
                    <textarea name="texte" class="form-control @error('texte') is-invalid @enderror" 
                              rows="6" required>{{ old('texte') }}</textarea>
                    @error('texte') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Type de contenu *</label>
                        <select name="id_type_contenu" class="form-select @error('id_type_contenu') is-invalid @enderror" required>
                            <option value="">— Choisir —</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id_type_contenu }}" 
                                    {{ old('id_type_contenu') == $type->id_type_contenu ? 'selected' : '' }}>
                                    {{ $type->nom_contenu }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_type_contenu') 
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Région</label>
                        <select name="id_region" class="form-select">
                            <option value="">— Aucune —</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id_region }}" 
                                    {{ old('id_region') == $region->id_region ? 'selected' : '' }}>
                                    {{ $region->nom_region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Langue</label>
                        <select name="id_langue" class="form-select">
                            <option value="">— Aucune —</option>
                            @foreach($langues as $langue)
                                <option value="{{ $langue->id_langue }}" 
                                    {{ old('id_langue') == $langue->id_langue ? 'selected' : '' }}>
                                    {{ $langue->nom_langue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contenu d'origine (optionnel)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Aucun —</option>
                            @foreach($contenusOrigine as $c)
                                <option value="{{ $c->id_contenu }}" 
                                    {{ old('parent_id') == $c->id_contenu ? 'selected' : '' }}>
                                    {{ $c->titre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Premium + Prix -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contenu Premium ?</label>
                        <select name="premium" class="form-select @error('premium') is-invalid @enderror" 
                                id="premiumSelect">
                            <option value="0" {{ old('premium') == '0' ? 'selected' : '' }}>Non (gratuit)</option>
                            <option value="1" {{ old('premium') == '1' ? 'selected' : '' }}>Oui (payant)</option>
                        </select>
                        @error('premium') 
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="prixGroup" style="display: none;">
                        <label class="form-label">Prix (en FCFA) *</label>
                        <input type="number" min="100" max="10000" name="prix" 
                               class="form-control @error('prix') is-invalid @enderror"
                               value="{{ old('prix', 100) }}" 
                               id="prixInput">
                        @error('prix') 
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Entre 100 et 10 000 FCFA</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="bi bi-plus-circle me-1"></i> Créer
                    </button>
                    <a href="{{ route('auteur.contenus.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log('Page Create chargée');
    
    const premiumSelect = document.getElementById('premiumSelect');
    const prixGroup = document.getElementById('prixGroup');
    const prixInput = document.getElementById('prixInput');
    const form = document.getElementById('createForm');
    const submitBtn = document.getElementById('submitBtn');

    // Initialiser selon la valeur existante
    function togglePrix() {
        console.log('Premium changé à:', premiumSelect.value);
        if (premiumSelect.value == "1") {
            prixGroup.style.display = "block";
            if (prixInput) {
                prixInput.required = true;
            }
        } else {
            prixGroup.style.display = "none";
            if (prixInput) {
                prixInput.required = false;
                prixInput.value = ""; // Réinitialiser
            }
        }
    }

    // Initialisation basée sur old('premium')
    @if(old('premium') == '1')
        premiumSelect.value = "1";
        togglePrix();
    @endif
    
    // Écouter les changements
    premiumSelect.addEventListener('change', togglePrix);
    
    // SIMPLIFIER la validation - Laisser Laravel gérer côté serveur
    form.addEventListener('submit', function(e) {
        console.log('Formulaire soumis, premium:', premiumSelect.value);
        
        // Désactiver le bouton pour éviter les doubles clics
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Création en cours...';
        
        // SIMPLE validation frontale - juste les champs requis de base
        const requiredFields = form.querySelectorAll('[required]:not(#prixInput)');
        let isValid = true;
        let firstInvalidField = null;
        
        requiredFields.forEach(field => {
            // Ignorer le champ prix s'il n'est pas visible
            if (field.id === 'prixInput' && prixGroup.style.display === 'none') {
                return;
            }
            
            if (!field.value || field.value.trim() === "") {
                isValid = false;
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid && firstInvalidField) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires (*).');
            firstInvalidField.focus();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Créer';
            return false;
        }
        
        // Si premium est activé, vérifier le prix
        if (premiumSelect.value == "1") {
            if (!prixInput.value || prixInput.value.trim() === "" || parseFloat(prixInput.value) < 0) {
                e.preventDefault();
                alert('Pour un contenu premium, veuillez entrer un prix valide (0 ou plus).');
                prixInput.focus();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Créer';
                return false;
            }
        }
        
        console.log('Formulaire valide, soumission continue...');
        return true;
    });
});
</script>
@endsection