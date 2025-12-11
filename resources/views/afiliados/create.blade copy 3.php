@extends('layouts.app')
@section('title', 'Crear Nuevo Carnet - CIZEE')

@push('styles')
    <link href="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" />
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

        /* Nuevos estilos para el layout compacto */
        .compact-form .form-control-sm,
        .compact-form .form-select-sm {
            font-size: 0.8rem;
            padding: 4px 8px;
            height: calc(1.5em + 0.5rem + 2px);
            /* background-color: var(--bs-body-bg); */
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
        }

        .instruction-item li {
            color: var(--bs-body-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 4fr 1fr;
            gap: 20px;
        }

        /* Estilos específicos para modo oscuro */
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

        /* Asegurar que el texto sea legible en ambos modos */
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

        /* Mejorar contraste en modo oscuro */
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

    <style>
        /* Estilos para el cropper */
        .cropper-container {
            direction: ltr;
            font-size: 0;
            line-height: 0;
            position: relative;
            touch-action: none;
            user-select: none;
        }

        .cropper-wrap-box,
        .cropper-canvas,
        .cropper-drag-box,
        .cropper-crop-box,
        .cropper-modal {
            bottom: 0;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
        }

        .cropper-container img {
            display: block;
            height: 100%;
            width: 100%;
            max-width: none !important;
        }

        .cropper-modal {
            background-color: #000;
            opacity: 0.5;
        }

        .cropper-view-box {
            display: block;
            height: 100%;
            outline: 1px solid #39f;
            outline-color: rgba(51, 153, 255, 0.75);
            overflow: hidden;
            width: 100%;
        }

        .cropper-dashed {
            border: 0 dashed #eee;
            display: block;
            opacity: 0.5;
            position: absolute;
        }

        .cropper-center {
            display: block;
            height: 0;
            left: 50%;
            opacity: 0.75;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .cropper-center::before,
        .cropper-center::after {
            background-color: #eee;
            content: ' ';
            display: block;
            position: absolute;
        }

        .cropper-face {
            background-color: #fff;
            display: block;
            height: 100%;
            opacity: 0.1;
            position: absolute;
            width: 100%;
        }

        .cropper-line {
            background-color: #39f;
            display: block;
            opacity: 0.1;
            position: absolute;
        }

        .cropper-point {
            background-color: #39f;
            display: block;
            height: 5px;
            opacity: 0.75;
            position: absolute;
            width: 5px;
        }

        .cropper-point.point-se {
            bottom: -3px;
            cursor: se-resize;
            right: -3px;
        }

        .cropper-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .cropper-preview-container {
            text-align: center;
            margin-top: 15px;
        }

        .cropper-preview {
            width: 150px;
            height: 150px;
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }

        .cropper-preview img {
            max-width: 100%;
        }
    </style>

    <style>
        /* Estilos específicos para la huella 2x3 */
        .huella-preview-container {
            text-align: center;
            margin-top: 15px;
        }

        .huella-preview {
            width: 150px;
            height: 225px;
            /* 2x3 ratio */
            border: 2px solid #28a745;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }

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
    </style>

    <style>
        /* Estilos para el layout de dos columnas */
        .form-section {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .form-section .text-center {
            margin-top: auto;
        }

        /* Asegurar que ambas columnas tengan la misma altura en pantallas grandes */
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

        /* Mejorar el aspecto de las secciones */
        .form-section {
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 0;
            background: var(--bs-light);
        }

        /* Espaciado entre columnas */
        .col-md-6:first-child {
            padding-right: 10px;
        }

        .col-md-6:last-child {
            padding-left: 10px;
        }

        @media (max-width: 767px) {

            .col-md-6:first-child,
            .col-md-6:last-child {
                padding-right: 12px;
                padding-left: 12px;
            }

            .col-md-6:first-child {
                margin-bottom: 15px;
            }
        }
    </style>

    <style>
        /* Modal específico para recorte de foto */
        .photo-modal .modal-dialog {
            max-width: 700px;
        }

        .photo-modal .modal-body {
            padding: 15px;
        }

        .photo-modal .cropper-container {
            height: 400px;
            overflow: hidden;
            background: #000;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .photo-preview-container {
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

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>


    <style>
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

        .archivo-item .col-md-3 {
            position: relative;
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
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
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
                        <form action="{{ route('afiliados.store') }}" method="POST" enctype="multipart/form-data"
                            data-parsley-validate>
                            @csrf

                            <!-- Sección 1: Datos Básicos del Afiliado -->
                            <div class="form-section">
                                <div class="section-title"><i class="bx bx-id-card me-1"></i> Datos Básicos</div>
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <label class="form-label required-label mb-0">CI</label>
                                        <input type="text" class="form-control form-control-sm" id="ci"
                                            name="ci" required placeholder="1234567" value="{{ old('ci') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label required-label mb-0">Exp.</label>
                                        <select class="form-select form-select-sm" id="expedicion" name="expedicion"
                                            required>
                                            <option value="" disabled selected>Seleccione.</option>
                                            @foreach (['S/E', 'LP', 'SC', 'CBBA', 'OR', 'PT', 'CH', 'TJ', 'BE', 'PD'] as $exp)
                                                <option value="{{ $exp }}"
                                                    {{ old('expedicion') == $exp ? 'selected' : '' }}>{{ $exp }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required-label mb-0">Nombres</label>
                                        <input type="text" class="form-control form-control-sm" id="nombres"
                                            name="nombres" required value="{{ old('nombres') }}">
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
                                        <label class="form-label mb-0">F. Nacimiento</label>
                                        <input type="text" class="form-control form-control-sm datepicker"
                                            id="fecha_nacimiento" name="fecha_nacimiento" placeholder="DD/MM/AAAA"
                                            value="{{ old('fecha_nacimiento') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label required-label mb-0">Celular</label>
                                        <input type="number" class="form-control form-control-sm" id="celular"
                                            name="celular" required placeholder="72000000" value="{{ old('celular') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label required-label mb-0">Género</label>
                                        <select class="form-select form-select-sm" id="genero" name="genero" required>
                                            <option value="">Seleccione.</option>
                                            <option value="MASCULINO"
                                                {{ old('genero') == 'MASCULINO' ? 'selected' : '' }}>MASCULINO</option>
                                            <option value="FEMENINO" {{ old('genero') == 'FEMENINO' ? 'selected' : '' }}>
                                                FEMENINO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required-label mb-0">Ciudad</label>
                                        <select class="form-select form-select-sm" id="ciudad_id" name="ciudad_id"
                                            required>
                                            <option value="">Seleccione ciudad</option>
                                            @foreach ($ciudades as $ciudad)
                                                <option value="{{ $ciudad->id }}"
                                                    {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                                    {{ $ciudad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Información Profesional -->
                            <div class="form-section">
                                <div class="section-title"><i class="bx bx-briefcase me-1"></i> Información Profesional
                                </div>

                                <div class="row g-2 mt-1">
                                    <div class="col-md-4">
                                        <label class="form-label required-label mb-0">Sector Económico</label>
                                        <select id="sector_economico_id" name="sector_economico_id"
                                            class="form-select form-select-sm" required>
                                            <option value="">Seleccione sector</option>
                                            @foreach ($sectoresEconomicos as $sector)
                                                <option value="{{ $sector->id }}"
                                                    {{ old('sector_economico_id') == $sector->id ? 'selected' : '' }}>
                                                    {{ $sector->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required-label mb-0">Profesión</label>
                                        <select id="profesion_tecnica_id" name="profesion_tecnica_id"
                                            class="default-select2 form-control form-select form-select-sm" required>
                                            <option value="">Seleccione profesión</option>
                                            @foreach ($profesiones as $profesion)
                                                <option value="{{ $profesion->id }}"
                                                    {{ old('profesion_tecnica_id') == $profesion->id ? 'selected' : '' }}>
                                                    {{ $profesion->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required-label mb-0">Especialidad</label>
                                        <select id="especialidad_id" name="especialidad_id"
                                            class="form-select form-select-sm" required>
                                            <option value="">Seleccione especialidad</option>
                                            @foreach ($especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}"
                                                    {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Organización Social</label>
                                        <select id="organizacion_social_id" name="organizacion_social_id"
                                            class="form-select form-select-sm">
                                            <option value="">Seleccione organización</option>
                                            @foreach ($organizacionesSociales as $organizacion)
                                                <option value="{{ $organizacion->id }}"
                                                    {{ old('organizacion_social_id') == $organizacion->id ? 'selected' : '' }}>
                                                    {{ $organizacion->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label mb-0">Dirección Actividad Económica</label>
                                        <input type="text" class="form-control form-control-sm" id="direccion"
                                            name="direccion" placeholder="" value="{{ old('direccion') }}">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <!-- Columna 1: Foto del Carnet -->
                                <div class="col-md-6">
                                    <div class="form-section">
                                        <div class="section-title"><i class="bx bx-camera me-1"></i> Foto para el Carnet
                                        </div>

                                        <div class="d-flex gap-2 mb-2">
                                            <div class="camera-option active" data-type="upload">
                                                <small><i class="bx bx-upload me-1"></i>Subir archivo</small>
                                            </div>
                                            <div class="camera-option" data-type="camera">
                                                <small><i class="bx bx-camera me-1"></i>Tomar foto</small>
                                            </div>
                                        </div>

                                        <div id="upload-section">
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                                                id="btn-seleccionar-foto">
                                                <i class="bx bx-upload me-1"></i>Seleccionar Foto
                                            </button>
                                            <input type="file" name="foto" class="d-none" id="foto_input"
                                                accept="image/*">
                                            <input type="hidden" id="foto_meta" name="foto_meta">
                                            <div id="foto-info" class="small-note text-center" style="display: none;">
                                                <span id="foto-nombre"></span>
                                            </div>
                                        </div>

                                        <div id="camera-section" style="display: none;">
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                                                onclick="openCamera()">
                                                <i class="bx bx-camera me-1"></i>Abrir Cámara
                                            </button>
                                            <input type="hidden" id="foto_data" name="foto_data">
                                        </div>

                                        <div class="small-note">JPG/PNG, max 2MB - Foto cuadrada para el carnet</div>

                                        <div class="text-center mt-3">
                                            <div class="small-note mb-2">Vista previa final</div>
                                            <img id="preview-foto" class="preview-img" alt="Preview foto"
                                                style="max-width: 150px; display: none;">
                                            <div id="no-preview" class="text-muted small">
                                                <i class="bx bx-image me-1"></i>No hay imagen seleccionada
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Columna 2: Huella Dactilar -->
                                <div class="col-md-6">
                                    <div class="form-section">
                                        <div class="section-title"><i class="bx bx-fingerprint me-1"></i> Huella Dactilar
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label required-label mb-2">Foto de la Huella</label>
                                            <input type="file" class="form-control form-control-sm" id="huella"
                                                name="huella" accept="image/*" required>
                                            <div class="small-note">JPG/PNG, max 2MB - Asegúrese que la huella sea visible
                                                y nítida</div>
                                        </div>


                                        <div class="text-center mt-3">
                                            <div class="small-note mb-2">Vista previa huella (formato 2x3)</div>
                                            <img id="preview-huella" class="preview-img ratio-2x3" alt="Preview huella"
                                                style="max-width: 150px; max-height: 225px; display: none;">
                                            <div id="no-preview-huella" class="text-muted small">
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
                                    <div class="archivo-item mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label class="form-label mb-0">Tipo de Documento</label>
                                                <select name="archivos[0][tipo]"
                                                    class="form-select form-select-sm tipo-documento">
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
                                                <input type="text" name="archivos[0][descripcion]"
                                                    class="form-control form-control-sm"
                                                    placeholder="Ej: DNI frente y dorso">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label mb-0">Archivo</label>
                                                <input type="file" name="archivos[0][archivo]"
                                                    class="form-control form-control-sm archivo-input"
                                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="preview-archivo" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-archivo">
                                    <i class="bx bx-plus me-1"></i> Agregar Otro Documento
                                </button>

                                <div class="small-note mt-2">
                                    Formatos aceptados: PDF, JPG, PNG, DOC (Máx. 5MB por archivo)
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
                            <ul class="mt-1 mb-0 ps-3">
                                <li>Formato JPG o PNG</li>
                                <li>Tamaño máximo: 2MB</li>
                                <li>Fondo claro preferible</li>
                                <li>Rostro visible y centrado</li>
                            </ul>
                        </div>

                        <div class="instruction-item">
                            <i class="bx bx-check"></i>
                            <strong>Huella Dactilar:</strong>
                            <ul class="mt-1 mb-0 ps-3">
                                <li>Foto nítida del dedo índice derecho</li>
                                <li>Buena iluminación</li>
                                <li>Formato JPG o PNG</li>
                                <li>Máximo 2MB</li>
                            </ul>
                        </div>

                        <div class="alert alert-info py-2 mb-3">
                            <small>
                                <i class="bx bx-info-circle me-1"></i>
                                <strong>Importante:</strong> Suba una foto clara de la huella del dedo
                                índice derecho en formato 2x3
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

    <!-- Modal para la cámara (se mantiene igual) -->
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


    <!-- Modal para recorte de foto (similar al de huella) -->
    <div class="modal fade photo-modal" id="photoCropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bx bx-crop me-2"></i>Recortar Foto - Formato Cuadrado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="alert alert-info py-2">
                        <small><i class="bx bx-info-circle me-1"></i>Recorta la foto en formato cuadrado para el
                            carnet</small>
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
@endsection


@push('scripts')
    <script src="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}">
    </script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/i18n/es.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/jQuery-Mask-Plugin/dist/jquery.mask.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


    <script>
        // Variables globales para cámara y cropper
        let stream = null;
        let photoData = null;
        let cropper = null;
        let currentImageSrc = null;

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
            $('#ciudad_id').select2(select2Config);
            $('#profesion_tecnica_id').select2(select2Config);
            $('#especialidad_id').select2(select2Config);
            $('#sector_economico_id').select2(select2Config);
            $('#organizacion_social_id').select2(select2Config);
            $('#expedicion').select2(select2Config);
            $('#genero').select2(select2Config);

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

            // ✅✅✅ CORRECCIÓN: Event listener para subida de archivos - DEBE usar el nuevo input
            $('#foto_input').on('change', function() {
                if (this.files && this.files[0]) {
                    previewImageWithCropper(this);
                }
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
                const url_ajax = '{{ url('/') }}/buscar-persona/' + ci;
                $.ajax({
                    url: url_ajax,
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
                $('#ciudad_id').val(persona.ciudad_id || '').trigger('change');
                $('#profesion_tecnica_id').val(persona.profesion_tecnica_id || '').trigger('change');
                $('#especialidad_id').val(persona.especialidad_id || '').trigger('change');
                $('#sector_economico_id').val(persona.sector_economico_id || '').trigger('change');
            }

            function limpiarDatosPersona() {
                $('#expedicion').val('').trigger('change');
                $('#nombres').val('');
                $('#apellido_paterno').val('');
                $('#apellido_materno').val('');
                $('#fecha_nacimiento').val('');
                $('#genero').val('').trigger('change');
                $('#celular').val('');
                $('#ciudad_id').val('').trigger('change');
                $('#profesion_tecnica_id').val('').trigger('change');
                $('#especialidad_id').val('').trigger('change');
                $('#sector_economico_id').val('').trigger('change');
            }

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

        // ========== FUNCIONES DE CÁMARA ==========

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
                // ✅✅✅ CORRECCIÓN: Ahora usa el cropper para fotos de cámara también
                initCropper(photoData);
                $('#cameraModal').modal('hide');
                if (stream) stream.getTracks().forEach(t => t.stop());
            }
        }

        // ========== FUNCIONES DE CROPPER ==========

        function previewImageWithCropper(input) {
            const file = input.files[0];
            if (file) {
                // Validar tamaño del archivo (2MB máximo)
                if (file.size > 2 * 1024 * 1024) {
                    alert('La imagen es demasiado grande. Máximo 2MB.');
                    input.value = '';
                    return;
                }

                // Validar tipo de archivo
                if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                    alert('Solo se permiten imágenes JPG o PNG.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    // En lugar de mostrar directamente, abrir el cropper
                    initCropper(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }

        // Función original de preview (para mantener compatibilidad)
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

        // Inicializar el cropper cuando se selecciona una imagen
        function initCropper(imageSrc) {
            currentImageSrc = imageSrc;

            // Mostrar el editor y ocultar otras secciones
            $('#upload-section').hide();
            $('#camera-section').hide();

            // Configurar la imagen para el cropper
            const image = document.getElementById('image-to-crop');
            const preview = document.getElementById('crop-preview');

            image.src = imageSrc;
            preview.src = imageSrc;

            // Destruir cropper anterior si existe
            if (cropper) {
                cropper.destroy();
            }

            // Inicializar cropper con configuración cuadrada
            cropper = new Cropper(image, {
                aspectRatio: 1, // Forzar relación de aspecto 1:1 (cuadrado)
                viewMode: 1,
                preview: '#crop-preview',
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                ready: function() {
                    // Ajustar automáticamente al área máxima posible
                    const containerData = cropper.getContainerData();
                    const cropBoxData = {
                        width: Math.min(containerData.width, containerData.height),
                        height: Math.min(containerData.width, containerData.height)
                    };
                    cropper.setCropBoxData(cropBoxData);
                }
            });
        }

        // Aplicar el recorte
        // Aplicar el recorte
        function applyCrop() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedImage = canvas.toDataURL('image/jpeg', 0.9);

            // Mostrar vista previa
            $('#preview-foto').attr('src', croppedImage).show();
            $('#no-preview').hide();

            // Determinar modo activo
            const activeType = $('.camera-option.active').data('type');

            if (activeType === 'camera') {
                // Guardar en hidden temporal y luego convertir a file
                $('#foto_data').val(croppedImage);
                setFileFromBase64(croppedImage, 'foto_input', 'foto_carnet.jpg');
            } else {
                setFileFromBase64(croppedImage, 'foto_input', 'foto_carnet.jpg');
            }

            // Ocultar editor
            $('#image-editor').hide();
            $(activeType === 'camera' ? '#camera-section' : '#upload-section').show();

            cropper.destroy();
            cropper = null;

            showAlertMessage('Foto recortada y lista para enviar', 'success');
        }

        // ✅✅✅ AGREGAR esta función para mostrar mensajes
        function showAlertMessage(mensaje, tipo) {
            // Remover mensajes anteriores
            $('.alert-cropper-message').remove();

            const bgClass = tipo === 'success' ? 'alert-success' :
                tipo === 'error' ? 'alert-danger' : 'alert-info';
            const icon = tipo === 'success' ? 'check-circle' :
                tipo === 'error' ? 'exclamation-circle' : 'info-circle';

            const $alert = $(`<div class="alert ${bgClass} alert-dismissible fade show mt-2 alert-cropper-message">
        <i class="fa fa-${icon} me-2"></i>${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`);

            $('.panel-body').prepend($alert);
            setTimeout(() => {
                $alert.alert('close');
            }, 4000);
        }

        // Cancelar el recorte
        function cancelCrop() {
            $('#image-editor').hide();

            // Mostrar la sección correspondiente según la opción activa
            const activeType = $('.camera-option.active').data('type');
            if (activeType === 'upload') {
                $('#upload-section').show();
            } else {
                $('#camera-section').show();
            }

            // Limpiar cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            // Limpiar vista previa si no hay imagen final
            const hasPhoto = (($('#foto_input')[0] && $('#foto_input')[0].files && $('#foto_input')[0].files.length > 0) ?
                true : false) || ($('#foto_data').val() && $('#foto_data').val() !== '');
            if (!hasPhoto) {
                $('#preview-foto').hide();
                $('#no-preview').show();
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

    <script>
        // En el $(document).ready(), agrega este event listener:
        $('#huella').on('change', function() {
            previewHuella(this);
        });

        // Función para vista previa de la huella con cropper 2x3
        function previewHuella(input) {
            const file = input.files[0];
            const noPreview = $('#no-preview-huella');

            if (file) {
                // Validaciones
                if (file.size > 2 * 1024 * 1024) {
                    alert('La imagen de la huella es demasiado grande. Máximo 2MB.');
                    input.value = '';
                    return;
                }

                if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                    alert('Solo se permiten imágenes JPG o PNG para la huella.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    // ✅✅✅ CORRECCIÓN: Usar cropper para la huella con formato 2x3
                    initHuellaCropper(e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                $('#preview-huella').hide().attr('src', '');
                noPreview.show();
            }
        }

        // ✅✅✅ NUEVA FUNCIÓN: Cropper específico para huella 2x3
        function initHuellaCropper(imageSrc) {
            // Crear modal temporal para el cropper de huella
            const huellaModal = `
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
                            <img id="huella-to-crop" src="${imageSrc}" alt="Huella a recortar" style="max-width: 100%;">
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
                                <img id="huella-crop-preview" src="${imageSrc}" alt="Vista previa huella">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

            // Remover modal anterior si existe
            $('#huellaCropperModal').remove();
            $('body').append(huellaModal);

            // Mostrar modal
            $('#huellaCropperModal').modal('show');

            // Inicializar cropper para huella
            const image = document.getElementById('huella-to-crop');
            let huellaCropper = new Cropper(image, {
                aspectRatio: 2 / 3, // Formato 2x3
                viewMode: 1,
                preview: '#huella-crop-preview',
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false
            });

            // Guardar referencia del cropper
            window.huellaCropper = huellaCropper;
        }

        function applyHuellaCrop() {
            if (!window.huellaCropper) return;

            const canvas = window.huellaCropper.getCroppedCanvas({
                width: 400,
                height: 600, // 2x3 = 400x600 o 200x300, tú eliges
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const croppedHuella = canvas.toDataURL('image/jpeg', 0.9);

            // Vista previa
            $('#preview-huella').attr('src', croppedHuella).show();
            $('#no-preview-huella').hide();

            // Crucial: Asignar al input file original
            setFileFromBase64(croppedHuella, 'huella', 'huella_dactilar.jpg');

            // Cerrar modal y limpiar
            $('#huellaCropperModal').modal('hide');
            window.huellaCropper.destroy();
            window.huellaCropper = null;

            showAlertMessage('Huella recortada en formato 2x3 correctamente', 'success');
        }

        // ✅✅✅ FUNCIÓN: Cancelar recorte de huella
        function cancelHuellaCrop() {
            $('#huellaCropperModal').modal('hide');
            if (window.huellaCropper) {
                window.huellaCropper.destroy();
                window.huellaCropper = null;
            }
            // Limpiar input file
            $('#huella').val('');
        }
    </script>

    <script>
        function dataURLtoFile(dataurl, filename) {
            if (!dataurl) return null;
            const arr = dataurl.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {
                type: mime
            });
        }

        // Actualizar el input file con la imagen recortada
        function setFileFromBase64(base64, inputId, filename) {
            if (!base64) return;
            const file = dataURLtoFile(base64, filename);
            if (file) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById(inputId).files = dataTransfer.files;
            }
        }
    </script>

    <script>
        // En initCropper() y initHuellaCropper()
        $('#btn-guardar').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');

        // En applyCrop() y applyHuellaCrop()
        $('#btn-guardar').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Guardar Afiliado');

        $('#foto_input, #huella').on('change', function() {
            $(this).after('<small class="text-primary">Procesando imagen...</small>');
        });
    </script>


    <script>
        // Gestión de archivos adicionales
        let archivoCounter = 1;

        document.getElementById('btn-agregar-archivo').addEventListener('click', function() {
            const container = document.getElementById('archivos-container');
            const nuevoArchivo = document.createElement('div');
            nuevoArchivo.className = 'archivo-item mb-3';
            nuevoArchivo.innerHTML = `
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label mb-0">Tipo de Documento</label>
                <select name="archivos[${archivoCounter}][tipo]" class="form-select form-select-sm tipo-documento">
                    <option value="">Seleccione tipo</option>
                    <option value="DNI">Documento de Identidad</option>
                    <option value="CERTIFICADO">Certificado</option>
                    <option value="CONTRATO">Contrato</option>
                    <option value="FOTO_EXTRA">Foto Adicional</option>
                    <option value="OTRO">Otro</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label mb-0">Descripción (opcional)</label>
                <input type="text" name="archivos[${archivoCounter}][descripcion]" 
                       class="form-control form-control-sm" 
                       placeholder="Ej: DNI frente y dorso">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-0">Archivo</label>
                <input type="file" name="archivos[${archivoCounter}][archivo]" 
                       class="form-control form-control-sm archivo-input" 
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-archivo mt-4">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="preview-archivo" style="display: none;"></div>
            </div>
        </div>
    `;

            container.appendChild(nuevoArchivo);
            archivoCounter++;

            // Agregar evento para eliminar
            nuevoArchivo.querySelector('.btn-remove-archivo').addEventListener('click', function() {
                nuevoArchivo.remove();
            });

            // Agregar preview para el nuevo input
            nuevoArchivo.querySelector('.archivo-input').addEventListener('change', function(e) {
                previewArchivo(e.target);
            });
        });

        // Preview de archivos
        function previewArchivo(input) {
            const file = input.files[0];
            const previewDiv = input.closest('.archivo-item').querySelector('.preview-archivo');

            if (!file) {
                previewDiv.style.display = 'none';
                previewDiv.innerHTML = '';
                return;
            }

            // Validar tamaño (5MB máximo)
            if (file.size > 5 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 5MB.');
                input.value = '';
                previewDiv.style.display = 'none';
                previewDiv.innerHTML = '';
                return;
            }

            // Si es imagen, mostrar preview
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                // Para PDF o documentos, mostrar icono
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

        // Delegación de eventos para inputs dinámicos
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('archivo-input')) {
                previewArchivo(e.target);
            }
        });

        // Validación antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const archivosInputs = document.querySelectorAll('.archivo-input');
            let archivosValidos = true;

            archivosInputs.forEach((input, index) => {
                const tipoSelect = input.closest('.archivo-item').querySelector('.tipo-documento');

                // Si hay archivo seleccionado, debe tener tipo
                if (input.files.length > 0 && !tipoSelect.value) {
                    archivosValidos = false;
                    alert(`El documento ${index + 1} necesita un tipo de documento seleccionado.`);
                    tipoSelect.focus();
                }

                // Si hay tipo seleccionado, debe tener archivo
                if (tipoSelect.value && input.files.length === 0) {
                    archivosValidos = false;
                    alert(
                        `El tipo de documento ${tipoSelect.options[tipoSelect.selectedIndex].text} necesita un archivo.`
                    );
                    input.focus();
                }
            });

            if (!archivosValidos) {
                e.preventDefault();
            }
        });
    </script>

    <script>
        // Variables globales
        let photoCropper = null;
        let photoImageSrc = null;
        let photoSource = 'upload'; // 'upload' o 'camera'

        // Evento para el botón de seleccionar foto
        $('#btn-seleccionar-foto').on('click', function() {
            $('#foto_input').click();
        });

        // Evento cuando se selecciona una foto
        $('#foto_input').on('change', function() {
            if (this.files && this.files[0]) {
                // Validaciones
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

        // Función para iniciar el cropper de foto
        function initPhotoCropper(imageSrc) {
            photoImageSrc = imageSrc;

            // Crear o actualizar modal
            const photoModal = `
    <div class="modal fade" id="photoCropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                        <img id="photo-to-crop" src="${imageSrc}" alt="Foto a recortar" style="max-width: 100%;">
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
                            <img id="photo-crop-preview" src="${imageSrc}" alt="Vista previa foto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;

            // Remover modal anterior si existe
            $('#photoCropperModal').remove();
            $('body').append(photoModal);

            // Mostrar modal
            $('#photoCropperModal').modal('show');

            // Inicializar cropper cuando el modal esté visible
            $('#photoCropperModal').on('shown.bs.modal', function() {
                const image = document.getElementById('photo-to-crop');
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
                        // Ajustar automáticamente al área máxima posible
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

        // Aplicar recorte de foto
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
            } else {
                setFileFromBase64(croppedPhoto, 'foto_input', 'foto_carnet.jpg');
            }

            // Cerrar modal y limpiar
            $('#photoCropperModal').modal('hide');
            photoCropper.destroy();
            photoCropper = null;

            showAlertMessage('Foto recortada correctamente', 'success');
        }

        // Cancelar recorte de foto
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
            } else {
                $('#foto_data').val('');
            }
        }

        // Modificar la función usePhoto() para usar el modal
        function usePhoto() {
            if (photoData) {
                photoSource = 'camera';
                initPhotoCropper(photoData);
                $('#cameraModal').modal('hide');
                if (stream) stream.getTracks().forEach(t => t.stop());
            }
        }

        // Función para formatear bytes
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Limpiar cuando se cierre el modal
        $('#photoCropperModal').on('hidden.bs.modal', function() {
            if (photoCropper) {
                photoCropper.destroy();
                photoCropper = null;
            }
        });
    </script>
@endpush
