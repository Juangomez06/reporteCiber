<?php

namespace App\Http\Requests;

use App\Models\Caso;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCasoEstadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('caso')) ?? false;
    }

    public function rules(): array
    {
        return [
            'estado' => ['required', Rule::in(Caso::ESTADOS)],
            'prioridad' => ['sometimes', Rule::in(['baja', 'media', 'alta', 'critica'])],
        ];
    }
}
