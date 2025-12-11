<!-- resources/views/afiliados/verificacion-publica.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Carnet - {{ $afiliado->nombres }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .verification-card { border: 2px solid #333; padding: 20px; max-width: 500px; margin: 0 auto; }
        .status-valid { color: green; font-weight: bold; }
        .status-invalid { color: red; font-weight: bold; }
        .info-row { margin-bottom: 10px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="verification-card">
        <h2 style="text-align: center;">VERIFICACIÓN DE CARNET</h2>
        
        <div class="info-row">
            <span class="label">Estado:</span>
            @if($afiliado->estado === 'ACTIVO')
                <span class="status-valid">● CARNET VÁLIDO</span>
            @else
                <span class="status-invalid">● CARNET NO VÁLIDO</span>
            @endif
        </div>

        <div class="info-row">
            <span class="label">Nombre:</span>
            {{ $afiliado->apellido_paterno }} {{ $afiliado->apellido_materno }} {{ $afiliado->nombres }}
        </div>

        <div class="info-row">
            <span class="label">C.I.:</span>
            {{ $afiliado->ci }} {{ $afiliado->expedicion }}
        </div>

        <div class="info-row">
            <span class="label">Profesión:</span>
            {{ $afiliado->profesion_tecnica }}
        </div>

        <div class="info-row">
            <span class="label">Fecha de Afiliación:</span>
            {{ $afiliado->fecha_afiliacion->format('d/m/Y') }}
        </div>

        <div class="info-row">
            <span class="label">Fecha de Vencimiento:</span>
            {{ $afiliado->fecha_vencimiento->format('d/m/Y') }}
        </div>

        @if($afiliado->fecha_vencimiento->isPast())
            <div style="background: #ffebee; padding: 10px; margin-top: 15px;">
                <strong>⚠ ADVERTENCIA:</strong> Este carnet ha vencido
            </div>
        @endif

        <div style="margin-top: 20px; text-align: center;">
            <small>Verificado el: {{ now()->format('d/m/Y H:i:s') }}</small>
        </div>
    </div>
</body>
</html>