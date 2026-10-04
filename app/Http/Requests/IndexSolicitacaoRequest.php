<?php

namespace App\Http\Requests;

use App\Enums\Categorias;
use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class IndexSolicitacaoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
        return [
            'data_inicio' => [
                'nullable',
                'date',
            ],

            'data_fim' => [
                'nullable',
                'date',
                'after_or_equal:data_inicio',
            ],

            'categoria' => [
                'nullable',
                 Rule::enum(Categorias::class),
            ],

            'status' => [
                'nullable',
                 Rule::enum(Status::class),
            ],

            'titulo' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
            return [
                'data_inicio.date' => 'A data inicial deve ser uma data válida.',
                'data_fim.date' => 'A data final deve ser uma data válida.',
                'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
                'categoria.enum' => 'A categoria selecionada é inválida.',
                'status.enum' => 'O status selecionado é inválido.',
                'titulo.string' => 'O título deve ser um texto.',
                'titulo.max' => 'O título deve ter no máximo 255 caracteres.',
                
            ];
    }
}
