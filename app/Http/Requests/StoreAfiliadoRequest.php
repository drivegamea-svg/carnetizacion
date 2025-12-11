<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAfiliadoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Datos básicos
            'ci' => [
                'required',
                'string',
                'min:5',
                'max:20',
                'regex:/^[0-9]+(-[A-Z]{2})?$/',  // Acepta 10020292 o 10020292-HG
                Rule::unique('afiliados')->whereNull('deleted_at')
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
            
            // Foto (al menos una opción debe estar presente)
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_data' => 'nullable|string',
            
            // Huella (obligatoria)
            'huella' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'huella_data' => 'nullable|string', // Opcional para futura funcionalidad con cámara
            
            // ===== NUEVO: Archivos adicionales =====
            'archivos' => 'nullable|array|max:10', // Máximo 10 archivos
            'archivos.*.tipo' => [
                'required_with:archivos.*.archivo',
                Rule::in(['DNI', 'CERTIFICADO', 'CONTRATO', 'FOTO_EXTRA', 'OTRO'])
            ],
            'archivos.*.descripcion' => 'nullable|string|max:255',
            'archivos.*.archivo' => [
                'required_with:archivos.*.tipo',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120' // 5MB
            ],
        ];
    }

    public function messages()
    {
        return [
            // CI
            'ci.required' => 'El número de CI es obligatorio.',
            'ci.min' => 'El CI debe tener al menos 5 dígitos.',
            'ci.max' => 'El CI no puede exceder 20 caracteres.',
            'ci.regex' => 'El CI debe tener formato válido (ej: 10020292 o 10020292-HG).',
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
            'fecha_nacimiento.date_format' => 'El formato de fecha debe ser DD/MM/AAAA. Ej: 26/08/1996',
            'fecha_nacimiento.before' => 'El afiliado debe ser mayor de 18 años.',
            
            // Género
            'genero.required' => 'El género es obligatorio.',
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
            'organizacion_social_id.exists' => 'La organización social seleccionada no existe.',
            'sector_economico_id.required' => 'El sector económico es obligatorio.',
            'sector_economico_id.exists' => 'El sector económico seleccionado no existe.',
            
            // Foto
            'foto.image' => 'El archivo debe ser una imagen válida.',
            'foto.mimes' => 'La imagen debe ser JPEG, PNG o JPG.',
            'foto.max' => 'La imagen no puede ser mayor a 2MB.',
            
            // Huella
            'huella.required' => 'La foto de la huella dactilar es obligatoria.',
            'huella.image' => 'El archivo de la huella debe ser una imagen válida.',
            'huella.mimes' => 'La huella debe ser formato JPG, PNG o JPEG.',
            'huella.max' => 'La imagen de la huella no puede superar los 2MB.',
            
            // ===== NUEVO: Mensajes para archivos adicionales =====
            'archivos.array' => 'Los archivos deben ser enviados en formato válido.',
            'archivos.max' => 'No se pueden adjuntar más de 10 archivos.',
            
            'archivos.*.tipo.required_with' => 'El tipo de documento es obligatorio cuando se adjunta un archivo.',
            'archivos.*.tipo.in' => 'El tipo de documento seleccionado no es válido.',
            
            'archivos.*.descripcion.max' => 'La descripción no puede exceder 255 caracteres.',
            
            'archivos.*.archivo.required_with' => 'Debe seleccionar un archivo para el tipo de documento.',
            'archivos.*.archivo.file' => 'El archivo adjunto no es válido.',
            'archivos.*.archivo.mimes' => 'El archivo debe ser: PDF, JPG, JPEG, PNG, DOC o DOCX.',
            'archivos.*.archivo.max' => 'El archivo no puede exceder 5MB.',
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
            'huella' => 'huella dactilar',
            'huella_data' => 'huella desde cámara',
            
            // ===== NUEVO: Atributos para archivos =====
            'archivos' => 'documentos adjuntos',
            'archivos.*.tipo' => 'tipo de documento',
            'archivos.*.descripcion' => 'descripción del documento',
            'archivos.*.archivo' => 'archivo adjunto',
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

        // Validación condicional para foto
        $cameraOption = $this->input('_camera_option', 'upload');
        
        if ($cameraOption === 'camera') {
            $this->merge([
                'foto' => null // Limpiar el campo de archivo si se usa cámara
            ]);
        } else {
            $this->merge([
                'foto_data' => null // Limpiar el campo de data si se usa upload
            ]);
        }
        
        // Validación condicional para huella (futura funcionalidad)
        $huellaCameraOption = $this->input('_huella_camera_option', 'upload');
        
        if ($huellaCameraOption === 'camera') {
            $this->merge([
                'huella' => null // Limpiar el campo de archivo si se usa cámara
            ]);
        } else {
            $this->merge([
                'huella_data' => null // Limpiar el campo de data si se usa upload
            ]);
        }
        
        // Filtrar archivos vacíos
        if ($this->has('archivos') && is_array($this->archivos)) {
            $archivosFiltrados = array_filter($this->archivos, function($archivo) {
                return isset($archivo['tipo']) && isset($archivo['archivo']);
            });
            
            $this->merge([
                'archivos' => array_values($archivosFiltrados) // Reindexar array
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validar que al menos una opción de foto esté presente
            if (empty($this->foto) && empty($this->foto_data)) {
                $validator->errors()->add(
                    'foto', 
                    'Debe subir una foto o tomar una con la cámara.'
                );
            }
            
            // Validar que al menos una opción de huella esté presente
            if (empty($this->huella) && empty($this->huella_data)) {
                $validator->errors()->add(
                    'huella', 
                    'Debe subir una foto de la huella.'
                );
            }
            
            // ===== NUEVO: Validaciones adicionales para archivos =====
            if ($this->has('archivos') && is_array($this->archivos)) {
                foreach ($this->archivos as $index => $archivo) {
                    // Validar que si hay tipo, también hay archivo
                    if (isset($archivo['tipo']) && !isset($archivo['archivo'])) {
                        $validator->errors()->add(
                            "archivos.{$index}.archivo",
                            'Debe adjuntar un archivo para el tipo de documento seleccionado.'
                        );
                    }
                    
                    // Validar que si hay archivo, también hay tipo
                    if (isset($archivo['archivo']) && !isset($archivo['tipo'])) {
                        $validator->errors()->add(
                            "archivos.{$index}.tipo",
                            'Debe seleccionar un tipo de documento para el archivo adjuntado.'
                        );
                    }
                    
                    // Validar tamaño del archivo (5MB máximo)
                    if (isset($archivo['archivo'])) {
                        if ($archivo['archivo']->getSize() > 5 * 1024 * 1024) {
                            $validator->errors()->add(
                                "archivos.{$index}.archivo",
                                'El archivo no puede exceder 5MB.'
                            );
                        }
                    }
                }
            }
        });
    }
}