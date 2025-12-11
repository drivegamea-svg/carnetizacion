@extends('layouts.app')
@section('title', 'Crear Nuevo Carnet - CIZEE')

@push('styles')
    <link href="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
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

                <form action="{{ route('afiliados.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    @csrf

                    <!-- Sección 1: Datos Básicos del Afiliado -->
                    <div class="form-section">
                        <div class="section-title"><i class="bx bx-id-card me-1"></i> Datos Básicos</div>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">CI</label>
                                <input type="text" class="form-control form-control-sm" id="ci" name="ci" required placeholder="1234567" value="{{ old('ci') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">Expedido</label>
                                <select class="form-select form-select-sm" id="expedicion" name="expedicion" required>
                                    <option value="">Selecc.</option>
                                    @foreach (['LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'] as $exp)
                                        <option value="{{ $exp }}" {{ old('expedicion') == $exp ? 'selected' : '' }}>{{ $exp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required-label mb-0">Nombres</label>
                                <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" required value="{{ old('nombres') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0">A. Paterno</label>
                                <input type="text" class="form-control form-control-sm" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0">A. Materno</label>
                                <input type="text" class="form-control form-control-sm" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Fecha Nacimiento</label>
                                <input type="text" class="form-control form-control-sm datepicker" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="DD/MM/AAAA" value="{{ old('fecha_nacimiento') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label required-label mb-0">Género</label>
                                <select class="form-select form-select-sm" id="genero" name="genero" required>
                                    <option value="">Sel.</option>
                                    <option value="MASCULINO" {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>M</option>
                                    <option value="FEMENINO" {{ old('genero') == 'FEMENINO' ? 'selected' : '' }}>F</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required-label mb-0">Celular</label>
                                <input type="text" class="form-control form-control-sm" id="celular" name="celular" required placeholder="72000000" value="{{ old('celular') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required-label mb-0">Ciudad/Municipio</label>
                                <select class="form-select form-select-sm" id="ciudad" name="ciudad" required>
                                    <option value="">Seleccione ciudad</option>
                                    @foreach ($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id }}" {{ old('ciudad') == $ciudad->id ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Información Profesional -->
                    <div class="form-section">
                        <div class="section-title"><i class="bx bx-briefcase me-1"></i> Información Profesional - Actividad Economica</div>
                        <div class="row g-2">
                            <div class="col-md-6 form-group">
                                <label class="form-label required-label mb-0">Profesión Técnica</label>
                                <select id="profesion_tecnica" name="profesion_tecnica" class="default-select2 form-control form-select form-select-sm" required>
                                    <option value="">Seleccione profesión</option>
                                    @foreach ($profesiones as $profesion)
                                        <option value="{{ $profesion->id }}" {{ old('profesion_tecnica') == $profesion->id ? 'selected' : '' }}>
                                            {{ $profesion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required-label mb-0">Especialidad</label>
                                <select id="especialidad" name="especialidad" class="form-select form-select-sm" required>
                                    <option value="">Seleccione especialidad</option>
                                    @foreach ($especialidades as $especialidad)
                                        <option value="{{ $especialidad->id }}" {{ old('especialidad') == $especialidad->id ? 'selected' : '' }}>
                                            {{ $especialidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                          
                            <div class="col-md-6">
                                <label class="form-label required-label mb-0">Sector Económico</label>
                                <select id="sector_economico" name="sector_economico" class="form-select form-select-sm" required>
                                    <option value="">Seleccione sector</option>
                                    @foreach ($sectoresEconomicos as $sector)
                                        <option value="{{ $sector->id }}" {{ old('sector_economico') == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label class="form-label mb-0">Organización Social</label>
                                <select id="organizacion_social" name="organizacion_social" class="form-select form-select-sm">
                                    <option value="">Seleccione organización</option>
                                    @foreach ($organizacionesSociales as $organizacion)
                                        <option value="{{ $organizacion->id }}" {{ old('organizacion_social') == $organizacion->id ? 'selected' : '' }}>
                                            {{ $organizacion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mb-0">Direccion Actividad Economica</label>
                                <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" placeholder="" value="{{ old('direccion') }}">
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
                                    <input type="file" class="form-control form-control-sm" id="foto" name="foto" accept="image/*" required>
                                </div>
                                <div id="camera-section" style="display: none;">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="openCamera()">
                                        <i class="bx bx-camera me-1"></i>Abrir Cámara
                                    </button>
                                    <input type="hidden" id="foto_data" name="foto_data">
                                </div>
                                <div class="small-note">JPG/PNG, max 2MB</div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-center">
                                    <div class="small-note mb-2">Vista previa</div>
                                    <img id="preview-foto" class="preview-img" alt="Preview foto" style="max-width: 150px; display: none;">
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
                        <button type="button" class="btn btn-secondary btn-camera" id="btnRetakePhoto" style="display: none;">
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
    <script src="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/i18n/es.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>

    <script>
        let stream = null;
        let photoData = null;

        $(document).ready(function() {
            Parsley.setLocale('es');

            // Configuración base para Select2
            const select2Config = {
                width: '100%',
                language: 'es',
                placeholder: 'Seleccione una opción',
                allowClear: true,
            };

            // Inicializar Select2 para selects simples
            $('#expedicion, #genero').select2({
                width: '100%',
                minimumResultsForSearch: 6
            });

            // Inicializar Select2 para selects con datos desde base de datos
            $('#ciudad').select2(select2Config);
            $('#profesion_tecnica').select2(select2Config);
            $('#especialidad').select2(select2Config);
            $('#sector_economico').select2(select2Config);
            $('#organizacion_social').select2(select2Config);

            // Datepicker
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

            $('form').on('submit', function() {
                $('.datepicker').each(function() {
                    const $input = $(this);
                    if ($input.data('fecha-bd')) {
                        $input.val($input.data('fecha-bd'));
                    }
                });
                return true;
            });

            // Convertir textos a MAYÚSCULAS automáticamente
            $('input[type="text"]').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Lógica de cámara
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
                previewImage(this, '#preview-foto');
            });

            // Búsqueda por CI
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

            function mostrarMensaje(mensaje, tipo) {
                $('.alert-message').remove();
                const bgClass = tipo === 'success' ? 'alert-success' : tipo === 'error' ? 'alert-danger' : 'alert-info';
                const icon = tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle';
                const $alert = $(`<div class="alert ${bgClass} alert-dismissible fade show mt-2 alert-message">
                    <i class="fa fa-${icon} me-2"></i>${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
                $('.panel-body').prepend($alert);
                setTimeout(() => {
                    $alert.alert('close');
                }, 4000);
            }
        });

        // Funciones de cámara
        async function startCamera() {
            try {
                if (stream) stream.getTracks().forEach(t => t.stop());

                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
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
                video.play();

            } catch (error) {
                console.error("Error al iniciar cámara:", error);
                alert("No se pudo acceder a la cámara");
            }
        }

        function openCamera() {
            $('#cameraModal').modal('show');
            startCamera();
        }

        function takePhoto() {
            const video = document.getElementById('videoCamera');
            const canvas = document.getElementById('canvasCamera');
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            photoData = canvas.toDataURL('image/jpeg', 0.85);
            video.style.display = 'none';
            const img = document.createElement('img');
            img.src = photoData;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            document.querySelector('.camera-preview').appendChild(img);
            $('#btnTakePhoto').hide();
            $('#btnRetakePhoto').show();
            $('#btnUsePhoto').show();
        }

        function retakePhoto() {
            const prev = document.querySelector('.camera-preview img');
            if (prev) prev.remove();
            const video = document.getElementById('videoCamera');
            video.style.display = 'block';
            $('#btnTakePhoto').show();
            $('#btnRetakePhoto').hide();
            $('#btnUsePhoto').hide();
            photoData = null;
        }

        function usePhoto() {
            if (photoData) {
                $('#preview-foto').attr('src', photoData).show();
                $('#no-preview').hide();
                $('#foto_data').val(photoData);
                $('#cameraModal').modal('hide');
                if (stream) stream.getTracks().forEach(t => t.stop());
                mostrarMensaje('Foto tomada correctamente', 'success');
            }
        }

        // Asignar eventos a los botones de la cámara
        document.getElementById('btnTakePhoto').addEventListener('click', takePhoto);
        document.getElementById('btnRetakePhoto').addEventListener('click', retakePhoto);
        document.getElementById('btnUsePhoto').addEventListener('click', usePhoto);
        
        $('#cameraModal').on('hidden.bs.modal', function() {
            if (stream) stream.getTracks().forEach(t => t.stop());
            stream = null;
        });
    </script>
@endpush