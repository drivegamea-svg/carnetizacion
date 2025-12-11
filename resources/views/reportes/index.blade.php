@extends('layouts.app')

@section('title', 'Reportes - CIZEE')

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reportes</li>
        </ol>
        <!-- END breadcrumb -->

        <!-- BEGIN page-header -->
        <h1 class="page-header">Sistema de Reportes <small>Centro de análisis y exportación de datos</small></h1>
        <!-- END page-header -->

        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-teal">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-users fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">TOTAL AFILIADOS</div>
                        <div class="stats-number">{{ number_format($totalAfiliados) }}</div>
                        <div class="stats-progress progress">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                        <div class="stats-desc">Base de datos completa</div>
                    </div>
                </div>
            </div>
            <!-- END col-3 -->

            <!-- BEGIN col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-blue">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-check-circle fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">AFILIADOS ACTIVOS</div>
                        <div class="stats-number">{{ number_format($afiliadosActivos) }}</div>
                        <div class="stats-progress progress">
                            <div class="progress-bar"
                                style="width: {{ $totalAfiliados > 0 ? ($afiliadosActivos / $totalAfiliados) * 100 : 0 }}%;">
                            </div>
                        </div>
                        <div class="stats-desc">
                            {{ $totalAfiliados > 0 ? round(($afiliadosActivos / $totalAfiliados) * 100, 1) : 0 }}% del total
                        </div>
                    </div>
                </div>
            </div>
            <!-- END col-3 -->

            <!-- BEGIN col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-indigo">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-clock fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">PENDIENTES</div>
                        <div class="stats-number">
                            {{ number_format($totalAfiliados - $afiliadosActivos - $afiliadosInactivos) }}</div>
                        <div class="stats-progress progress">
                            <div class="progress-bar"
                                style="width: {{ $totalAfiliados > 0 ? (($totalAfiliados - $afiliadosActivos - $afiliadosInactivos) / $totalAfiliados) * 100 : 0 }}%;">
                            </div>
                        </div>
                        <div class="stats-desc">En proceso de validación</div>
                    </div>
                </div>
            </div>
            <!-- END col-3 -->

            <!-- BEGIN col-3 -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-gray-900">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-times-circle fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">INACTIVOS</div>
                        <div class="stats-number">{{ number_format($afiliadosInactivos) }}</div>
                        <div class="stats-progress progress">
                            <div class="progress-bar"
                                style="width: {{ $totalAfiliados > 0 ? ($afiliadosInactivos / $totalAfiliados) * 100 : 0 }}%;">
                            </div>
                        </div>
                        <div class="stats-desc">
                            {{ $totalAfiliados > 0 ? round(($afiliadosInactivos / $totalAfiliados) * 100, 1) : 0 }}% del total
                        </div>
                    </div>
                </div>
            </div>
            <!-- END col-3 -->
        </div>
        <!-- END row -->

        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-8 -->
            <div class="col-xl-8">
                <div class="panel panel-inverse" data-sortable-id="index-1">
                    <div class="panel-heading">
                        <h4 class="panel-title">Módulos de Reportes Disponibles</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @can('ver reportes afiliados')
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-gradient-blue text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-users fa-2x text-white opacity-75"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">Reporte de Afiliados</h5>
                                                    <p class="mb-0 opacity-75">Listado completo con filtros</p>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-3">
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Filtros avanzados</li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Búsqueda por ubicación
                                                </li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Exportación múltiple
                                                </li>
                                            </ul>
                                            <a href="{{ route('reportes.afiliados') }}"
                                                class="btn btn-outline-white btn-sm w-100">
                                                <i class="fas fa-play-circle me-1"></i> Iniciar Reporte
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            @can('ver reportes estadisticos')
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-gradient-green text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-chart-bar fa-2x text-white opacity-75"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">Estadísticas Generales</h5>
                                                    <p class="mb-0 opacity-75">Métricas y análisis globales</p>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-3">
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Distribución por
                                                    género</li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Análisis por sectores
                                                </li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Estadísticas
                                                    comparativas</li>
                                            </ul>
                                            <a href="{{ route('reportes.estadisticas') }}"
                                                class="btn btn-outline-white btn-sm w-100">
                                                <i class="fas fa-chart-line me-1"></i> Ver Estadísticas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            @can('ver reportes sectores')
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 bg-gradient-purple text-white h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-industry fa-2x text-white opacity-75"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">Sectores Económicos</h5>
                                                    <p class="mb-0 opacity-75">Análisis por actividad económica</p>
                                                </div>
                                            </div>
                                            <ul class="list-unstyled mb-3">
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Top sectores</li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Distribución
                                                    porcentual</li>
                                                <li class="mb-1"><i class="fas fa-check fa-fw me-2"></i>Gráficos
                                                    interactivos</li>
                                            </ul>
                                            <a href="{{ route('reportes.sectores') }}"
                                                class="btn btn-outline-white btn-sm w-100">
                                                <i class="fas fa-chart-pie me-1"></i> Analizar Sectores
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- END col-8 -->

            <!-- BEGIN col-4 -->
            <div class="col-xl-4">
                <div class="panel panel-inverse" data-sortable-id="index-2">
                    <div class="panel-heading">
                        <h4 class="panel-title">Formatos de Exportación</h4>
                    </div>
                    <div class="panel-body bg-light">
                        <div class="d-flex align-items-center mb-3 p-3 bg-success bg-opacity-10 rounded">
                            <i class="far fa-file-excel fa-2x text-success me-3"></i>
                            <div>
                                <h5 class="mb-1">Microsoft Excel</h5>
                                <p class="mb-0 text-muted">Formato .xlsx optimizado</p>
                                <small class="text-success">✓ Ideal para análisis de datos</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-3 bg-danger bg-opacity-10 rounded">
                            <i class="far fa-file-pdf fa-2x text-danger me-3"></i>
                            <div>
                                <h5 class="mb-1">Documento PDF</h5>
                                <p class="mb-0 text-muted">Formato .pdf profesional</p>
                                <small class="text-danger">✓ Perfecto para informes</small>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>
            <!-- END col-4 -->
        </div>
        <!-- END row -->

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.card').hover(
                function() {
                    $(this).css('transform', 'translateY(-5px)');
                    $(this).css('transition', 'all 0.3s ease');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        });
    </script>

    <style>
        .bg-gradient-blue {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .bg-gradient-green {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #6f42c1 0%, #59359a 100%);
        }

        .btn-outline-white {
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        .btn-outline-white:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
