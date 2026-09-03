<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Mis casos</h2></x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Código</th>
                            <th class="px-4 py-3 text-left">Tipo</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($casos as $caso)
                            <tr>
                                <td class="px-4 py-3 font-mono">{{ $caso->codigo }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ',$caso->tipo_acoso)) }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ',$caso->estado)) }}</td>
                                <td class="px-4 py-3">{{ $caso->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('casos.show', $caso) }}" class="text-indigo-600">Ver</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No tienes casos asignados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $casos->links() }}</div>
        </div>
    </div>
</x-app-layout>
