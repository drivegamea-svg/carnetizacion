@extends('layouts.app')

@section('title', 'Crear Usuario')

@push('styles')
    <style>
        .required-label::after {
            content: " *";
            color: red;
        }

        fieldset {
            border: 1px solid #dcdcdc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        legend {
            width: auto;
            padding: 0 10px;
            font-weight: bold;
            font-size: 1.1rem;
            color: #2c3e50;
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">

        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Crear Usuario</li>
        </ol>

        <h1 class="page-header">Usuarios <small>Crear nuevo usuario del sistema</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-user-plus me-2"></i>Nuevo Usuario</h4>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Cancelar
                </a>
            </div>

            <div class="panel-body">
                <form method="POST" action="{{ route('admin.usuarios.store') }}" data-parsley-validate>
                    @csrf

                    {{-- Datos Personales --}}
                    <fieldset>
                        <legend><i class="fa fa-id-badge me-1 text-primary"></i>Datos Personales</legend>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label fw-bold required-label">
                                    <i class="fa fa-user me-1 text-primary"></i> Nombres
                                </label>
                                <input type="text" name="nombres" id="nombres" class="form-control shadow-sm"
                                    placeholder="Ej: Juan Carlos" required data-parsley-pattern="^[a-zA-ZÀ-ÿ\s]+$"
                                    data-parsley-pattern-message="Solo se permiten letras."
                                    data-parsley-required-message="El nombre es obligatorio.">

                                @error('nombres')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label fw-bold required-label">
                                    <i class="fa fa-user-tag me-1 text-primary"></i> Apellidos
                                </label>
                                <input type="text" name="apellidos" id="apellidos" class="form-control shadow-sm"
                                    placeholder="Ej: Pérez Gómez" required data-parsley-pattern="^[a-zA-ZÀ-ÿ\s]+$"
                                    data-parsley-pattern-message="Solo se permiten letras."
                                    data-parsley-required-message="El apellido es obligatorio.">

                                @error('apellidos')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="ci" class="form-label fw-bold required-label">
                                    <i class="fa fa-id-card me-1 text-secondary"></i> Cédula de Identidad
                                </label>
                                <input type="text" name="ci" id="ci" class="form-control shadow-sm"
                                    placeholder="Ej: 12345678" required
                                    data-parsley-required-message="La cédula es obligatoria.">
                                @error('ci')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="genero" class="form-label fw-bold">
                                    <i class="fa fa-venus-mars me-1 text-info"></i> Género
                                </label>
                                <select name="genero" id="genero" class="form-select shadow-sm">
                                    <option value="">Seleccione</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                @error('genero')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="cargo" class="form-label fw-bold">
                                    <i class="fa fa-briefcase me-1 text-warning"></i> Cargo
                                </label>
                                <input type="text" name="cargo" id="cargo" class="form-control shadow-sm"
                                    placeholder="Ej: Coordinador">
                                @error('cargo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="celular" class="form-label fw-bold">
                                    <i class="fa fa-mobile-alt me-1 text-success"></i> Celular
                                </label>
                                <input type="text" name="celular" id="celular" class="form-control shadow-sm"
                                    placeholder="Ej: 76543210" data-parsley-type="digits" data-parsley-length="[5, 15]"
                                    data-parsley-type-message="Solo se permiten números."
                                    data-parsley-length-message="El celular debe tener entre 5 y 15 dígitos."
                                    data-parsley-trigger="keyup">
                                @error('celular')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Seguridad y acceso --}}
                    <fieldset>
                        <legend><i class="fa fa-shield-alt me-1 text-danger"></i>Seguridad y Acceso</legend>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="email" class="form-label fw-bold required-label">
                                    <i class="fa fa-envelope me-1 text-danger"></i> Correo electrónico
                                </label>
                                <input type="email" name="email" id="email" class="form-control shadow-sm"
                                    placeholder="Ej: usuario@ejemplo.com" required data-parsley-type="email"
                                    data-parsley-required-message="El correo es obligatorio."
                                    data-parsley-type-message="Debe ser un correo válido.">
                                <div class="form-text">Este será su nombre de usuario para acceder al sistema.</div>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="password" class="form-label fw-bold required-label">
                                    <i class="fa fa-lock me-1 text-dark"></i> Contraseña
                                </label>
                                <input type="password" name="password" id="password" class="form-control shadow-sm"
                                    placeholder="Ingrese una contraseña segura" required
                                    data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                    data-parsley-pattern-message="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo."
                                    data-parsley-trigger="keyup"
                                    data-parsley-required-message="La contraseña es obligatoria.">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold required-label">
                                    <i class="fa fa-lock me-1 text-dark"></i> Confirmar Contraseña
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control shadow-sm" placeholder="Repita la contraseña" required
                                    data-parsley-equalto="#password"
                                    data-parsley-equalto-message="Las contraseñas no coinciden."
                                    data-parsley-required-message="Debe confirmar la contraseña."
                                    data-parsley-trigger="keyup">
                            </div>


                            <div class="col-md-3 mb-3">
                                <label for="rol" class="form-label fw-bold required-label">
                                    <i class="fa fa-user-tag me-1 text-purple"></i> Rol asignado
                                </label>
                                <select name="rol" id="rol" class="form-select shadow-sm" required
                                    data-parsley-required-message="Debe seleccionar un rol.">
                                    <option value="">Seleccione un rol</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}">{{ ucfirst($rol->name) }}</option>
                                    @endforeach
                                </select>
                                @error('rol')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa fa-save me-2"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('color-admin/plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('form').parsley({});
        });
    </script>
@endpush
