<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateProfileRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'], 
            'email' => [
                'sometimes', 
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->user()->id), 
            ]
        ];
    }

    public function messages(): array 
    {
        return [
            'name.required' => 'El nombre es obligatorio.', 
            'email.required' => 'El correo electrónico es obligatorio.', 
            'email.email' => 'El correo electrónico debe ser válido.', 
            'email.unique' => 'Este correo ya está en uso.', 
        ];
    }
}
