<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Mis casos</h2>
            <a href="{{ route('casos.reportar') }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium">
                Reportar un caso de bullying
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">
                Si tú o alguien que conoces está viviendo una situación de ciberacoso, puedes
                <a href="{{ route('casos.reportar') }}" target="_blank" class="font-semibold underline">reportarlo aquí</a>,
                de forma anónima si lo prefieres.
            </div>

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
                                <td class="px-4 py-3 font-mono">
                                    {{ $caso->codigo }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst(str_replace('_', ' ', $caso->tipo_acoso)) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst(str_replace('_', ' ', $caso->estado)) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $caso->created_at->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('casos.show', $caso) }}"
                                       class="text-indigo-600">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-6 text-center text-gray-500">
                                    No tienes casos asignados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $casos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>