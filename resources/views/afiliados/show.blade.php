@extends('layouts.app')

@section('title', 'Afiliado: ' . $afiliado->nombre_completo . ' - CIZEE')

@push('styles')
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<style>
    /* ===== ESTILOS CON MEJOR CONTRASTE ===== */
    
    /* Variables de ColorAdmin pero con más contraste */
    :root {
        --card-bg-contrast: #ffffff;
        --border-contrast: #e0e0e0;
        --sidebar-bg: #f8f9fa;
        --header-gradient: linear-gradient(135deg, #2c5282 0%, #3182ce 100%);
        --hover-bg: #edf2f7;
    }
    
    /* Header - Más diferenciado */
    .afiliado-header-ca {
        background: var(--header-gradient);
        color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: none;
    }

    .afiliado-avatar-ca {
        width: 100px;
        height: 100px;
        border: 3px solid rgba(255,255,255,0.8);
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        background: white;
    }

    .afiliado-avatar-ca img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Panel de control */
    .control-panel-ca {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin-bottom: 25px;
    }

    @media (min-width: 992px) {
        .control-panel-ca {
            flex-wrap: nowrap;
        }
    }

    /* Barra lateral de acciones - Más diferenciada */
    .action-sidebar-ca {
        flex: 0 0 260px;
        background: var(--sidebar-bg);
        border-radius: 8px;
        padding: 20px;
        border: 1px solid var(--border-contrast);
        height: fit-content;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    @media (max-width: 991px) {
        .action-sidebar-ca {
            flex: 0 0 100%;
            order: 2;
        }
    }

    .action-title-ca {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2d3748;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-contrast);
    }

    .action-btn-ca {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 10px;
        border: 1px solid var(--border-contrast);
        background: white;
        border-radius: 6px;
        color: #2d3748;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        text-align: left;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .action-btn-ca:hover {
        background: var(--hover-bg);
        border-color: #3182ce;
        color: #2c5282;
        transform: translateX(5px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .action-btn-ca i {
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    /* Contenido principal */
    .main-content-ca {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    /* Tarjetas con mejor contraste */
    .modern-card-ca {
        background: var(--card-bg-contrast);
        border: 1px solid var(--border-contrast);
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .modern-card-ca:hover {
        border-color: #3182ce;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .card-header-modern-ca {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-contrast);
    }

    .card-header-modern-ca h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
    }

    /* Tarjeta de estado destacada */
    .status-card-ca {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        color: white;
        border: none;
    }

    .status-card-ca .card-header-modern-ca {
        border-bottom-color: rgba(255,255,255,0.2);
    }

    .status-card-ca h5 {
        color: white;
    }

    .status-card-ca .field-label-ca {
        color: rgba(255,255,255,0.8);
    }

    .status-card-ca .field-value-ca {
        color: white;
        font-weight: 500;
    }

    /* Documentos - Más diferenciados */
    .document-grid-ca {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .document-card-ca {
        background: white;
        border: 1px solid var(--border-contrast);
        border-radius: 6px;
        padding: 15px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .document-card-ca:hover {
        border-color: #3182ce;
        background: var(--hover-bg);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .doc-icon-ca {
        font-size: 1.8rem;
        color: #3182ce;
        margin-bottom: 10px;
        text-align: center;
    }

    .doc-name-ca {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }

    .doc-meta-ca {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 15px;
        text-align: center;
    }

    .doc-actions-ca {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    /* Responsive mejorado */
    @media (max-width: 768px) {
        .afiliado-header-ca {
            padding: 15px;
        }
        
        .afiliado-avatar-ca {
            width: 80px;
            height: 80px;
        }
        
        .main-content-ca {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .document-grid-ca {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
        
        .action-sidebar-ca {
            padding: 15px;
        }
    }

    /* Utilitarios con mejor contraste */
    .field-label-ca {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .field-value-ca {
        font-size: 0.95rem;
        color: #2d3748;
        margin-bottom: 15px;
        min-height: 22px;
        font-weight: 500;
    }

    .status-badge-ca {
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Modal con mejor contraste */
    .modal-header-ca {
        background: var(--header-gradient);
        color: white;
        border-radius: 0;
        padding: 15px 20px;
    }

    .modal-header-ca .btn-close {
        filter: invert(1);
        opacity: 0.8;
        background-size: 0.8rem;
    }

    .modal-header-ca .btn-close:hover {
        opacity: 1;
    }

    /* Área de subida de archivos */
    .file-upload-area-ca {
        border: 2px dashed #cbd5e0;
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .file-upload-area-ca:hover {
        border-color: #3182ce;
        background: #edf2f7;
    }

    /* Grid para información */
    .info-grid-ca {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
    }

    /* Separador */
    .separator-ca {
        height: 1px;
        background: var(--border-contrast);
        margin: 20px 0;
    }

    /* Contenedores de foto y huella */
    .photo-container, .huella-container {
        width: 100%;
        height: 140px;
        border: 2px solid var(--border-contrast);
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-container img, .huella-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Badges de estado en header */
    .header-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    /* Información adicional en header */
    .header-info {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 8px;
    }

    /* Botones de acción pequeños */
    .btn-xs {
        padding: 4px 8px;
        font-size: 0.75rem;
        line-height: 1.2;
    }
</style>
@endpush

@section('content')
<div id="content" class="app-content">
    <!-- Breadcrumb de ColorAdmin -->
    <ol class="breadcrumb float-xl-end">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('afiliados.index') }}">Afiliados</a></li>
        <li class="breadcrumb-item active">{{ $afiliado->nombre_completo }}</li>
    </ol>

    <!-- Título de página -->
    <h1 class="page-header mb-3">
        Afiliado <small>{{ $afiliado->nombre_completo }}</small>
    </h1>

    <!-- ===== HEADER CON MEJOR CONTRASTE ===== -->
    <div class="afiliado-header-ca">
        <div class="d-flex align-items-center gap-3">
            <div class="afiliado-avatar-ca">
                @if($afiliado->foto_url)
                    <img src="{{ $afiliado->foto_url }}" alt="{{ $afiliado->nombre_completo }}" class="img-fluid">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 bg-white">
                        <i class="fas fa-user fa-2x text-muted"></i>
                    </div>
                @endif
            </div>
            
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h3 class="mb-0 text-white">{{ $afiliado->nombre_completo }}</h3>
                </div>
                
                <div class="header-info text-white-90">
                    <div class="d-flex flex-wrap gap-3">
                        <span><i class="fas fa-id-card me-1"></i>{{ $afiliado->ci }} {{ $afiliado->expedicion }}</span>
                        <span><i class="fas fa-phone me-1"></i>{{ $afiliado->celular }}</span>
                        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $afiliado->ciudad->nombre ?? 'Sin ciudad' }}</span>
                        @if($afiliado->fecha_afiliacion)
                            <span><i class="fas fa-calendar-check me-1"></i>Afiliado: {{ $afiliado->fecha_afiliacion->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="header-badges">
                    @if($afiliado->trashed())
                        <span class="badge bg-danger status-badge-ca">ELIMINADO</span>
                    @else
                        @php
                            $statusConfig = [
                                'ACTIVO' => ['class' => 'bg-success', 'icon' => 'check-circle'],
                                'INACTIVO' => ['class' => 'bg-warning', 'icon' => 'pause-circle'],
                                'PENDIENTE' => ['class' => 'bg-info', 'icon' => 'clock']
                            ];
                            $config = $statusConfig[$afiliado->estado] ?? ['class' => 'bg-secondary', 'icon' => 'question-circle'];
                        @endphp
                        <span class="badge {{ $config['class'] }} status-badge-ca">
                            <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $afiliado->estado }}
                        </span>
                    @endif
                    
                    @if($afiliado->fecha_vencimiento && $afiliado->fecha_vencimiento->isPast() && $afiliado->estado === 'ACTIVO')
                        <span class="badge bg-danger status-badge-ca">
                            <i class="fas fa-exclamation-triangle me-1"></i>VENCIDO
                        </span>
                    @endif
                    
                    @if($afiliado->edad)
                        <span class="badge bg-secondary status-badge-ca">
                            <i class="fas fa-birthday-cake me-1"></i>{{ $afiliado->edad }} años
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PANEL DE CONTROL CON MEJOR CONTRASTE ===== -->
    <div class="control-panel-ca">
        <!-- BARRA LATERAL DE ACCIONES -->
        <div class="action-sidebar-ca">
            <div class="action-title-ca">Acciones</div>
            
            @if(!$afiliado->trashed())
                @can('editar afiliados')
                <button class="action-btn-ca" onclick="window.location.href='{{ route('afiliados.edit', $afiliado->id) }}'">
                    <i class="fas fa-edit text-warning"></i>
                    <span>Editar Datos</span>
                </button>
                @endcan

                @if($afiliado->estado === 'ACTIVO' && auth()->user()->can('imprimir carnets afiliados'))
                <button class="action-btn-ca" onclick="window.open('{{ route('afiliados.carnet.pdf', $afiliado->id) }}', '_blank')">
                    <i class="fas fa-print text-primary"></i>
                    <span>Imprimir Carnet</span>
                </button>
                @endif

                @if($afiliado->estado === 'PENDIENTE' && auth()->user()->can('activar afiliados'))
                <button class="action-btn-ca" onclick="activarAfiliado()">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>Activar Afiliado</span>
                </button>
                @endif

                @if($afiliado->estado === 'ACTIVO' && auth()->user()->can('activar afiliados'))
                <button class="action-btn-ca" onclick="desactivarAfiliado()">
                    <i class="fas fa-pause-circle text-warning"></i>
                    <span>Desactivar</span>
                </button>
                @endif

                @if(auth()->user()->can('editar afiliados'))
                <button class="action-btn-ca" data-bs-toggle="modal" data-bs-target="#modalAgregarArchivo">
                    <i class="fas fa-paperclip text-info"></i>
                    <span>Agregar Documento</span>
                </button>
                @endif
            @endif

            @if($afiliado->trashed() && auth()->user()->can('restaurar afiliados'))
            <button class="action-btn-ca" onclick="restaurarAfiliado()">
                <i class="fas fa-undo text-success"></i>
                <span>Restaurar Afiliado</span>
            </button>
            @endif

            @if(!$afiliado->trashed() && auth()->user()->can('eliminar afiliados'))
            <button class="action-btn-ca" onclick="eliminarAfiliado()">
                <i class="fas fa-trash text-danger"></i>
                <span>Eliminar</span>
            </button>
            @endif

            <div class="separator-ca"></div>
            
            <button class="action-btn-ca" onclick="copiarEnlace()">
                <i class="fas fa-link text-secondary"></i>
                <span>Copiar Enlace</span>
            </button>
            
            <a href="{{ route('afiliados.index') }}" class="action-btn-ca text-decoration-none">
                <i class="fas fa-arrow-left text-secondary"></i>
                <span>Volver al Listado</span>
            </a>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="main-content-ca">
            <!-- TARJETA DE ESTADO DESTACADA -->
            <div class="modern-card-ca status-card-ca">
                <div class="card-header-modern-ca">
                    <h5><i class="fas fa-chart-line me-2"></i>Estado del Afiliado</h5>
                </div>
                <div class="info-grid-ca">
                    <div>
                        <div class="field-label-ca">Fecha Afiliación</div>
                        <div class="field-value-ca">
                            @if($afiliado->fecha_afiliacion)
                                {{ $afiliado->fecha_afiliacion->format('d/m/Y') }}
                            @else
                                <span class="opacity-75">No asignada</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-label-ca">Vencimiento</div>
                        <div class="field-value-ca">
                            @if($afiliado->fecha_vencimiento)
                                {{ $afiliado->fecha_vencimiento->format('d/m/Y') }}
                            @else
                                <span class="opacity-75">No asignado</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-label-ca">Días Restantes</div>
                        <div class="field-value-ca">
                            @if($afiliado->fecha_vencimiento)
                                @php
                                    $dias = now()->diffInDays($afiliado->fecha_vencimiento, false);
                                    $clase = $dias < 30 ? 'text-warning' : '';
                                @endphp
                                <span class="{{ $clase }} fw-bold">{{ $dias }} días</span>
                            @else
                                <span class="opacity-75">-</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-label-ca">Edad</div>
                        <div class="field-value-ca">
                            {{ $afiliado->edad ? $afiliado->edad . ' años' : 'No especificada' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA DE INFORMACIÓN PERSONAL -->
            <div class="modern-card-ca">
                <div class="card-header-modern-ca">
                    <h5><i class="fas fa-user-circle me-2"></i>Datos Personales</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="field-label-ca">Nombres Completos</div>
                        <div class="field-value-ca">{{ $afiliado->nombres }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Apellidos</div>
                        <div class="field-value-ca">{{ trim($afiliado->apellido_paterno . ' ' . $afiliado->apellido_materno) ?: '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Fecha Nacimiento</div>
                        <div class="field-value-ca">
                            @if($afiliado->fecha_nacimiento)
                                {{ $afiliado->fecha_nacimiento->format('d/m/Y') }}
                            @else
                                <span class="text-muted">No especificada</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Género</div>
                        <div class="field-value-ca">{{ $afiliado->genero }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Celular</div>
                        <div class="field-value-ca">{{ $afiliado->celular }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Dirección</div>
                        <div class="field-value-ca">{{ $afiliado->direccion ?: 'No especificada' }}</div>
                    </div>
                </div>
            </div>

            <!-- TARJETA DE INFORMACIÓN PROFESIONAL -->
            <div class="modern-card-ca">
                <div class="card-header-modern-ca">
                    <h5><i class="fas fa-briefcase me-2"></i>Información Profesional</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="field-label-ca">Profesión Técnica</div>
                        <div class="field-value-ca">{{ $afiliado->profesionTecnica->nombre ?? 'No especificada' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Especialidad</div>
                        <div class="field-value-ca">{{ $afiliado->especialidad->nombre ?? 'No especificada' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Sector Económico</div>
                        <div class="field-value-ca">{{ $afiliado->sectorEconomico->nombre ?? 'No especificada' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca">Organización Social</div>
                        <div class="field-value-ca">{{ $afiliado->organizacionSocial->nombre ?? 'No especificada' }}</div>
                    </div>
                </div>
            </div>

            <!-- TARJETA DE MULTIMEDIA -->
            <div class="modern-card-ca">
                <div class="card-header-modern-ca">
                    <h5><i class="fas fa-images me-2"></i>Multimedia</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="field-label-ca mb-2 text-center">Foto Carnet</div>
                        <div class="photo-container mx-auto">
                            @if($afiliado->foto_url)
                                <img src="{{ $afiliado->foto_url }}" alt="Foto" class="img-fluid">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-label-ca mb-2 text-center">Huella Dactilar</div>
                        <div class="huella-container mx-auto">
                            @if($afiliado->huella_url)
                                <img src="{{ $afiliado->huella_url }}" alt="Huella" class="img-fluid">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="fas fa-fingerprint fa-2x"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETA DE DOCUMENTOS (ocupa 2 columnas) -->
            <div class="modern-card-ca" style="grid-column: 1 / -1;">
                <div class="card-header-modern-ca">
                    <h5><i class="fas fa-file-alt me-2"></i>Documentos Adjuntos ({{ $afiliado->archivos->count() }})</h5>
                    @if(!$afiliado->trashed() && auth()->user()->can('editar afiliados'))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarArchivo">
                            <i class="fas fa-plus me-1"></i> Nuevo Documento
                        </button>
                    @endif
                </div>
                
                @if($afiliado->archivos->count() > 0)
                    <div class="document-grid-ca">
                        @foreach($afiliado->archivos as $archivo)
                            <div class="document-card-ca">
                                <div class="doc-icon-ca">
                                    @if($archivo->es_imagen)
                                        <i class="fas fa-file-image"></i>
                                    @elseif(str_contains($archivo->mime_type, 'pdf'))
                                        <i class="fas fa-file-pdf"></i>
                                    @elseif(str_contains($archivo->mime_type, 'word'))
                                        <i class="fas fa-file-word"></i>
                                    @else
                                        <i class="fas fa-file"></i>
                                    @endif
                                </div>
                                <div class="doc-name-ca" title="{{ $archivo->nombre_original }}">
                                    {{ $archivo->nombre_original }}
                                </div>
                                <div class="doc-meta-ca">
                                    <div class="mb-2">{{ $archivo->tamanio_formateado }}</div>
                                    <span class="badge bg-info">{{ \App\Models\ArchivoAfiliado::TIPOS_DOCUMENTO[$archivo->tipo_documento] ?? $archivo->tipo_documento }}</span>
                                </div>
                                <div class="doc-actions-ca">
                                    <a href="{{ $archivo->url }}" target="_blank" class="btn btn-xs btn-outline-primary" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ $archivo->url }}" download class="btn btn-xs btn-outline-success" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if(!$afiliado->trashed() && auth()->user()->can('editar afiliados'))
                                        <button type="button" class="btn btn-xs btn-outline-danger" 
                                                onclick="confirmarEliminarArchivo('{{ $archivo->id }}', '{{ $archivo->nombre_original }}')"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-4">No hay documentos adjuntos</p>
                        @if(!$afiliado->trashed() && auth()->user()->can('editar afiliados'))
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarArchivo">
                                <i class="fas fa-plus me-2"></i> Agregar Documento
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL CON MEJOR CONTRASTE ===== -->
@if(!$afiliado->trashed() && auth()->user()->can('editar afiliados'))
<div class="modal fade" id="modalAgregarArchivo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-ca">
                <h5 class="modal-title"><i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSubirArchivo" action="{{ route('afiliados.archivos.store', $afiliado->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                        <select name="tipo" class="form-select form-select-sm" required>
                            <option value="">Seleccionar...</option>
                            @foreach(\App\Models\ArchivoAfiliado::TIPOS_DOCUMENTO as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción (opcional)</label>
                        <input type="text" name="descripcion" class="form-control form-control-sm" placeholder="Ej: DNI frente y dorso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo <span class="text-danger">*</span></label>
                        <div class="file-upload-area-ca">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                            <p class="mb-2 small">Arrastra y suelta tu archivo aquí</p>
                            <input type="file" name="archivo" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required 
                                   onchange="mostrarNombreArchivo(this)">
                            <small class="text-muted d-block mt-2">PDF, JPG, PNG, DOC (Máximo 5MB)</small>
                        </div>
                        <div id="nombre-archivo" class="mt-2" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSubirArchivo">
                        <i class="fas fa-upload me-1"></i> Subir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar archivos -->
<form id="formEliminarArchivo" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('scripts')
<script>
// ===== FUNCIONES COMPATIBLES =====

// Manejo de archivos
function confirmarEliminarArchivo(archivoId, nombreArchivo) {
    Swal.fire({
        title: '¿Eliminar documento?',
        text: `"${nombreArchivo}" será eliminado permanentemente`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('formEliminarArchivo');
            form.action = `/afiliados/archivos/${archivoId}`;
            form.submit();
        }
    });
}

function mostrarNombreArchivo(input) {
    const fileName = input.files[0]?.name;
    const container = document.getElementById('nombre-archivo');
    
    if (fileName) {
        container.innerHTML = `
            <div class="alert alert-success py-2 small">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Archivo seleccionado:</strong> ${fileName}
            </div>
        `;
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

// Acciones del afiliado (mantén las mismas funciones)
function activarAfiliado() {
    Swal.fire({
        title: '¿Activar afiliado?',
        text: 'Se generará un número de carnet y fecha de vencimiento',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("afiliados.activar", $afiliado->id) }}';
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function desactivarAfiliado() {
    Swal.fire({
        title: '¿Desactivar afiliado?',
        text: 'Ya no podrá usar su carnet hasta que sea reactivado',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f39c12'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("afiliados.desactivar", $afiliado->id) }}';
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function restaurarAfiliado() {
    Swal.fire({
        title: '¿Restaurar afiliado?',
        text: 'Volverá a estar disponible en el sistema',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("afiliados.restaurar", $afiliado->id) }}';
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function eliminarAfiliado() {
    Swal.fire({
        title: '¿Eliminar permanentemente?',
        html: '<div class="text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br><strong>ADVERTENCIA:</strong> Esta acción eliminará todos los datos, fotos, huellas y documentos.<br>No se podrá recuperar la información.</div>',
        icon: 'error',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar permanentemente',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        backdrop: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("afiliados.destroy", $afiliado->id) }}';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Funciones adicionales
function copiarEnlace() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        // Usar Toast de ColorAdmin si está disponible
        if (typeof toastr !== 'undefined') {
            toastr.success('Enlace copiado al portapapeles');
        } else {
            // Fallback simple
            const alert = document.createElement('div');
            alert.className = 'alert alert-success position-fixed';
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; padding: 10px 15px;';
            alert.innerHTML = '<i class="fas fa-check me-2"></i>Enlace copiado';
            document.body.appendChild(alert);
            
            setTimeout(() => alert.remove(), 2000);
        }
    });
}

// Manejo del formulario de subida
document.addEventListener('DOMContentLoaded', function() {
    const formArchivo = document.getElementById('formSubirArchivo');
    if (formArchivo) {
        formArchivo.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnSubirArchivo');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Subiendo...';
        });
    }
});
</script>
@endpush