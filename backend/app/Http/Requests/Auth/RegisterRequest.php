<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], 
            'password' => ['required', 'confirmed', Password::defaults()], 
            'currency_id' => ['required', 'exists:currencies,id'], 
        ];
    }

    public function messages(): array 
    {
        return [
            'name:required' => 'El nombre es obligatorio.', 
            'email.required' => 'El correo electrónico es obligatorio.', 
            'email.email' => 'El correo electrónico debe ser válido.', 
            'email.unique' => 'Este correo ya está registrado.', 
            'password.required' => 'La contraseña es obligatoria.', 
            'password.confirmed' => 'Las contraseñas no coinciden.', 
            'currency_id.required' => 'Debes seleccionar una moneda.', 
            'currency_id.exists' => 'La moneda seleccionada no es válida.', 
        ];
    }
}
