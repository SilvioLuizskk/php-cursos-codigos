<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId)
            ],
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|json',
            'images' => 'nullable|json',
            'tags' => 'nullable|json',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'O nome do produto deve ser um texto.',
            'description.string' => 'A descrição do produto deve ser um texto.',
            'price.numeric' => 'O preço deve ser um valor numérico.',
            'price.min' => 'O preço deve ser maior ou igual a zero.',
            'stock_quantity.integer' => 'A quantidade em estoque deve ser um número inteiro.',
            'stock_quantity.min' => 'A quantidade em estoque deve ser maior ou igual a zero.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'sku.unique' => 'Este SKU já está sendo usado por outro produto.',
            'weight.numeric' => 'O peso deve ser um valor numérico.',
            'weight.min' => 'O peso deve ser maior ou igual a zero.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge(['is_active' => $this->boolean('is_active')]);
        }

        if ($this->has('is_featured')) {
            $this->merge(['is_featured' => $this->boolean('is_featured')]);
        }
    }
}
