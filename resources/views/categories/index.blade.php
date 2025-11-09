@extends('layouts.app')

@section('title', 'Categorias - Catálogo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tags me-2"></i> Categorias</h1>
    @auth
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nova Categoria
        </a>
    @endauth
</div>

<div class="row">
    @if($categories->count() > 0)
        @foreach($categories as $category)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $category->name }}</h5>

                            @if(!$category->active)
                                <span class="badge bg-warning">Inativa</span>
                            @endif
                        </div>

                        @if($category->description)
                            <p class="card-text text-muted">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-box me-1"></i>
                                {{ $category->products_count }}
                                {{ $category->products_count === 1 ? 'produto' : 'produtos' }}
                            </small>

                            <div class="btn-group" role="group">
                                <a href="{{ route('categories.show', $category) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @auth
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($category->products_count === 0)
                                        <form action="{{ route('categories.destroy', $category) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4>Nenhuma categoria encontrada</h4>
                <p class="text-muted">
                    @auth
                        <a href="{{ route('categories.create') }}">Criar a primeira categoria</a>
                    @else
                        Aguarde as categorias serem criadas.
                    @endauth
                </p>
            </div>
        </div>
    @endif
</div>

<!-- Paginação -->
@if($categories->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
@endif

@endsection
