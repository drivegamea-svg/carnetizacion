@extends('layouts.app')

@section('title', 'Reporte de Afiliados - CIZEE')

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Reporte de Afiliados</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Reporte de Afiliados <small>Filtros y exportación</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-filter me-2"></i>Filtros del Reporte</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <form action="{{ route('reportes.afiliados.generar') }}" method="POST" id="form-reporte">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Estado:</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="ACTIVO">Activo</option>
                                <option value="INACTIVO">Inactivo</option>
                                <option value="PENDIENTE">Pendiente</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Sector Económico:</label>
                            <select name="sector_economico" class="form-select">
                                <option value="">Todos los sectores</option>
                                @foreach ($sectores as $sector)
                                    <option value="{{ $sector }}">{{ $sector }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Género:</label>
                            <select name="genero" class="form-select">
                                <option value="">Todos</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Ciudad:</label>
                            <input type="text" name="ciudad" class="form-control"
                                placeholder="Ej: La Paz, Santa Cruz...">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Fecha Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Fecha Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control">
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="solo_activos" name="solo_activos"
                                    value="1">
                                <label class="form-check-label" for="solo_activos">Mostrar solo afiliados activos</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2 flex-wrap">
                                {{-- En los botones de exportación --}}
                                @can('exportar excel afiliados')
                                    <button type="submit" name="exportar" value="excel" class="btn btn-success">
                                        <i class="far fa-file-excel me-1"></i> Exportar Excel
                                    </button>
                                @endcan

                                @can('exportar pdf afiliados')
                                    <button type="submit" name="exportar" value="pdf" class="btn btn-danger">
                                        <i class="far fa-file-pdf me-1"></i> Exportar PDF
                                    </button>
                                @endcan
                                <button type="button" id="btn-preview" class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i> Vista Previa
                                </button>
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-undo me-1"></i> Limpiar Filtros
                                </button>
                                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel para vista previa -->
        <div class="panel panel-inverse d-none" id="panel-preview">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-eye me-2"></i>Vista Previa del Reporte</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-collapse">
                        <i class="fa fa-minus"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body" id="preview-content">
                <!-- Aquí se cargará la vista previa -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Vista previa
            $('#btn-preview').click(function() {
                const form = $('#form-reporte');
                const url = form.attr('action');
                const data = form.serialize();

                // Mostrar loading
                $('#preview-content').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 mb-0">Generando vista previa...</p>
                </div>
            `);

                $('#panel-preview').removeClass('d-none');

                // Enviar solicitud AJAX
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        $('#preview-content').html(response);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al generar la vista previa';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#preview-content').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${errorMessage}
                        </div>
                    `);
                    }
                });
            });

            // Limpiar formulario
            $('button[type="reset"]').click(function() {
                $('#panel-preview').addClass('d-none');
                $('#preview-content').empty();
            });

            // Cerrar panel de vista previa
            $('[data-toggle="panel-collapse"]').click(function() {
                $('#panel-preview').toggleClass('d-none');
            });
        });
    </script>
@endpush
