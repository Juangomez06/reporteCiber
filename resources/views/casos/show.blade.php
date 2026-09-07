<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Caso {{ $caso->codigo }}</h2></x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-lg grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-500">Institución:</span> {{ $caso->institucion->nombre }}</div>
                <div><span class="text-gray-500">Reportante:</span> {{ $caso->anonimo ? 'Anónimo' : ($caso->reporter->name ?? '—') }}</div>
                <div><span class="text-gray-500">Tipo:</span> {{ ucfirst(str_replace('_',' ',$caso->tipo_acoso)) }}</div>
                <div><span class="text-gray-500">Plataforma:</span> {{ ucfirst(str_replace('_',' ',$caso->plataforma)) }}</div>
                <div><span class="text-gray-500">Estado actual:</span> {{ ucfirst(str_replace('_',' ',$caso->estado)) }}</div>
                <div><span class="text-gray-500">Prioridad:</span> {{ ucfirst($caso->prioridad) }}</div>
                <div class="col-span-2"><span class="text-gray-500">Descripción:</span><p class="mt-1">{{ $caso->descripcion }}</p></div>
            </div>

            @if ($caso->evidencias->count())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="font-semibold mb-2">Evidencia</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($caso->evidencias as $ev)
                            <li>{{ $ev->nombre_original }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('assign', $caso)
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="font-semibold mb-2">Asignar orientador</h3>
                    <form method="POST" action="{{ route('casos.asignar', $caso) }}" class="flex gap-3">
                        @csrf
                        <select name="orientador_id" required class="rounded-md border-gray-300 text-sm flex-1">
                            <option value="">Selecciona un orientador</option>
                            @foreach ($orientadores as $o)
                                <option value="{{ $o->id }}" @selected($caso->orientador_id == $o->id)>{{ $o->name }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Asignar</x-primary-button>
                    </form>
                </div>
            @endcan

            @can('update', $caso)
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="font-semibold mb-2">Actualizar estado</h3>
                    <form method="POST" action="{{ route('casos.estado', $caso) }}" class="flex gap-3">
                        @csrf @method('PATCH')
                        <select name="estado" class="rounded-md border-gray-300 text-sm">
                            @foreach (\App\Models\Caso::ESTADOS as $estado)
                                <option value="{{ $estado }}" @selected($caso->estado == $estado)>{{ ucfirst(str_replace('_',' ',$estado)) }}</option>
                            @endforeach
                        </select>
                        <select name="prioridad" class="rounded-md border-gray-300 text-sm">
                            @foreach (['baja','media','alta','critica'] as $p)
                                <option value="{{ $p }}" @selected($caso->prioridad == $p)>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Actualizar</x-primary-button>
                    </form>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="font-semibold mb-2">Notas</h3>
                    <ul class="space-y-2 mb-4 text-sm">
                        @foreach ($caso->notas as $nota)
                            <li class="border-b pb-2">
                                <span class="font-medium">{{ $nota->autor->name }}</span>
                                <span class="text-xs text-gray-400">({{ $nota->privada ? 'privada' : 'visible al reportante' }})</span>
                                <p>{{ $nota->contenido }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <form method="POST" action="{{ route('casos.notas.store', $caso) }}" class="space-y-2">
                        @csrf
                        <textarea name="contenido" rows="3" required class="block w-full rounded-md border-gray-300 text-sm" placeholder="Escribe una nota..."></textarea>
                        <label class="text-sm"><input type="checkbox" name="privada" value="1" checked> Nota privada</label>
                        <x-primary-button>Agregar nota</x-primary-button>
                    </form>
                </div>
            @endcan

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="font-semibold mb-2">Historial / auditoría</h3>
                <ul class="text-sm space-y-1">
                    @foreach ($caso->historial as $h)
                        <li>{{ $h->created_at->format('d/m/Y H:i') }} — {{ $h->accion }} @if($h->usuario) por {{ $h->usuario->name }} @endif @if($h->valor_anterior || $h->valor_nuevo) ({{ $h->valor_anterior }} → {{ $h->valor_nuevo }}) @endif</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
