@extends('layouts.app')
@section('title', 'Crear Nuevo Carnet - CIZEE')

@push('styles')
    <link href="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    
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

        .preview-img {
            max-height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--bs-border-color);
        }

        /* Estilos para cámara */
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
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .camera-option:hover {
            border-color: var(--bs-primary);
            background: var(--bs-light);
        }

        .camera-option.active {
            border-color: var(--bs-primary);
            background: var(--bs-primary-bg-subtle);
        }

        .form-label {
            color: var(--bs-body-color);
            font-weight: 500;
        }

        /* Layout compacto */
        .compact-form .form-control-sm,
        .compact-form .form-select-sm {
            font-size: 0.8rem;
            padding: 4px 8px;
            height: calc(1.5em + 0.5rem + 2px);
            color: var(--bs-body-color);
            border-color: var(--bs-border-color);
        }

        .compact-form .form-control-sm:focus,
        .compact-form .form-select-sm:focus {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .compact-form .form-label {
            font-size: 0.8rem;
            margin-bottom: 2px;
            color: var(--bs-body-color);
        }

        .compact-form .row {
            margin-bottom: 8px;
        }

        .compact-form .form-section {
            padding: 8px;
            margin-bottom: 10px;
            background: var(--bs-secondary-bg);
            border-color: var(--bs-border-color);
        }

        /* Panel de instrucciones */
        .instructions-panel {
            background: var(--bs-secondary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 15px;
            font-size: 0.75rem;
            color: var(--bs-body-color);
        }

        .instructions-panel h6 {
            color: var(--bs-body-color);
            font-weight: 600;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--bs-border-color);
            padding-bottom: 8px;
        }

        .instruction-item {
            margin-bottom: 10px;
            padding-left: 8px;
            border-left: 3px solid var(--bs-primary);
            color: var(--bs-body-color);
        }

        .instruction-item i {
            color: var(--bs-primary);
            margin-right: 5px;
        }

        .instruction-item strong {
            color: var(--bs-body-color);
        }

        .instruction-item ul {
            color: var(--bs-body-color);
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .instruction-item li {
            color: var(--bs-body-color);
        }

        /* Grid principal */
        .form-grid {
            display: grid;
            grid-template-columns: 4fr 1fr;
            gap: 20px;
        }

        /* Estilos para el cropper */
        .cropper-container {
            height: 400px;
            overflow: hidden;
            background: #000;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .cropper-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .photo-preview-container,
        .huella-preview-container {
            text-align: center;
            margin-top: 15px;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }

        .huella-preview {
            width: 150px;
            height: 225px;
            border: 2px solid #28a745;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }

        .photo-preview img,
        .huella-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-huella.ratio-2x3 {
            max-width: 150px;
            max-height: 225px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--bs-border-color);
        }

        /* Layout de dos columnas */
        .form-section {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .form-section .text-center {
            margin-top: auto;
        }

        @media (min-width: 768px) {
            .row {
                display: flex;
                flex-wrap: wrap;
            }
            .col-md-6 {
                display: flex;
            }
            .form-section {
                flex: 1;
            }
        }

        /* Modal específico para recorte de foto */
        .photo-modal .modal-dialog {
            max-width: 700px;
        }

        .photo-modal .modal-body {
            padding: 15px;
        }

        /* Archivos adjuntos */
        .archivo-item {
            border: 1px dashed var(--bs-border-color);
            border-radius: 6px;
            padding: 10px;
            background: var(--bs-body-bg);
            transition: all 0.3s ease;
        }

        .archivo-item:hover {
            border-color: var(--bs-primary);
            background: var(--bs-light);
        }

        .btn-remove-archivo {
            position: absolute;
            right: 5px;
            top: 5px;
            width: 24px;
            height: 24px;
            padding: 0;
            font-size: 0.8rem;
        }

        .preview-archivo {
            max-width: 150px;
            max-height: 150px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .preview-archivo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Modo oscuro */
        .app-dark .instructions-panel {
            background: var(--bs-dark);
            border-color: var(--bs-border-color);
        }

        .app-dark .compact-form .form-section {
            background: var(--bs-dark);
            border-color: var(--bs-border-color);
        }

        .app-dark .instruction-item {
            color: var(--bs-light);
        }

        .app-dark .instruction-item strong {
            color: var(--bs-light);
        }

        .app-dark .instruction-item ul {
            color: var(--bs-light);
        }

        .app-dark .instruction-item li {
            color: var(--bs-light);
        }

        .compact-form .form-control-sm::placeholder {
            color: var(--bs-secondary-color) !important;
            opacity: 0.7;
        }

        .compact-form .form-select-sm {
            color: var(--bs-body-color) !important;
        }

        .compact-form .form-select-sm option {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .app-dark .small-note {
            color: var(--bs-secondary-color);
        }

        .app-dark .text-muted {
            color: var(--bs-secondary-color) !important;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
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
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div>
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

            <div class="form-grid">
                <!-- Columna principal del formulario -->
                <div class="compact-form">
                    <form action="{{ route('afiliados.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate data-can-activate="{{ auth()->user()->can('activar afiliados') ? 'true' : 'false' }}" data-can-print="{{ auth()->user()->can('imprimir carnet') ? 'true' : 'false' }}">
                        @csrf
                        <input type="hidden" name="_camera_option" id="_camera_option" value="upload">
                        <input type="hidden" name="_huella_camera_option" id="_huella_camera_option" value="upload">

                        <!-- Sección 1: Datos Básicos -->
                        <div class="form-section">
                            <div class="section-title"><i class="bx bx-id-card me-1"></i> Datos Básicos</div>
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <label class="form-label required-label mb-0">CI</label>
                                    <input type="text" class="form-control form-control-sm" id="ci" name="ci" required placeholder="1234567" value="{{ old('ci') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required-label mb-0">Exp.</label>
                                    <select class="form-select form-select-sm" id="expedicion" name="expedicion" required>
                                        <option value="" disabled selected>Seleccione.</option>
                                        @foreach (['S/E', 'LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'] as $exp)
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
                                    <label class="form-label mb-0">F. Nacimiento</label>
                                    <input type="text" class="form-control form-control-sm datepicker" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="DD/MM/AAAA" value="{{ old('fecha_nacimiento') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required-label mb-0">Celular</label>
                                    <input type="number" class="form-control form-control-sm" id="celular" name="celular" required placeholder="72000000" value="{{ old('celular') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required-label mb-0">Género</label>
                                    <select class="form-select form-select-sm" id="genero" name="genero" required>
                                        <option value="">Seleccione.</option>
                                        <option value="MASCULINO" {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>MASCULINO</option>
                                        <option value="FEMENINO" {{ old('genero') == 'FEMENINO' ? 'selected' : '' }}>FEMENINO</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label mb-0">Ciudad</label>
                                    <select class="form-select form-select-sm" id="ciudad_id" name="ciudad_id" required>
                                        <option value="">Seleccione ciudad</option>
                                        @foreach ($ciudades as $ciudad)
                                            <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>{{ $ciudad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Información Profesional -->
                        <div class="form-section">
                            <div class="section-title"><i class="bx bx-briefcase me-1"></i> Información Profesional</div>
                            <div class="row g-2 mt-1">
                                <div class="col-md-4">
                                    <label class="form-label required-label mb-0">Sector Económico</label>
                                    <select id="sector_economico_id" name="sector_economico_id" class="form-select form-select-sm" required>
                                        <option value="">Seleccione sector</option>
                                        @foreach ($sectoresEconomicos as $sector)
                                            <option value="{{ $sector->id }}" {{ old('sector_economico_id') == $sector->id ? 'selected' : '' }}>{{ $sector->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label mb-0">Profesión</label>
                                    <select id="profesion_tecnica_id" name="profesion_tecnica_id" class="default-select2 form-control form-select form-select-sm" required>
                                        <option value="">Seleccione profesión</option>
                                        @foreach ($profesiones as $profesion)
                                            <option value="{{ $profesion->id }}" {{ old('profesion_tecnica_id') == $profesion->id ? 'selected' : '' }}>{{ $profesion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required-label mb-0">Especialidad</label>
                                    <select id="especialidad_id" name="especialidad_id" class="form-select form-select-sm" required>
                                        <option value="">Seleccione especialidad</option>
                                        @foreach ($especialidades as $especialidad)
                                            <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>{{ $especialidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label mb-0">Organización Social</label>
                                    <select id="organizacion_social_id" name="organizacion_social_id" class="form-select form-select-sm">
                                        <option value="">Seleccione organización</option>
                                        @foreach ($organizacionesSociales as $organizacion)
                                            <option value="{{ $organizacion->id }}" {{ old('organizacion_social_id') == $organizacion->id ? 'selected' : '' }}>{{ $organizacion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label mb-0">Dirección Actividad Económica</label>
                                    <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" placeholder="" value="{{ old('direccion') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Fotos y Huella: fila única con 4 columnas (foto input, foto preview, huella input, huella preview) -->
                        <div class="row">
                            <!-- Foto: Input -->
                            <div class="col-md-3 col-12 mb-2">
                                <div class="form-section">
                                    <div class="section-title"><i class="bx bx-camera me-1"></i> Foto para el Carnet</div>

                                    <div class="d-flex gap-2 mb-2">
                                        <div class="camera-option active" data-type="upload">
                                            <small><i class="bx bx-upload me-1"></i>Subir archivo</small>
                                        </div>
                                        <div class="camera-option" data-type="camera">
                                            <small><i class="bx bx-camera me-1"></i>Tomar foto</small>
                                        </div>
                                    </div>

                                    <div id="upload-section">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" id="btn-seleccionar-foto">
                                            <i class="bx bx-upload me-1"></i>Seleccionar Foto
                                        </button>
                                        <input type="file" name="foto" class="d-none" id="foto_input" accept="image/*">
                                        <input type="hidden" id="foto_meta" name="foto_meta">
                                        <div id="foto-info" class="small-note text-start" style="display: none;">
                                            <span id="foto-nombre"></span>
                                        </div>
                                    </div>

                                    <div id="camera-section" style="display: none;">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="openCamera()">
                                            <i class="bx bx-camera me-1"></i>Abrir Cámara
                                        </button>
                                        <input type="hidden" id="foto_data" name="foto_data">
                                    </div>

                                    <div class="small-note">JPG/PNG, max 2MB - Foto cuadrada para el carnet</div>
                                </div>
                            </div>

                            <!-- Foto: Preview -->
                            <div class="col-md-3 col-12 mb-2">
                                <div class="form-section">
                                    <div class="section-title"><i class="bx bx-image me-1"></i> Vista Previa Foto</div>
                                    <div class="text-center">
                                        <img id="preview-foto" class="preview-img" alt="Preview foto" style="width: 150px; height: 150px; display: none;">
                                        <div id="no-preview" class="text-muted small p-3">
                                            <i class="bx bx-image me-1"></i>No hay imagen seleccionada
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Huella: Input -->
                            <div class="col-md-3 col-12 mb-2">
                                <div class="form-section">
                                    <div class="section-title"><i class="bx bx-fingerprint me-1"></i> Huella Dactilar</div>

                                    <div class="mb-3">
                                        <label class="form-label required-label mb-2">Foto de la Huella</label>
                                        <input type="file" class="form-control form-control-sm" id="huella" name="huella" accept="image/*" required>
                                        <div class="small-note">JPG/PNG, max 2MB - Asegúrese que la huella sea visible y nítida</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Huella: Preview -->
                            <div class="col-md-3 col-12 mb-2">
                                <div class="form-section">
                                    <div class="section-title"><i class="bx bx-fingerprint me-1"></i> Vista Previa Huella</div>
                                    <div class="text-center">
                                        <img id="preview-huella" class="preview-img ratio-2x3" alt="Preview huella" style="width: 120px; height: 180px; display: none;">
                                        <div id="no-preview-huella" class="text-muted small p-3">
                                            <i class="bx bx-fingerprint me-1"></i>No hay huella seleccionada
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 5: Documentos Escaneados -->
                        <div class="form-section">
                            <div class="section-title"><i class="bx bx-file me-1"></i> Documentos Escaneados</div>
                            
                            <div class="alert alert-info py-2 mb-3">
                                <small>
                                    <i class="bx bx-info-circle me-1"></i>
                                    <strong>Opcional:</strong> Puede adjuntar documentos escaneados del afiliado
                                </small>
                            </div>

                            <div id="archivos-container">
                                <!-- Primer documento -->
                                <div class="row mb-3">
                                    <!-- Inputs del documento -->
                                    <div class="col-md-6">
                                        <div class="archivo-item">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <label class="form-label mb-0">Tipo</label>
                                                    <select name="archivos[0][tipo]" class="form-select form-select-sm tipo-documento">
                                                        <option value="">Seleccione tipo</option>
                                                        <option value="DNI">Documento de Identidad</option>
                                                        <option value="CERTIFICADO">Certificado</option>
                                                        <option value="CONTRATO">Contrato</option>
                                                        <option value="FOTO_EXTRA">Foto Adicional</option>
                                                        <option value="OTRO">Otro</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <label class="form-label mb-0">Descripción</label>
                                                    <input type="text" name="archivos[0][descripcion]" class="form-control form-control-sm" placeholder="DNI frente">
                                                </div>
                                            </div>
                                            <div class="row g-2 mt-1">
                                                <div class="col-md-8">
                                                    <label class="form-label mb-0">Archivo</label>
                                                    <input type="file" name="archivos[0][archivo]" class="form-control form-control-sm archivo-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label mb-0">&nbsp;</label>
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-remove-archivo" style="display: none;">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview del documento -->
                                    <div class="col-md-6">
                                        <div class="form-section">
                                            <div class="preview-archivo" style="display: none;"></div>
                                            <div class="text-muted small text-center p-3" style="display: none;" class="no-preview-archivo">
                                                <i class="bx bx-file"></i> Ningún archivo
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-archivo">
                                <i class="bx bx-plus me-1"></i> Agregar Otro Documento
                            </button>
                            
                            <div class="small-note mt-2">
                                Formatos: PDF, JPG, PNG, DOC (Máx. 5MB)
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

                <!-- Panel de instrucciones a la derecha -->
                <div class="instructions-panel">
                    <h6><i class="bx bx-info-circle me-2"></i>Instrucciones</h6>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Datos Básicos:</strong> Complete todos los campos obligatorios (*)
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>CI:</strong> Ingrese solo números sin espacios ni guiones
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Nombres:</strong> Escriba en mayúsculas, sin abreviaturas
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Fecha Nacimiento:</strong> Use formato DD/MM/AAAA
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Celular:</strong> Solo números, sin espacios ni código de país
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Información Profesional:</strong> Seleccione todas las opciones requeridas
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Foto Carnet:</strong>
                        <ul>
                            <li>Formato JPG o PNG</li>
                            <li>Tamaño máximo: 2MB</li>
                            <li>Fondo claro preferible</li>
                            <li>Rostro visible y centrado</li>
                        </ul>
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Huella Dactilar:</strong>
                        <ul>
                            <li>Foto nítida del dedo índice derecho</li>
                            <li>Buena iluminación</li>
                            <li>Formato JPG o PNG</li>
                            <li>Máximo 2MB</li>
                        </ul>
                    </div>

                    <div class="alert alert-info py-2 mb-3">
                        <small>
                            <i class="bx bx-info-circle me-1"></i>
                            <strong>Importante:</strong> Suba una foto clara de la huella del dedo índice derecho en formato 2x3
                        </small>
                    </div>

                    <div class="instruction-item">
                        <i class="bx bx-check"></i>
                        <strong>Campos Obligatorios:</strong> Todos los marcados con (*) deben ser completados
                    </div>

                    <div class="alert alert-warning mt-3 p-2">
                        <small>
                            <i class="bx bx-time me-1"></i>
                            <strong>Tiempo estimado:</strong> 3-5 minutos para completar el registro
                        </small>
                    </div>
                </div>
            </div>
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

<!-- Modal para recorte de foto -->
<div class="modal fade photo-modal" id="photoCropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-crop me-2"></i>Recortar Foto - Formato Cuadrado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-info py-2">
                    <small><i class="bx bx-info-circle me-1"></i>Recorta la foto en formato cuadrado para el carnet</small>
                </div>
                <div class="cropper-container mb-3">
                    <img id="photo-to-crop" src="#" alt="Foto a recortar" style="max-width: 100%;">
                </div>
                <div class="cropper-actions">
                    <button type="button" class="btn btn-success btn-sm" onclick="applyPhotoCrop()">
                        <i class="bx bx-check me-1"></i>Aplicar Recorte
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelPhotoCrop()">
                        <i class="bx bx-x me-1"></i>Cancelar
                    </button>
                </div>
                <div class="photo-preview-container">
                    <small class="text-muted">Vista previa formato cuadrado:</small>
                    <div class="photo-preview">
                        <img id="photo-crop-preview" src="#" alt="Vista previa foto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para recorte de huella -->
<div class="modal fade" id="huellaCropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-crop me-2"></i>Recortar Huella - Formato 2x3</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-info py-2">
                    <small><i class="bx bx-info-circle me-1"></i>Recorta la huella en formato 2x3 (vertical)</small>
                </div>
                <div class="cropper-container mb-3">
                    <img id="huella-to-crop" src="#" alt="Huella a recortar" style="max-width: 100%;">
                </div>
                <div class="cropper-actions">
                    <button type="button" class="btn btn-success btn-sm" onclick="applyHuellaCrop()">
                        <i class="bx bx-check me-1"></i>Aplicar Recorte
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelHuellaCrop()">
                        <i class="bx bx-x me-1"></i>Cancelar
                    </button>
                </div>
                <div class="huella-preview-container">
                    <small class="text-muted">Vista previa formato 2x3:</small>
                    <div class="huella-preview">
                        <img id="huella-crop-preview" src="#" alt="Vista previa huella">
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
        let photoData = null;
        let photoCropper = null;
        let huellaCropper = null;
        let photoSource = 'upload'; // 'upload' o 'camera'

        $(document).ready(function() {
            Parsley.setLocale('es');

            // === NUEVO: Restaurar fotos y documentos desde localStorage al cargar ===
            restaurarFotosDesdeLocalStorage();
            restaurarDocumentosDesdeLocalStorage();

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

            $('#ciudad_id, #profesion_tecnica_id, #especialidad_id, #sector_economico_id, #organizacion_social_id').select2(select2Config);

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

            // Convertir textos a MAYÚSCULAS automáticamente (EXCEPTO fecha_nacimiento)
            $('input[type="text"]:not(#fecha_nacimiento)').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Lógica de cámara
            $('.camera-option').on('click', function() {
                const type = $(this).data('type');
                $('.camera-option').removeClass('active');
                $(this).addClass('active');

                // Guardar la opción de origen (upload | camera) en el input oculto
                $('#_camera_option').val(type === 'camera' ? 'camera' : 'upload');

                if (type === 'camera') {
                    $('#upload-section').hide();
                    $('#camera-section').show();
                    $('#foto_input').removeAttr('required');
                    $('#foto_data').attr('required', 'required');
                } else {
                    $('#upload-section').show();
                    $('#camera-section').hide();
                    $('#foto_input').attr('required', 'required');
                    $('#foto_data').removeAttr('required');
                    photoData = null;
                }
            });

            // Evento para el botón de seleccionar foto
            $('#btn-seleccionar-foto').on('click', function() {
                $('#foto_input').click();
            });

            // Evento cuando se selecciona una foto
            $('#foto_input').on('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    if (file.size > 2 * 1024 * 1024) {
                        alert('La imagen es demasiado grande. Máximo 2MB.');
                        this.value = '';
                        return;
                    }

                    if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                        alert('Solo se permiten imágenes JPG o PNG.');
                        this.value = '';
                        return;
                    }

                    // Mostrar información del archivo
                    $('#foto-info').show();
                    $('#foto-nombre').text(file.name + ' (' + formatBytes(file.size) + ')');

                    // Preparar para recorte
                    photoSource = 'upload';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        initPhotoCropper(e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Evento para la huella
            $('#huella').on('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    if (file.size > 2 * 1024 * 1024) {
                        alert('La imagen de la huella es demasiado grande. Máximo 2MB.');
                        this.value = '';
                        return;
                    }

                    if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                        alert('Solo se permiten imágenes JPG o PNG para la huella.');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        initHuellaCropper(e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Búsqueda por CI - Mostrar modal con opciones
            let debounceTimer;
            $('#ci').on('input', function() {
                clearTimeout(debounceTimer);
                const ci = $(this).val().trim();
                if (ci.length >= 5) {
                    debounceTimer = setTimeout(() => buscarPersonaParaModal(ci), 700);
                }
            });

            // Validación antes de enviar
            $('form').on('submit', function(e) {
                const fotoFile = $('#foto_input')[0].files[0];
                const huellaFile = $('#huella')[0].files[0];

                if (!fotoFile) {
                    e.preventDefault();
                    showAlertMessage('Debe subir y recortar la foto del carnet', 'danger');
                    return false;
                }

                if (!huellaFile) {
                    e.preventDefault();
                    showAlertMessage('Debe subir y recortar la huella dactilar', 'danger');
                    return false;
                }

                // Validar documentos adjuntos
                const archivosInputs = document.querySelectorAll('.archivo-input');
                let archivosValidos = true;

                archivosInputs.forEach((input, index) => {
                    const tipoSelect = input.closest('.archivo-item').querySelector('.tipo-documento');

                    if (input.files.length > 0 && !tipoSelect.value) {
                        archivosValidos = false;
                        alert(`El documento ${index + 1} necesita un tipo de documento seleccionado.`);
                        tipoSelect.focus();
                    }

                    if (tipoSelect.value && input.files.length === 0) {
                        archivosValidos = false;
                        alert(`El tipo de documento "${tipoSelect.options[tipoSelect.selectedIndex].text}" necesita un archivo.`);
                        input.focus();
                    }
                });

                if (!archivosValidos) {
                    e.preventDefault();
                    return false;
                }

                // === NUEVO: Guardar fotos y documentos en localStorage antes de enviar ===
                guardarFotosEnLocalStorage();
                guardarDocumentosEnLocalStorage();

                return true;
            });

            // Función de búsqueda por CI - Mostrar modal con opciones
            function buscarPersonaParaModal(ci) {
                $('#ci').after('<span class="loading-api" id="loading-ci" title="Buscando..."></span>');
                const url_ajax = '{{ url('/') }}/buscar-persona/' + ci;
                
                $.ajax({
                    url: url_ajax,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#loading-ci').remove();
                        if (response.success && response.data) {
                            // Mostrar modal con opciones
                            mostrarModalOpcionesAfiliado(response.data);
                        } else {
                            mostrarMensaje('CI no encontrado - complete los datos nuevos', 'info');
                        }
                    },
                    error: function() {
                        $('#loading-ci').remove();
                        mostrarMensaje('Error al buscar CI', 'error');
                    }
                });
            }

            // Modal para mostrar opciones de afiliado existente
            function mostrarModalOpcionesAfiliado(afiliado) {
                const estado = afiliado.estado || 'PENDIENTE';
                const nombreCompleto = afiliado.nombres + ' ' + (afiliado.apellido_paterno || '') + ' ' + (afiliado.apellido_materno || '');
                const canActivate = $('form').data('can-activate') === 'true';
                const canPrint = $('form').data('can-print') === 'true';
                
                let contenidoModal = '<h5>' + nombreCompleto + '</h5>';
                contenidoModal += '<p class="text-muted"><strong>CI:</strong> ' + afiliado.ci + ' ' + afiliado.expedicion + '</p>';
                contenidoModal += '<p><strong>Estado:</strong> <span class="badge bg-' + (estado === 'ACTIVO' ? 'success' : estado === 'PENDIENTE' ? 'warning' : 'danger') + '">' + estado + '</span></p>';
                contenidoModal += '<div class="mt-3">';
                
                if (estado === 'ACTIVO') {
                    if (canPrint) {
                        contenidoModal += '<p class="mb-3">Este afiliado ya está activo. ¿Desea reimprimir su carnet?</p>';
                        contenidoModal += '<button class="btn btn-danger btn-sm" onclick="reimprimir(' + afiliado.id + ')" data-bs-dismiss="modal"><i class="fa fa-print me-1"></i>Reimprimir Carnet</button>';
                    } else {
                        contenidoModal += '<p class="mb-3 text-muted"><i class="fa fa-lock me-1"></i>No tiene permisos para reimprimir carnets.</p>';
                    }
                } else if (estado === 'PENDIENTE') {
                    if (canActivate) {
                        contenidoModal += '<p class="mb-3">Este afiliado está pendiente de activación. ¿Desea activarlo ahora?</p>';
                        contenidoModal += '<button class="btn btn-success btn-sm" onclick="activarAfiliado(' + afiliado.id + ')" data-bs-dismiss="modal"><i class="fa fa-check me-1"></i>Activar Afiliado</button>';
                    } else {
                        contenidoModal += '<p class="mb-3 text-muted"><i class="fa fa-lock me-1"></i>No tiene permisos para activar afiliados.</p>';
                    }
                } else {
                    contenidoModal += '<p class="mb-3">Este afiliado está inactivo. Contacte al administrador para reactivarlo.</p>';
                }
                
                contenidoModal += '</div>';
                
                // Crear modal dinámico
                let $modalExistente = $('#modalOpcionesAfiliado');
                if ($modalExistente.length) {
                    $modalExistente.find('.modal-body').html(contenidoModal);
                } else {
                    const html = '<div class="modal fade" id="modalOpcionesAfiliado" tabindex="-1" aria-hidden="true">' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<h5 class="modal-title">Afiliado Encontrado</h5>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                        '</div>' +
                        '<div class="modal-body">' + contenidoModal + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    
                    $('body').append(html);
                    $modalExistente = $('#modalOpcionesAfiliado');
                }
                
                const modal = new bootstrap.Modal($modalExistente[0]);
                modal.show();
            }

            // Funciones para acciones del modal
            function reimprimir(afiliadoId) {
                window.location.href = '{{ url('/') }}/afiliados/' + afiliadoId + '/carnet/pdf';
            }

            function activarAfiliado(afiliadoId) {
                if (confirm('¿Está seguro de que desea activar este afiliado?')) {
                    // Realizar petición AJAX para activar
                    $.ajax({
                        url: '{{ url('/') }}/afiliados/' + afiliadoId + '/activar',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        success: function() {
                            mostrarMensaje('Afiliado activado exitosamente', 'success');
                            setTimeout(() => {
                                window.location.href = '{{ url('/') }}/afiliados';
                            }, 2000);
                        },
                        error: function() {
                            mostrarMensaje('Error al activar afiliado', 'error');
                        }
                    });
                }
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

        // ========== FUNCIONES DE CÁMARA ==========
        async function startCamera() {
            try {
                if (stream) stream.getTracks().forEach(t => t.stop());

                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
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
                photoSource = 'camera';
                initPhotoCropper(photoData);
                $('#cameraModal').modal('hide');
                if (stream) stream.getTracks().forEach(t => t.stop());
            }
        }

        // ========== FUNCIONES DE CROPPER FOTO ==========
        function initPhotoCropper(imageSrc) {
            const image = document.getElementById('photo-to-crop');
            const preview = document.getElementById('photo-crop-preview');
            
            image.src = imageSrc;
            preview.src = imageSrc;

            $('#photoCropperModal').modal('show');

            $('#photoCropperModal').on('shown.bs.modal', function() {
                if (photoCropper) {
                    photoCropper.destroy();
                }
                
                photoCropper = new Cropper(image, {
                    aspectRatio: 1, // Formato cuadrado
                    viewMode: 1,
                    preview: '#photo-crop-preview',
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    ready: function() {
                        const containerData = photoCropper.getContainerData();
                        const cropBoxData = {
                            width: Math.min(containerData.width, containerData.height),
                            height: Math.min(containerData.width, containerData.height)
                        };
                        photoCropper.setCropBoxData(cropBoxData);
                    }
                });
            });
        }

        function applyPhotoCrop() {
            if (!photoCropper) return;

            const canvas = photoCropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedPhoto = canvas.toDataURL('image/jpeg', 0.9);

            // Vista previa
            $('#preview-foto').attr('src', croppedPhoto).show();
            $('#no-preview').hide();

            // Guardar en el input correspondiente
            if (photoSource === 'camera') {
                $('#foto_data').val(croppedPhoto);
                setFileFromBase64(croppedPhoto, 'foto_input', 'foto_carnet.jpg');
                // Indicar que la foto vino de la cámara
                $('#_camera_option').val('camera');
            } else {
                setFileFromBase64(croppedPhoto, 'foto_input', 'foto_carnet.jpg');
                // Indicar origen upload
                $('#_camera_option').val('upload');
            }

            // Cerrar modal y limpiar
            $('#photoCropperModal').modal('hide');
            photoCropper.destroy();
            photoCropper = null;

            showAlertMessage('Foto recortada correctamente', 'success');
        }

        function cancelPhotoCrop() {
            $('#photoCropperModal').modal('hide');
            if (photoCropper) {
                photoCropper.destroy();
                photoCropper = null;
            }

            // Limpiar según el origen
            if (photoSource === 'upload') {
                $('#foto_input').val('');
                $('#foto-info').hide();
                $('#foto-nombre').text('');
                $('#_camera_option').val('upload');
            } else {
                $('#foto_data').val('');
                $('#_camera_option').val('upload');
            }
        }

        // ========== FUNCIONES DE CROPPER HUELLA ==========
        function initHuellaCropper(imageSrc) {
            const image = document.getElementById('huella-to-crop');
            const preview = document.getElementById('huella-crop-preview');
            
            image.src = imageSrc;
            preview.src = imageSrc;

            $('#huellaCropperModal').modal('show');

            $('#huellaCropperModal').on('shown.bs.modal', function() {
                if (huellaCropper) {
                    huellaCropper.destroy();
                }
                
                huellaCropper = new Cropper(image, {
                    aspectRatio: 2/3, // Formato 2x3
                    viewMode: 1,
                    preview: '#huella-crop-preview',
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false
                });
            });
        }

        function applyHuellaCrop() {
            if (!huellaCropper) return;

            const canvas = huellaCropper.getCroppedCanvas({
                width: 400,
                height: 600,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedHuella = canvas.toDataURL('image/jpeg', 0.9);

            // Vista previa
            $('#preview-huella').attr('src', croppedHuella).show();
            $('#no-preview-huella').hide();

            // Guardar en el input
            setFileFromBase64(croppedHuella, 'huella', 'huella_dactilar.jpg');

            // Cerrar modal y limpiar
            $('#huellaCropperModal').modal('hide');
            huellaCropper.destroy();
            huellaCropper = null;

            showAlertMessage('Huella recortada en formato 2x3 correctamente', 'success');
        }

        function cancelHuellaCrop() {
            $('#huellaCropperModal').modal('hide');
            if (huellaCropper) {
                huellaCropper.destroy();
                huellaCropper = null;
            }
            $('#huella').val('');
        }

        // ========== FUNCIONES UTILITARIAS ==========
        function setFileFromBase64(base64, inputId, filename) {
            if (!base64) return;
            
            const arr = base64.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            
            const file = new File([u8arr], filename, { type: mime });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById(inputId).files = dataTransfer.files;
            
            if (inputId === 'foto_input') {
                $('#foto-info').show();
                $('#foto-nombre').text(filename + ' (' + formatBytes(file.size) + ')');
            }
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function showAlertMessage(mensaje, tipo) {
            $('.alert-cropper-message').remove();
            const bgClass = tipo === 'success' ? 'alert-success' : tipo === 'error' ? 'alert-danger' : 'alert-info';
            const icon = tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle';

            const $alert = $(`<div class="alert ${bgClass} alert-dismissible fade show mt-2 alert-cropper-message">
                <i class="fa fa-${icon} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`);

            $('.panel-body').prepend($alert);
            setTimeout(() => {
                $alert.alert('close');
            }, 4000);
        }

        // ========== MANEJO DE ARCHIVOS ADICIONALES ==========
        let archivoCounter = 1;

        document.getElementById('btn-agregar-archivo').addEventListener('click', function() {
            const container = document.getElementById('archivos-container');
            const nuevoDocumento = document.createElement('div');
            nuevoDocumento.className = 'row mb-3';
            nuevoDocumento.innerHTML = `
                <!-- Inputs del documento -->
                <div class="col-md-6">
                    <div class="archivo-item">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label mb-0">Tipo</label>
                                <select name="archivos[${archivoCounter}][tipo]" class="form-select form-select-sm tipo-documento">
                                    <option value="">Seleccione tipo</option>
                                    <option value="DNI">Documento de Identidad</option>
                                    <option value="CERTIFICADO">Certificado</option>
                                    <option value="CONTRATO">Contrato</option>
                                    <option value="FOTO_EXTRA">Foto Adicional</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label mb-0">Descripción</label>
                                <input type="text" name="archivos[${archivoCounter}][descripcion]" 
                                       class="form-control form-control-sm" 
                                       placeholder="DNI frente">
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-md-8">
                                <label class="form-label mb-0">Archivo</label>
                                <input type="file" name="archivos[${archivoCounter}][archivo]" 
                                       class="form-control form-control-sm archivo-input" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-0">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-remove-archivo">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview del documento -->
                <div class="col-md-6">
                    <div class="form-section">
                        <div class="preview-archivo" style="display: none;"></div>
                        <div class="text-muted small text-center p-3 no-preview-archivo">
                            <i class="bx bx-file"></i> Ningún archivo
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(nuevoDocumento);
            archivoCounter++;
            
            // Agregar eventos
            nuevoDocumento.querySelector('.btn-remove-archivo').addEventListener('click', function() {
                nuevoDocumento.remove();
            });
            
            nuevoDocumento.querySelector('.archivo-input').addEventListener('change', function(e) {
                previewArchivo(e.target);
            });
        });

        function previewArchivo(input) {
            const file = input.files[0];
            const previewDiv = input.closest('.archivo-item').querySelector('.preview-archivo');
            
            if (!file) {
                previewDiv.style.display = 'none';
                previewDiv.innerHTML = '';
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 5MB.');
                input.value = '';
                previewDiv.style.display = 'none';
                previewDiv.innerHTML = '';
                return;
            }
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewDiv.innerHTML = `
                    <div class="text-center p-3 bg-light rounded">
                        <i class="bx bx-file" style="font-size: 3rem;"></i>
                        <br>
                        <small>${file.name}</small>
                    </div>
                `;
                previewDiv.style.display = 'block';
            }
        }

        // Agregar evento para el primer input
        document.querySelector('.archivo-input').addEventListener('change', function(e) {
            previewArchivo(e.target);
        });

        // Eventos para la cámara
        document.getElementById('btnTakePhoto').addEventListener('click', takePhoto);
        document.getElementById('btnRetakePhoto').addEventListener('click', retakePhoto);
        document.getElementById('btnUsePhoto').addEventListener('click', usePhoto);

        // Limpiar cuando se cierren los modales
        $('#cameraModal').on('hidden.bs.modal', function() {
            if (stream) stream.getTracks().forEach(t => t.stop());
            stream = null;
        });

        $('#photoCropperModal').on('hidden.bs.modal', function() {
            if (photoCropper) {
                photoCropper.destroy();
                photoCropper = null;
            }
        });

        $('#huellaCropperModal').on('hidden.bs.modal', function() {
            if (huellaCropper) {
                huellaCropper.destroy();
                huellaCropper = null;
            }
        });

        // ========== FUNCIONES DE LOCALSTORAGE PARA PRESERVAR FOTOS Y DOCUMENTOS ==========
        /**
         * Guardar fotos en localStorage antes de enviar el formulario
         */
        function guardarFotosEnLocalStorage() {
            const fotoFile = $('#foto_input')[0].files[0];
            const huellaFile = $('#huella')[0].files[0];
            const previewFotoSrc = $('#preview-foto').attr('src');
            const previewHuellaSrc = $('#preview-huella').attr('src');

            // Guardar foto
            if (fotoFile && previewFotoSrc) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        localStorage.setItem('carnetizacion_foto_preview', previewFotoSrc);
                        localStorage.setItem('carnetizacion_foto_nombre', fotoFile.name);
                    } catch (err) {
                        console.warn('No se pudo guardar foto en localStorage:', err);
                    }
                };
                reader.readAsDataURL(fotoFile);
            }

            // Guardar huella
            if (huellaFile && previewHuellaSrc) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        localStorage.setItem('carnetizacion_huella_preview', previewHuellaSrc);
                        localStorage.setItem('carnetizacion_huella_nombre', huellaFile.name);
                    } catch (err) {
                        console.warn('No se pudo guardar huella en localStorage:', err);
                    }
                };
                reader.readAsDataURL(huellaFile);
            }
        }

        /**
         * Guardar documentos escaneados en localStorage
         */
        function guardarDocumentosEnLocalStorage() {
            try {
                const archivosData = [];
                const archivosItems = document.querySelectorAll('.archivo-item');
                
                archivosItems.forEach((item, index) => {
                    const tipoSelect = item.querySelector('.tipo-documento');
                    const descripcionInput = item.querySelector('input[name^="archivos"][name*="descripcion"]');
                    const archivoInput = item.querySelector('.archivo-input');
                    
                    if (archivoInput && archivoInput.files[0]) {
                        const file = archivoInput.files[0];
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            try {
                                const docData = {
                                    tipo: tipoSelect.value,
                                    descripcion: descripcionInput.value,
                                    nombre: file.name,
                                    data: e.target.result
                                };
                                archivosData.push(docData);
                                localStorage.setItem('carnetizacion_documentos_' + index, JSON.stringify(docData));
                            } catch (err) {
                                console.warn('No se pudo guardar documento ' + index + ' en localStorage:', err);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                localStorage.setItem('carnetizacion_documentos_count', archivosItems.length.toString());
            } catch (err) {
                console.warn('Error guardando documentos en localStorage:', err);
            }
        }

        /**
         * Restaurar fotos desde localStorage si existen (cuando hay error de validación)
         */
        function restaurarFotosDesdeLocalStorage() {
            try {
                const fotoPreview = localStorage.getItem('carnetizacion_foto_preview');
                const fotoNombre = localStorage.getItem('carnetizacion_foto_nombre');
                const huellaPreview = localStorage.getItem('carnetizacion_huella_preview');
                const huellaNombre = localStorage.getItem('carnetizacion_huella_nombre');

                // Restaurar foto
                if (fotoPreview && fotoNombre) {
                    $('#preview-foto').attr('src', fotoPreview).show();
                    $('#no-preview').hide();
                    $('#foto-info').show();
                    $('#foto-nombre').text(fotoNombre);
                    setFileFromBase64(fotoPreview, 'foto_input', fotoNombre);
                }

                // Restaurar huella
                if (huellaPreview && huellaNombre) {
                    $('#preview-huella').attr('src', huellaPreview).show();
                    $('#no-preview-huella').hide();
                    setFileFromBase64(huellaPreview, 'huella', huellaNombre);
                }
            } catch (err) {
                console.warn('Error restaurando fotos desde localStorage:', err);
            }
        }

        /**
         * Restaurar documentos escaneados desde localStorage
         */
        function restaurarDocumentosDesdeLocalStorage() {
            try {
                const docCount = localStorage.getItem('carnetizacion_documentos_count');
                if (!docCount) return;
                
                const container = document.getElementById('archivos-container');
                const count = parseInt(docCount);
                
                // Obtener la cantidad actual de items
                const actualesCount = document.querySelectorAll('.archivo-item').length;
                
                for (let i = 0; i < count; i++) {
                    const docJson = localStorage.getItem('carnetizacion_documentos_' + i);
                    if (docJson) {
                        try {
                            const docData = JSON.parse(docJson);
                            
                            // Si es el primer item, usar el existente
                            let archivoItem;
                            if (i === 0 && actualesCount === 1) {
                                archivoItem = document.querySelector('.archivo-item');
                            } else if (i >= actualesCount) {
                                // Crear nuevo item
                                const nuevoItem = document.createElement('div');
                                nuevoItem.className = 'archivo-item mb-3';
                                nuevoItem.innerHTML = `
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Tipo de Documento</label>
                                            <select name="archivos[${i}][tipo]" class="form-select form-select-sm tipo-documento">
                                                <option value="">Seleccione tipo</option>
                                                <option value="DNI">Documento de Identidad</option>
                                                <option value="CERTIFICADO">Certificado</option>
                                                <option value="CONTRATO">Contrato</option>
                                                <option value="FOTO_EXTRA">Foto Adicional</option>
                                                <option value="OTRO">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mb-0">Descripción (opcional)</label>
                                            <input type="text" name="archivos[${i}][descripcion]" class="form-control form-control-sm" placeholder="Ej: DNI frente y dorso">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-0">Archivo</label>
                                            <input type="file" name="archivos[${i}][archivo]" class="form-control form-control-sm archivo-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="preview-archivo" style="display: none;"></div>
                                        </div>
                                    </div>
                                `;
                                container.appendChild(nuevoItem);
                                archivoItem = nuevoItem;
                            } else {
                                archivoItem = document.querySelectorAll('.archivo-item')[i];
                            }
                            
                            // Rellenar datos
                            archivoItem.querySelector('.tipo-documento').value = docData.tipo;
                            archivoItem.querySelector('input[name*="descripcion"]').value = docData.descripcion;
                            
                            // Restaurar archivo y preview
                            const archivoInput = archivoItem.querySelector('.archivo-input');
                            // Generar ID único si no existe
                            if (!archivoInput.id) {
                                archivoInput.id = 'archivo_input_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                            }
                            setFileFromBase64(docData.data, archivoInput.id, docData.nombre);
                            
                            // Mostrar preview si es imagen
                            if (docData.data.startsWith('data:image')) {
                                const preview = archivoItem.querySelector('.preview-archivo');
                                preview.innerHTML = '<img src="' + docData.data + '" alt="Preview">';
                                preview.style.display = 'block';
                            }
                        } catch (e) {
                            console.warn('Error restaurando documento ' + i + ':', e);
                        }
                    }
                }
            } catch (err) {
                console.warn('Error restaurando documentos desde localStorage:', err);
            }
        }

        /**
         * Limpiar localStorage después de guardado exitoso
         */
        function limpiarLocalStorageFotos() {
            try {
                localStorage.removeItem('carnetizacion_foto_preview');
                localStorage.removeItem('carnetizacion_foto_nombre');
                localStorage.removeItem('carnetizacion_huella_preview');
                localStorage.removeItem('carnetizacion_huella_nombre');
                
                // Limpiar documentos
                const docCount = localStorage.getItem('carnetizacion_documentos_count') || 0;
                for (let i = 0; i < parseInt(docCount); i++) {
                    localStorage.removeItem('carnetizacion_documentos_' + i);
                }
                localStorage.removeItem('carnetizacion_documentos_count');
            } catch (err) {
                console.warn('Error limpiando localStorage:', err);
            }
        }

        // Limpiar localStorage al cargar (si el usuario llegó con éxito)
        // Se ejecuta al cargar la página si no hay errores
        if (!document.querySelector('.alert-danger')) {
            limpiarLocalStorageFotos();
        }
    </script>
@endpush