<div style="text-align: center; padding: 12px 6px;">
    <!-- Cabecera Institucional -->
    <div style="display: flex; justify-content: center; margin-bottom: 12px;">
        <img src="{{ asset('images/logo-ciac.png') }}?v=2"
             alt="Centro de Instrucción de Aeronáutica Civil"
             style="height: 46px; width: auto; object-fit: contain;">
    </div>

    <!-- Contenedor del Código QR en Alta Calidad -->
    <div style="display: inline-block; background: #ffffff; padding: 14px; border-radius: 12px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.06); margin-bottom: 16px;">
        <img src="{{ route('certificados.qr', ['codigo' => $certificado->codigo_cer, 'format' => 'png']) }}" 
             alt="Código QR {{ $certificado->nombreCompleto() }}" 
             style="width: 240px; height: 240px; display: block; object-fit: contain;">
        <div style="font-family: monospace; font-size: 11px; color: #64748b; margin-top: 6px; font-weight: bold;">
            {{ $certificado->codigo_cer }}
        </div>
    </div>

    <!-- Ficha Resumen del Estudiante -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; text-align: left; max-width: 380px; margin-left: auto; margin-right: auto;">
        <div style="font-size: 10px; color: #64748b; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Estudiante</div>
        <div style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">{{ $certificado->nombreCompleto() }}</div>

        <div style="display: flex; justify-content: space-between; gap: 12px; font-size: 12px;">
            <div>
                <span style="color: #64748b; font-weight: 600;">C.I.:</span>
                <span style="font-weight: 800; color: #1e293b;">{{ $certificado->carnet_per_cer }}</span>
            </div>
            <div>
                <span style="color: #64748b; font-weight: 600;">N° Certificado:</span>
                <span style="font-weight: 800; color: #0284c7;">{{ $certificado->numeroRegistroNormalizado() }}</span>
            </div>
        </div>

        <div style="font-size: 11px; color: #475569; margin-top: 6px; border-top: 1px dashed #cbd5e1; padding-top: 6px;">
            <strong>Curso:</strong> {{ $certificado->curso_cer }} @if($certificado->codigo_curso_cer) ({{ $certificado->codigo_curso_cer }}) @endif
        </div>
    </div>

    <!-- Botones de Descarga en Formatos de Impresión -->
    <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
        <!-- Descarga PNG -->
        <a href="{{ route('certificados.qr', ['codigo' => $certificado->codigo_cer, 'format' => 'png', 'download' => 1]) }}"
           target="_blank"
           style="display: inline-flex; align-items: center; gap: 6px; background: #19499C; color: #ffffff; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 2px 6px rgba(25,73,156,0.25);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Descargar PNG (Foto/Word)
        </a>

        <!-- Descarga SVG Vectorial -->
        <a href="{{ route('certificados.qr', ['codigo' => $certificado->codigo_cer, 'format' => 'svg', 'download' => 1]) }}"
           target="_blank"
           style="display: inline-flex; align-items: center; gap: 6px; background: #0f766e; color: #ffffff; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 2px 6px rgba(15,118,110,0.25);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            Descargar SVG (Vectorial Imprenta)
        </a>
    </div>
</div>
