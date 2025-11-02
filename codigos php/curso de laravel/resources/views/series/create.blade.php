<x-layout title="Adicionar Série">
    <form action="{{ route('series.store') }}" method="post">
        @csrf

        <!-- Campo Nome da Série (linha completa) -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Série:</label>
            <input type="text"
                id="nome"
                name="nome"
                class="form-control"
                value="{{ old('nome') }}"
                required>
        </div>

        <!-- Campos lado a lado -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="seasonsQty" class="form-label">Nº Temporadas:</label>
                <input type="number"
                    id="seasonsQty"
                    name="seasonsQty"
                    class="form-control"
                    value="{{ old('seasonsQty') }}"
                    min="1">
            </div>

            <div class="col-md-6">
                <label for="episodesPerSeason" class="form-label">Episódios por Temporada:</label>
                <input type="number"
                    id="episodesPerSeason"
                    name="episodesPerSeason"
                    class="form-control"
                    value="{{ old('episodesPerSeason') }}"
                    min="1">
            </div>
        </div>

        <!-- Botões -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Adicionar Série</button>
            <a href="{{ route('series.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layout>
