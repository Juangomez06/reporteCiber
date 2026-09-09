<x-app-layout>
    @vite('resources/css/dashboard.css')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Importar Estudiantes</h2>
    </x-slot>

    <div class="py-12 dash">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="dash-header">
                <div>
                    <p class="dash-header__eyebrow">Gestión de datos</p>
                    <h1 class="dash-header__title">Importar Estudiantes</h1>
                    <p class="dash-header__subtitle">
                        Carga masiva de estudiantes desde archivo Excel
                    </p>
                </div>
            </div>

            <!-- Mensajes de éxito/error mejorados -->
            @if(session('success'))
                <div class="dash-alert dash-alert--success">
                    <span class="dash-alert__icon">✅</span>
                    <div>
                        <p class="dash-alert__title">¡Éxito!</p>
                        <p class="dash-alert__message">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="dash-alert dash-alert--error">
                    <span class="dash-alert__icon">❌</span>
                    <div>
                        <p class="dash-alert__title">Error</p>
                        <p class="dash-alert__message">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Panel principal -->
            <div class="dash-panel dash-panel--wide">
                <h3 class="dash-panel__title">📂 Seleccionar archivo Excel</h3>

                <form action="{{ route('estudiantes.importar.post') }}" method="POST" enctype="multipart/form-data" class="dash-import-form">
                    @csrf

                    <div class="dash-upload-zone">
                        <div class="dash-upload-zone__icon">📊</div>
                        <p class="dash-upload-zone__text">Arrastra tu archivo aquí o</p>
                        <label for="archivo" class="dash-btn dash-btn--secondary">
                            📁 Seleccionar archivo
                        </label>
                        <input type="file"
                               name="archivo"
                               id="archivo"
                               class="dash-upload-zone__input"
                               accept=".xlsx,.xls,.csv"
                               required>
                        <p class="dash-upload-zone__hint">
                            Formatos aceptados: .xlsx, .xls, .csv
                        </p>
                    </div>

                    <!-- Zona de archivo seleccionado -->
                    <div id="file-preview" class="dash-file-preview" style="display: none;">
                        <div class="dash-file-preview__item">
                            <span class="dash-file-preview__icon">📄</span>
                            <div class="dash-file-preview__info">
                                <p class="dash-file-preview__name" id="file-name"></p>
                                <p class="dash-file-preview__size" id="file-size"></p>
                            </div>
                            <button type="button" class="dash-file-preview__remove" id="file-remove" title="Eliminar archivo">
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Botón de importar -->
                    <div class="dash-import-actions">
                        <a href="{{ route('dashboard') }}" class="dash-btn dash-btn--ghost">
                            ← Volver al panel
                        </a>
                        <button type="submit" class="dash-btn dash-btn--primary dash-btn--lg">
                            📥 Importar estudiantes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Panel de instrucciones -->
            <div class="dash-panel">
                <h3 class="dash-panel__title">ℹ️ Instrucciones</h3>
                <div class="dash-instructions">
                    <p>1. Descarga la plantilla de ejemplo si es necesario.</p>
                    <p>2. Completa los datos en el archivo Excel.</p>
                    <p>3. Selecciona el archivo y haz clic en "Importar".</p>
                    <div class="dash-instructions__download">
                        <a href="#" class="dash-btn dash-btn--ghost dash-btn--sm">
                            📄 Descargar plantilla
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Script para manejar la selección de archivos
        const fileInput = document.getElementById('archivo');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileRemove = document.getElementById('file-remove');
        const uploadZone = document.querySelector('.dash-upload-zone');

        fileInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                filePreview.style.display = 'block';
                uploadZone.style.borderColor = '#457B9D';
                uploadZone.style.backgroundColor = '#F0F7FF';
            }
        });

        fileRemove.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.style.display = 'none';
            uploadZone.style.borderColor = '';
            uploadZone.style.backgroundColor = '';
        });

        // Drag and drop
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#457B9D';
            uploadZone.style.backgroundColor = '#F0F7FF';
        });

        uploadZone.addEventListener('dragleave', function() {
            uploadZone.style.borderColor = '';
            uploadZone.style.backgroundColor = '';
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    </script>

    <style>
        .dash-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .dash-alert--success {
            background-color: #F0FFF4;
            border: 1px solid #48BB78;
            color: #276749;
        }
        .dash-alert--error {
            background-color: #FFF5F5;
            border: 1px solid #F56565;
            color: #C53030;
        }
        .dash-alert__icon {
            font-size: 20px;
        }
        .dash-alert__title {
            font-weight: 600;
            margin: 0;
        }
        .dash-alert__message {
            margin: 4px 0 0 0;
            font-size: 14px;
        }

        .dash-upload-zone {
            border: 2px dashed #CBD5E0;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .dash-upload-zone:hover {
            border-color: #457B9D;
            background-color: #F7FAFC;
        }
        .dash-upload-zone__icon {
            font-size: 48px;
            margin-bottom: 16px;
        }
        .dash-upload-zone__text {
            font-size: 16px;
            color: #4A5568;
            margin-bottom: 16px;
        }
        .dash-upload-zone__input {
            display: none;
        }
        .dash-upload-zone__hint {
            font-size: 12px;
            color: #A0AEC0;
            margin-top: 16px;
        }

        .dash-file-preview {
            margin-top: 20px;
            padding: 12px;
            background-color: #F7FAFC;
            border-radius: 6px;
        }
        .dash-file-preview__item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .dash-file-preview__icon {
            font-size: 24px;
        }
        .dash-file-preview__info {
            flex: 1;
        }
        .dash-file-preview__name {
            font-weight: 500;
            color: #2D3748;
            margin: 0;
        }
        .dash-file-preview__size {
            font-size: 12px;
            color: #A0AEC0;
            margin: 2px 0 0 0;
        }
        .dash-file-preview__remove {
            background: none;
            border: none;
            color: #F56565;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
        }
        .dash-file-preview__remove:hover {
            color: #E53E3E;
        }

        .dash-import-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            gap: 12px;
        }
        .dash-import-actions .dash-btn {
            flex: 1;
            text-align: center;
        }

        .dash-instructions {
            padding: 12px 0;
        }
        .dash-instructions p {
            margin-bottom: 8px;
            color: #4A5568;
            line-height: 1.5;
        }
        .dash-instructions__download {
            margin-top: 16px;
        }

        .dash-btn--sm {
            padding: 8px 16px;
            font-size: 14px;
        }
        .dash-btn--lg {
            padding: 14px 24px;
            font-size: 16px;
        }
        .dash-btn--secondary {
            background-color: #F7FAFC;
            color: #457B9D;
            border: 1px solid #457B9D;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dash-btn--secondary:hover {
            background-color: #457B9D;
            color: white;
        }
    </style>
</x-app-layout>
