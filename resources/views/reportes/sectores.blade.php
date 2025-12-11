@extends('layouts.app')

@section('title', 'Reporte por Sectores - CIZEE')

@push('styles')
    @if (request('preview'))
        <link href="{{ asset('color-admin/plugins/apexcharts/dist/apexcharts.css') }}" rel="stylesheet" />
    @endif
@endpush

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Sectores Económicos</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Reporte por Sectores Económicos <small>Distribución de afiliados</small></h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-industry me-2"></i>Configuración del Reporte</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <form action="{{ route('reportes.exportar.sectores') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Ordenar por:</label>
                            <select name="orden" class="form-select">
                                <option value="total_desc">Mayor cantidad</option>
                                <option value="total_asc">Menor cantidad</option>
                                <option value="nombre_asc">Nombre A-Z</option>
                                <option value="nombre_desc">Nombre Z-A</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Límite de registros:</label>
                            <select name="limite" class="form-select">
                                <option value="10">Top 10</option>
                                <option value="20">Top 20</option>
                                <option value="50">Top 50</option>
                                <option value="0">Todos</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Opciones:</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="incluir_porcentajes" value="1"
                                    checked>
                                <label class="form-check-label">Incluir porcentajes</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            {{-- En los botones de exportación --}}
                            <div class="d-flex gap-2 flex-wrap">
                                @can('exportar excel sectores')
                                    <button type="submit" name="tipo" value="excel" class="btn btn-success">
                                        <i class="far fa-file-excel me-1"></i> Exportar Excel
                                    </button>
                                @endcan

                                @can('exportar pdf sectores')
                                    <button type="submit" name="tipo" value="pdf" class="btn btn-danger">
                                        <i class="far fa-file-pdf me-1"></i> Exportar PDF
                                    </button>
                                @endcan

                                @can('ver reportes sectores')
                                    <a href="{{ route('reportes.sectores') }}?preview=1" class="btn btn-primary">
                                        <i class="fas fa-chart-pie me-1"></i> Ver Gráfico
                                    </a>
                                @endcan

                                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (request('preview'))
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fas fa-chart-pie me-2"></i>Vista Previa - Distribución por Sectores
                    </h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th>Sector Económico</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-center">Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sectores as $sector)
                                            @php
                                                $porcentaje =
                                                    $totalGeneral > 0
                                                        ? round(($sector->total / $totalGeneral) * 100, 2)
                                                        : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $sector->sector_economico }}</td>
                                                <td class="text-center">{{ $sector->total }}</td>
                                                <td class="text-center">{{ $porcentaje }}%</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-success">
                                            <td><strong>TOTAL GENERAL</strong></td>
                                            <td class="text-center"><strong>{{ $totalGeneral }}</strong></td>
                                            <td class="text-center"><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="chart-sectores" style="min-height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if (request('preview'))
        <script src="{{ asset('color-admin/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                var sectores = @json($sectores);

                var options = {
                    chart: {
                        type: 'pie',
                        height: 400,
                        fontFamily: 'inherit'
                    },
                    series: sectores.map(item => item.total),
                    labels: sectores.map(item => item.sector_economico),
                    colors: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997',
                        '#6610f2', '#6c757d'
                    ],
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return value + ' afiliados';
                            }
                        }
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

                var chart = new ApexCharts(document.querySelector("#chart-sectores"), options);
                chart.render();
            });
        </script>
    @endif
@endpush
