<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'quantity' => [
                'integer',
                'min:1',
                'max:99',
            ],
            'customization' => [
                'nullable',
                'array',
            ],
            'customization.size' => [
                'nullable',
                'string',
                'in:PP,P,M,G,GG,XG',
            ],
            'customization.color' => [
                'nullable',
                'string',
                'max:50',
            ],
            'customization.text' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'O produto é obrigatório.',
            'product_id.exists' => 'Produto não encontrado.',
            'quantity.min' => 'A quantidade mínima é 1.',
            'quantity.max' => 'A quantidade máxima é 99.',
            'customization.size.in' => 'Tamanho inválido.',
            'customization.text.max' => 'O texto personalizado não pode ter mais de 255 caracteres.',
        ];
    }
}
