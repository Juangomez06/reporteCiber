<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte enviado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow text-center">
        <h1 class="text-xl font-bold text-gray-800 mb-2">Reporte recibido</h1>
        <p class="text-gray-600 mb-4">Tu caso fue registrado con el siguiente código de seguimiento:</p>
        <p class="text-2xl font-mono font-bold text-red-600 mb-4">{{ $codigo }}</p>
        <p class="text-sm text-gray-500">Guarda este código; un orientador dará seguimiento pronto.</p>
    </div>
</body>
</html>
