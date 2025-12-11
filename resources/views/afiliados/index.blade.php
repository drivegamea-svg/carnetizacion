@extends('layouts.app')

@section('title', 'Afiliados CIZEE')

@push('styles')
    <!-- ================== BEGIN page-css ================== -->
    <link href="{{ asset('color-admin/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <style>
        .search-box {
            position: relative;
        }

        .search-box .input-group {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .search-box .input-group-text {
            border-right: none;
            background: #667eea;
            color: white !important;
        }

        .search-box .form-control {
            border-left: none;
            padding-left: 0;
            font-size: 1rem;
            height: 45px;
        }

        .search-box .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        #btn-clear-search {
            transition: all 0.3s ease;
        }

        #btn-clear-search:hover {
            background-color: #f8f9fa;
            color: #dc3545 !important;
        }

        .badge {
            font-size: 0.75em;
        }

        .action-group {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .reportes-group {
            display: flex;
            gap: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .reportes-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Afiliados CIZEE</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Afiliados CIZEE <small>Listado de afiliados registrados</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-users me-2"></i>Gestión de Afiliados</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload">
                        <i class="fa fa-redo"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
                        <i class="fa fa-minus"></i>
                    </a>
                    @can('crear afiliados')
                        <a href="{{ route('afiliados.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Nuevo Afiliado
                        </a>
                    @endcan
                </div>
            </div>

            <div class="panel-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <!-- BUSCADOR CENTRAL MEJORADO -->
                <div class="row mb-4">
                    <div class="col-md-8 mx-auto">
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="global-search" class="form-control border-start-0 ps-0"
                                    placeholder="Buscar afiliados por nombre, CI, profesión, celular...">
                                <button class="btn btn-outline-secondary" type="button" id="btn-clear-search"
                                    title="Limpiar búsqueda">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                Puedes buscar por cualquier campo: nombre completo, cédula de identidad, profesión, etc.
                            </small>
                        </div>
                    </div>
                </div>

                <table id="afiliados-table" class="table table-bordered table-striped align-middle" width="100%">
                    <thead class="text-center">
                        <tr>
                            <th>Nombre Completo</th>
                            <th>CI</th>
                            <th>Profesión</th>
                            <th>Sector Económico</th>
                            <th>Ciudad</th>
                            <th>Celular</th>
                            <th>Estado</th>
                            <th>Fecha Afiliación</th>
                            <th width="15%">Reportes PDF</th>
                            <th width="15%">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- ================== BEGIN page-js ================== -->
    <script src="{{ asset('color-admin/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <!-- ================== END page-js ================== -->

    <script src="{{ asset('color-admin/js-global/idiomaDatatables.js') }}"></script>

    <script>
        $(function() {
            var table = $('#afiliados-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('afiliados.index') }}",
                responsive: true,
                columns: [
                    {
                        data: 'nombre_completo',
                        name: 'nombre_completo',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return data || `${row.apellido_paterno || ''} ${row.apellido_materno || ''} ${row.nombres || ''}`.trim();
                        }
                    },
                    {
                        data: 'ci_completo',
                        name: 'ci_completo',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return data || `${row.ci || ''} ${row.expedicion || ''}`.trim();
                        }
                    },
                    {
                        data: 'profesion_tecnica_id',
                        name: 'profesion_tecnica_id',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return row.profesion_tecnica?.nombre || 'N/A';
                        }
                    },
                    {
                        data: 'sector_economico_id',
                        name: 'sector_economico_id',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return row.sector_economico?.nombre || 'N/A';
                        }
                    },
                    {
                        data: 'ciudad_id',
                        name: 'ciudad_id',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return row.ciudad?.nombre || 'N/A';
                        }
                    },
                    {
                        data: 'celular',
                        name: 'celular',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            let badgeClass = 'bg-secondary';
                            let estadoText = data;
                            
                            if (row.deleted_at) {
                                badgeClass = 'bg-secondary';
                                estadoText = 'ELIMINADO';
                            } else {
                                switch(data) {
                                    case 'ACTIVO':
                                        badgeClass = 'bg-success';
                                        break;
                                    case 'INACTIVO':
                                        badgeClass = 'bg-warning';
                                        break;
                                    case 'PENDIENTE':
                                        badgeClass = 'bg-info';
                                        break;
                                }
                            }
                            
                            return `${estadoText}`;
                        }
                    },
                    {
                        data: 'fecha_afiliacion',
                        name: 'fecha_afiliacion',
                        className: 'align-middle text-center',
                       
                    },
                    {
                        data: 'reportes_pdf',
                        name: 'reportes_pdf',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    }
                ],
                dom: '<"row mb-3"<"col-sm-12 col-md-6"l>>t<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                order: [[7, 'DESC']], // Ordenar por fecha de afiliación descendente

                stateSave: true,
                stateDuration: -1,
                language: {
                    "search": "Buscar:",
                    "searchPlaceholder": "Buscar en todos los campos...",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                initComplete: function() {
                    $('.dataTables_filter').hide();
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('#global-search').focus();
                }
            });

            // Buscador personalizado
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
                updateSearchStatus(this.value);
            });

            $('#btn-clear-search').on('click', function() {
                $('#global-search').val('').focus();
                table.search('').draw();
                updateSearchStatus('');
            });

            function updateSearchStatus(searchTerm) {
                if (searchTerm.length > 0) {
                    $('#btn-clear-search').addClass('text-danger');
                } else {
                    $('#btn-clear-search').removeClass('text-danger');
                }
            }
        });

        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El afiliado será archivado!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function confirmRestore(id) {
            Swal.fire({
                title: '¿Restaurar este afiliado?',
                text: "El afiliado volverá a estar activo.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
                }
            });
        }

        function confirmActivate(id) {
            Swal.fire({
                title: '¿Activar este afiliado?',
                text: "Se generará un número de carnet y el afiliado estará activo.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('activate-form-' + id).submit();
                }
            });
        }

        function confirmDeactivate(id) {
            Swal.fire({
                title: '¿Desactivar este afiliado?',
                text: "El afiliado cambiará a estado INACTIVO.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deactivate-form-' + id).submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                $.gritter.add({
                    title: 'Éxito',
                    text: '{{ session('success') }}',
                    class_name: 'gritter-success',
                    sticky: true,
                    time: '3000'
                });
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                $.gritter.add({
                    title: 'Información',
                    text: '{{ session('info') }}',
                    class_name: 'gritter-info',
                    sticky: true,
                    time: '3000'
                });
            });
        </script>
    @endif
@endpush