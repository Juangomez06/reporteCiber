<x-app-layout>
    @vite('resources/css/dashboard.css')

    <x-slot name="header">
        <div class="instituciones-header">
            <div>
                <h2 class="instituciones-title">Editar Estudiante</h2>
                <p class="instituciones-subtitle">
                    Modifica la información del estudiante
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
            <div class="instituciones-card">
                <form action="{{ route('estudiantes.update', $estudiante) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-sections">
                        {{-- Información Académica --}}
                        <div class="form-section">
                            <h3 class="form-section__title">📚 Información Académica</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="sede">Sede</label>
                                    <input type="text" name="sede" id="sede" value="{{ old('sede', $estudiante->sede) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="jornada">Jornada</label>
                                    <input type="text" name="jornada" id="jornada" value="{{ old('jornada', $estudiante->jornada) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="fechaini">Fecha de inicio</label>
                                    <input type="date" name="fechaini" id="fechaini" value="{{ old('fechaini', $estudiante->fechaini ? $estudiante->fechaini->format('Y-m-d') : '') }}" class="form-input">
                                </div>
                            </div>
                        </div>

                        {{-- Información Personal --}}
                        <div class="form-section">
                            <h3 class="form-section__title">👤 Información Personal</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="tipodoc">Tipo de documento</label>
                                    <input type="text" name="tipodoc" id="tipodoc" value="{{ old('tipodoc', $estudiante->tipodoc) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="doc">Documento</label>
                                    <input type="text" name="doc" id="doc" value="{{ old('doc', $estudiante->doc) }}" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="nombre1">Primer nombre</label>
                                    <input type="text" name="nombre1" id="nombre1" value="{{ old('nombre1', $estudiante->nombre1) }}" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="nombre2">Segundo nombre</label>
                                    <input type="text" name="nombre2" id="nombre2" value="{{ old('nombre2', $estudiante->nombre2) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="apellido1">Primer apellido</label>
                                    <input type="text" name="apellido1" id="apellido1" value="{{ old('apellido1', $estudiante->apellido1) }}" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellido2">Segundo apellido</label>
                                    <input type="text" name="apellido2" id="apellido2" value="{{ old('apellido2', $estudiante->apellido2) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="genero">Género</label>
                                    <select name="genero" id="genero" class="form-input">
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('genero', $estudiante->genero) === 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('genero', $estudiante->genero) === 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento ? $estudiante->fecha_nacimiento->format('Y-m-d') : '') }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="tipo_sangre">Tipo de sangre</label>
                                    <input type="text" name="tipo_sangre" id="tipo_sangre" value="{{ old('tipo_sangre', $estudiante->tipo_sangre) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="pais_origen">País de origen</label>
                                    <input type="text" name="pais_origen" id="pais_origen" value="{{ old('pais_origen', $estudiante->pais_origen) }}" class="form-input">
                                </div>
                            </div>
                        </div>

                        {{-- Información Socioeconómica --}}
                        <div class="form-section">
                            <h3 class="form-section__title">💰 Información Socioeconómica</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="estrato">Estrato</label>
                                    <select name="estrato" id="estrato" class="form-input">
                                        <option value="">Seleccionar...</option>
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('estrato', $estudiante->estrato) == $i ? 'selected' : '' }}>
                                                Estrato {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sisben">SISBEN IV</label>
                                    <input type="text" name="sisben" id="sisben" value="{{ old('sisben', $estudiante->sisben) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="barrio">Barrio</label>
                                    <input type="text" name="barrio" id="barrio" value="{{ old('barrio', $estudiante->barrio) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="eps">EPS</label>
                                    <input type="text" name="eps" id="eps" value="{{ old('eps', $estudiante->eps) }}" class="form-input">
                                </div>
                            </div>
                        </div>

                        {{-- Información de Salud --}}
                        <div class="form-section">
                            <h3 class="form-section__title">🏥 Información de Salud</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="discapacidad">Discapacidad</label>
                                    <input type="text" name="discapacidad" id="discapacidad" value="{{ old('discapacidad', $estudiante->discapacidad) }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $estudiante->telefono) }}" class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="form-actions">
                        <a href="{{ route('estudiantes.index') }}" class="dash-btn dash-btn--ghost">
                            Cancelar
                        </a>
                        <button type="submit" class="dash-btn dash-btn--primary">
                            💾 Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-sections {
            padding: 24px;
        }

        .form-section {
            margin-bottom: 24px;
        }

        .form-section__title {
            font-size: 18px;
            font-weight: 600;
            color: #2D3748;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #E2E8F0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 500;
            color: #4A5568;
        }

        .form-input {
            padding: 10px 12px;
            border: 1px solid #CBD5E0;
            border-radius: 6px;
            font-size: 14px;
            color: #2D3748;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #457B9D;
            box-shadow: 0 0 0 3px rgba(69, 123, 157, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid #E2E8F0;
            background: #F7FAFC;
        }
    </style>
</x-app-layout>
