@extends('admin.layout')

@section('Content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h3>Créer un Parler</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.parlers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Région</label>
                <select name="id_region" class="form-select">
                    @foreach($regions as $region)
                        <option value="{{ $region->id_region }}">{{ $region->nom_region }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Langue</label>
                <select name="id_langue" class="form-select">
                    @foreach($langues as $langue)
                        <option value="{{ $langue->id_langue }}">{{ $langue->nom_langue }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.parlers.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection
