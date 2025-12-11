<x-app-layout>

    <h2 class="text-lg font-bold mb-4">Activer l'authentification Google</h2>

    <p>Scannez ce QR Code dans Google Authenticator :</p>

    <div class="my-4">
        <img src="{{ $qrCode }}" alt="QR Code Google Authenticator">
    </div>

    <p>Ou entrez cette clé manuellement :</p>
    <div class="font-mono p-2 bg-gray-100 inline-block rounded">
        {{ $secret }}
    </div>

</x-app-layout>
