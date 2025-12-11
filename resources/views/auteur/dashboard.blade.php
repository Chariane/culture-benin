@extends('auteur.layout')

@section('Content')

<div class="container">

    <h2 class="fw-bold mb-4">Tableau de Bord</h2>

    <div class="row g-4">

        <!-- BON -->
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-hand-thumbs-up text-success" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3">Contenus Bon</h4>
                    <p class="fs-2 fw-bold text-success">{{ $bons }}</p>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="col-md-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3">Contenus en attentes</h4>
                    <p class="fs-2 fw-bold text-warning">{{ $attentes }}</p>
                </div>
            </div>
        </div>

        <!-- MÉDIOCRE -->
        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-graph-down text-danger" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3">Contenus Médiocres</h4>
                    <p class="fs-2 fw-bold text-danger">{{ $mediocres }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- === GRAPHIQUE === -->
    <div class="mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="fw-bold mb-3">Comparaison visuelle</h4>

                <canvas id="contenuChart" height="130"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('contenuChart').getContext('2d');

    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Bon', 'En attente', 'Médiocre'],
        datasets: [{
            label: 'Nombre de contenus',
            data: [{{ $bons }}, {{ $attentes }}, {{ $mediocres }}],
            backgroundColor: [
                'rgba(25, 135, 84, 0.7)',     // Bon
                'rgba(255, 193, 7, 0.7)',     // En attente
                'rgba(218, 4, 4, 0.6)',       // Médiocre
            ],
            borderColor: [
                'rgb(25, 135, 84)',
                'rgb(255, 193, 7)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: "#000",
                titleColor: "#fff",
                bodyColor: "#fff",
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});


});
</script>
@endpush
