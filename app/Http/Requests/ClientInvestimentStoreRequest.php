<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientInvestimentStoreRequest extends FormRequest
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
            'invested_value' => 'required|numeric|min:0.01|max:9999999999.99'
        ];
    }

    public function messages()
    {
        return [
            'invested_value.required' => 'Este campo é obrigatório.',
            'invested_value.numeric' => 'Deve ser um número (se necessário, use "." para separar as casas decimais).',
            'invested_value.min' => 'Deve ser maior que 0.01.',
            'invested_value.max' => 'Deve ser menor que 9999999999.99.'
        ];
    }
}
