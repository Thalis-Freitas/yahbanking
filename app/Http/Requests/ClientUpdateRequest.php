<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('clients')->ignore(Client::find($this->route('client'))->id),
            ],
            'avatar' => 'file|mimes:jpeg,jpg,png',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo é obrigatório.',
            'email.email' => 'Formato inválido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'avatar.mimes' => 'Deve estar no formato jpeg, jpg ou png.',
        ];
    }
}
