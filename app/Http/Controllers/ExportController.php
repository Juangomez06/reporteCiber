<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private function casosFiltrados(Request $request)
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

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }
        if ($institucionId = $request->get('institucion_id')) {
            $query->where('institucion_id', $institucionId);
        }

        return $query->get();
    }

    public function csv(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Caso::class);

        $casos = $this->casosFiltrados($request);

        return response()->streamDownload(function () use ($casos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Código', 'Institución', 'Tipo', 'Plataforma', 'Estado', 'Prioridad', 'Orientador', 'Anónimo', 'Fecha']);

            foreach ($casos as $caso) {
                fputcsv($out, [
                    $caso->codigo,
                    $caso->institucion->nombre,
                    $caso->tipo_acoso,
                    $caso->plataforma,
                    $caso->estado,
                    $caso->prioridad,
                    $caso->orientador->name ?? '',
                    $caso->anonimo ? 'Sí' : 'No',
                    $caso->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($out);
        }, 'informe-casos-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function pdf(Request $request)
    {
        $this->authorize('viewAny', Caso::class);

        $casos = $this->casosFiltrados($request);

        // Requiere: composer require barryvdh/laravel-dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('casos.pdf', compact('casos'));

        return $pdf->download('informe-casos-'.now()->format('Y-m-d').'.pdf');
    }
}
