<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sede',
        'jornada',
        'fechaini',
        'estrato',
        'sisben',
        'doc',
        'tipodoc',
        'apellido1',
        'apellido2',
        'nombre1',
        'nombre2',
        'genero',
        'fecha_nacimiento',
        'barrio',
        'eps',
        'tipo_sangre',
        'discapacidad',
        'pais_origen',
        'telefono',
    ];

    protected $casts = [
        'fechaini' => 'date',
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
