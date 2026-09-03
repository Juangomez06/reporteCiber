<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Casos</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-4 flex gap-3 items-center">
                <select name="estado" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    @foreach (\App\Models\Caso::ESTADOS as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') == $estado)>{{ ucfirst(str_replace('_',' ',$estado)) }}</option>
                    @endforeach
                </select>
                <select name="institucion_id" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">Todas las instituciones</option>
                    @foreach ($instituciones as $institucion)
                        <option value="{{ $institucion->id }}" @selected(request('institucion_id') == $institucion->id)>{{ $institucion->nombre }}</option>
                    @endforeach
                </select>
                <a href="{{ route('casos.export.csv', request()->query()) }}" class="ml-auto px-3 py-2 bg-gray-800 text-white rounded-md text-sm">Exportar CSV</a>
                <a href="{{ route('casos.export.pdf', request()->query()) }}" class="px-3 py-2 bg-gray-200 text-gray-800 rounded-md text-sm">Exportar PDF</a>
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Institución</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Orientador</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($casos as $caso)
                            <tr>
                                <td class="px-4 py-3 font-mono">{{ $caso->codigo }}</td>
                                <td class="px-4 py-3">{{ $caso->institucion->nombre }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ',$caso->tipo_acoso)) }}</td>
                                <td class="px-4 py-3"><span class="px-2 py-1 rounded bg-gray-100">{{ ucfirst(str_replace('_',' ',$caso->estado)) }}</span></td>
                                <td class="px-4 py-3">{{ $caso->orientador->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $caso->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('casos.show', $caso) }}" class="text-indigo-600">Ver</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No hay casos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $casos->links() }}</div>
        </div>
    </div>
</x-app-layout>
