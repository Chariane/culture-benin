@extends('layout')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2>Code de vérification</h2>
        <p>Un code à 6 chiffres a été envoyé sur votre email.</p>
        <form method="POST" action="{{ route('2fa.store') }}">
            @csrf
            <div class="form-group">
                <label>Code 2FA</label>
                <input type="text" name="two_factor_code" maxlength="6" required>
                @error('two_factor_code') <span class="error">{{ $message }}</span> @enderror
            </div>
            <button type="submit">Valider</button>
        </form>
    </div>
</div>

<style>
.login-wrapper { display:flex; justify-content:center; align-items:center; height:100vh; background:#f0f2f5; }
.login-card { background:#fff; padding:30px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.1); width:350px; text-align:center; }
.login-card h2 { margin-bottom:10px; color:#333; }
.login-card p { margin-bottom:20px; color:#555; }
.form-group { margin-bottom:15px; }
.form-group input { width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; text-align:center; font-size:1.2rem; }
button { width:100%; padding:10px; background:#10b981; color:#fff; border:none; border-radius:8px; font-weight:bold; cursor:pointer; transition:.3s; }
button:hover { background:#059669; }
.error { color:red; font-size:.9rem; }
</style>
@endsection
