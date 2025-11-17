<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SetCurrencyRequest extends FormRequest
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
            'currency_id' => ['required', 'exists:currencies,id']
        ];
    }

    public function messages(): array 
    {
        return [
            'currency_id.required' => 'Debes seleccionar una moneda.',
            'currency_id.exists' => 'La moneda seleccionada no es válida.'
        ];
    }
}
