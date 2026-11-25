<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Códigos QR para Certificados Físicos - CIAC TAMEP</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ciac.png') }}?v=2">
    <style>
        :root {
            --azul-tam: #19499C;
            --dorado-tam: #FFCD05;
            --gris-borde: #cbd5e1;
            --texto-oscuro: #0f172a;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #f1f5f9;
            color: var(--texto-oscuro);
            padding: 20px;
        }

        /* Barra Superior de Control (Solo en Pantalla, se oculta al imprimir) */
        .toolbar {
            max-width: 1080px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .toolbar-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toolbar-logo {
            height: 44px;
            width: auto;
            max-width: 96px;
            object-fit: contain;
        }

        .toolbar-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--azul-tam);
        }

        .toolbar-sub {
            font-size: 12px;
            color: #64748b;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            background: #ffffff;
            outline: none;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--azul-tam);
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(25,73,156,0.3);
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: #143878;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        /* Contenedor de Planilla de Impresión */
        .sheet-container {
            max-width: 1080px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .sheet-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--azul-tam);
        }

        .sheet-header h1 {
            font-size: 18px;
            font-weight: 900;
            color: var(--azul-tam);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sheet-header p {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Cuadrícula de Etiquetas / Stickers QR */
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .sticker-card {
            border: 1.5px dashed #94a3b8;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            background: #ffffff;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .sticker-header {
            font-size: 9.5px;
            font-weight: 800;
            color: var(--azul-tam);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            width: 100%;
            padding-bottom: 4px;
        }

        .sticker-qr {
            width: 140px;
            height: 140px;
            object-fit: contain;
            display: block;
            margin: 0 auto 8px auto;
        }

        .sticker-student {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .sticker-meta {
            font-size: 10px;
            color: #475569;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .sticker-meta strong {
            color: #0f172a;
        }

        .sticker-course {
            font-size: 9.5px;
            font-weight: 700;
            color: #0369a1;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .sticker-code {
            font-family: monospace;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Reglas Específicas para Impresión en Hoja Física */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .toolbar {
                display: none !important;
            }

            .sheet-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .sheet-header {
                margin-bottom: 16px;
                padding-bottom: 8px;
            }

            .qr-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 10px !important;
            }

            .sticker-card {
                border: 1px dashed #64748b !important;
                padding: 8px !important;
            }

            .sticker-qr {
                width: 110px !important;
                height: 110px !important;
            }

            .sticker-student {
                font-size: 10px !important;
            }

            .sticker-meta {
                font-size: 9px !important;
            }
        }
    </style>
</head>
<body>

    <!-- Barra de Herramientas Superior -->
    <div class="toolbar">
        <div class="toolbar-info">
            <img src="{{ asset('images/logo-ciac.png') }}?v=2" alt="Logo CIAC" class="toolbar-logo">
            <div>
                <div class="toolbar-title">Planilla de Códigos QR para Certificados Físicos</div>
                <div class="toolbar-sub">CIAC TAMEP &bull; Total: {{ $certificados->count() }} alumnos listos para impresión</div>
            </div>
        </div>

        <div class="toolbar-actions">
            <!-- Filtro por Curso -->
            <form method="GET" action="{{ route('certificados.planilla') }}" style="display: flex; gap: 8px;">
                <select name="curso" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Todos los Cursos --</option>
                    @foreach(\App\Models\Curso::opcionesParaFiltro() as $key => $label)
                        <option value="{{ $key }}" {{ $cursoSeleccionado === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="gestion" value="{{ $gestionSeleccionada }}">
            </form>

            <button onclick="window.print()" class="btn-print">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimir Planilla
            </button>

            <a href="{{ url('/admin/certificados') }}" class="btn-back">
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Hoja Principal de Etiquetas -->
    <div class="sheet-container">
        <div class="sheet-header">
            <h1>Centro de Instrucción de Aeronáutica Civil &bull; TAMEP</h1>
            <p>
                Planilla Oficial de Códigos QR para Diplomas y Certificados &bull; 
                Gestión {{ $gestionSeleccionada }} 
                @if($cursoSeleccionado) &bull; {{ $cursoSeleccionado }} @endif
            </p>
        </div>

        <div class="qr-grid">
            @forelse($certificados as $cert)
                <div class="sticker-card">
                    <div class="sticker-header">CIAC TAMEP &bull; DIPLOMA OFICIAL</div>
                    
                    <img src="{{ route('certificados.qr', ['codigo' => $cert->codigo_cer, 'format' => 'png']) }}" 
                         alt="QR {{ $cert->nombreCompleto() }}" 
                         class="sticker-qr">

                    <div class="sticker-student">{{ $cert->nombreCompleto() }}</div>
                    <div class="sticker-meta">C.I.: <strong>{{ $cert->carnet_per_cer }}</strong></div>
                    <div class="sticker-meta">N°: <strong>{{ $cert->numeroRegistroNormalizado() }}</strong></div>
                    <div class="sticker-course">{{ $cert->curso_cer }}</div>
                    <div class="sticker-code">{{ $cert->codigo_cer }}</div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #64748b;">
                    No se encontraron certificados para el filtro seleccionado.
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>
