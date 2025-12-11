@extends('layouts.app')

@section('title', 'Ver Rol')

@push('styles')
    <style>
        .card-permission-group {
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .badge-permission {
            background-color: #f0f3f5;
            color: #343a40;
            font-size: 0.85rem;
            margin: 2px;
            padding: 5px 10px;
            border-radius: 0.375rem;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">

        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Detalles del Rol</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Roles <small>Visualización detallada del rol</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <h4 class="panel-title">
                    <i class="fas fa-user-shield me-2"></i>Detalles del Rol: <strong>{{ $role->name }}</strong>
                </h4>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Volver a la lista
                </a>
            </div>

            <div class="panel-body">
                <div class="mb-4">
                    <label class="form-label fw-bold text-primary">
                        <i class="fa fa-id-badge me-1"></i> Nombre del Rol
                    </label>
                    <div class="form-control bg-light">{{ $role->name }}</div>
                </div>

                <h5 class="mb-3 text-primary"><i class="fa fa-key me-1"></i> Permisos Asignados</h5>

                <div class="row">
                    @foreach ($permissions as $group => $perms)
                        @php
                            $groupPerms = $perms->whereIn('name', $rolePermissions);
                        @endphp

                        @if ($groupPerms->isNotEmpty())
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card card-permission-group">
                                    <div class="card-header bg-light">
                                        <strong><i class="fa fa-layer-group me-1"></i> {{ ucfirst($group) }}</strong>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($groupPerms as $perm)
                                            <div class="badge-permission">
                                                <i class="fa fa-check text-success me-1"></i> {{ ucfirst($perm->name) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if (empty($rolePermissions))
                    <div class="alert alert-warning mt-4">
                        <i class="fa fa-exclamation-triangle me-2"></i> Este rol no tiene permisos asignados.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
