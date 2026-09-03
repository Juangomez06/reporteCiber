<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function coordinador(Request $request): View
    {
        $user = $request->user();

        $base = Caso::query();
        if ($user->institucion_id) {
            $base->where('institucion_id', $user->institucion_id);
        }

        $porEstado = (clone $base)->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')->pluck('total', 'estado');

        $porTipo = (clone $base)->select('tipo_acoso', DB::raw('count(*) as total'))
            ->groupBy('tipo_acoso')->pluck('total', 'tipo_acoso');

        $porMes = (clone $base)
            ->select(DB::raw("strftime('%Y-%m', created_at) as mes"), DB::raw('count(*) as total'))
            ->groupBy('mes')->orderBy('mes')->pluck('total', 'mes');

        $totales = [
            'total' => (clone $base)->count(),
            'abiertos' => (clone $base)->whereNotIn('estado', ['resuelto', 'cerrado'])->count(),
            'resueltos' => (clone $base)->where('estado', 'resuelto')->count(),
            'criticos' => (clone $base)->where('prioridad', 'critica')->count(),
        ];

        $instituciones = Institucion::orderBy('nombre')->get();

        return view('coordinador.dashboard', compact('porEstado', 'porTipo', 'porMes', 'totales', 'instituciones'));
    }

    public function estudiante(Request $request): View
    {
        $user = $request->user();

        $casos = Caso::where('orientador_id', $user->id)
            ->orWhere('reporter_id', $user->id)
            ->latest()->paginate(10);

        return view('estudiante.dashboard', compact('casos'));
    }
}
