<?php

namespace App\Http\Requests;

use App\Enums\Categorias;
use App\Enums\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SolicitacaoRequest extends FormRequest
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
        $regrasObrigatorias = $this->isMethod('POST');

        return [
            'titulo' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'descricao' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],

            'categoria' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                Rule::enum(Categorias::class),
            ],

            'status' => [
                'sometimes',
                Rule::enum(Status::class),
            ],

            'usuario_id' => [
                'sometimes',
                'uuid',
                'exists:users,id',
            ],
        ];
    }
    public function messages(): array
{
    return [
        'titulo.required' => 'O título é obrigatório.',
        'titulo.string' => 'O título deve ser um texto.',
        'titulo.max' => 'O título não pode ter mais de :max caracteres.',

        'descricao.required' => 'A descrição é obrigatória.',
        'descricao.string' => 'A descrição deve ser um texto.',
        'descricao.max' => 'A descrição não pode ter mais de :max caracteres.',

        'categoria.required' => 'A categoria é obrigatória.',

        'status.enum' => 'O status selecionado é inválido.',

        'usuario_id.required' => 'O usuário é obrigatório.',
        'usuario_id.uuid' => 'O ID do usuário deve ser um UUID válido.',
        'usuario_id.exists' => 'O usuário informado não existe.',
        'categoria.enum' => 'A categoria selecionada é inválida.',        
    ];
}
}
