<x-app-layout>
    @vite('resources/css/dashboard.css')

    <x-slot name="header">
        <div class="instituciones-header">
            <div>
                <h2 class="instituciones-title">Detalles del Estudiante</h2>
                <p class="instituciones-subtitle">
                    Información completa del estudiante
                </p>
            </div>

            <a href="{{ route('estudiantes.index') }}" class="btn-nueva-institucion">
                <span class="btn-icon">←</span>
                Volver
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

            <div class="estudiante-profile">
                {{-- Encabezado del perfil --}}
                <div class="estudiante-profile__header">
                    <div class="estudiante-avatar-large">
                        {{ strtoupper(substr($estudiante->nombre1 ?? 'E', 0, 1)) }}
                    </div>
                    <div class="estudiante-profile__info">
                        <h1 class="estudiante-profile__name">
                            {{ trim(($estudiante->nombre1 ?? '') . ' ' . ($estudiante->nombre2 ?? '') . ' ' . ($estudiante->apellido1 ?? '') . ' ' . ($estudiante->apellido2 ?? '')) }}
                        </h1>
                        <p class="estudiante-profile__subtitle">
                            {{ $estudiante->tipodoc ?? 'N/A' }} {{ $estudiante->doc ?? '' }}
                        </p>
                    </div>
                    <div class="estudiante-profile__actions">
                        <a href="{{ route('estudiantes.edit', $estudiante) }}" class="dash-btn dash-btn--ghost">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dash-btn dash-btn--danger"
                                    onclick="return confirm('¿Está seguro de eliminar este estudiante?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Información académica --}}
                <div class="estudiante-sections">
                    <div class="estudiante-section">
                        <h3 class="estudiante-section__title">📚 Información Académica</h3>
                        <div class="estudiante-info-grid">
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Sede</span>
                                <span class="estudiante-info__value">{{ $estudiante->sede ?: 'No asignada' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Jornada</span>
                                <span class="estudiante-info__value">{{ $estudiante->jornada ?: 'No asignada' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Fecha de inicio</span>
                                <span class="estudiante-info__value">
                                    {{ $estudiante->fechaini ? \Carbon\Carbon::parse($estudiante->fechaini)->format('d/m/Y') : 'No registrada' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Información personal --}}
                    <div class="estudiante-section">
                        <h3 class="estudiante-section__title">👤 Información Personal</h3>
                        <div class="estudiante-info-grid">
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Género</span>
                                <span class="estudiante-info__value">
                                    {{ $estudiante->genero === 'M' ? 'Masculino' : ($estudiante->genero === 'F' ? 'Femenino' : ($estudiante->genero ?: 'No registrado')) }}
                                </span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Fecha de nacimiento</span>
                                <span class="estudiante-info__value">
                                    @if($estudiante->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }}
                                        ({{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->age }} años)
                                    @else
                                        No registrada
                                    @endif
                                </span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Tipo de sangre</span>
                                <span class="estudiante-info__value">{{ $estudiante->tipo_sangre ?: 'No registrado' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">País de origen</span>
                                <span class="estudiante-info__value">{{ $estudiante->pais_origen ?: 'No registrado' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Información socioeconómica --}}
                    <div class="estudiante-section">
                        <h3 class="estudiante-section__title">💰 Información Socioeconómica</h3>
                        <div class="estudiante-info-grid">
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Estrato</span>
                                <span class="estudiante-info__value">{{ $estudiante->estrato ?: 'No registrado' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">SISBEN IV</span>
                                <span class="estudiante-info__value">{{ $estudiante->sisben ?: 'No registrado' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Barrio</span>
                                <span class="estudiante-info__value">{{ $estudiante->barrio ?: 'No registrado' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">EPS</span>
                                <span class="estudiante-info__value">{{ $estudiante->eps ?: 'No registrada' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Información de salud --}}
                    <div class="estudiante-section">
                        <h3 class="estudiante-section__title">🏥 Información de Salud</h3>
                        <div class="estudiante-info-grid">
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Discapacidad</span>
                                <span class="estudiante-info__value">{{ $estudiante->discapacidad ?: 'Ninguna' }}</span>
                            </div>
                            <div class="estudiante-info-item">
                                <span class="estudiante-info__label">Teléfono</span>
                                <span class="estudiante-info__value">{{ $estudiante->telefono ?: 'No registrado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .estudiante-profile {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .estudiante-profile__header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .estudiante-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .estudiante-profile__info {
            flex: 1;
        }

        .estudiante-profile__name {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 8px 0;
        }

        .estudiante-profile__subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .estudiante-profile__actions {
            display: flex;
            gap: 10px;
        }

        .estudiante-sections {
            padding: 24px;
        }

        .estudiante-section {
            margin-bottom: 24px;
        }

        .estudiante-section:last-child {
            margin-bottom: 0;
        }

        .estudiante-section__title {
            font-size: 18px;
            font-weight: 600;
            color: #2D3748;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #E2E8F0;
        }

        .estudiante-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .estudiante-info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .estudiante-info__label {
            font-size: 12px;
            color: #A0AEC0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .estudiante-info__value {
            font-size: 15px;
            color: #2D3748;
            font-weight: 500;
        }

        .dash-btn--danger {
            background-color: #F56565;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dash-btn--danger:hover {
            background-color: #E53E3E;
        }
    </style>
</x-app-layout>
