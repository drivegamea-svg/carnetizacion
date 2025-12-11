<?php

// app/Http/Requests/StoreDireccionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDireccionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'departamento' => 'required|string|max:100',
            'zona_id' => 'nullable|exists:zonas,id',
            'calle' => 'nullable|string|max:100',
            'numero_puerta' => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'departamento.required' => 'El departamento es obligatorio.',
        ];
    }
}
