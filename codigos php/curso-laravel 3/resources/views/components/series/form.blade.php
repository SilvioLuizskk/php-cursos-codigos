<form action="{{ $action }}" method="post">
    @csrf
    @if($update)
        @method('PUT')
    @endif
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Série</label>
        <input type="text" id="nome" name="nome" class="form-control" @isset($nome)value="{{ $nome }}"@endisset required>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="seasonsQty" class="form-label">Nº Temporadas</label>
            <input type="number" id="seasonsQty" name="seasonsQty" class="form-control" @isset($seasonsQty)value="{{ $seasonsQty }}"@endisset min="1" required>
        </div>
        <div class="col-md-6">
            <label for="episodesPerSeason" class="form-label">Eps / Temporada</label>
            <input type="number" id="episodesPerSeason" name="episodesPerSeason" class="form-control" @isset($episodesPerSeason)value="{{ $episodesPerSeason }}"@endisset min="1" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">{{ $update ? 'Atualizar' : 'Adicionar' }}</button>
</form>
