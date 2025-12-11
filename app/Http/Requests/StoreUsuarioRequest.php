<?php
// app/Http/Requests/StoreTipoEmpresaRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTipoEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_empresa')->whereNull('deleted_at')
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del tipo de empresa es obligatorio.',
            'nombre.unique' => 'Este tipo de empresa ya existe (incluso si fue eliminado anteriormente).',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ];
    }
}