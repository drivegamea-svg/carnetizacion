@extends('layouts.app')

@section('title', 'Usuarios')

@push('styles')
    <link href="{{ asset('color-admin/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endpush

@section('content')
    <div id="content" class="app-content">
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>

        <h1 class="page-header">Usuarios <small>Listado de usuarios registrados</small></h1>

        <div class="panel panel-inverse">

            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-users me-2"></i>Gestión de Usuarios</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i
                            class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                    {{-- <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a> --}}
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                </div>
            </div>



            <div class="panel-body">
                <div class="row">
                    <table class="table table-bordered table-striped align-middle" id="users-table" width="100%">
                        <thead class="text-center">
                            <tr>
                                <th>ID</th>
                                <th>Nombre completo</th>
                                <th>CI</th>
                                <th>Celular</th>
                                <th>Género</th>
                                <th>Cargo</th>
                                <th>Correo</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todos"]
                ],
                ajax: '{{ route('admin.usuarios.index') }}',
                columns: [{
                        data: 'id',
                        className: 'text-center align-middle noVis',
                        visible: true,
                    },
                    {
                        data: 'name',
                        className: 'align-middle noVis',
                        visible: true,

                    },
                    {
                        data: 'ci',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'celular',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'genero',
                        className: 'text-center align-middle',
                        render: function(data) {
                            if (data === 'M') {
                                return '<i class="fas fa-mars"></i> MASCULINO';
                            } else if (data === 'F') {
                                return '<i class="fas fa-venus"></i> FEMENINO';
                            } else {
                                return '<i class="fas fa-genderless"></i> OTRO';
                            }
                        }
                    },
                    {
                        data: 'cargo',
                        className: 'align-middle'
                    },
                    {
                        data: 'email',
                        className: 'align-middle'
                    },
                    {
                        data: 'created_at',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'updated_at',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle  noVis noExport'
                    },
                ],
                dom: '<"row mb-3"<"col-lg-8 d-lg-block"<"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 me-0 me-md-3"l><"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 "B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row mt-3"<"col-md-auto me-auto"i><"col-md-auto ms-auto"p>>',
                buttons: [{
                        extend: 'colvis',
                        text: '<i class="far fa-eye me-1"></i> Columnas',
                        className: 'btn btn-sm btn-dark mx-1',
                        columns: ':not(.noVis)' // Esto evita que las columnas con .noVis aparezcan en el menú

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
                                30, // ID
                                '*', // Nombre completo
                                60, // CI
                                70, // Celular
                                70, // Género
                                '*', // Cargo
                                '*', // Correo
                                60, // Creado
                                60, // Actualizado
                                60 // Acciones
                            ];
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
                stateSave: true, // Habilita el guardado del estado (incluyendo columnas visibles)
                stateDuration: -1, // Guarda el estado permanentemente (localStorage)
                initComplete: function() {
                    // Añade tooltips a los botones
                    $('.dt-buttons button').attr('title', function() {
                        return $(this).find('.fa').parent().text().trim();
                    }).tooltip({
                        placement: 'top'
                    });
                },
                columnDefs: [{
                    targets: [0, 1],
                    className: 'never-hide', // útil si quieres estilo personalizado
                    visible: true
                }],

            });
        });
    </script>
@endpush
