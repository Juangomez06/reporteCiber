<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitucionRequest;
use App\Models\Institucion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstitucionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Institucion::class, 'institucion');
    }

    public function index(): View
    {
        $instituciones = Institucion::withCount('casos')->orderBy('nombre')->paginate(15);

        return view('instituciones.index', compact('instituciones'));
    }

    public function create(): View
    {
        return view('instituciones.create');
    }

    public function store(StoreInstitucionRequest $request): RedirectResponse
    {
        Institucion::create($request->validated());

        return redirect()->route('instituciones.index')->with('status', 'Institución creada correctamente.');
    }

    public function edit(Institucion $institucion): View
    {
        return view('instituciones.edit', compact('institucion'));
    }

    public function update(StoreInstitucionRequest $request, Institucion $institucion): RedirectResponse
    {
        $institucion->update($request->validated());

        return redirect()->route('instituciones.index')->with('status', 'Institución actualizada.');
    }

    public function destroy(Institucion $institucion): RedirectResponse
    {
        $institucion->delete();

        return redirect()->route('instituciones.index')->with('status', 'Institución eliminada.');
    }
}
