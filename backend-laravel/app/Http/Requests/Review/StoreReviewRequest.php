<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autenticação será verificada no controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'A avaliação é obrigatória.',
            'rating.integer' => 'A avaliação deve ser um número inteiro.',
            'rating.min' => 'A avaliação deve ser no mínimo 1 estrela.',
            'rating.max' => 'A avaliação deve ser no máximo 5 estrelas.',
            'title.string' => 'O título deve ser um texto.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'comment.string' => 'O comentário deve ser um texto.',
            'comment.max' => 'O comentário não pode ter mais de 1000 caracteres.',
        ];
    }
}
