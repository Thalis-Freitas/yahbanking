<?php

namespace App\Http\Requests;

use App\Models\Investiment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestimentUpdateRequest extends FormRequest
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
            'abbreviation' => 'required',
            'name' => [
                'required',
                Rule::unique('investiments')->ignore(Investiment::find($this->route('investiment'))->id),
            ],
            'description' => 'required',
        ];

    }

    public function messages()
    {
        return [
            'required' => 'Este campo é obrigatório.',
            'name.unique' => 'Este nome comercial já está em uso.',
        ];
    }
}
