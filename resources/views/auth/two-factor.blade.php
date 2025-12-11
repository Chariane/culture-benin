<x-guest-layout>

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">
            Vérification en deux étapes
        </h2>

        <p class="mb-4 text-center text-gray-600">
            Un code vous a été envoyé par email.
        </p>

        <form action="{{ route('2fa.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold">Code reçu</label>
                <input type="text" name="code" class="w-full border rounded-lg px-4 py-2" required>
                @error('code') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                Vérifier le code
            </button>
        </form>
    </div>
</div>
<x-guest-layout>
