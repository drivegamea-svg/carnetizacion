@extends('layouts.app')
@section('title', 'Crear Nuevo Carnet - CIZEE')

@push('styles')
    <link href="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        .required-label::after {
            content: " *";
            color: #d9534f;
            margin-left: 2px;
        }

        .alert-warning {
            border-left: 4px solid #ffc107;
            background-color: #fffbf0;
        }

        .alert-warning .alert-heading {
            color: #856404;
            font-size: 1.1rem;
        }


        /* Estilos que funcionan en modo dark y light */
        .form-section {
            border: 1px solid var(--bs-border-color, #e0e0e0);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
            background: var(--bs-light, #fafafa);
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--bs-body-color, #2c3e50);
            margin-bottom: 10px;
        }

        .small-note {
            font-size: 0.75rem;
            color: var(--bs-secondary-color, #6c757d);
        }

        .preview-img {
            max-height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--bs-border-color, #dee2e6);
        }

        /* Estilos para la cámara compatibles con dark mode */
        .camera-modal .modal-dialog {
            max-width: 500px;
        }

        .camera-preview {
            width: 100%;
            height: 300px;
            background: #000;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        #videoCamera {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        #canvasCamera {
            display: none;
        }

        .camera-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-camera {
            padding: 8px 20px;
        }

        .camera-option {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 10px;
            background: var(--bs-body-bg, #fff);
            color: var(--bs-body-color, #000);
        }

        .camera-option:hover {
            border-color: var(--bs-primary, #007bff);
            background: var(--bs-light, #f8f9fa);
        }

        .camera-option.active {
            border-color: var(--bs-primary, #007bff);
            background: var(--bs-primary-bg-subtle, #e3f2fd);
        }

        /* Asegurar que los inputs mantengan buen contraste en dark mode */
        .form-control,
        .form-select {
            color: var(--bs-body-color, #000);
            border-color: var(--bs-border-color, #ced4da);
        }

        /* Mejorar legibilidad de labels en dark mode */
        .form-label {
            color: var(--bs-body-color, #2c3e50);
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('afiliados.index') }}">Afiliados</a></li>
            <li class="breadcrumb-item active">Nuevo Afiliado</li>
        </ol>

        <h1 class="page-header">Registro de Afiliado <small class="text-muted">CIZEE - Zona Económica Especial</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-user-plus me-2"></i> Nuevo Afiliado {{ date('Y') }}</h4>
            </div>

            <div class="panel-body p-3">
                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('afiliado_eliminado_data'))
                    @php
                        $afiliadoEliminado = session('afiliado_eliminado_data');
                    @endphp
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Afiliado Eliminado Encontrado
                                </h5>
                                <p class="mb-2">
                                    Ya existe un afiliado eliminado con la CI <strong>{{ $afiliadoEliminado['ci'] }} {{ $afiliadoEliminado['expedicion'] }}</strong>
                                    correspondiente a: <strong>{{ $afiliadoEliminado['nombre_completo'] }}</strong>
                                </p>
                                <p class="mb-3">
                                    ¿Desea restaurar este afiliado y actualizar sus datos con la información del formulario?
                                </p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('afiliados.restaurar.desde.create', $afiliadoEliminado['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-undo me-1"></i> Sí, restaurar y actualizar
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" onclick="continuarCreacion()">
                                        <i class="fas fa-plus me-1"></i> No, crear nuevo afiliado
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                    <script>
                        function continuarCreacion() {
                            // Ocultar la alerta
                            document.querySelector('.alert-warning').style.display = 'none';
                            // Habilitar el botón de guardar si estaba deshabilitado
                            document.getElementById('btn-guardar').disabled = false;
                        }
                        
                        // Deshabilitar el botón de guardar principal mientras se muestra la alerta
                        document.addEventListener('DOMContentLoaded', function() {
                            const btnGuardar = document.getElementById('btn-guardar');
                            if (btnGuardar) {
                                btnGuardar.disabled = true;
                            }
                        });
                    </script>
                @endif

                <form action="{{ route('afiliados.store') }}" method="POST" enctype="multipart/form-data"
                    data-parsley-validate>
                    @csrf

                    <!-- Sección 1: Datos Básicos del Afiliado -->
                    <div class="form-section">
                        <div class="section-title"><i class="bx bx-id-card me-1"></i> Datos Básicos</div>

                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">CI</label>
                                <input type="text" class="form-control form-control-sm" id="ci" name="ci"
                                    required placeholder="1234567" value="{{ old('ci') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">Expedido</label>
                                <select class="form-select form-select-sm" id="expedicion" name="expedicion" required>
                                    <option value="">Selecc.</option>
                                    @foreach (['LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'] as $exp)
                                        <option value="{{ $exp }}"
                                            {{ old('expedicion') == $exp ? 'selected' : '' }}>{{ $exp }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required-label mb-0">Nombres</label>
                                <input type="text" class="form-control form-control-sm" id="nombres" name="nombres"
                                    required value="{{ old('nombres') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0">A. Paterno</label>
                                <input type="text" class="form-control form-control-sm" id="apellido_paterno"
                                    name="apellido_paterno" value="{{ old('apellido_paterno') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-0">A. Materno</label>
                                <input type="text" class="form-control form-control-sm" id="apellido_materno"
                                    name="apellido_materno" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>

                        <div class="row g-2 mt-1">

                            <div class="col-md-3">
                                <label class="form-label mb-0">Fecha Nacimiento</label>
                                <input type="text" class="form-control form-control-sm datepicker" id="fecha_nacimiento"
                                    name="fecha_nacimiento" placeholder="DD/MM/AAAA" value="{{ old('fecha_nacimiento') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">Género</label>
                                <select class="form-select form-select-sm" id="genero" name="genero" required>
                                    <option value="">Sel.</option>
                                    <option value="MASCULINO" {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>M
                                    </option>
                                    <option value="FEMENINO" {{ old('genero') == 'FEMENINO' ? 'selected' : '' }}>F</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label required-label mb-0">Celular</label>
                                <input type="text" class="form-control form-control-sm" id="celular" name="celular"
                                    required placeholder="72000000" value="{{ old('celular') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required-label mb-0">Ciudad/Municipio</label>
                                <select class="form-select form-select-sm" id="ciudad" name="ciudad" required>
                                    <option value="">Seleccionar ciudad...</option>
                                    <option value="EL ALTO">EL ALTO</option>

                                    @if (old('ciudad'))
                                        <option value="{{ old('ciudad') }}" selected>{{ old('ciudad') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Información Profesional -->
                    <div class="form-section">
                        <div class="section-title"><i class="bx bx-briefcase me-1"></i> Información Profesional</div>

                        <div class="row g-2">

                            <div class="col-md-6">
                                <label class="form-label required-label mb-0">Profesión Técnica</label>
                                <input type="text" class="form-control form-control-sm" id="profesion_tecnica"
                                    name="profesion_tecnica" required value="{{ old('profesion_tecnica') }}"
                                    placeholder="Ej: Técnico en Informática">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-label mb-0">Especialidad</label>
                                <input type="text" class="form-control form-control-sm" id="especialidad"
                                    name="especialidad" required value="{{ old('especialidad') }}"
                                    placeholder="Ej: Desarrollo de Software">
                            </div>


                        </div>

                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label class="form-label mb-0">Empresa/Organización</label>
                                <input type="text" class="form-control form-control-sm" id="empresa" name="empresa"
                                    value="{{ old('empresa') }}" placeholder="Nombre de la empresa (opcional)">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-label mb-0">Sector Económico</label>
                                <select class="form-select form-select-sm" id="sector_economico" name="sector_economico"
                                    required>
                                    <option value="">Seleccionar</option>
                                    <option value="INDUSTRIA">Industria</option>
                                    <option value="COMERCIO">Comercio</option>
                                    <option value="SERVICIOS">Servicios</option>
                                    <option value="LOGISTICA">Logística</option>
                                    <option value="TECNOLOGIA">Tecnología</option>
                                    <option value="AGROINDUSTRIA">Agroindustria</option>
                                    <option value="CONSTRUCCION">Construcción</option>
                                    <option value="ENERGIA">Energía</option>
                                    <option value="MINERIA">Minería</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Foto para el Carnet -->
                    <div class="form-section">
                        <div class="section-title"><i class="bx bx-camera me-1"></i> Foto para el Carnet</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex gap-2 mb-2">
                                    <div class="camera-option active" data-type="upload">
                                        <small><i class="bx bx-upload me-1"></i>Subir archivo</small>
                                    </div>
                                    <div class="camera-option" data-type="camera">
                                        <small><i class="bx bx-camera me-1"></i>Tomar foto</small>
                                    </div>
                                </div>

                                <div id="upload-section">
                                    <input type="file" class="form-control form-control-sm" id="foto"
                                        name="foto" accept="image/*" required>
                                </div>

                                <div id="camera-section" style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                                        onclick="openCamera()">
                                        <i class="bx bx-camera me-1"></i>Abrir Cámara
                                    </button>
                                    <input type="hidden" id="foto_data" name="foto_data">
                                </div>

                                <div class="small-note">JPG/PNG, max 2MB</div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-center">
                                    <div class="small-note mb-2">Vista previa</div>
                                    <img id="preview-foto" class="preview-img" alt="Preview foto"
                                        style="max-width: 150px; display: none;">
                                    <div id="no-preview" class="text-muted small">
                                        <i class="bx bx-image me-1"></i>No hay imagen seleccionada
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('afiliados.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-guardar">
                            <i class="fa fa-save me-1"></i> Guardar Afiliado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para la cámara -->
    <div class="modal fade camera-modal" id="cameraModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bx bx-camera me-2"></i>Tomar Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="camera-preview">
                        <video id="videoCamera" autoplay playsinline></video>
                        <canvas id="canvasCamera"></canvas>
                    </div>
                    <div class="camera-controls">
                        <button type="button" class="btn btn-success btn-camera" id="btnTakePhoto">
                            <i class="bx bx-camera me-1"></i>Tomar Foto
                        </button>
                        <button type="button" class="btn btn-secondary btn-camera" id="btnRetakePhoto"
                            style="display: none;">
                            <i class="bx bx-reset me-1"></i>Volver a Tomar
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnUsePhoto" style="display: none;">
                        <i class="bx bx-check me-1"></i>Usar Esta Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}">
    </script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/i18n/es.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>

    <script>
        // Variables globales para la cámara
        let stream = null;
        let photoData = null;

        $(document).ready(function() {
            // Configuración básica
            Parsley.setLocale('es');

            // Inicializar select2
            $('#expedicion, #genero, #ciudad, #sector_economico').select2({
                width: '100%',
                minimumResultsForSearch: 6
            });

            // Datepicker CON TU CONFIGURACIÓN ORIGINAL (pero ahora opcional)
            const baseConfig = {
                language: 'es',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                templates: {
                    leftArrow: '<i class="fa fa-chevron-left"></i>',
                    rightArrow: '<i class="fa fa-chevron-right"></i>'
                }
            };

            $('#fecha_nacimiento').datepicker($.extend({}, baseConfig, {
                format: 'dd/mm/yyyy',
                endDate: '-18y',
                startView: 2,
                maxViewMode: 2,
                orientation: 'bottom auto',
                todayBtn: false
            })).mask('00/00/0000', {
                placeholder: 'DD/MM/YYYY',
                clearIfNotMatch: true
            });

            // Convertir fecha a formato ISO antes de enviar (tu lógica original)
            $('form').on('submit', function() {
                $('.datepicker').each(function() {
                    const $input = $(this);
                    if ($input.data('fecha-bd')) {
                        $input.val($input.data('fecha-bd'));
                    }
                });
                return true;
            });

            // Alternar entre subir archivo y cámara
            $('.camera-option').on('click', function() {
                const type = $(this).data('type');
                $('.camera-option').removeClass('active');
                $(this).addClass('active');

                if (type === 'camera') {
                    $('#upload-section').hide();
                    $('#camera-section').show();
                    $('#foto').removeAttr('required');
                    $('#foto_data').attr('required', 'required');
                } else {
                    $('#upload-section').show();
                    $('#camera-section').hide();
                    $('#foto').attr('required', 'required');
                    $('#foto_data').removeAttr('required');
                    photoData = null;
                }
            });

            // Previsualización de imágenes para archivos subidos
            function previewImage(input, previewId) {
                const file = input.files[0];
                const preview = $(previewId);
                const noPreview = $('#no-preview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).show();
                        noPreview.hide();
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.hide().attr('src', '');
                    noPreview.show();
                }
            }

            $('#foto').on('change', function() {
                if (this.files && this.files[0]) {
                    previewImage(this, '#preview-foto');
                }
            });

            // Búsqueda automática por CI (manteniendo tu lógica original)
            let debounceTimer;
            $('#ci').on('input', function() {
                clearTimeout(debounceTimer);
                const ci = $(this).val().trim();
                if (ci.length >= 5) {
                    debounceTimer = setTimeout(() => buscarPersona(ci), 700);
                } else if (ci.length === 0) {
                    limpiarDatosPersona();
                }
            });

            function buscarPersona(ci) {
                $('#ci').after('<span class="loading-api" id="loading-ci" title="Buscando..."></span>');
                $.ajax({
                    url: `/buscar-persona/${ci}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data) {
                            llenarDatosPersona(response.data);
                            mostrarMensaje('Persona encontrada en el diccionario', 'success');
                        } else {
                            limpiarDatosPersona();
                            mostrarMensaje('Nueva persona - complete los datos', 'info');
                        }
                    },
                    error: function() {
                        limpiarDatosPersona();
                        mostrarMensaje('Error al buscar persona', 'error');
                    },
                    complete: function() {
                        $('#loading-ci').remove();
                    }
                });
            }

            function llenarDatosPersona(persona) {
                $('#expedicion').val(persona.expedicion).trigger('change');
                $('#nombres').val(persona.nombres);
                $('#apellido_paterno').val(persona.apellido_paterno || '');
                $('#apellido_materno').val(persona.apellido_materno || '');
                $('#fecha_nacimiento').val(persona.fecha_nacimiento || '');
                $('#genero').val(persona.genero).trigger('change');
                $('#celular').val(persona.celular || '');
                $('#ciudad').val(persona.ciudad || '').trigger('change');
            }

            function limpiarDatosPersona() {
                $('#expedicion').val('').trigger('change');
                $('#nombres').val('');
                $('#apellido_paterno').val('');
                $('#apellido_materno').val('');
                $('#fecha_nacimiento').val('');
                $('#genero').val('').trigger('change');
                $('#celular').val('');
                $('#ciudad').val('').trigger('change');
            }

            // Mostrar mensajes
            function mostrarMensaje(mensaje, tipo) {
                $('.alert-message').remove();
                const bgClass = tipo === 'success' ? 'alert-success' : tipo === 'error' ? 'alert-danger' :
                    'alert-info';
                const icon = tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' :
                    'info-circle';
                const $alert = $(`<div class="alert ${bgClass} alert-dismissible fade show mt-2 alert-message">
                <i class="fa fa-${icon} me-2"></i>${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`);
                $('.panel-body').prepend($alert);
                setTimeout(() => {
                    $alert.alert('close');
                }, 4000);
            }
        });

        // ========== FUNCIONES DE LA CÁMARA ==========

        function openCamera() {
            $('#cameraModal').modal('show');
            startCamera();
        }

        async function startCamera() {
            try {
                // Detener stream anterior si existe
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Solicitar acceso a la cámara
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user', // Usar cámara frontal
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                });

                const video = document.getElementById('videoCamera');
                video.srcObject = stream;

                // Resetear controles
                $('#btnTakePhoto').show();
                $('#btnRetakePhoto').hide();
                $('#btnUsePhoto').hide();

            } catch (error) {
                console.error('Error al acceder a la cámara:', error);
                alert('No se pudo acceder a la cámara. Por favor, verifica los permisos.');
            }
        }

        function takePhoto() {
            const video = document.getElementById('videoCamera');
            const canvas = document.getElementById('canvasCamera');
            const context = canvas.getContext('2d');

            // Configurar canvas con las dimensiones del video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Dibujar el frame actual del video en el canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convertir a Data URL (JPEG con 85% de calidad)
            photoData = canvas.toDataURL('image/jpeg', 0.85);

            // Mostrar preview en el modal
            const videoElement = document.getElementById('videoCamera');
            videoElement.style.display = 'none';

            const imgPreview = document.createElement('img');
            imgPreview.src = photoData;
            imgPreview.style.width = '100%';
            imgPreview.style.height = '100%';
            imgPreview.style.objectFit = 'cover';
            imgPreview.style.borderRadius = '8px';

            document.querySelector('.camera-preview').appendChild(imgPreview);

            // Mostrar controles de retomar y usar foto
            $('#btnTakePhoto').hide();
            $('#btnRetakePhoto').show();
            $('#btnUsePhoto').show();
        }

        function retakePhoto() {
            // Remover preview de la foto
            const preview = document.querySelector('.camera-preview img');
            if (preview) {
                preview.remove();
            }

            // Mostrar video nuevamente
            const video = document.getElementById('videoCamera');
            video.style.display = 'block';

            // Resetear controles
            $('#btnTakePhoto').show();
            $('#btnRetakePhoto').hide();
            $('#btnUsePhoto').hide();

            photoData = null;
        }

        function usePhoto() {
            if (photoData) {
                // Mostrar preview en el formulario
                $('#preview-foto').attr('src', photoData).show();
                $('#no-preview').hide();

                // Guardar datos en el campo hidden
                $('#foto_data').val(photoData);

                // Cerrar modal y detener cámara
                $('#cameraModal').modal('hide');
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                mostrarMensaje('Foto tomada correctamente', 'success');
            }
        }

        // Event listeners para los botones de la cámara
        document.getElementById('btnTakePhoto').addEventListener('click', takePhoto);
        document.getElementById('btnRetakePhoto').addEventListener('click', retakePhoto);
        document.getElementById('btnUsePhoto').addEventListener('click', usePhoto);

        // Limpiar cuando se cierre el modal
        $('#cameraModal').on('hidden.bs.modal', function() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        });
    </script>
@endpush
