<x-app-layout>
    <x-slot name="header">
        <div class="instituciones-header">
            <div>
                <h2 class="instituciones-title">Instituciones</h2>
                <p class="instituciones-subtitle">
                    Administración de las instituciones registradas en el sistema
                </p>
            </div>

            <a href="{{ route('instituciones.create') }}" class="btn-nueva-institucion">
                <span class="btn-icon">+</span>
                Nueva institución
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
                        🏫
                    </div>
                    <div>
                        <span class="summary-label">Instituciones</span>
                        <strong class="summary-value">{{ $instituciones->total() }}</strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon summary-icon-green">
                        ✓
                    </div>
                    <div>
                        <span class="summary-label">Activas</span>
                        <strong class="summary-value">
                            {{ $instituciones->where('activa', true)->count() }}
                        </strong>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon summary-icon-purple">
                        📊
                    </div>
                    <div>
                        <span class="summary-label">Casos registrados</span>
                        <strong class="summary-value">
                            {{ $instituciones->sum('casos_count') }}
                        </strong>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="instituciones-card">

                <div class="table-header">
                    <div>
                        <h3>Listado de instituciones</h3>
                        <p>Consulta y administra las instituciones del sistema.</p>
                    </div>
                </div>

                {{-- Filtros --}}
                <form method="GET" action="{{ route('instituciones.index') }}" class="filter-form">
                    <div class="filter-field">
                        <label for="search" class="sr-only">Buscar</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            placeholder="Buscar por nombre, código o ciudad..."
                            value="{{ request('search') }}"
                            class="filter-input"
                        >
                    </div>

                    <div class="filter-field">
                        <label for="status" class="sr-only">Estado</label>
                        <select name="status" id="status" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activas</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivas</option>
                        </select>
                    </div>

                    <button type="submit" class="filter-button">Filtrar</button>

                    @if(request('search') || request('status') !== null)
                        <a href="{{ route('instituciones.index') }}" class="filter-clear">Limpiar</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="instituciones-table">
                        <thead>
                            <tr>
                                <th scope="col">Institución</th>
                                <th scope="col">Código</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col">Casos</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($instituciones as $institucion)
                                <tr>
                                    <td scope="row">
                                        <div class="institution-info">
                                            <div class="institution-avatar">
                                                {{ strtoupper(substr($institucion->nombre, 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="institution-name">
                                                    {{ $institucion->nombre }}
                                                </div>

                                                @if ($institucion->contacto_email)
                                                    <div class="institution-email">
                                                        {{ $institucion->contacto_email }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="codigo-badge">
                                            {{ $institucion->codigo }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="city-text">
                                            {{ $institucion->ciudad ?: 'Sin ciudad' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="casos-count">
                                            {{ $institucion->casos_count }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($institucion->activa)
                                            <span class="status-badge status-active">
                                                <span class="status-dot"></span>
                                                Activa
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <span class="status-dot"></span>
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="actions">
                                           
                                            <a
                                                href="{{ route('instituciones.edit', $institucion) }}"
                                                class="action-edit"
                                                title="Editar"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route('instituciones.destroy', $institucion) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar esta institución? Esta acción no se puede deshacer.')"
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
                                            <div class="empty-icon">🏫</div>
                                            <h3>No hay instituciones registradas</h3>
                                            <p>
                                                {{ request('search') || request('status') !== null
                                                    ? 'No se encontraron instituciones con los filtros aplicados.'
                                                    : 'Comienza agregando la primera institución al sistema.' }}
                                            </p>

                                            @if(request('search') || request('status') !== null)
                                                <a href="{{ route('instituciones.index') }}" class="btn-empty">
                                                    Limpiar filtros
                                                </a>
                                            @else
                                                <a href="{{ route('instituciones.create') }}" class="btn-empty">
                                                    Nueva institución
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
                @if ($instituciones->hasPages())
                    <div class="pagination-container">
                        {{ $instituciones->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>