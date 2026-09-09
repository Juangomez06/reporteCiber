<x-app-layout>
    @vite('resources/css/dashboard.css')

    <x-slot name="header">
        <div class="instituciones-header">
            <div>
                <h2 class="instituciones-title">Estudiantes</h2>
                <p class="instituciones-subtitle">
                    Administración de los estudiantes registrados en el sistema
                </p>
            </div>

            <a href="{{ route('estudiantes.importar') }}" class="btn-nueva-institucion">
                <span class="btn-icon">📥</span>
                Importar estudiantes
            </a>
        </div>
    </x-slot>

    <div class="instituciones-page">
        <div class="instituciones-container">

            {{-- Mensaje de éxito --}}
            @if (session('status'))
                <div class="alert-success">
                    <div class="alert-icon">✓</div>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            {{-- Resumen --}}
            <div class="instituciones-summary">
                <div class="summary-card">
                    <div class="summary-icon summary-icon-blue">
                        👨‍🎓
                    </div>
                    <div>
                        <span class="summary-label">Total estudiantes</span>
                        <strong class="summary-value">{{ $estudiantes->total() }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon summary-icon-green">
                        📚
                    </div>
                    <div>
                        <span class="summary-label">Con sede asignada</span>
                        <strong class="summary-value">
                            {{ $estudiantes->where('sede', '!=', null)->where('sede', '!=', '')->count() }}
                        </strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon summary-icon-purple">
                        🏠
                    </div>
                    <div>
                        <span class="summary-label">Estratos registrados</span>
                        <strong class="summary-value">
                            {{ $estudiantes->where('estrato', '!=', null)->count() }}
                        </strong>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="instituciones-card">

                <div class="table-header">
                    <div>
                        <h3>Listado de estudiantes</h3>
                        <p>Consulta y administra los estudiantes del sistema.</p>
                    </div>
                </div>

                {{-- Filtros --}}
                <form method="GET" action="{{ route('estudiantes.index') }}" class="filter-form">
                    <div class="filter-field">
                        <label for="search" class="sr-only">Buscar</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            placeholder="Buscar por nombre, documento o teléfono..."
                            value="{{ request('search') }}"
                            class="filter-input"
                        >
                    </div>

                    <div class="filter-field">
                        <label for="sede" class="sr-only">Sede</label>
                        <select name="sede" id="sede" class="filter-select">
                            <option value="">Todas las sedes</option>
                            @foreach($sedes as $sedeOption)
                                <option value="{{ $sedeOption }}" {{ request('sede') === $sedeOption ? 'selected' : '' }}>
                                    {{ $sedeOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="jornada" class="sr-only">Jornada</label>
                        <select name="jornada" id="jornada" class="filter-select">
                            <option value="">Todas las jornadas</option>
                            @foreach($jornadas as $jornadaOption)
                                <option value="{{ $jornadaOption }}" {{ request('jornada') === $jornadaOption ? 'selected' : '' }}>
                                    {{ $jornadaOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="filter-button">Filtrar</button>

                    @if(request('search') || request('sede') || request('jornada'))
                        <a href="{{ route('estudiantes.index') }}" class="filter-clear">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="instituciones-table">
                        <thead>
                            <tr>
                                <th scope="col">Estudiante</th>
                                <th scope="col">Documento</th>
                                <th scope="col">Sede/Jornada</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Estrato</th>
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($estudiantes as $estudiante)
                                <tr>
                                    <td scope="row">
                                        <div class="institution-info">
                                            <div class="institution-avatar">
                                                {{ strtoupper(substr($estudiante->nombre1 ?? 'E', 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="institution-name">
                                                    {{ trim(($estudiante->nombre1 ?? '') . ' ' . ($estudiante->nombre2 ?? '') . ' ' . ($estudiante->apellido1 ?? '') . ' ' . ($estudiante->apellido2 ?? '')) }}
                                                </div>

                                                @if ($estudiante->genero)
                                                    <div class="institution-email">
                                                        {{ $estudiante->genero === 'M' ? 'Masculino' : ($estudiante->genero === 'F' ? 'Femenino' : $estudiante->genero) }}
                                                        @if($estudiante->fecha_nacimiento)
                                                            • {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="codigo-badge">
                                            {{ ($estudiante->tipodoc ?? '') }} {{ $estudiante->doc ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="city-text">
                                            {{ $estudiante->sede ?: 'Sin sede' }}
                                        </span>
                                        @if($estudiante->jornada)
                                            <br>
                                            <span class="jornada-badge">
                                                {{ $estudiante->jornada }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="contact-info">
                                            @if($estudiante->telefono)
                                                <div class="contact-phone">
                                                    📞 {{ $estudiante->telefono }}
                                                </div>
                                            @else
                                                <span class="text-muted">Sin teléfono</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        @if($estudiante->estrato)
                                            <span class="estrato-badge estrato-{{ $estudiante->estrato }}">
                                                Estrato {{ $estudiante->estrato }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="actions">
                                            <a
                                                href="{{ route('estudiantes.show', $estudiante) }}"
                                                class="action-view"
                                                title="Ver detalles"
                                            >
                                                Ver
                                            </a>

                                            <a
                                                href="{{ route('estudiantes.edit', $estudiante) }}"
                                                class="action-edit"
                                                title="Editar"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route('estudiantes.destroy', $estudiante) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar este estudiante? Esta acción no se puede deshacer.')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="action-delete" title="Eliminar">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-icon">👨‍🎓</div>
                                            <h3>No hay estudiantes registrados</h3>
                                            <p>
                                                {{ request('search') || request('sede') || request('jornada')
                                                    ? 'No se encontraron estudiantes con los filtros aplicados.'
                                                    : 'Comienza importando estudiantes desde un archivo Excel.' }}
                                            </p>

                                            @if(request('search') || request('sede') || request('jornada'))
                                                <a href="{{ route('estudiantes.index') }}" class="btn-empty">
                                                    Limpiar filtros
                                                </a>
                                            @else
                                                <a href="{{ route('estudiantes.importar') }}" class="btn-empty">
                                                    📥 Importar estudiantes
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($estudiantes->hasPages())
                    <div class="pagination-container">
                        {{ $estudiantes->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <style>
        /* Estilos adicionales para estudiantes */
        .jornada-badge {
            display: inline-block;
            padding: 2px 8px;
            background-color: #F0F4F8;
            color: #4A5568;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 2px;
        }

        .contact-info {
            font-size: 13px;
            color: #4A5568;
            line-height: 1.4;
        }

        .contact-phone {
            margin-bottom: 2px;
        }

        .estrato-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .estrato-1, .estrato-2 {
            background-color: #FED7D7;
            color: #C53030;
        }

        .estrato-3, .estrato-4 {
            background-color: #FEFCBF;
            color: #975A16;
        }

        .estrato-5, .estrato-6 {
            background-color: #C6F6D5;
            color: #276749;
        }

        .action-view {
            color: #457B9D;
            text-decoration: none;
            font-size: 13px;
            padding: 4px 8px;
            transition: color 0.2s;
        }

        .action-view:hover {
            color: #1D3557;
        }

        .text-muted {
            color: #A0AEC0;
            font-style: italic;
            font-size: 12px;
        }
    </style>
</x-app-layout>
