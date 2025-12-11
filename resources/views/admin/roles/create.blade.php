@extends('layouts.app')

@section('title', 'Crear Rol')
@push('styles')
    <style>
        .card-permission-group {
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">

        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Crear Rol</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Roles <small>Crear nuevo rol para el sistema</small></h1>


        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-user-shield me-2"></i>Crear nuevo rol</h4>
                   <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Cancelar
                    </a>
            </div>

            <div class="panel-body">
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">
                            <i class="fa fa-id-badge me-1 text-primary"></i> Nombre del Rol
                        </label>
                        <input type="text" name="name" id="name" class="form-control shadow-sm" required
                            placeholder="Ej: administrador, editor, supervisor">
                    </div>

                    <h5 class="mb-3 text-primary"><i class="fa fa-key me-1"></i> Permisos del Rol</h5>

                    <div class="row">
                        @foreach ($permissions as $group => $perms)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card card-permission-group">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <strong><i class="fa fa-layer-group me-1"></i> {{ ucfirst($group) }}</strong>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input select-all-group"
                                                data-group="{{ $group }}">
                                            <label class="form-check-label small">Todos</label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($perms as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-checkbox" type="checkbox"
                                                    name="permissions[]" value="{{ $permission->name }}"
                                                    id="perm_{{ $permission->id }}" data-group="{{ $group }}">
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    <i class="fa fa-check-circle text-success me-1"></i>
                                                    {{ ucfirst($permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa fa-save me-2"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection




@push('scripts')
<script>
    // Selección grupal de checkboxes
    document.querySelectorAll('.select-all-group').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`)
                .forEach(cb => cb.checked = this.checked);
        });
    });
</script>
@endpush
