<x-layout title="Nova Série">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title text-center mb-4 fw-bold">Adicionar Série</h2>
                <form action="{{ route('series.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Série</label>
                        <input type="text" autofocus id="nome" name="nome" class="form-control" value="{{ old('nome') }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="seasonsQty" class="form-label">Nº Temporadas</label>
                            <input type="number" id="seasonsQty" name="seasonsQty" class="form-control" value="{{ old('seasonsQty') }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="episodesPerSeason" class="form-label">Eps / Temporada</label>
                            <input type="number" id="episodesPerSeason" name="episodesPerSeason" class="form-control" value="{{ old('episodesPerSeason') }}" min="1" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Adicionar Série</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
