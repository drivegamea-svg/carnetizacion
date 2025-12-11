@extends('layouts.app')

@section('title', 'Estadísticas Generales - CIZEE')

@section('content')
    <div id="content" class="app-content">
        <!-- BEGIN breadcrumb -->
        <ol class="breadcrumb float-xl-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Estadísticas Generales</li>
        </ol>
        <!-- END breadcrumb -->

        <h1 class="page-header">Estadísticas Generales <small>Métricas y distribuciones</small></h1>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="widget widget-stats bg-gradient-cyan">
                    <div class="stats-icon"><i class="fas fa-users"></i></div>
                    <div class="stats-info">
                        <h4>TOTAL AFILIADOS</h4>
                        <p>{{ $estadisticas['generales']['Total Afiliados'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget widget-stats bg-gradient-green">
                    <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stats-info">
                        <h4>ACTIVOS</h4>
                        <p>{{ $estadisticas['generales']['Activos'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget widget-stats bg-gradient-orange">
                    <div class="stats-icon"><i class="fas fa-clock"></i></div>
                    <div class="stats-info">
                        <h4>PENDIENTES</h4>
                        <p>{{ $estadisticas['generales']['Pendientes'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget widget-stats bg-gradient-red">
                    <div class="stats-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="stats-info">
                        <h4>INACTIVOS</h4>
                        <p>{{ $estadisticas['generales']['Inactivos'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-chart-bar me-2"></i>Distribuciones</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-industry me-2"></i>Por Sector Económico</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Sector</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estadisticas['por_sector'] as $sector)
                                        @php
                                            $porcentaje =
                                                $estadisticas['generales']['Total Afiliados'] > 0
                                                    ? round(
                                                        ($sector->total /
                                                            $estadisticas['generales']['Total Afiliados']) *
                                                            100,
                                                        2,
                                                    )
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $sector->name }}</td>
                                            <td class="text-center">{{ $sector->total }}</td>
                                            <td class="text-center">{{ $porcentaje }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-venus-mars me-2"></i>Por Género</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Género</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estadisticas['por_genero'] as $genero)
                                        @php
                                            $porcentaje =
                                                $estadisticas['generales']['Total Afiliados'] > 0
                                                    ? round(
                                                        ($genero->total /
                                                            $estadisticas['generales']['Total Afiliados']) *
                                                            100,
                                                        2,
                                                    )
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $genero->name }}</td>
                                            <td class="text-center">{{ $genero->total }}</td>
                                            <td class="text-center">{{ $porcentaje }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        {{-- En los botones de exportación --}}
                        <div class="d-flex gap-2 justify-content-center">
                            <form action="{{ route('reportes.exportar.estadisticas') }}" method="POST" class="d-inline">
                                @csrf
                                @can('exportar excel estadisticos')
                                    <button type="submit" name="tipo" value="excel" class="btn btn-success">
                                        <i class="far fa-file-excel me-1"></i> Exportar Excel
                                    </button>
                                @endcan

                                @can('exportar pdf estadisticos')
                                    <button type="submit" name="tipo" value="pdf" class="btn btn-danger">
                                        <i class="far fa-file-pdf me-1"></i> Exportar PDF
                                    </button>
                                @endcan
                            </form>
                            <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
