<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstitucionRequest;
use App\Http\Resources\InstitucionResource;
use App\Models\Institucion;

class InstitucionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Institucion::class, 'institucion');
    }

    public function index()
    {
        return InstitucionResource::collection(
            Institucion::withCount('casos')->orderBy('nombre')->paginate(20)
        );
    }

    public function store(StoreInstitucionRequest $request)
    {
        $institucion = Institucion::create($request->validated());

        return (new InstitucionResource($institucion))->response()->setStatusCode(201);
    }

    public function show(Institucion $institucion)
    {
        return new InstitucionResource($institucion->loadCount('casos'));
    }

    public function update(StoreInstitucionRequest $request, Institucion $institucion)
    {
        $institucion->update($request->validated());

        return new InstitucionResource($institucion);
    }

    public function destroy(Institucion $institucion)
    {
        $institucion->delete();

        return response()->json(null, 204);
    }
}
