@extends('layouts.app')

@section('title', 'Roles y Permisos')

@push('styles')
    <!-- ================== BEGIN page-css ================== -->
    <link href="{{ asset('color-admin/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <!-- ================== END page-css ================== -->


@endpush

@section('content')
    <div id="content" class="app-content">

        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Roles</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Roles <small>Listado de roles del sistema</small></h1>

        <div class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-user-shield me-2"></i>Gestión de Roles y Permisos</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i
                            class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                    {{-- <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a> --}}
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Rol
                    </a>
                </div>
            </div>



            <div class="panel-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <table class="table table-bordered table-striped align-middle" id="roles-table" width="100%">
                        <thead class="text-center">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Usuarios asignados</th>
                                <th>Permisos</th>
                                <th>Estado</th>
                                <th>Creado</th>
                                <th>Actualizado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>

                </div>
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
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/jszip/dist/jszip.min.js') }}"></script>
    <!-- ================== END page-js ================== -->
    <script src="{{ asset('color-admin/js-global/idiomaDatatables.js') }}"></script>


    <script>
        $(function() {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.roles.index') }}',
                responsive: true,
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'align-middle'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return data === 'web' ?
                                '<span class="badge bg-primary rounded-pill">Usuario Web</span>' :
                                '<span class="badge bg-dark rounded-pill">Administrador</span>';
                        }
                    },
                    {
                        data: 'users_count',
                        name: 'users_count',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'permissions_count',
                        name: 'permissions_count',
                        className: 'align-middle text-center',
                        render: data => `<span class="badge bg-info rounded-pill">${data}</span>`
                    },
                    {
                        data: 'deleted_at',
                        name: 'deleted_at',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return data ?
                                '<span class="badge bg-danger rounded-pill">Eliminado</span>' :
                                '<span class="badge bg-success rounded-pill">Activo</span>';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'align-middle text-center',
                        render: data => data ? new Date(data).toLocaleString() : ''
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        className: 'align-middle text-center',
                        render: data => data ? new Date(data).toLocaleString() : ''
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle noVis noExport'
                    },
                ],
                columnDefs: [{
                        targets: [0, 1],
                        visible: true
                    } // Las primeras 2 columnas nunca se ocultan
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
                        className: 'btn  btn-sm btn-green mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="far fa-file-pdf me-1"></i> PDF',
                        className: 'btn  btn-sm btn-danger mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Imprimir',
                        className: 'btn  btn-sm btn-info mx-1',
                        exportOptions: {
                            columns: ':visible:not(.noExport)'
                        }
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                stateSave: true, // Habilita el guardado del estado (incluyendo columnas visibles)
                stateDuration: -1, // Guarda el estado permanentemente (localStorage)
                initComplete: function() {
                    // Añade tooltips a los botones
                    $('.dt-buttons button').attr('title', function() {
                        return $(this).find('.fa').parent().text().trim();
                    }).tooltip({
                        placement: 'top'
                    });
                }
            });
        });
    </script>

    @push('scripts')
        <script>
            // Confirmación de restauración de rol
            function confirmRestore(roleId) {
                swal({
                    title: '¿Estás seguro?',
                    text: 'Este rol será restaurado.',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Cancelar',
                            value: null,
                            visible: true,
                            className: 'btn btn-default',
                            closeModal: true,
                        },
                        confirm: {
                            text: 'Restaurar',
                            value: true,
                            visible: true,
                            className: 'btn btn-warning',
                            closeModal: true
                        }
                    }
                }).then((result) => {
                    if (result) {
                        document.getElementById('restore-form-' + roleId).submit();
                    }
                });
            }

            // Confirmación de eliminación permanente de rol
            function confirmDelete(roleId) {
                swal({
                    title: '¿Estás seguro?',
                    text: 'Este rol será eliminado permanentemente.',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: 'Cancelar',
                            value: null,
                            visible: true,
                            className: 'btn btn-default',
                            closeModal: true,
                        },
                        confirm: {
                            text: 'Eliminar',
                            value: true,
                            visible: true,
                            className: 'btn btn-danger',
                            closeModal: true
                        }
                    }
                }).then((result) => {
                    if (result) {
                        document.getElementById('force-delete-form-' + roleId).submit();
                    }
                });
            }
        </script>
    @endpush


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

    @if (session('error'))
        <script>
            $(document).ready(function() {
                $.gritter.add({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    class_name: 'gritter-error',
                    time: '3000'
                });
            });
        </script>
    @endif
    <script>
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
