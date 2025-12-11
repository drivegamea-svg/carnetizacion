<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEspecialidadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $especialidadId = $this->route('especialidad')->id;

        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\(\)_]+$/',
                Rule::unique('especialidades')->whereNull('deleted_at')->ignore($especialidadId)
            ],
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre de la especialidad es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.regex' => 'Solo se permiten letras, números, espacios, paréntesis y guiones.',
            'nombre.unique' => 'Esta especialidad ya existe en el sistema.',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'nombre de la especialidad',
            'descripcion' => 'descripción',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => strtoupper(trim($this->nombre))
            ]);
        }
        
        if ($this->has('descripcion')) {
            $this->merge([
                'descripcion' => strtoupper(trim($this->descripcion))
            ]);
        }
    }
}