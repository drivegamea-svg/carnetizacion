@extends('layouts.app')

@section('title', 'Afiliados CIZEE')

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

                <table id="afiliados-table" class="table table-bordered table-striped align-middle" width="100%">
                    <thead class="text-center">
                        <tr>
                            <th>Nombre Completo</th>
                            <th>CI</th>
                            <th>Profesión</th>
                            <th>Sector Económico</th>
                            <th>Celular</th>
                            <th>Estado</th>
                            <th>Fecha Afiliación</th>
                            <th width="12%">Acciones</th>
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
            $('#afiliados-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('afiliados.index') }}",
                responsive: true,
                columns: [
                  
                
                    {
                        data: null,
                        name: 'nombre_completo',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return `${row.apellido_paterno || ''} ${row.apellido_materno || ''} ${row.nombres || ''}`.trim();
                        }
                    },
                    {
                        data: null,
                        name: 'ci_completo',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return `${row.ci || ''} ${row.expedicion || ''}`.trim();
                        }
                    },
                    {
                        data: 'profesion_tecnica',
                        name: 'profesion_tecnica',
                        className: 'align-middle',
                        render: function(data) {
                            return data ? data.substring(0, 25) + (data.length > 25 ? '...' : '') : '';
                        }
                    },
                    {
                        data: 'sector_economico',
                        name: 'sector_economico',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'celular',
                        name: 'celular',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'fecha_afiliacion',
                        name: 'fecha_afiliacion',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle'
                    }
                ],
                dom: '<"row mb-3"<"col-lg-8 d-lg-block"<"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 me-0 me-md-3"l><"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 "B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row mt-3"<"col-md-auto me-auto"i><"col-md-auto ms-auto"p>>',
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="far fa-eye me-1"></i> Columnas',
                        className: 'btn btn-sm btn-dark mx-1'
                    },
                    {
                        extend: 'copy',
                        text: '<i class="far fa-copy me-1"></i> Copiar',
                        className: 'btn btn-sm btn-default mx-1'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="far fa-file-excel me-1"></i> Excel',
                        className: 'btn btn-sm btn-success mx-1'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="far fa-file-pdf me-1"></i> PDF',
                        className: 'btn btn-sm btn-danger mx-1'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Imprimir',
                        className: 'btn btn-sm btn-info mx-1'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                stateSave: true,
                stateDuration: -1,
                initComplete: function() {
                    $('.dt-buttons button').attr('title', function() {
                        return $(this).find('.fa').parent().text().trim();
                    }).tooltip({
                        placement: 'top'
                    });
                }
            });
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