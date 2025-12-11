@if ($afiliados->count() > 0)
    <div class="alert alert-info d-flex align-items-center">
        <i class="fas fa-info-circle fa-lg me-3"></i>
        <div>
            <h5 class="alert-heading mb-1">Resultados encontrados</h5>
            Se encontraron <strong>{{ $afiliados->count() }}</strong> registros con los filtros aplicados.
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="text-center">CI</th>
                    <th>Nombre Completo</th>
                    <th class="text-center">Celular</th>
                    <th>Ciudad</th>
                    <th>Profesión</th>
                    <th class="text-center">Sector</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Fecha Afiliación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($afiliados as $afiliado)
                    <tr>
                        <td class="text-center">{{ $afiliado->ci }} {{ $afiliado->expedicion }}</td>
                        <td>
                            <strong>{{ $afiliado->nombres }} {{ $afiliado->apellido_paterno }}
                                {{ $afiliado->apellido_materno }}</strong>
                        </td>
                        <td class="text-center">{{ $afiliado->celular }}</td>
                        <td>{{ $afiliado->ciudad }}</td>
                        <td>
                            <div><strong>{{ $afiliado->profesion_tecnica }}</strong></div>
                            <small class="text-muted">{{ $afiliado->especialidad }}</small>
                        </td>
                        <td class="text-center">{{ $afiliado->sector_economico }}</td>
                        <td class="text-center">
                            <span
                                class="badge 
                            @if ($afiliado->estado == 'ACTIVO') bg-success
                            @elseif($afiliado->estado == 'INACTIVO') bg-danger
                            @else bg-warning @endif">
                                {{ $afiliado->estado }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($afiliado->fecha_afiliacion)
                                {{ $afiliado->fecha_afiliacion->format('d/m/Y') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            Reporte generado el: {{ now()->format('d/m/Y H:i:s') }}
        </small>
        {{-- En los botones de exportación --}}
        <div class="btn-group">
            @can('exportar excel afiliados')
                <button type="button" class="btn btn-success btn-sm" onclick="exportar('excel')">
                    <i class="far fa-file-excel me-1"></i> Excel
                </button>
            @endcan

            @can('exportar pdf afiliados')
                <button type="button" class="btn btn-danger btn-sm" onclick="exportar('pdf')">
                    <i class="far fa-file-pdf me-1"></i> PDF
                </button>
            @endcan
        </div>
    </div>

    <script>
        function exportar(formato) {
            const form = document.getElementById('form-reporte');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'exportar';
            input.value = formato;
            form.appendChild(input);
            form.submit();
        }
    </script>
@else
    <div class="alert alert-warning text-center py-4">
        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
        <h4>No se encontraron registros</h4>
        <p class="mb-0">No hay afiliados que coincidan con los filtros aplicados.</p>
        <p class="mb-0">Intenta con otros criterios de búsqueda.</p>
    </div>
@endif
