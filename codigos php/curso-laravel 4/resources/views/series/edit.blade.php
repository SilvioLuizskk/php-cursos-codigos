<x-layout title="Editar Série '{{ $serie->nome }}'">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4 fw-bold">Editar Série</h2>
                <x-series.form :action="route('series.update', $serie->id)" :nome="$serie->nome" :seasonsQty="$serie->seasonsQty ?? ''" :episodesPerSeason="$serie->episodesPerSeason ?? ''" :update="true" />
            </div>
        </div>
    </div>
</x-layout>
