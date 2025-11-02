<x-layout title="Séries" :mensagem-sucesso="$mensagemSucesso">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-dark">Minhas Séries</h2>
                @auth
                    <a href="{{ route('series.create') }}" class="btn btn-success shadow">+ Nova Série</a>
                @endauth
            </div>
        </div>
        <div class="row">
            @forelse ($series as $serie)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title mb-2">
                                    <a href="{{ route('seasons.index', $serie->id) }}" class="text-decoration-none text-primary fw-bold">
                                        {{ $serie->nome }}
                                    </a>
                                </h5>
                            </div>
                            @auth
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-outline-primary btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('series.destroy', $serie->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Nenhuma série cadastrada ainda.</div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
