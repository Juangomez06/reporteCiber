<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCasoRequest;
use App\Http\Requests\UpdateCasoEstadoRequest;
use App\Http\Resources\CasoResource;
use App\Models\Caso;
use App\Models\User;
use App\Services\CasoService;
use Illuminate\Http\Request;

class CasoController extends Controller
{
    public function __construct(private CasoService $casos) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Caso::with(['institucion', 'orientador'])->latest();

        if ($user->isEstudiante()) {
            $query->where(function ($q) use ($user) {
                $q->where('orientador_id', $user->id)->orWhere('reporter_id', $user->id);
            });
        } elseif ($user->institucion_id) {
            $query->where('institucion_id', $user->institucion_id);
        }

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        return CasoResource::collection($query->paginate(20));
    }

    /** Reporte vía API (autenticado o anónimo con token público limitado). */
    public function store(StoreCasoRequest $request)
    {
        $caso = $this->casos->crear($request->validated(), $request->user(), $request->file('evidencias', []));

        return (new CasoResource($caso))->response()->setStatusCode(201);
    }

    public function show(Request $request, Caso $caso)
    {
        $this->authorize('view', $caso);

        return new CasoResource($caso->load(['institucion', 'orientador', 'reporter', 'evidencias', 'notas', 'historial']));
    }

    public function updateEstado(UpdateCasoEstadoRequest $request, Caso $caso)
    {
        $caso = $this->casos->cambiarEstado($caso, $request->estado, $request->prioridad);

        return new CasoResource($caso);
    }

    public function asignar(Request $request, Caso $caso)
    {
        $this->authorize('assign', $caso);
        $request->validate(['orientador_id' => ['required', 'exists:users,id']]);

        $caso = $this->casos->asignar($caso, User::findOrFail($request->orientador_id));

        return new CasoResource($caso);
    }
}
