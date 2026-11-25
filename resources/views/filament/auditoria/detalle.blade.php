@php
    use App\Models\AuditoriaLog;
    $anteriores = is_array($registro->valores_anteriores) ? $registro->valores_anteriores : [];
    $nuevos = is_array($registro->valores_nuevos) ? $registro->valores_nuevos : [];
    $todosCampos = array_values(array_unique(array_merge(array_keys($anteriores), array_keys($nuevos))));
@endphp

<div style="display: flex; flex-direction: column; gap: 16px; font-size: 13px;">
    <!-- Metadatos generales -->
    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; background: rgba(148, 163, 184, 0.08); border: 1px solid rgba(148, 163, 184, 0.25); border-radius: 10px; padding: 14px;">
        <div>
            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Evento</div>
            <div style="font-weight: 700; margin-top: 2px;">{{ $registro->evento }} ({{ $registro->modulo }})</div>
        </div>
        <div>
            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Fecha y Hora</div>
            <div style="font-weight: 600; margin-top: 2px;">{{ $registro->created_at?->format('d/m/Y H:i:s') ?? '-' }}</div>
        </div>
        <div>
            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Usuario</div>
            <div style="font-weight: 600; margin-top: 2px;">{{ $registro->usuario_nombre ?? 'Sistema' }}</div>
        </div>
        <div>
            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Dirección IP</div>
            <div style="font-family: monospace; font-weight: 600; margin-top: 2px;">{{ $registro->ip_address ?? 'N/A' }}</div>
        </div>
        <div style="grid-column: span 2;">
            <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Descripción</div>
            <div style="margin-top: 2px;">{{ $registro->descripcion }}</div>
        </div>
        @if($registro->user_agent)
            <div style="grid-column: span 2;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Navegador / Dispositivo</div>
                <div style="font-size: 11.5px; color: #64748b; word-break: break-all; margin-top: 2px;">{{ $registro->user_agent }}</div>
            </div>
        @endif
    </div>

    <!-- Comparativa de cambios o datos registrados -->
    @if(!empty($todosCampos))
        <div>
            <div style="font-size: 12px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em;">
                @if($registro->evento === 'MODIFICACION')
                    Campos Modificados (Antes vs. Después)
                @elseif($registro->evento === 'ELIMINACION')
                    Datos del Registro Eliminado
                @else
                    Datos Registrados
                @endif
            </div>

            <div style="overflow-x: auto; border: 1px solid rgba(148, 163, 184, 0.25); border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 12.5px;">
                    <thead>
                        <tr style="background: rgba(148, 163, 184, 0.12); text-align: left;">
                            <th style="padding: 8px 10px; border-bottom: 1px solid rgba(148, 163, 184, 0.25);">Campo</th>
                            @if($registro->evento === 'MODIFICACION' || $registro->evento === 'ELIMINACION')
                                <th style="padding: 8px 10px; border-bottom: 1px solid rgba(148, 163, 184, 0.25);">Valor Anterior</th>
                            @endif
                            @if($registro->evento !== 'ELIMINACION')
                                <th style="padding: 8px 10px; border-bottom: 1px solid rgba(148, 163, 184, 0.25);">Valor Nuevo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todosCampos as $campo)
                            <tr style="border-bottom: 1px solid rgba(148, 163, 184, 0.15);">
                                <td style="padding: 7px 10px; font-weight: 600;">{{ AuditoriaLog::etiquetaCampo($campo) }}</td>
                                @if($registro->evento === 'MODIFICACION' || $registro->evento === 'ELIMINACION')
                                    <td style="padding: 7px 10px; color: #dc2626;">
                                        {{ filled($anteriores[$campo] ?? null) ? $anteriores[$campo] : '—' }}
                                    </td>
                                @endif
                                @if($registro->evento !== 'ELIMINACION')
                                    <td style="padding: 7px 10px; color: #16a34a; font-weight: 600;">
                                        {{ filled($nuevos[$campo] ?? null) ? $nuevos[$campo] : '—' }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
