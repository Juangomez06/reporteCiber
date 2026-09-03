<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCasoNotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('caso')) ?? false;
    }

    public function rules(): array
    {
        return [
            'contenido' => ['required', 'string', 'min:3', 'max:3000'],
            'privada' => ['sometimes', 'boolean'],
        ];
    }
}
