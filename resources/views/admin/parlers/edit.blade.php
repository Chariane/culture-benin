@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h3>Éditer le Parler</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.parlers.update', [
                'id_region' => $parler->id_region,
                'id_langue' => $parler->id_langue
            ]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Région</label>
                <select name="id_region" class="form-select">
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}" {{ $region->id_region==$parler->id_region ? 'selected' : '' }}>{{ $region->nom_region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Langue</label>
                <select name="id_langue" class="form-select">
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}" {{ $langue->id_langue==$parler->id_langue ? 'selected' : '' }}>{{ $langue->nom_langue }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="{{ route('admin.parlers.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
