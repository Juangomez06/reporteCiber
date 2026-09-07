<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1 { font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Informe de casos — {{ now()->format('d/m/Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th><th>Institución</th><th>Tipo</th><th>Plataforma</th>
                <th>Estado</th><th>Prioridad</th><th>Orientador</th><th>Anónimo</th><th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($casos as $caso)
                <tr>
                    <td>{{ $caso->codigo }}</td>
                    <td>{{ $caso->institucion->nombre }}</td>
                    <td>{{ $caso->tipo_acoso }}</td>
                    <td>{{ $caso->plataforma }}</td>
                    <td>{{ $caso->estado }}</td>
                    <td>{{ $caso->prioridad }}</td>
                    <td>{{ $caso->orientador->name ?? '—' }}</td>
                    <td>{{ $caso->anonimo ? 'Sí' : 'No' }}</td>
                    <td>{{ $caso->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
