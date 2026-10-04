<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regrasObrigatorias = $this->isMethod('POST');

        return [
            'name' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                'string',
                'max:150',
            ],

            'email' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],

            'cpf' => [
                'sometimes',
                'nullable',
                'string',
                'size:14',
                Rule::unique('users', 'cpf')->ignore($this->route('user')),
            ],

            'password' => [
                $regrasObrigatorias ? 'required' : 'sometimes',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de :max caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.max' => 'O e-mail não pode ter mais de :max caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.size' => 'O CPF deve ter :size caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',

            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser um texto.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.max' => 'A senha não pode ter mais de :max caracteres.',
        ];
    }
}