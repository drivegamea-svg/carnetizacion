<?php

// app/Http/Requests/StoreAdultoResponsableRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdultoResponsableRequest extends FormRequest
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
            'numero_documento' => 'required|string|max:30',
            'sexo' => 'required|in:M,F',
            'parentesco' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'vive_con_menor' => 'nullable|boolean',
            'direccion_id' => 'nullable|exists:direcciones,id',
        ];
    }

    public function messages()
    {
        return [
            'nombres.required' => 'El nombre del adulto responsable es obligatorio.',
        ];
    }
}
