@extends('layouts.app')

@section('title', 'Produtos - Catálogo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-box me-2"></i> Produtos</h1>
    @auth
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Novo Produto
        </a>
    @endauth
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nome, descrição ou SKU...">
                </div>

                <div class="col-md-2">
                    <label for="category_id" class="form-label">Categoria</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="sort" class="form-label">Ordenar por</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                            Mais recentes
                        </option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                            Nome
                        </option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>
                            Preço
                        </option>
                        <option value="stock_quantity" {{ request('sort') == 'stock_quantity' ? 'selected' : '' }}>
                            Estoque
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="direction" class="form-label">Direção</label>
                    <select class="form-select" id="direction" name="direction">
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>
                            Decrescente
                        </option>
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>
                            Crescente
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" value="1"
                               {{ request('in_stock') ? 'checked' : '' }}>
                        <label class="form-check-label" for="in_stock">
                            Apenas em estoque
                        </label>
                    </div>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Resultados -->
<div class="row">
    @if($products->count() > 0)
        @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->full_url }}"
                                 class="card-img-top" alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        @if(!$product->isInStock())
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                Sem estoque
                            </span>
                        @endif

                        @if(!$product->active)
                            <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                                Inativo
                            </span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($product->description, 80) }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="h5 text-primary mb-0">
                                    {{ $product->formatted_price }}
                                </span>
                                <small class="text-muted">
                                    Estoque: {{ $product->stock_quantity }}
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $product->category->name }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $product->user->name }}
                                </small>
                            </div>

                            <div class="mt-2">
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i> Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>Nenhum produto encontrado</h4>
                <p class="text-muted">Tente alterar os filtros de busca ou
                    @auth
                        <a href="{{ route('products.create') }}">criar um novo produto</a>.
                    @else
                        aguarde novos produtos serem adicionados.
                    @endauth
                </p>
            </div>
        </div>
    @endif
</div>

<!-- Paginação -->
@if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-submit do formulário quando filtros mudam
    document.querySelectorAll('#category_id, #sort, #direction, #in_stock').forEach(element => {
        element.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush
