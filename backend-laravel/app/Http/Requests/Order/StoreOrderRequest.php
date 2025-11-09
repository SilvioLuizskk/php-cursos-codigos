<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'shipping_address' => [
                'required',
                'array',
            ],
            'shipping_address.street' => [
                'required',
                'string',
                'max:255',
            ],
            'shipping_address.number' => [
                'required',
                'string',
                'max:20',
            ],
            'shipping_address.complement' => [
                'nullable',
                'string',
                'max:255',
            ],
            'shipping_address.city' => [
                'required',
                'string',
                'max:100',
            ],
            'shipping_address.state' => [
                'required',
                'string',
                'size:2',
            ],
            'shipping_address.zip_code' => [
                'required',
                'string',
                'regex:/^\d{5}-?\d{3}$/',
            ],
            'billing_address' => [
                'nullable',
                'array',
            ],
            'payment_method' => [
                'required',
                'string',
                'in:credit_card,debit_card,pix,boleto',
            ],
            'payment_data' => [
                'nullable',
                'array',
            ],
            'coupon_code' => [
                'nullable',
                'string',
                'max:50',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
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
            'shipping_address.required' => 'O endereço de entrega é obrigatório.',
            'shipping_address.street.required' => 'A rua é obrigatória.',
            'shipping_address.number.required' => 'O número é obrigatório.',
            'shipping_address.city.required' => 'A cidade é obrigatória.',
            'shipping_address.state.required' => 'O estado é obrigatório.',
            'shipping_address.state.size' => 'O estado deve ter 2 caracteres.',
            'shipping_address.zip_code.required' => 'O CEP é obrigatório.',
            'shipping_address.zip_code.regex' => 'O CEP deve estar no formato 00000-000.',
            'payment_method.required' => 'O método de pagamento é obrigatório.',
            'payment_method.in' => 'Método de pagamento inválido.',
            'notes.max' => 'As observações não podem ter mais de 500 caracteres.',
        ];
    }
}
