@extends('layouts.app')

@section('title', 'Especialidades')

@push('styles')
    <link href="{{ asset('color-admin/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div id="content" class="app-content">
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Especialidades</li>
        </ol>

        <h1 class="page-header">Especialidades <small>Gestiona las especialidades del sistema</small></h1>

        <!-- Alertas de información -->
        <div class="alert alert-info alert-dismissible fade show mb-4">
            <div class="d-flex align-items-center">
                <i class="fa fa-certificate fa-2x me-3"></i>
                <div class="flex-fill">
                    <h5 class="alert-heading mb-1">¡Bienvenido a la gestión de especialidades!</h5>
                    <p class="mb-0">Aquí puedes administrar todas las especialidades disponibles para asignar a los afiliados.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-certificate me-2"></i>Listado de Especialidades
                </h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"
                        title="Expandir panel">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"
                        title="Recargar datos">
                        <i class="fa fa-redo"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"
                        title="Minimizar panel">
                        <i class="fa fa-minus"></i>
                    </a>
                    @can('crear especialidades')
                        <a href="{{ route('especialidades.create') }}" class="btn btn-primary btn-sm ms-2">
                            <i class="fa fa-plus me-1"></i> Nueva Especialidad
                        </a>
                    @endcan
                </div>
            </div>

            <div class="panel-body">
                <!-- Tabla Mejorada -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle" id="especialidades-table"
                        width="100%">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center">ID</th>
                                <th width="32%">Nombre</th>
                                <th width="45%">Descripción</th>
                                <th width="15%" class="text-center">Creado</th>
                                <th width="10%" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargan via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Estado de carga -->
                <div id="loading-state" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2">Cargando especialidades...</p>
                </div>
            </div>

            <!-- Footer informativo -->
            <div class="panel-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fa fa-database me-1"></i>
                            <span id="info-registros">Cargando información...</span>
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">
                            <i class="fa fa-clock me-1"></i>
                            Actualizado: <span id="last-update">{{ now()->format('d/m/Y H:i') }}</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('color-admin/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('color-admin/js-global/idiomaDatatables.js') }}"></script>

    <script>
        // Función para confirmar eliminación mejorada
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Eliminar Especialidad?',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                        </div>
                        <h5 class="text-danger mb-3">¿Estás seguro de eliminar esta especialidad?</h5>
                        <div class="alert alert-warning">
                            <strong><i class="fa fa-certificate me-2"></i>${nombre}</strong>
                        </div>
                        <p class="text-muted">
                            <small>Esta acción no se puede deshacer y podría afectar a los afiliados asociados.</small>
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: '<i class="fa fa-times me-1"></i> Cancelar',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    const deleteButton = document.querySelector(
                        `[onclick="confirmarEliminacion(${id}, '${nombre}')"]`);
                    const originalHtml = deleteButton.innerHTML;
                    deleteButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                    deleteButton.disabled = true;

                    // Enviar formulario
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        $(document).ready(function() {
            // Ocultar loading state inicial
            $('#loading-state').hide();

            // Mostrar mensajes automáticamente
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    showClass: {
                        popup: 'animate__animated animate__slideInRight'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 4000,
                    showConfirmButton: true,
                    showClass: {
                        popup: 'animate__animated animate__headShake'
                    }
                });
            @endif

            // Inicializar DataTable
            const table = $('#especialidades-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todos"]
                ],
                ajax: '{{ route('especialidades.index') }}',
                initComplete: function() {
                    // Ocultar loading state
                    $('#loading-state').hide();
                    $('#especialidades-table').show();
                },
                columns: [{
                        data: 'id',
                        className: 'text-center align-middle fw-bold text-primary',
                    },
                    {
                        data: 'nombre',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fa fa-certificate text-primary me-3 fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">${data}</div>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'descripcion',
                        className: 'align-middle',
                        render: function(data) {
                            if (!data) {
                                return '<span class="text-muted fst-italic"><i class="fa fa-info-circle me-1"></i>Sin descripción</span>';
                            }
                            if (data.length > 120) {
                                return `
                                    <div class="descripcion-content">
                                        <span class="descripcion-corta">${data.substring(0, 120)}...</span>
                                        <a href="javascript:;" class="text-primary ms-1 ver-mas" 
                                           data-bs-toggle="tooltip" title="Ver descripción completa">
                                            <small>ver más</small>
                                        </a>
                                        <span class="descripcion-completa d-none">${data}</span>
                                    </div>
                                `;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'created_at',
                        className: 'text-center align-middle',
                        render: function(data) {
                            if (!data) return '<span class="text-muted">N/A</span>';
                            const date = new Date(data);
                            return `
                                <div class="text-center">
                                    <div class="small text-success fw-bold">${date.toLocaleDateString('es-ES')}</div>
                                    <div class="small text-muted">${date.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle',
                        render: function(data, type, row) {
                            const canEdit =
                                {{ auth()->user()->can('editar especialidades') ? 'true' : 'false' }};
                            const canDelete =
                                {{ auth()->user()->can('eliminar especialidades') ? 'true' : 'false' }};

                            let buttons = '';

                            if (canEdit) {
                                buttons += `
                                    <a href="/especialidades/${data}/edit" 
                                       class="btn btn-sm btn-outline-warning mx-1" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar ${row.nombre}"
                                       data-bs-placement="top">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                `;
                            }

                            if (canDelete) {
                                buttons += `
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger mx-1" 
                                            data-bs-toggle="tooltip" 
                                            title="Eliminar ${row.nombre}"
                                            data-bs-placement="top"
                                            onclick="confirmarEliminacion(${data}, '${row.nombre.replace(/'/g, "\\'")}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                `;
                            }

                            if (!buttons) {
                                return '<span class="text-muted">Sin acciones</span>';
                            }

                            return `
                                <div class="btn-group btn-group-sm" role="group">
                                    ${buttons}
                                </div>
                                <form id="delete-form-${data}" 
                                      action="/especialidades/${data}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            `;
                        }
                    },
                ],
                dom: '<"row mb-3"<"col-lg-8 d-lg-block"<"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 me-0 me-md-3"l><"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 "B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row mt-3"<"col-md-auto me-auto"i><"col-md-auto ms-auto"p>>',
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="far fa-eye me-1"></i> Columnas',
                        className: 'btn btn-sm btn-dark mx-1',
                        columns: ':not(.noVis)'
                    },
                    {
                        extend: 'copy',
                        text: '<i class="far fa-copy me-1"></i> Copiar',
                        className: 'btn btn-sm btn-default mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="far fa-file-excel me-1"></i> Excel',
                        className: 'btn btn-sm btn-green mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="far fa-file-pdf me-1"></i> PDF',
                        className: 'btn btn-sm btn-danger mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        },
                        customize: function(doc) {
                            doc.pageOrientation = 'landscape';
                            doc.pageMargins = [30, 20, 20, 20];
                            doc.defaultStyle.fontSize = 9;
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 9,
                                color: 'white',
                                fillColor: '#2d353c',
                                alignment: 'center'
                            };
                            doc.content[1].table.widths = [
                                '10%', // ID
                                '30%', // Nombre
                                '45%', // Descripción
                                '15%', // Creado
                                '10%' // Acciones
                            ];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Imprimir',
                        className: 'btn btn-sm btn-info mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    }
                ],
                stateSave: true,
                stateDuration: -1,
                drawCallback: function() {
                    // Actualizar información del footer
                    const info = this.api().page.info();
                    $('#info-registros').html(`
                            Mostrando <strong>${info.recordsDisplay}</strong> especialidades
                        `);
                    $('#last-update').text(new Date().toLocaleString('es-ES'));

                    // Destruir tooltips existentes primero
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');

                    // Inicializar tooltips
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger: 'hover',
                        boundary: 'window'
                    });

                    // Manejar descripciones largas
                    $('.ver-mas').off('click').on('click', function(e) {
                        e.preventDefault();

                        // Destruir el tooltip actual
                        $(this).tooltip('dispose');

                        const $content = $(this).closest('.descripcion-content');
                        const $corta = $content.find('.descripcion-corta');
                        const $completa = $content.find('.descripcion-completa');

                        if ($corta.is(':visible')) {
                            $corta.hide();
                            $completa.removeClass('d-none');
                            $(this).html('<small>ver menos</small>')
                                .attr('title', 'Ver menos');
                        } else {
                            $corta.show();
                            $completa.addClass('d-none');
                            $(this).html('<small>ver más</small>')
                                .attr('title', 'Ver descripción completa');
                        }

                        // Recrear el tooltip con la nueva configuración
                        $(this).tooltip({
                            trigger: 'hover',
                            boundary: 'window'
                        });
                    });
                }
            });

            // Efectos hover en filas
            $('#especialidades-table').on('mouseenter', 'tbody tr', function() {
                $(this).addClass('table-active');
            }).on('mouseleave', 'tbody tr', function() {
                $(this).removeClass('table-active');
            });
        });
    </script>

    <style>
        .descripcion-content {
            line-height: 1.4;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.08) !important;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .btn-outline-warning {
            border-color: #ffc107;
            color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
            transform: scale(1.05);
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            transform: scale(1.05);
        }

        .alert-info {
            border-left: 4px solid #0dcaf0;
        }

        #loading-state {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
        }
    </style>
@endpush