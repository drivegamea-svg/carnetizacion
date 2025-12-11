@extends('layouts.app')

@section('title', 'Crear Ciudad')

@push('styles')
    <link href="{{ asset('color-admin/plugins/parsleyjs/src/parsley.css') }}" rel="stylesheet" />
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .form-group.focus .input-group-text {
            background-color: var(--bs-primary) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .parsley-errors-list {
            padding: 0;
            margin: 0.25rem 0 0 0;
            list-style-type: none;
        }

        .parsley-error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .parsley-success {
            border-color: #198754 !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
        }

        .list-unstyled li {
            margin-bottom: 0.5rem;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        .btn-outline-warning {
            border-color: #f59e0b;
            color: #f59e0b;
        }

        .btn-outline-warning:hover {
            background-color: #f59e0b;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ciudades.index') }}">Ciudades</a></li>
            <li class="breadcrumb-item active">Crear Ciudad</li>
        </ol>

        <h1 class="page-header">Crear Ciudad <small>Registrar nueva ciudad en el sistema</small></h1>

        <div class="row">
            <!-- Columna Izquierda - Formulario -->
            <div class="col-xl-8">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-plus-circle me-2"></i>Formulario de Registro
                        </h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                                <i class="fa fa-expand"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload">
                                <i class="fa fa-redo"></i>
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('ciudades.store') }}" method="POST" id="form-ciudad">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="nombre" class="form-label">
                                            <i class="fa fa-tag me-1 text-primary"></i>
                                            Nombre de la Ciudad <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fa fa-city"></i>
                                            </span>
                                            <input type="text" class="form-control text-uppercase @error('nombre') is-invalid @enderror" 
                                                   id="nombre" name="nombre" value="{{ old('nombre') }}"
                                                   placeholder="EJ: LA PAZ, SANTA CRUZ, COCHABAMBA..." required
                                                   data-parsley-required-message="El nombre de la ciudad es obligatorio"
                                                   data-parsley-minlength="2"
                                                   data-parsley-minlength-message="El nombre debe tener al menos 2 caracteres"
                                                   data-parsley-maxlength="255"
                                                   data-parsley-maxlength-message="El nombre no puede exceder 255 caracteres"
                                                   data-parsley-pattern="^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\(\)_]+$"
                                                   data-parsley-pattern-message="Solo se permiten letras, números, espacios, paréntesis y guiones"
                                                   autofocus>
                                        </div>

                                        @error('nombre')
                                            <div class="invalid-feedback d-block">
                                                <i class="fa fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror

                                        <div class="parsley-errors-list" id="parsley-errors-list-nombre"></div>

                                        <div class="form-text text-muted">
                                            <small>Ingrese un nombre único para la ciudad</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="departamento" class="form-label">
                                            <i class="fa fa-map-marker-alt me-1 text-success"></i>
                                            Departamento <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fa fa-globe-americas"></i>
                                            </span>
                                            <input type="text" class="form-control text-uppercase @error('departamento') is-invalid @enderror" 
                                                   id="departamento" name="departamento" value="{{ old('departamento') }}"
                                                   placeholder="EJ: LA PAZ, SANTA CRUZ, COCHABAMBA..." required
                                                   data-parsley-required-message="El departamento es obligatorio"
                                                   data-parsley-minlength="2"
                                                   data-parsley-minlength-message="El departamento debe tener al menos 2 caracteres"
                                                   data-parsley-maxlength="255"
                                                   data-parsley-maxlength-message="El departamento no puede exceder 255 caracteres"
                                                   data-parsley-pattern="^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\(\)_]+$"
                                                   data-parsley-pattern-message="Solo se permiten letras, números, espacios, paréntesis y guiones">
                                        </div>
                                        
                                        @error('departamento')
                                            <div class="invalid-feedback d-block">
                                                <i class="fa fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        
                                        <div class="form-text text-muted">
                                            <small>Ingrese el departamento al que pertenece la ciudad</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('ciudades.index') }}" class="btn btn-default">
                                            <i class="fa fa-arrow-left me-2"></i> Volver al Listado
                                        </a>

                                        <div class="d-flex gap-2">
                                            <button type="reset" class="btn btn-outline-warning" id="btn-reset">
                                                <i class="fa fa-broom me-2"></i> Limpiar
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                                <i class="fa fa-save me-2"></i> Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha - Instrucciones -->
            <div class="col-xl-4">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-info-circle me-2"></i>Instrucciones
                        </h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-collapse">
                                <i class="fa fa-minus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="alert alert-info mb-3">
                            <i class="fa fa-lightbulb me-2"></i>
                            <strong>Consejo:</strong> Complete todos los campos requeridos (*).
                        </div>

                        <h5 class="text-primary mb-3">
                            <i class="fa fa-question-circle me-2"></i>¿Cómo llenar el formulario?
                        </h5>

                        <div class="mb-4">
                            <h6 class="text-success">
                                <i class="fa fa-tag me-2"></i>Campo "Nombre"
                            </h6>
                            <ul class="list-unstyled ps-3">
                                <li><i class="fa fa-check text-success me-2"></i>Debe ser único en el sistema</li>
                                <li><i class="fa fa-check text-success me-2"></i>Se convierte a MAYÚSCULAS automáticamente</li>
                                <li><i class="fa fa-check text-success me-2"></i>Mínimo 2 caracteres</li>
                                <li><i class="fa fa-check text-success me-2"></i>Máximo 255 caracteres</li>
                                <li><i class="fa fa-check text-success me-2"></i>Permite letras, números y espacios</li>
                                <li><i class="fa fa-check text-success me-2"></i>Permite guiones (-) y paréntesis ( )</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info">
                                <i class="fa fa-map-marker-alt me-2"></i>Campo "Departamento"
                            </h6>
                            <ul class="list-unstyled ps-3">
                                <li><i class="fa fa-check text-info me-2"></i>Campo obligatorio</li>
                                <li><i class="fa fa-check text-info me-2"></i>Se convierte a MAYÚSCULAS automáticamente</li>
                                <li><i class="fa fa-check text-info me-2"></i>Mínimo 2 caracteres</li>
                                <li><i class="fa fa-check text-info me-2"></i>Máximo 255 caracteres</li>
                                <li><i class="fa fa-check text-info me-2"></i>Permite letras, números y espacios</li>
                                <li><i class="fa fa-check text-info me-2"></i>Indica el departamento de la ciudad</li>
                            </ul>
                        </div>

                        <div class="alert alert-success mt-4">
                            <i class="fa fa-shield-alt me-2"></i>
                            <strong>Validación:</strong> El formulario valida automáticamente todos los campos.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/i18n/es.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Parsley con configuración en español
            $('#form-ciudad').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<div class="invalid-feedback d-block"></div>',
                errorTemplate: '<span><i class="fa fa-exclamation-circle me-1"></i></span>',
                excluded: 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
            });

            // Convertir a mayúsculas automáticamente y validar
            $('#nombre').on('input', function() {
                this.value = this.value.toUpperCase();
                $(this).parsley().validate();
            });

            // Convertir departamento a mayúsculas
            $('#departamento').on('input', function() {
                this.value = this.value.toUpperCase();
                $(this).parsley().validate();
            });

            // Loading state en submit
            $('#form-ciudad').on('submit', function(e) {
                const btn = $('#btn-submit');
                
                if ($(this).parsley().isValid()) {
                    btn.prop('disabled', true);
                    btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Guardando...');
                } else {
                    e.preventDefault();
                    $(this).parsley().validate();
                }
            });

            // Efectos visuales para inputs
            $('.form-control').on('focus', function() {
                $(this).closest('.form-group').addClass('focus');
            }).on('blur', function() {
                $(this).closest('.form-group').removeClass('focus');
            });

            // Reset del formulario
            $('#btn-reset').on('click', function() {
                $('#form-ciudad').parsley().reset();
                $('.form-control').removeClass('is-valid is-invalid');
                $('.invalid-feedback').hide();
                $('.parsley-errors-list').empty();
            });

            // Si hay errores del servidor, mostrarlos
            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error('{{ $error }}', 'Error', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                @endforeach
                
                $(document).ready(function() {
                    $('#form-ciudad').parsley().validate();
                });
            @endif
        });
    </script>
@endpush