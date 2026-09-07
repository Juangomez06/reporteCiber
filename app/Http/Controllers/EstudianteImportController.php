<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;

class EstudianteImportController extends Controller
{
    public function showForm()
    {
        return view('estudiantes.import');
    }

    //funcion para importar estudiantes desde un archivo Excel
    public function import(Request $request)
    {
        set_time_limit(300); // 5 minutos

        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            // Importar estudiantes desde el archivo Excel
            Excel::import(new EstudiantesImport, $request->file('archivo'));
            // Redirigir con un mensaje de éxito
            return redirect()->back()->with('success', 'Estudiantes importados correctamente.');
        } catch (\Exception $e) {
            // Redirigir con un mensaje de error
            return redirect()->back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
