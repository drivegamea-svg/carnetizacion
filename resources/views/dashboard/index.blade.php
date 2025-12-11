@extends('layouts.app')

@section('title', 'Dashboard - Afiliados')

@push('styles')
    <link href="{{ asset('color-admin/plugins/jvectormap-next/jquery-jvectormap.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/nvd3/build/nv.d3.css') }}" rel="stylesheet" />
    <link href="{{ asset('color-admin/plugins/apexcharts/dist/apexcharts.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>
            <li class="breadcrumb-item active">Afiliados</li>
        </ol>
        <!-- END breadcrumb -->

        <!-- BEGIN page-header -->
        <h1 class="page-header">Dashboard de Afiliados <small>Resumen visual y análisis de datos</small></h1>
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
                            <div class="progress-bar" style="width: {{ $totalAfiliados > 0 ? ($afiliadosActivos/$totalAfiliados)*100 : 0 }}%;"></div>
                        </div>
                        <div class="stats-desc">{{ $totalAfiliados > 0 ? round(($afiliadosActivos/$totalAfiliados)*100, 1) : 0 }}% del total</div>
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
                        <div class="stats-number">{{ number_format($afiliadosPendientes) }}</div>
                        <div class="stats-progress progress">
                            <div class="progress-bar" style="width: {{ $totalAfiliados > 0 ? ($afiliadosPendientes/$totalAfiliados)*100 : 0 }}%;"></div>
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
                            <div class="progress-bar" style="width: {{ $totalAfiliados > 0 ? ($afiliadosInactivos/$totalAfiliados)*100 : 0 }}%;"></div>
                        </div>
                        <div class="stats-desc">{{ $totalAfiliados > 0 ? round(($afiliadosInactivos/$totalAfiliados)*100, 1) : 0 }}% del total</div>
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
                <div class="panel panel-inverse" data-sortable-id="chart-1">
                    <div class="panel-heading">
                        <h4 class="panel-title">Afiliaciones por Mes (Último Año)</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                                <i class="fa fa-expand"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="apex-line-chart" style="height: 320px;"></div>
                    </div>
                </div>
            </div>
            <!-- END col-8 -->
            
            <!-- BEGIN col-4 -->
            <div class="col-xl-4">
                <div class="panel panel-inverse" data-sortable-id="chart-2">
                    <div class="panel-heading">
                        <h4 class="panel-title">Distribución por Estado</h4>
                    </div>
                    <div class="panel-body">
                        <div id="apex-donut-chart" style="height: 320px;"></div>
                    </div>
                </div>
            </div>
            <!-- END col-4 -->
        </div>
        <!-- END row -->

        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-6 -->
            <div class="col-xl-6">
                <div class="panel panel-inverse" data-sortable-id="chart-3">
                    <div class="panel-heading">
                        <h4 class="panel-title">Distribución por Sector Económico</h4>
                    </div>
                    <div class="panel-body">
                        <div id="apex-pie-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- END col-6 -->
            
            <!-- BEGIN col-6 -->
            <div class="col-xl-6">
                <div class="panel panel-inverse" data-sortable-id="chart-4">
                    <div class="panel-heading">
                        <h4 class="panel-title">Distribución por Género</h4>
                    </div>
                    <div class="panel-body">
                        <div id="apex-radial-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- END col-6 -->
        </div>
        <!-- END row -->

        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-12 -->
            <div class="col-xl-12">
                <div class="panel panel-inverse" data-sortable-id="chart-5">
                    <div class="panel-heading">
                        <h4 class="panel-title">Top 10 Ciudades con Más Afiliados</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                                <i class="fa fa-expand"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="apex-bar-chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <!-- END col-12 -->
        </div>
        <!-- END row -->

        <!-- BEGIN row -->
        <div class="row">
            <!-- BEGIN col-4 -->
            <div class="col-xl-4">
                <div class="panel panel-inverse" data-sortable-id="stats-1">
                    <div class="panel-heading">
                        <h4 class="panel-title">Resumen por Género</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-panel mb-0">
                                <thead>
                                    <tr>
                                        <th>Género</th>
                                        <th class="text-end">Cantidad</th>
                                        <th class="text-end">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($porGenero as $genero)
                                    @php
                                        $porcentaje = $totalAfiliados > 0 ? round(($genero->total / $totalAfiliados) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle fa-fw text-{{ $genero->name == 'MASCULINO' ? 'blue' : 'pink' }}"></i>
                                            {{ $genero->name }}
                                        </td>
                                        <td class="text-end">{{ number_format($genero->total) }}</td>
                                        <td class="text-end">{{ $porcentaje }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END col-4 -->
            
            <!-- BEGIN col-4 -->
            <div class="col-xl-4">
                <div class="panel panel-inverse" data-sortable-id="stats-2">
                    <div class="panel-heading">
                        <h4 class="panel-title">Resumen por Estado</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-panel mb-0">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th class="text-end">Cantidad</th>
                                        <th class="text-end">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($porEstado as $estado)
                                    @php
                                        $porcentaje = $totalAfiliados > 0 ? round(($estado->total / $totalAfiliados) * 100, 1) : 0;
                                        $color = $estado->name == 'ACTIVO' ? 'success' : ($estado->name == 'PENDIENTE' ? 'warning' : 'danger');
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fa fa-circle fa-fw text-{{ $color }}"></i>
                                            {{ $estado->name }}
                                        </td>
                                        <td class="text-end">{{ number_format($estado->total) }}</td>
                                        <td class="text-end">{{ $porcentaje }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END col-4 -->
            
            <!-- BEGIN col-4 -->
            <div class="col-xl-4">
                <div class="panel panel-inverse" data-sortable-id="stats-3">
                    <div class="panel-heading">
                        <h4 class="panel-title">Top Sectores Económicos</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-panel mb-0">
                                <thead>
                                    <tr>
                                        <th>Sector</th>
                                        <th class="text-end">Cantidad</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(collect($porSector)->take(5) as $sector)
                                    @php
                                        $porcentaje = $totalAfiliados > 0 ? round(($sector['total'] / $totalAfiliados) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-ellipsis">{{ $sector['name'] }}</td>
                                        <td class="text-end">{{ number_format($sector['total']) }}</td>
                                        <td class="text-end">{{ $porcentaje }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('color-admin/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('color-admin/plugins/nvd3/build/nv.d3.min.js') }}"></script>

    <script>
        // Datos desde el controlador
        var afiliadosPorSector = @json($porSector);
        var afiliadosPorGenero = @json($porGenero);
        var afiliadosPorEstado = @json($porEstado);
        var afiliadosPorCiudad = @json($porCiudad);
        var afiliacionesPorMes = @json($porMes);

        // Colores de Color Admin
        const colorAdmin = {
            blue: '#007bff',
            indigo: '#6610f2',
            purple: '#6f42c1',
            pink: '#e83e8c',
            red: '#dc3545',
            orange: '#fd7e14',
            yellow: '#ffc107',
            green: '#28a745',
            teal: '#20c997',
            cyan: '#17a2b8',
            gray: '#6c757d'
        };

        // Gráfico de líneas (afiliaciones por mes)
        function handleLineChart() {
            var options = {
                chart: {
                    type: 'line',
                    height: '100%',
                    fontFamily: 'inherit',
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Afiliaciones',
                    data: afiliacionesPorMes.map(item => item.total)
                }],
                xaxis: {
                    categories: afiliacionesPorMes.map(item => item.mes),
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '11px'
                        }
                    }
                },
                colors: [colorAdmin.blue],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    colors: [colorAdmin.blue]
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 4
                }
            };
            new ApexCharts(document.querySelector("#apex-line-chart"), options).render();
        }

        // Gráfico de pastel (sector económico)
        function handlePieChart() {
            var options = {
                chart: {
                    type: 'pie',
                    height: '100%',
                    fontFamily: 'inherit'
                },
                labels: afiliadosPorSector.map(item => item.name),
                series: afiliadosPorSector.map(item => item.total),
                colors: [
                    colorAdmin.blue,
                    colorAdmin.indigo,
                    colorAdmin.green,
                    colorAdmin.orange,
                    colorAdmin.red,
                    colorAdmin.purple,
                    colorAdmin.teal,
                    colorAdmin.cyan,
                    colorAdmin.gray,
                    colorAdmin.pink
                ],
                legend: {
                    position: 'bottom',
                    fontSize: '12px'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            new ApexCharts(document.querySelector("#apex-pie-chart"), options).render();
        }

        // Gráfico donut (género)
        function handleDonutChart() {
            var options = {
                chart: {
                    type: 'donut',
                    height: '100%',
                    fontFamily: 'inherit'
                },
                labels: afiliadosPorGenero.map(item => item.name),
                series: afiliadosPorGenero.map(item => item.total),
                colors: [
                    colorAdmin.blue,
                    colorAdmin.pink,
                    colorAdmin.green
                ],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#373d3f',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#apex-donut-chart"), options).render();
        }

        // Gráfico radial (estado)
        function handleRadialChart() {
            var options = {
                chart: {
                    type: 'radialBar',
                    height: '100%',
                    fontFamily: 'inherit'
                },
                series: afiliadosPorEstado.map(item => item.total),
                labels: afiliadosPorEstado.map(item => item.name),
                colors: [
                    colorAdmin.green,
                    colorAdmin.orange,
                    colorAdmin.red
                ],
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '14px',
                            },
                            value: {
                                fontSize: '16px',
                                fontWeight: 'bold'
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return afiliadosPorEstado.reduce((a, b) => a + b.total, 0)
                                }
                            }
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#apex-radial-chart"), options).render();
        }

        // Gráfico de barras (ciudades)
        function handleBarChart() {
            var options = {
                chart: {
                    type: 'bar',
                    height: '100%',
                    fontFamily: 'inherit',
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Afiliados',
                    data: afiliadosPorCiudad.map(item => item.total)
                }],
                xaxis: {
                    categories: afiliadosPorCiudad.map(item => item.name),
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6c757d',
                            fontSize: '11px'
                        }
                    }
                },
                colors: [colorAdmin.indigo],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 4
                }
            };
            new ApexCharts(document.querySelector("#apex-bar-chart"), options).render();
        }

        $(document).ready(function() {
            handleLineChart();
            handlePieChart();
            handleDonutChart();
            handleRadialChart();
            handleBarChart();
        });
    </script>

    <style>
        .table-panel th {
            border-top: none;
            font-weight: 600;
            font-size: 12px;
            color: #6c757d;
        }
        .table-panel td {
            font-size: 13px;
            vertical-align: middle;
        }
        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
    </style>
@endpush