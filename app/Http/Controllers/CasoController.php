<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCasoNotaRequest;
use App\Http\Requests\StoreCasoRequest;
use App\Http\Requests\UpdateCasoEstadoRequest;
use App\Models\Caso;
use App\Models\Institucion;
use App\Models\User;
use App\Services\CasoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CasoController extends Controller
{
    public function __construct(private CasoService $casos)
    {
    }

    /** Formulario público de reporte (autenticado o anónimo). */
    public function create(): View
    {
        $instituciones = Institucion::where('activa', true)->orderBy('nombre')->get();

        return view('casos.reportar', compact('instituciones'));
    }

    public function store(StoreCasoRequest $request): RedirectResponse
    {
        $caso = $this->casos->crear(
            $request->validated(),
            $request->user(),
            $request->file('evidencias', [])
        );

        return redirect()->route('casos.confirmacion', $caso->codigo)
            ->with('status', 'Tu reporte fue registrado. Guarda tu código de seguimiento.');
    }

    public function confirmacion(string $codigo): View
    {
        return view('casos.confirmacion', ['codigo' => $codigo]);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Caso::class);

        $user = $request->user();
        $query = Caso::with(['institucion', 'orientador'])->latest();

        if ($user->isEstudiante()) {
            $query->where(function ($q) use ($user) {
                $q->where('orientador_id', $user->id)->orWhere('reporter_id', $user->id);
            });
        } elseif ($user->institucion_id) {
            $query->where('institucion_id', $user->institucion_id);
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }
        if ($institucionId = $request->get('institucion_id')) {
            $query->where('institucion_id', $institucionId);
        }

        $casos = $query->paginate(15)->withQueryString();
        $instituciones = Institucion::orderBy('nombre')->get();

        return view('casos.index', compact('casos', 'instituciones'));
    }

    public function show(Caso $caso): View
    {
        $this->authorize('view', $caso);

        $caso->load(['institucion', 'orientador', 'evidencias', 'notas.autor', 'historial.usuario']);
        $orientadores = User::where('role', User::ROLE_ESTUDIANTE)
            ->when($caso->institucion_id, fn ($q) => $q->where('institucion_id', $caso->institucion_id))
            ->get();

        return view('casos.show', compact('caso', 'orientadores'));
    }

    public function asignar(Request $request, Caso $caso): RedirectResponse
    {
        $this->authorize('assign', $caso);

        $request->validate(['orientador_id' => ['required', 'exists:users,id']]);

        $this->casos->asignar($caso, User::findOrFail($request->orientador_id));

        return back()->with('status', 'Caso asignado correctamente.');
    }

    public function actualizarEstado(UpdateCasoEstadoRequest $request, Caso $caso): RedirectResponse
    {
        $this->casos->cambiarEstado($caso, $request->estado, $request->prioridad);

        return back()->with('status', 'Estado del caso actualizado.');
    }

    public function agregarNota(StoreCasoNotaRequest $request, Caso $caso): RedirectResponse
    {
        $this->casos->agregarNota($caso, $request->user(), $request->contenido, $request->boolean('privada', true));

        return back()->with('status', 'Nota agregada.');
    }
}
