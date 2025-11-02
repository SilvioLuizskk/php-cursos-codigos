<x-layout title="Temporadas - {{ $series->nome }}">
    <!-- Botão para voltar às séries -->
    <div class="mb-3">
        <a href="{{ route('series.index') }}" class="btn btn-secondary">
            ← Voltar às Séries
        </a>
    </div>

    <h4 class="mb-3">Temporadas de "{{ $series->nome }}"</h4>

    @if($seasons->count() > 0)
        <ul class="list-group">
            @foreach ($seasons as $season)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Temporada {{ $season->number }}</strong>
                        <small class="text-muted d-block">
                            {{ $season->episodes->count() ?? 0 }} episódios
                        </small>
                    </div>

                    <span class="badge bg-primary rounded-pill">
                        {{ $season->number }}
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-info">
            <h5>📺 Nenhuma temporada encontrada</h5>
            <p>Esta série ainda não possui temporadas cadastradas.</p>
        </div>
    @endif
</x-layout>
