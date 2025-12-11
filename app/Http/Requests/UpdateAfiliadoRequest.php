<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAfiliadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Obtener el id de afiliado desde la ruta de forma robusta
        $routeParam = $this->route('afiliado') ?? $this->route('id');
        if ($routeParam instanceof \App\Models\Afiliado) {
            $afiliadoId = $routeParam->id;
        } else {
            $afiliadoId = $routeParam;
        }

        return [
            // Datos básicos
            'ci' => [
                'required',
                'string',
                'min:5',
                'max:20',
                'regex:/^[0-9]+(-[A-Z]{2})?$/',
                Rule::unique('afiliados')->whereNull('deleted_at')->ignore($afiliadoId)
            ],
            'expedicion' => 'required|string|in:LP,SC,CBBA,OR,PT,CH,TJ,BE,PD,S/E',
            'nombres' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'apellido_paterno' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/'
            ],
            'apellido_materno' => [
                'nullable', 
                'string',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/'
            ],
            'fecha_nacimiento' => 'nullable|date_format:d/m/Y|before:-18 years',
            'genero' => 'required|in:MASCULINO,FEMENINO',
            'celular' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[0-9+\-\s]+$/'
            ],
            'direccion' => 'nullable|string|max:500',
            
            // Relaciones
            'ciudad_id' => 'required|exists:ciudades,id',
            'profesion_tecnica_id' => 'required|exists:profesiones_tecnicas,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'organizacion_social_id' => 'nullable|exists:organizaciones_sociales,id',
            'sector_economico_id' => 'required|exists:sectores_economicos,id',
            
            // Foto (opcional en actualización)
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_data' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            // CI
            'ci.required' => 'El número de CI es obligatorio.',
            'ci.min' => 'El CI debe tener al menos 5 dígitos.',
            'ci.max' => 'El CI no puede exceder 20 caracteres.',
            'ci.regex' => 'El CI solo puede contener números.',
            'ci.unique' => 'Este número de CI ya está registrado en el sistema.',
            
            // Expedición
            'expedicion.required' => 'La expedición del CI es obligatoria.',
            'expedicion.in' => 'La expedición seleccionada no es válida.',
            
            // Nombres
            'nombres.required' => 'Los nombres son obligatorios.',
            'nombres.min' => 'Los nombres deben tener al menos 2 caracteres.',
            'nombres.max' => 'Los nombres no pueden exceder 255 caracteres.',
            'nombres.regex' => 'Los nombres solo pueden contener letras y espacios.',
            
            // Apellidos
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            
            // Fecha nacimiento
            'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
            'fecha_nacimiento.before' => 'El afiliado debe ser mayor de 18 años.',
            
            // Género
                    'ci.regex' => 'El CI debe tener formato válido (ej: 10020292 o 10020292-HG).',
            'genero.in' => 'El género seleccionado no es válido.',
            
            // Celular
            'celular.required' => 'El número de celular es obligatorio.',
            'celular.min' => 'El celular debe tener al menos 8 dígitos.',
            'celular.max' => 'El celular no puede exceder 20 caracteres.',
            'celular.regex' => 'El celular solo puede contener números, espacios y guiones.',
            
            // Dirección
            'direccion.max' => 'La dirección no puede exceder 500 caracteres.',
            
            // Relaciones
            'ciudad_id.required' => 'La ciudad/municipio es obligatoria.',
            'ciudad_id.exists' => 'La ciudad seleccionada no existe.',
            'profesion_tecnica_id.required' => 'La profesión técnica es obligatoria.',
            'profesion_tecnica_id.exists' => 'La profesión técnica seleccionada no existe.',
            'especialidad_id.required' => 'La especialidad es obligatoria.',
            'especialidad_id.exists' => 'La especialidad seleccionada no existe.',
                    'fecha_nacimiento.date_format' => 'El formato de fecha debe ser DD/MM/AAAA. Ej: 26/08/1996',
            'sector_economico_id.required' => 'El sector económico es obligatorio.',
            'sector_economico_id.exists' => 'El sector económico seleccionado no existe.',
            
            // Foto
            'foto.image' => 'El archivo debe ser una imagen válida.',
            'foto.mimes' => 'La imagen debe ser JPEG, PNG o JPG.',
            'foto.max' => 'La imagen no puede ser mayor a 2MB.',
        ];
    }

    public function attributes()
    {
        return [
            'ci' => 'número de CI',
            'expedicion' => 'expedición de CI',
            'nombres' => 'nombres',
            'apellido_paterno' => 'apellido paterno',
            'apellido_materno' => 'apellido materno',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'genero' => 'género',
            'celular' => 'número de celular',
            'direccion' => 'dirección',
            'ciudad_id' => 'ciudad/municipio',
            'profesion_tecnica_id' => 'profesión técnica',
            'especialidad_id' => 'especialidad',
            'organizacion_social_id' => 'organización social',
            'sector_economico_id' => 'sector económico',
            'foto' => 'foto',
            'foto_data' => 'foto desde cámara',
        ];
    }

    protected function prepareForValidation()
    {
        // Convertir a mayúsculas y trim
        $textFields = [
            'ci', 'nombres', 'apellido_paterno', 'apellido_materno',
            'celular', 'direccion'
        ];

        foreach ($textFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => strtoupper(trim($this->$field))
                ]);
            }
        }
    }
}