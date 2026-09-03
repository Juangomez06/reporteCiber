<?php

namespace App\Http\Requests;

use App\Models\Caso;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCasoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Formulario público: cualquiera puede reportar (autenticado o anónimo)
        return true;
    }

    public function rules(): array
    {
        return [
            'institucion_id' => ['required', 'exists:instituciones,id'],
            'anonimo' => ['sometimes', 'boolean'],
            'tipo_acoso' => ['required', Rule::in(Caso::TIPOS)],
            'plataforma' => ['required', Rule::in(Caso::PLATAFORMAS)],
            'descripcion' => ['required', 'string', 'min:20', 'max:5000'],
            'evidencias' => ['sometimes', 'array', 'max:5'],
            'evidencias.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,mp4,mp3,webp,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.min' => 'Por favor describe la situación con al menos 20 caracteres para poder evaluarla.',
            'evidencias.*.mimes' => 'Formato de evidencia no permitido.',
            'evidencias.*.max' => 'Cada archivo de evidencia debe pesar máximo 10MB.',
        ];
    }
}
