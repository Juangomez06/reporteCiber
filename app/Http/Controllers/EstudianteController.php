<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Estudiante::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre1', 'LIKE', "%{$search}%")
                  ->orWhere('nombre2', 'LIKE', "%{$search}%")
                  ->orWhere('apellido1', 'LIKE', "%{$search}%")
                  ->orWhere('apellido2', 'LIKE', "%{$search}%")
                  ->orWhere('doc', 'LIKE', "%{$search}%")
                  ->orWhere('telefono', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por sede
        if ($request->filled('sede')) {
            $query->where('sede', $request->sede);
        }

        // Filtro por jornada
        if ($request->filled('jornada')) {
            $query->where('jornada', $request->jornada);
        }

        // Obtener sedes y jornadas únicas para los filtros
        $sedes = Estudiante::distinct()
                           ->whereNotNull('sede')
                           ->where('sede', '!=', '')
                           ->pluck('sede')
                           ->filter()
                           ->values();

        $jornadas = Estudiante::distinct()
                              ->whereNotNull('jornada')
                              ->where('jornada', '!=', '')
                              ->pluck('jornada')
                              ->filter()
                              ->values();

        $estudiantes = $query->orderBy('apellido1')
                             ->orderBy('apellido2')
                             ->orderBy('nombre1')
                             ->paginate(20)
                             ->withQueryString();

        return view('estudiantes.index', compact('estudiantes', 'sedes', 'jornadas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        return view('estudiantes.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'sede' => 'nullable|string|max:255',
            'jornada' => 'nullable|string|max:255',
            'fechaini' => 'nullable|date',
            'estrato' => 'nullable|integer|between:1,6',
            'sisben' => 'nullable|string|max:255',
            'doc' => 'required|string|max:255',
            'tipodoc' => 'nullable|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'nombre1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'genero' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'barrio' => 'nullable|string|max:255',
            'eps' => 'nullable|string|max:255',
            'tipo_sangre' => 'nullable|string|max:10',
            'discapacidad' => 'nullable|string|max:255',
            'pais_origen' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
        ]);

        $estudiante->update($validated);

        return redirect()
            ->route('estudiantes.index')
            ->with('status', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        $nombreCompleto = trim(
            ($estudiante->nombre1 ?? '') . ' ' .
            ($estudiante->nombre2 ?? '') . ' ' .
            ($estudiante->apellido1 ?? '') . ' ' .
            ($estudiante->apellido2 ?? '')
        );

        $estudiante->delete();

        return redirect()
            ->route('estudiantes.index')
            ->with('status', "Estudiante {$nombreCompleto} eliminado exitosamente.");
    }
}
