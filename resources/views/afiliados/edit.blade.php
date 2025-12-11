@extends('layouts.app')

@section('title', 'Editar Afiliado - CIZEE')

@push('styles')
<link href="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
<link href="{{ asset('color-admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<style>
    .required-label::after { content: " *"; color: #d9534f; margin-left: 2px; }
    .form-section { 
        border: 1px solid var(--bs-border-color); 
        border-radius: 8px; 
        padding: 12px; 
        margin-bottom: 15px; 
        background: var(--bs-light); 
    }
    .section-title { 
        font-size: 0.9rem; 
        font-weight: 700; 
        color: var(--bs-body-color); 
        margin-bottom: 10px; 
    }
    .small-note { 
        font-size: 0.75rem; 
        color: var(--bs-secondary-color); 
    }
    .photo-preview {
        width: 150px;
        height: 180px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .photo-preview img, .huella-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .huella-preview {
        width: 120px;
        height: 180px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .camera-option {
        cursor: pointer;
        padding: 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
    }
    .camera-option.active {
        border-color: #007bff;
        background: #e7f1ff;
    }
    .alert-warning-bg {
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }
    .cropper-container {
        max-height: 400px;
        margin-bottom: 15px;
    }
    .compact-form .form-control-sm, 
    .compact-form .form-select-sm {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
    .compact-form .form-label {
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
</style>
@endpush

@section('content')
<div id="content" class="app-content">
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('afiliados.index') }}">Afiliados</a></li>
        <li class="breadcrumb-item"><a href="{{ route('afiliados.show', $afiliado->id) }}">Ver Afiliado</a></li>
        <li class="breadcrumb-item active">Editar Afiliado</li>
    </ol>

    <h1 class="page-header">Editar Afiliado <small>{{ $afiliado->nombre_completo }} ({{ $afiliado->ci }})</small></h1>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-edit me-2"></i> Editar Afiliado</h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
        </div>

        <div class="panel-body p-3">
            <!-- Mensaje de advertencia -->
            @if($afiliado->estado === 'ACTIVO')
            <div class="alert alert-warning-bg alert-dismissible fade show mb-3 py-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Precaución:</strong> Este afiliado está ACTIVO. Los cambios podrían afectar la validez del carnet.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2 mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Errores encontrados:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('afiliados.update', $afiliado->id) }}" method="POST" enctype="multipart/form-data" id="form-editar-afiliado" data-parsley-validate>
                @csrf
                @method('PUT')

                <!-- Sección 1: Datos Básicos -->
                <div class="form-section compact-form">
                    <div class="section-title"><i class="bx bx-id-card me-1"></i> Datos Básicos</div>
                    
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label required-label mb-0">CI</label>
                            <input type="text" class="form-control form-control-sm" id="ci" name="ci" 
                                required value="{{ old('ci', $afiliado->ci) }}" 
                                data-original-value="{{ $afiliado->ci }}"
                                onchange="mostrarAdvertenciaCI(this)">
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label required-label mb-0">Expedido</label>
                            <select class="form-select form-select-sm" id="expedicion" name="expedicion" required>
                                <option value="">Sel.</option>
                                @foreach (['S/E', 'LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'] as $exp)
                                <option value="{{ $exp }}" {{ old('expedicion', $afiliado->expedicion) == $exp ? 'selected' : '' }}>{{ $exp }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required-label mb-0">Nombres</label>
                            <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" 
                                required value="{{ old('nombres', $afiliado->nombres) }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-0">A. Paterno</label>
                            <input type="text" class="form-control form-control-sm" id="apellido_paterno" 
                                name="apellido_paterno" value="{{ old('apellido_paterno', $afiliado->apellido_paterno) }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-0">A. Materno</label>
                            <input type="text" class="form-control form-control-sm" id="apellido_materno" 
                                name="apellido_materno" value="{{ old('apellido_materno', $afiliado->apellido_materno) }}">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-3">
                            <label class="form-label mb-0">Fecha Nacimiento</label>
                            <input type="text" class="form-control form-control-sm datepicker" id="fecha_nacimiento" 
                                name="fecha_nacimiento" placeholder="DD/MM/AAAA" 
                                value="{{ old('fecha_nacimiento', $afiliado->fecha_nacimiento ? $afiliado->fecha_nacimiento->format('d/m/Y') : '') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label required-label mb-0">Género</label>
                            <select class="form-select form-select-sm" id="genero" name="genero" required>
                                <option value="">Sel.</option>
                                <option value="MASCULINO" {{ old('genero', $afiliado->genero) == 'MASCULINO' ? 'selected' : '' }}>M</option>
                                <option value="FEMENINO" {{ old('genero', $afiliado->genero) == 'FEMENINO' ? 'selected' : '' }}>F</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label required-label mb-0">Celular</label>
                            <input type="text" class="form-control form-control-sm" id="celular" name="celular" 
                                required value="{{ old('celular', $afiliado->celular) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required-label mb-0">Ciudad/Municipio</label>
                            <select class="form-select form-select-sm" id="ciudad_id" name="ciudad_id" required>
                                <option value="">Seleccione ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id }}" 
                                        {{ old('ciudad_id', $afiliado->ciudad_id) == $ciudad->id ? 'selected' : '' }}>
                                        {{ $ciudad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="form-label mb-0">Dirección</label>
                            <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" 
                                value="{{ old('direccion', $afiliado->direccion) }}">
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Información Profesional -->
                <div class="form-section compact-form">
                    <div class="section-title"><i class="bx bx-briefcase me-1"></i> Información Profesional</div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label required-label mb-0">Sector Económico</label>
                            <select id="sector_economico_id" name="sector_economico_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione sector</option>
                                @foreach ($sectoresEconomicos as $sector)
                                    <option value="{{ $sector->id }}" 
                                        {{ old('sector_economico_id', $afiliado->sector_economico_id) == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required-label mb-0">Profesión Técnica</label>
                            <select id="profesion_tecnica_id" name="profesion_tecnica_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione profesión</option>
                                @foreach ($profesiones as $profesion)
                                    <option value="{{ $profesion->id }}" 
                                        {{ old('profesion_tecnica_id', $afiliado->profesion_tecnica_id) == $profesion->id ? 'selected' : '' }}>
                                        {{ $profesion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-6">
                            <label class="form-label required-label mb-0">Especialidad</label>
                            <select id="especialidad_id" name="especialidad_id" class="form-select form-select-sm" required>
                                <option value="">Seleccione especialidad</option>
                                @foreach ($especialidades as $especialidad)
                                    <option value="{{ $especialidad->id }}" 
                                        {{ old('especialidad_id', $afiliado->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                                        {{ $especialidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-0">Organización Social</label>
                            <select id="organizacion_social_id" name="organizacion_social_id" class="form-select form-select-sm">
                                <option value="">Seleccione organización</option>
                                @foreach ($organizacionesSociales as $organizacion)
                                    <option value="{{ $organizacion->id }}" 
                                        {{ old('organizacion_social_id', $afiliado->organizacion_social_id) == $organizacion->id ? 'selected' : '' }}>
                                        {{ $organizacion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Foto y Huella -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <div class="section-title"><i class="bx bx-camera me-1"></i> Foto del Carnet</div>
                            
                            <div class="mb-2">
                                <div class="d-flex gap-2">
                                    <div class="camera-option active" data-type="upload" data-target="foto">
                                        <small><i class="bx bx-upload me-1"></i>Subir archivo</small>
                                    </div>
                                    <div class="camera-option" data-type="camera" data-target="foto">
                                        <small><i class="bx bx-camera me-1"></i>Tomar foto</small>
                                    </div>
                                    <div class="camera-option" data-type="keep" data-target="foto">
                                        <small><i class="bx bx-check me-1"></i>Mantener actual</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="foto-upload-section">
                                <input type="file" class="form-control form-control-sm" id="foto" name="foto" 
                                    accept="image/*">
                                <div class="small-note mt-1">JPG/PNG, max 2MB</div>
                            </div>
                            
                            <div id="foto-camera-section" style="display: none;">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="openCamera('foto')">
                                    <i class="bx bx-camera me-1"></i>Abrir Cámara
                                </button>
                                <input type="hidden" id="foto_data" name="foto_data">
                            </div>
                            
                            <input type="hidden" id="keep_foto" name="keep_foto" value="1">
                            
                            <div class="text-center mt-3">
                                <div class="small-note mb-2">Foto actual</div>
                                <div class="photo-preview mx-auto">
                                    @if($afiliado->foto_url)
                                        <img src="{{ $afiliado->foto_url }}?t={{ time() }}" alt="Foto actual" id="preview-foto">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bx bx-user bx-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div id="no-preview-foto" class="text-muted small mt-2" style="display: none;">
                                    <i class="bx bx-image me-1"></i>Nueva imagen seleccionada
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-section">
                            <div class="section-title"><i class="bx bx-fingerprint me-1"></i> Huella Dactilar</div>
                            
                            <div class="mb-2">
                                <div class="d-flex gap-2">
                                    <div class="camera-option active" data-type="upload" data-target="huella">
                                        <small><i class="bx bx-upload me-1"></i>Subir archivo</small>
                                    </div>
                                    <div class="camera-option" data-type="keep" data-target="huella">
                                        <small><i class="bx bx-check me-1"></i>Mantener actual</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="huella-upload-section">
                                <input type="file" class="form-control form-control-sm" id="huella" name="huella" 
                                    accept="image/*">
                                <div class="small-note mt-1">JPG/PNG, max 2MB - Formato 2x3</div>
                            </div>
                            
                            <input type="hidden" id="keep_huella" name="keep_huella" value="1">
                            
                            <div class="text-center mt-3">
                                <div class="small-note mb-2">Huella actual</div>
                                <div class="huella-preview mx-auto">
                                    @if($afiliado->huella_url)
                                        <img src="{{ $afiliado->huella_url }}?t={{ time() }}" alt="Huella actual" id="preview-huella">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bx bx-fingerprint bx-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div id="no-preview-huella" class="text-muted small mt-2" style="display: none;">
                                    <i class="bx bx-image me-1"></i>Nueva huella seleccionada
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Sistema (solo lectura) -->
                <div class="form-section">
                    <div class="section-title"><i class="bx bx-info-circle me-1"></i> Información del Sistema</div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label mb-0">Estado</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $afiliado->estado }}" readonly>
                        </div>
                        @if($afiliado->fecha_afiliacion)
                        <div class="col-md-3">
                            <label class="form-label mb-0">Fecha Afiliación</label>
                            <input type="text" class="form-control form-control-sm bg-light" 
                                value="{{ $afiliado->fecha_afiliacion->format('d/m/Y') }}" readonly>
                        </div>
                        @endif
                        @if($afiliado->fecha_vencimiento)
                        <div class="col-md-3">
                            <label class="form-label mb-0">Vencimiento</label>
                            <input type="text" class="form-control form-control-sm bg-light" 
                                value="{{ $afiliado->fecha_vencimiento->format('d/m/Y') }}" readonly>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label mb-0">Última Actualización</label>
                            <input type="text" class="form-control form-control-sm bg-light" 
                                value="{{ $afiliado->updated_at->format('d/m/Y H:i') }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <a href="{{ route('afiliados.show', $afiliado->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="resetForm()">
                            <i class="fas fa-redo me-1"></i> Restablecer
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-guardar">
                            <i class="fas fa-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="camera-preview mb-3">
                    <video id="videoCamera" autoplay playsinline></video>
                </div>
                <div class="camera-controls">
                    <button type="button" class="btn btn-success btn-sm" id="btnTakePhoto">
                        <i class="bx bx-camera me-1"></i>Tomar Foto
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="btnRetakePhoto" style="display: none;">
                        <i class="bx bx-reset me-1"></i>Volver a Tomar
                    </button>
                </div>
                <div id="photo-preview" class="mt-3" style="display: none;">
                    <img id="captured-photo" src="" alt="Foto capturada" style="max-width: 100%; border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnUsePhoto" style="display: none;">
                    <i class="bx bx-check me-1"></i>Usar Esta Foto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-crop me-2"></i>Recortar Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="cropper-container">
                    <img id="imageToCrop" src="#" alt="Imagen a recortar" style="max-width: 100%;">
                </div>
                <div class="mt-3 text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm" onclick="applyCrop()">
                            <i class="bx bx-check me-1"></i>Aplicar Recorte
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelCrop()">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                    </div>
                </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    // Variables globales
    let stream = null;
    let currentPhotoTarget = null;
    let currentPhotoData = null;
    let cropper = null;
    let isFormChanged = false;
    let originalFormData = {};

    $(document).ready(function () {
        // Guardar datos originales del formulario
        saveOriginalFormData();

        // Inicializar configuración
        Parsley.setLocale('es');
        
        // Configuración base para Select2
        const select2Config = {
            width: '100%',
            language: 'es',
            placeholder: 'Seleccione una opción',
            allowClear: true,
        };

        // Inicializar Select2
        $('#expedicion, #genero').select2({
            width: '100%',
            minimumResultsForSearch: 6
        });

        $('#ciudad_id').select2(select2Config);
        $('#profesion_tecnica_id').select2(select2Config);
        $('#especialidad_id').select2(select2Config);
        $('#sector_economico_id').select2(select2Config);
        $('#organizacion_social_id').select2(select2Config);

        // Datepicker
        $('#fecha_nacimiento').datepicker({
            language: 'es',
            autoclose: true,
            format: 'dd/mm/yyyy',
            endDate: '-18y',
            todayHighlight: true
        }).mask('00/00/0000', { placeholder: 'DD/MM/YYYY' });

        // Convertir textos a MAYÚSCULAS
        $('input[type="text"]').on('input', function() {
            this.value = this.value.toUpperCase();
            markAsChanged();
        });

        // Marcar cambios en selects
        $('select').on('change', function() {
            markAsChanged();
        });

        // Manejo de opciones de foto/huella
        $('.camera-option').on('click', function() {
            const type = $(this).data('type');
            const target = $(this).data('target');
            
            // Actualizar estado visual
            $(`.camera-option[data-target="${target}"]`).removeClass('active');
            $(this).addClass('active');
            
            // Mostrar/ocultar secciones correspondientes
            $(`#${target}-upload-section`).hide();
            $(`#${target}-camera-section`).hide();
            
            // Manejar cada tipo
            if (type === 'upload') {
                $(`#${target}-upload-section`).show();
                $(`#keep_${target}`).val('0');
            } else if (type === 'camera') {
                $(`#${target}-camera-section`).show();
                $(`#keep_${target}`).val('0');
            } else if (type === 'keep') {
                $(`#keep_${target}`).val('1');
                $(`#${target}`).val(''); // Limpiar input file
                $(`#${target}_data`).val(''); // Limpiar data
            }
            
            markAsChanged();
        });

        // Previsualización de imágenes
        $('#foto').on('change', function(e) {
            handleImageUpload(e, 'foto');
        });

        $('#huella').on('change', function(e) {
            handleImageUpload(e, 'huella');
        });

        // Prevenir envío accidental
        window.addEventListener('beforeunload', function (e) {
            if (isFormChanged) {
                e.preventDefault();
                e.returnValue = 'Tienes cambios sin guardar. ¿Seguro que quieres salir?';
            }
        });

        // Confirmar antes de enviar
        $('#form-editar-afiliado').on('submit', function(e) {
            if (!confirm('¿Está seguro de guardar los cambios?')) {
                e.preventDefault();
                return false;
            }
            
            // Desactivar el botón para evitar doble envío
            $('#btn-guardar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Guardando...');
            
            return true;
        });
    });

    // Funciones
    function saveOriginalFormData() {
        $('input, select, textarea').each(function() {
            const id = $(this).attr('id');
            if (id) {
                if ($(this).is(':file')) {
                    originalFormData[id] = $(this).val();
                } else {
                    originalFormData[id] = $(this).val();
                }
            }
        });
    }

    function markAsChanged() {
        isFormChanged = true;
    }

    function resetForm() {
        if (confirm('¿Restablecer todos los cambios?')) {
            // Restaurar valores originales
            $.each(originalFormData, function(id, value) {
                const element = $('#' + id);
                if (element.length) {
                    if (element.is('select')) {
                        element.val(value).trigger('change');
                    } else {
                        element.val(value);
                    }
                }
            });
            
            // Restaurar opciones por defecto
            $('.camera-option[data-type="keep"]').click();
            
            isFormChanged = false;
            showToast('success', 'Formulario restablecido');
        }
    }

    function mostrarAdvertenciaCI(input) {
        const original = $(input).data('original-value');
        const nuevo = $(input).val();
        
        if (original !== nuevo) {
            alert('ADVERTENCIA: Cambiar el número de CI puede afectar la validez del carnet emitido.');
        }
    }

    function handleImageUpload(event, type) {
        const file = event.target.files[0];
        if (!file) return;

        // Validar tamaño (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('La imagen no debe exceder 2MB.');
            event.target.value = '';
            return;
        }

        // Mostrar cropper para recortar
        const reader = new FileReader();
        reader.onload = function(e) {
            if (type === 'foto') {
                // Para foto: usar cropper 1:1
                openCropper(e.target.result, 1, type);
            } else if (type === 'huella') {
                // Para huella: usar cropper 2:3
                openCropper(e.target.result, 2/3, type);
            }
        };
        reader.readAsDataURL(file);
    }

    function openCropper(imageSrc, aspectRatio, type) {
        currentPhotoTarget = type;
        
        $('#imageToCrop').attr('src', imageSrc);
        $('#cropperModal').modal('show');

        // Inicializar cropper
        setTimeout(() => {
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(document.getElementById('imageToCrop'), {
                aspectRatio: aspectRatio,
                viewMode: 1,
                autoCropArea: 0.8,
                movable: true,
                rotatable: false,
                scalable: true,
                zoomable: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false
            });
        }, 100);
    }

    function applyCrop() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: currentPhotoTarget === 'foto' ? 400 : 400,
            height: currentPhotoTarget === 'foto' ? 400 : 600,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        const croppedImage = canvas.toDataURL('image/jpeg', 0.9);

        // Actualizar vista previa
        $(`#preview-${currentPhotoTarget}`).attr('src', croppedImage);
        $(`#no-preview-${currentPhotoTarget}`).show();

        // Actualizar campo correspondiente
        if (currentPhotoTarget === 'foto') {
            $('#foto_data').val(croppedImage);
            $('#keep_foto').val('0');
        } else {
            // Para huella necesitaríamos manejar base64 o guardar como archivo
            // Por ahora, solo vista previa
            $('#keep_huella').val('0');
        }

        $('#cropperModal').modal('hide');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        showToast('success', 'Imagen recortada correctamente');
    }

    function cancelCrop() {
        $('#cropperModal').modal('hide');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Limpiar input file
        $(`#${currentPhotoTarget}`).val('');
    }

    // Funciones de cámara (simplificadas)
    function openCamera(target) {
        currentPhotoTarget = target;
        $('#cameraModal').modal('show');
        
        // Iniciar cámara (código simplificado)
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream;
                const video = document.getElementById('videoCamera');
                video.srcObject = stream;
            })
            .catch(function(err) {
                console.error("Error al acceder a la cámara:", err);
                alert("No se pudo acceder a la cámara. Verifique los permisos.");
            });
    }

    function showToast(type, message) {
        // Implementar notificación toast
        const toast = `<div class="alert alert-${type} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('body').append(toast);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }
</script>
@endpush