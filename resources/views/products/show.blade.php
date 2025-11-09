@extends('layouts.app')

@section('title', $product->name . ' - Produto')

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Galeria de Imagens -->
        @if($product->images->count() > 0)
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $image->full_url }}"
                                 class="d-block w-100" alt="{{ $product->name }}"
                                 style="height: 400px; object-fit: cover;">
                        </div>
                    @endforeach
                </div>

                @if($product->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                @endif
            </div>

            <!-- Thumbnails -->
            @if($product->images->count() > 1)
                <div class="d-flex mt-3 gap-2 flex-wrap">
                    @foreach($product->images as $index => $image)
                        <button class="btn btn-outline-secondary p-1"
                                data-bs-target="#productCarousel"
                                data-bs-slide-to="{{ $index }}">
                            <img src="{{ $image->full_url }}"
                                 class="rounded" alt="{{ $product->name }}"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </button>
                    @endforeach
                </div>
            @endif
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                 style="height: 400px;">
                <i class="fas fa-image fa-5x text-muted"></i>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <!-- Informações do Produto -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h1>{{ $product->name }}</h1>

            @auth
                @if($product->user_id === auth()->id())
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('products.edit', $product) }}">
                                    <i class="fas fa-edit me-2"></i> Editar
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-trash me-2"></i> Excluir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Badges de Status -->
        <div class="mb-3">
            <span class="badge bg-primary me-2">
                <i class="fas fa-tag me-1"></i> {{ $product->category->name }}
            </span>

            @if($product->isInStock())
                <span class="badge bg-success">
                    <i class="fas fa-check me-1"></i> Em Estoque ({{ $product->stock_quantity }})
                </span>
            @else
                <span class="badge bg-danger">
                    <i class="fas fa-times me-1"></i> Sem Estoque
                </span>
            @endif

            @if(!$product->active)
                <span class="badge bg-warning">
                    <i class="fas fa-pause me-1"></i> Inativo
                </span>
            @endif
        </div>

        <!-- Preço -->
        <div class="mb-4">
            <h2 class="text-primary">{{ $product->formatted_price }}</h2>
            <small class="text-muted">SKU: {{ $product->sku }}</small>
        </div>

        <!-- Descrição -->
        @if($product->description)
            <div class="mb-4">
                <h5>Descrição</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
        @endif

        <!-- Informações do Vendedor -->
        <div class="card bg-light mb-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-user me-2"></i> Vendido por
                </h6>
                <p class="card-text">
                    <strong>{{ $product->user->name }}</strong><br>
                    <small class="text-muted">
                        Cadastrado em {{ $product->created_at->format('d/m/Y') }}
                    </small>
                </p>
            </div>
        </div>

        <!-- Ações -->
        <div class="d-grid gap-2">
            @if($product->isInStock() && $product->active)
                <button class="btn btn-success btn-lg" disabled>
                    <i class="fas fa-shopping-cart me-2"></i>
                    Adicionar ao Carrinho (Em breve)
                </button>
            @else
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="fas fa-times me-2"></i>
                    Produto Indisponível
                </button>
            @endif

            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Voltar aos Produtos
            </a>
        </div>
    </div>
</div>

<!-- Produtos Relacionados -->
@if($relatedProducts->count() > 0)
    <hr class="my-5">
    <h3 class="mb-4">
        <i class="fas fa-tags me-2"></i>
        Outros produtos em {{ $product->category->name }}
    </h3>

    <div class="row">
        @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        @if($relatedProduct->primaryImage)
                            <img src="{{ $relatedProduct->primaryImage->full_url }}"
                                 class="card-img-top" alt="{{ $relatedProduct->name }}"
                                 style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 150px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif

                        @if(!$relatedProduct->isInStock())
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                Sem estoque
                            </span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <p class="text-primary fw-bold">{{ $relatedProduct->formatted_price }}</p>
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $relatedProduct) }}"
                               class="btn btn-outline-primary btn-sm w-100">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
