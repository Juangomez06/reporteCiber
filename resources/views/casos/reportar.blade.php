<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reportar caso — Convivencia Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Reportar un caso</h1>
        <p class="text-gray-600 mb-6">Tu información es confidencial. Puedes reportar de forma anónima si lo prefieres.</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('casos.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Institución</label>
                <select name="institucion_id" required class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Selecciona tu institución</option>
                    @foreach ($instituciones as $institucion)
                        <option value="{{ $institucion->id }}" @selected(old('institucion_id') == $institucion->id)>{{ $institucion->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="anonimo" name="anonimo" value="1" @checked(old('anonimo'))>
                <label for="anonimo" class="text-sm text-gray-700">Deseo reportar de forma anónima (no se guardará mi identidad)</label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo de acoso</label>
                <select name="tipo_acoso" required class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Selecciona una opción</option>
                    @foreach (\App\Models\Caso::TIPOS as $tipo)
                        <option value="{{ $tipo }}" @selected(old('tipo_acoso') == $tipo)>{{ ucfirst(str_replace('_', ' ', $tipo)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Plataforma</label>
                <select name="plataforma" required class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Selecciona una opción</option>
                    @foreach (\App\Models\Caso::PLATAFORMAS as $plataforma)
                        <option value="{{ $plataforma }}" @selected(old('plataforma') == $plataforma)>{{ ucfirst(str_replace('_', ' ', $plataforma)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción de lo sucedido</label>
                <textarea name="descripcion" rows="6" required minlength="20" class="mt-1 block w-full rounded-md border-gray-300">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Evidencia (opcional, máx. 5 archivos)</label>
                <input type="file" name="evidencias[]" multiple class="mt-1 block w-full">
                <p class="text-xs text-gray-500 mt-1">Formatos: imágenes, PDF, audio, video, docx. Máx. 10MB c/u.</p>
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-red-600 text-white rounded-md font-medium">Enviar reporte</button>
        </form>
    </div>
</body>
</html>
