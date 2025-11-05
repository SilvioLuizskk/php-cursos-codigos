@extends('layouts.app')

@section('title', 'Novo Produto - Catálogo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus me-2"></i> Novo Produto
                </h4>
            </div>

            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                       id="sku" name="sku" value="{{ old('sku') }}" required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço (R$) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price') }}"
                                       step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                       min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Imagens do Produto</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            Selecione uma ou mais imagens. Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB por imagem.
                        </div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="active" name="active" value="1"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">
                                Produto ativo
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Salvar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview de imagens
    document.getElementById('images').addEventListener('change', function(e) {
        const files = e.target.files;
        const preview = document.getElementById('image-preview');

        if (preview) {
            preview.remove();
        }

        if (files.length > 0) {
            const previewContainer = document.createElement('div');
            previewContainer.id = 'image-preview';
            previewContainer.className = 'mt-3';

            const title = document.createElement('h6');
            title.textContent = 'Preview das Imagens:';
            previewContainer.appendChild(title);

            const row = document.createElement('div');
            row.className = 'row g-2';

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';

                    const img = document.createElement('img');
                    img.className = 'img-thumbnail';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.width = '100%';

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    const caption = document.createElement('small');
                    caption.className = 'text-muted d-block text-center mt-1';
                    caption.textContent = index === 0 ? 'Imagem Principal' : `Imagem ${index + 1}`;

                    col.appendChild(img);
                    col.appendChild(caption);
                    row.appendChild(col);
                }
            });

            previewContainer.appendChild(row);
            e.target.parentNode.appendChild(previewContainer);
        }
    });

    // Auto-gerar SKU baseado no nome
    document.getElementById('name').addEventListener('input', function(e) {
        const skuField = document.getElementById('sku');
        if (!skuField.value) {
            const sku = e.target.value
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .substring(0, 10);
            skuField.value = sku;
        }
    });
</script>
@endpush
