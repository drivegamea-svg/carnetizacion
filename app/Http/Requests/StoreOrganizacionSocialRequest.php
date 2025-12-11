<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizacionSocialRequest extends FormRequest
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
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\,\.\-\(\)_]+$/',
                Rule::unique('organizaciones_sociales')->whereNull('deleted_at')
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la organización social es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.regex' => 'Solo se permiten letras, números, espacios, comas, puntos, paréntesis y guiones.',
            'nombre.unique' => 'Esta organización social ya existe en el sistema.',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'nombre de la organización social',
            'descripcion' => 'descripción',
        ];
    }
}