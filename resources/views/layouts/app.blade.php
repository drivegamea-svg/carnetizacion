<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    {{-- 
    <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta content="SISTEMA WEB DE CARNETIZACIÓN" name="CARNETIZACIÓN" />
    <meta content="Comite Impulsor de la Zona Economica Especial" name="C.I.Z.E.E." />
    <title>@yield('title', 'Panel Admin')</title>


   <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logueo/img/CARNETIZACION.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('logueo/img/CARNETIZACION.png') }}">

    <link rel="apple-touch-icon" sizes="120x120"
        href="https://sialcsub.elalto.gob.bo/assets/dist/img/plantilla/apple-touch-icon-120x120-precomposed.png" />
    <link rel="apple-touch-icon" sizes="152x152"
        href="https://sialcsub.elalto.gob.bo/assets/dist/img/plantilla/apple-touch-icon-152x152-precomposed.png" />

    <meta property="og:title" content="SISTEMA DE ADMINISTRACION DE PCARNETIZACIÓN">
    <meta property="og:site_name" content="CARNETIZACIÓN">
    {{-- <meta property="og:url" content="https://permisos-viajes.elalto.gob.bo"> --}}
    <meta property="og:description" content="SISTEMA DE ADMINISTRACION DE CARNETIZACIÓN">
    <meta property="og:type" content="website">
    <meta property="og:image"
        content="https://sialcsub.elalto.gob.bo/assets/dist/img/plantilla/apple-touch-icon-120x120-precomposed.png">

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ asset('color-admin/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/css/default/app.min.css') }}" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
    <link href="{{ asset('color-admin/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/sweetalert/dist/sweetalert2.min.css') }}" rel="stylesheet" />
    <!-- ================== END page-css ================== -->
    <style>
        body #gritter-notice-wrapper .gritter-item-wrapper .gritter-item .gritter-close:before {
            content: "Cerrar";
        }

        .gritter-success {
            background-color: #d4edda !important;
            color: #155724 !important;
            border-left: 5px solid #28a745;
        }

        .gritter-error {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            border-left: 5px solid #dc3545;
        }

        .gritter-info {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
            border-left: 5px solid #17a2b8;
        }

        .gritter-success,
        .gritter-success .gritter-item,
        .gritter-success .gritter-title,
        .gritter-success p {
            color: #155724 !important;
        }

        .gritter-error,
        .gritter-error .gritter-item,
        .gritter-error .gritter-title,
        .gritter-error p {
            color: #721c24 !important;
        }

        .gritter-info,
        .gritter-info .gritter-item,
        .gritter-info .gritter-title,
        .gritter-info p {
            color: #0c5460 !important;
        }

        .gritter-success p,
        .gritter-success .gritter-title {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Puedes poner esto en tu archivo CSS o dentro de <style> en la vista */
        #gritter-notice-wrapper {
            z-index: 9999 !important;
            /* más alto que cualquier modal de Bootstrap */
            top: 70px !important;
            /* opcional: baja un poco para no tapar el header del modal */
        }
    </style>

    @stack('styles')
</head>

<body>


    <div id="app" class="app app-header-fixed app-sidebar-fixed ">
        @include('layouts.partials.sidebar')
        @include('layouts.partials.header')

        @yield('content')

        @include('layouts.partials.theme-panel')

    </div>
    <!-- END #app -->
    <script>
        window.assetBaseUrl = "{{ asset('color-admin') }}";
    </script>

    <!-- ================== BEGIN core-js ================== -->
    <script src="{{ asset('color-admin/js/vendor.min.js') }}"></script>
    <script src="{{ asset('color-admin/js/app.min.js') }}"></script>
    <!-- ================== END core-js ================== -->

    <!-- ================== BEGIN page-js ================== -->
    <script src="{{ asset('color-admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
    <!-- ================== END page-js ================== -->
    <script src="{{ asset('color-admin/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/sweetalert/dist/sweetalert2.min.js') }}"></script>


    @stack('scripts')

</body>

</html>
