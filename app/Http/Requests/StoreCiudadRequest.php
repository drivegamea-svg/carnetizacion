<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCiudadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\(\)_]+$/',
                Rule::unique('ciudades')->whereNull('deleted_at')
            ],
            'departamento' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\(\)_]+$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.regex' => 'Solo se permiten letras, números, espacios, paréntesis y guiones.',
            'nombre.unique' => 'Esta ciudad ya existe en el sistema.',
            'departamento.required' => 'El departamento es obligatorio.',
            'departamento.min' => 'El departamento debe tener al menos 2 caracteres.',
            'departamento.max' => 'El departamento no puede exceder 255 caracteres.',
            'departamento.regex' => 'Solo se permiten letras, números, espacios, paréntesis y guiones.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'nombre de la ciudad',
            'departamento' => 'departamento',
        ];
    }
}