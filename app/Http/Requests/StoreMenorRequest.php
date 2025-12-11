<?php

// app/Http/Requests/StoreMenorRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo' => 'required|in:M,F', // M para Masculino, F para Femenino
            'numero_documento' => 'required|string|max:30',
            'expedido' => 'required|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
            'direccion_id' => 'nullable|exists:direcciones,id',
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'El nombre del menor es obligatorio.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento debe ser anterior o igual a la fecha actual.',
        ];
    }
}
