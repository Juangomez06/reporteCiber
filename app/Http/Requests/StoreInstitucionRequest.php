<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstitucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCoordinador() ?? false;
    }

    public function rules(): array
    {
        $institucionId = $this->route('institucion')?->id;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'codigo' => ['required', 'string', 'max:50', Rule::unique('instituciones', 'codigo')->ignore($institucionId)],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'contacto_email' => ['nullable', 'email', 'max:255'],
            'contacto_telefono' => ['nullable', 'string', 'max:30'],
            'activa' => ['sometimes', 'boolean'],
        ];
    }
}
