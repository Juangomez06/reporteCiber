<?php

namespace App\Services;

use App\Models\Caso;
use App\Models\User;
use App\Notifications\CasoAsignadoNotification;
use App\Notifications\CasoCreadoNotification;
use App\Notifications\CasoEstadoActualizadoNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notification as Notifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class CasoService
{
    public function crear(array $datos, ?User $reporter, array $evidencias = []): Caso
    {
        $anonimo = (bool) ($datos['anonimo'] ?? false);

        $caso = Caso::create([
            'institucion_id' => $datos['institucion_id'],
            'reporter_id' => $anonimo ? null : $reporter?->id,
            'anonimo' => $anonimo,
            'tipo_acoso' => $datos['tipo_acoso'],
            'plataforma' => $datos['plataforma'],
            'descripcion' => $datos['descripcion'],
        ]);

        foreach ($evidencias as $archivo) {
            $this->adjuntarEvidencia($caso, $archivo);
        }

        $caso->registrarHistorial('creado', null, $caso->estado);

        $coordinadores = User::where('role', User::ROLE_COORDINADOR)
            ->where(function ($q) use ($caso) {
                $q->where('institucion_id', $caso->institucion_id)->orWhereNull('institucion_id');
            })->get();

        Notification::send($coordinadores, new CasoCreadoNotification($caso));

        return $caso;
    }

    public function adjuntarEvidencia(Caso $caso, UploadedFile $archivo): void
    {
        $ruta = $archivo->store("evidencias/{$caso->id}", 'local');

        $caso->evidencias()->create([
            'ruta' => $ruta,
            'nombre_original' => $archivo->getClientOriginalName(),
            'mime' => $archivo->getClientMimeType(),
            'tamano' => $archivo->getSize(),
        ]);

        $caso->registrarHistorial('evidencia_subida', null, $archivo->getClientOriginalName());
    }

    public function asignar(Caso $caso, User $orientador): Caso
    {
        $anterior = $caso->orientador_id;
        $caso->update([
            'orientador_id' => $orientador->id,
            'estado' => $caso->estado === 'nuevo' ? 'asignado' : $caso->estado,
        ]);

        $caso->registrarHistorial('asignado', (string) $anterior, (string) $orientador->id);
        $orientador->notify(new CasoAsignadoNotification($caso));

        return $caso;
    }

    public function cambiarEstado(Caso $caso, string $estadoNuevo, ?string $prioridad = null): Caso
    {
        $anterior = $caso->estado;

        $caso->estado = $estadoNuevo;
        if ($prioridad) {
            $caso->prioridad = $prioridad;
        }
        if ($estadoNuevo === 'resuelto' && ! $caso->resuelto_at) {
            $caso->resuelto_at = now();
        }
        $caso->save();

        $caso->registrarHistorial('cambio_estado', $anterior, $estadoNuevo);

        if ($caso->reporter && ! $caso->anonimo) {
            $caso->reporter->notify(new CasoEstadoActualizadoNotification($caso, $anterior));
        }

        return $caso;
    }

    public function agregarNota(Caso $caso, User $autor, string $contenido, bool $privada = true): void
    {
        $caso->notas()->create([
            'autor_id' => $autor->id,
            'contenido' => $contenido,
            'privada' => $privada,
        ]);

        $caso->registrarHistorial('nota_agregada');
    }
}
