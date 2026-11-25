<x-filament-panels::page>
    <style>
        /* =========================================================================
           ESTILOS BLINDADOS PARA LA GUÍA OPERATIVA (NO DEPENDEN DE TAILWIND)
           ========================================================================= */
        .guia-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        /* Cabecera Principal */
        .guia-header-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .dark .guia-header-box {
            background: #111827;
            border-color: #1f2937;
        }

        .guia-brand-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .dark .guia-brand-box {
            background: #1e293b;
            border-color: #334155;
        }

        /* Selector Superior de Pestañas / Filtro */
        .guia-filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            padding: 6px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }
        .dark .guia-filter-bar {
            background: #1e293b;
            border-color: #334155;
        }

        .guia-filter-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #475569;
            border: 1px solid transparent;
            cursor: pointer;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }
        .dark .guia-filter-btn {
            color: #94a3b8;
        }

        .guia-filter-btn.active {
            background: #ffffff;
            color: #19499C;
            font-weight: 700;
            border-color: #cbd5e1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        .dark .guia-filter-btn.active {
            background: #0f172a;
            color: #60a5fa;
            border-color: #334155;
        }

        /* Tarjetas Principales por Módulo */
        .guia-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .dark .guia-card {
            background: #111827;
            border-color: #1f2937;
        }

        .guia-card-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .guia-card-header {
            border-bottom-color: #1f2937;
        }

        .guia-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .guia-header-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        /* Iconos en Caja Cuadrada */
        .guia-icon-box {
            width: 42px;
            height: 42px;
            min-width: 42px;
            min-height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .guia-icon-box.blue { background: rgba(25, 73, 156, 0.1); color: #19499C; }
        .guia-icon-box.emerald { background: rgba(5, 150, 105, 0.1); color: #059669; }
        .guia-icon-box.amber { background: rgba(217, 119, 6, 0.1); color: #d97706; }
        .guia-icon-box.indigo { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }

        .dark .guia-icon-box.blue { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .dark .guia-icon-box.emerald { background: rgba(16, 185, 129, 0.2); color: #34d399; }
        .dark .guia-icon-box.amber { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .dark .guia-icon-box.indigo { background: rgba(99, 102, 241, 0.2); color: #818cf8; }

        .guia-icon-box svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            display: block;
        }

        /* Badges de Módulo */
        .guia-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .guia-badge.blue { background: rgba(25, 73, 156, 0.1); color: #19499C; }
        .guia-badge.emerald { background: rgba(5, 150, 105, 0.1); color: #059669; }
        .guia-badge.amber { background: rgba(217, 119, 6, 0.1); color: #d97706; }
        .guia-badge.indigo { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }

        .dark .guia-badge.blue { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
        .dark .guia-badge.emerald { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
        .dark .guia-badge.amber { background: rgba(245, 158, 11, 0.2); color: #fde68a; }
        .dark .guia-badge.indigo { background: rgba(99, 102, 241, 0.2); color: #c7d2fe; }

        /* Cuadrícula de 3 Sub-tarjetas de Pasos */
        .guia-steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .guia-step-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
        }
        .dark .guia-step-card {
            background: #1e293b;
            border-color: #334155;
        }

        .guia-step-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .guia-step-num {
            width: 28px;
            height: 28px;
            min-width: 28px;
            min-height: 28px;
            border-radius: 7px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .guia-step-num.blue { background: #19499C; }
        .guia-step-num.emerald { background: #059669; }
        .guia-step-num.amber { background: #d97706; }
        .guia-step-num.indigo { background: #4f46e5; }

        .guia-step-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }
        .dark .guia-step-title {
            color: #f8fafc;
        }

        .guia-step-desc {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
            margin: 0;
        }
        .dark .guia-step-desc {
            color: #94a3b8;
        }

        .guia-step-bullets {
            font-size: 11px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: #64748b;
            padding-top: 8px;
            border-top: 1px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .dark .guia-step-bullets {
            color: #94a3b8;
            border-top-color: #475569;
        }

        /* Botones de Acción */
        .guia-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            line-height: 1.2;
            white-space: nowrap;
        }

        .guia-btn-primary {
            background: #19499C;
            color: #ffffff !important;
        }
        .guia-btn-primary:hover {
            background: #123675;
        }

        .guia-btn-emerald {
            background: #059669;
            color: #ffffff !important;
        }
        .guia-btn-emerald:hover {
            background: #047857;
        }

        .guia-btn-amber {
            background: #d97706;
            color: #ffffff !important;
        }
        .guia-btn-amber:hover {
            background: #b45309;
        }

        .guia-btn-secondary {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #334155 !important;
        }
        .guia-btn-secondary:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }
        .dark .guia-btn-secondary {
            background: #1e293b;
            border-color: #475569;
            color: #e2e8f0 !important;
        }
        .dark .guia-btn-secondary:hover {
            background: #334155;
            border-color: #64748b;
        }

        .guia-btn svg {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            min-height: 14px !important;
            flex-shrink: 0;
            display: inline-block;
        }

        /* Paneles Desplegables y Tablas */
        .guia-drawer {
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .dark .guia-drawer {
            border-top-color: #1f2937;
        }

        .guia-table-wrapper {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
        }
        .dark .guia-table-wrapper {
            border-color: #334155;
            background: #0f172a;
        }

        .guia-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: left;
        }
        .guia-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
            padding: 9px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 10.5px;
            letter-spacing: 0.05em;
        }
        .dark .guia-table th {
            background: #1e293b;
            color: #cbd5e1;
            border-bottom-color: #334155;
        }
        .guia-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }
        .dark .guia-table td {
            border-bottom-color: #1e293b;
            color: #94a3b8;
        }
        .guia-table tr:last-child td {
            border-bottom: none;
        }
    </style>

    <div x-data="{
        filtroTarjeta: 'todas',
        expandirCursos: false,
        expandirColumnas: false,
        expandirDetalleQr: false,
        expandirVerificacion: false
    }" class="guia-wrapper">

        <!-- ========================================================================= -->
        <!-- CABECERA INSTITUCIONAL COMPACTA -->
        <!-- ========================================================================= -->
        <div class="guia-header-box">
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="guia-badge blue">CIAC TAMEP</span>
                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">| Centro de Operaciones y Guías</span>
                </div>
                <h1 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.01em;">
                    Guía de Procedimientos del Sistema
                </h1>
                <p style="font-size: 12.5px; color: #475569; margin: 0; max-width: 720px; line-height: 1.4;">
                    Seleccione una operación para revisar su procedimiento paso a paso, descargar recursos o iniciar el asistente guiado en pantalla.
                </p>
            </div>

            <div class="guia-brand-box">
                <img src="{{ asset('images/logo-tam.png') }}" alt="TAMEP" style="height: 28px; width: auto; object-fit: contain; display: block;">
                <div style="height: 20px; width: 1px; background: #cbd5e1;"></div>
                <img src="{{ asset('images/logo-ciac.png') }}?v=2" alt="Centro de Instrucción de Aeronáutica Civil" style="height: 32px; width: auto; object-fit: contain; display: block;">
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SELECTOR DE FILTRO DE TARJETAS (SEGMENTED CONTROL) -->
        <!-- ========================================================================= -->
        <div class="guia-filter-bar">
            <button type="button" @click="filtroTarjeta = 'todas'"
                :class="filtroTarjeta === 'todas' ? 'active' : ''"
                class="guia-filter-btn">
                <span>Todas las Operaciones</span>
                <span style="font-size: 10px; padding: 1px 6px; border-radius: 10px; background: rgba(0,0,0,0.06); font-family: monospace; font-weight: 700;">4</span>
            </button>

            <button type="button" @click="filtroTarjeta = 'manual'"
                :class="filtroTarjeta === 'manual' ? 'active' : ''"
                class="guia-filter-btn">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #19499C; display: inline-block;"></span>
                <span>1. Registro Manual</span>
            </button>

            <button type="button" @click="filtroTarjeta = 'masivo'"
                :class="filtroTarjeta === 'masivo' ? 'active' : ''"
                class="guia-filter-btn">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                <span>2. Carga Masiva (Excel)</span>
            </button>

            <button type="button" @click="filtroTarjeta = 'exportacion'"
                :class="filtroTarjeta === 'exportacion' ? 'active' : ''"
                class="guia-filter-btn">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #d97706; display: inline-block;"></span>
                <span>3. Exportación con QRs</span>
            </button>

            <button type="button" @click="filtroTarjeta = 'verificacion'"
                :class="filtroTarjeta === 'verificacion' ? 'active' : ''"
                class="guia-filter-btn">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #4f46e5; display: inline-block;"></span>
                <span>4. Verificación Pública</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- TARJETA 1: REGISTRO MANUAL INDIVIDUAL -->
        <!-- ========================================================================= -->
        <div x-show="filtroTarjeta === 'todas' || filtroTarjeta === 'manual'" class="guia-card">
            
            <!-- Encabezado de la Tarjeta con Ícono Integrado al Título -->
            <div class="guia-card-header">
                <div class="guia-header-left">
                    <div class="guia-icon-box blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                                1. Registro Individual de Certificados
                            </h2>
                            <span class="guia-badge blue">Registro Manual</span>
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Emisión unitaria de diplomas mediante formulario oficial con conversión a mayúsculas y autocompletado.
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción de la Tarjeta -->
                <div class="guia-header-actions">
                    <button type="button" @click="expandirCursos = !expandirCursos" class="guia-btn guia-btn-secondary">
                        <span x-text="expandirCursos ? 'Ocultar Especialidades' : 'Ver Especialidades 2026'"></span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <button type="button" onclick="window.iniciarRutaTour('manual')" class="guia-btn guia-btn-secondary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        <span>Asistente en Pantalla</span>
                    </button>

                    <a href="{{ url('/admin/certificados/create') }}" class="guia-btn guia-btn-primary">
                        <span>Abrir Formulario</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Cuadrícula de 3 Sub-tarjetas de Pasos -->
            <div class="guia-steps-grid">
                
                <!-- Sub-tarjeta Paso 1 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num blue">1</div>
                            <div class="guia-step-title">Identificación del Estudiante</div>
                        </div>
                        <p class="guia-step-desc">
                            Llenado de nombres y apellidos con conversión automática a <strong>MAYÚSCULAS</strong>. La Cédula de Identidad (C.I.) actúa como identificador único obligatorio.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Paterno + Materno + Nombres</div>
                        <div>&bull; Cédula única (sin duplicados)</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 2 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num blue">2</div>
                            <div class="guia-step-title">Programa Académico</div>
                        </div>
                        <p class="guia-step-desc">
                            Al seleccionar la especialidad (DV, TCP o AGT), el sistema completa automáticamente las fechas lectivas, la autoridad firmante y el tipo de documento.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; DV/2026: Despachador de Vuelo</div>
                        <div>&bull; TCP/2026: Tripulante de Cabina</div>
                        <div>&bull; AGT/2026: Agente de Tráfico</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 3 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num blue">3</div>
                            <div class="guia-step-title">Libro Matriz y Código QR</div>
                        </div>
                        <p class="guia-step-desc">
                            Asignación del folio correlativo oficial. Al hacer clic en <strong>Crear</strong>, el sistema sella el registro y genera el código QR permanente para verificación.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Formato: CIAC TAMEP [CURSO] 2026...</div>
                        <div>&bull; Generación de QR criptográfico</div>
                    </div>
                </div>

            </div>

            <!-- Panel Desplegable: Tabla de Especialidades Oficiales -->
            <div x-show="expandirCursos" class="guia-drawer">
                <span style="font-size: 11.5px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em;">
                    Programas Académicos y Nomenclaturas Oficiales (Gestión 2026)
                </span>
                <div class="guia-table-wrapper">
                    <table class="guia-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Programa Académico</th>
                                <th>Calendario Lectivo</th>
                                <th>Tipo de Documento</th>
                                <th>Ejemplo Libro Matriz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: #19499C;">DV/2026</td>
                                <td style="font-weight: 600; color: #0f172a;">DESPACHADOR DE VUELO</td>
                                <td>02/mar al 30/nov 2026</td>
                                <td>DIPLOMA Y CERTIFICADO DE ESTUDIOS</td>
                                <td style="font-family: monospace; font-size: 11px;">CIAC TAMEP DV 2026 CI 9895606 N° 001/26</td>
                            </tr>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: #19499C;">TCP/2026</td>
                                <td style="font-weight: 600; color: #0f172a;">TRIPULANTE DE CABINA</td>
                                <td>02/mar al 30/oct 2026</td>
                                <td>DIPLOMA Y CERTIFICADO DE APROBACIÓN</td>
                                <td style="font-family: monospace; font-size: 11px;">CIAC TAMEP TCP 2026 CI 11066284 N° 001/26</td>
                            </tr>
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: #19499C;">AGT/2026</td>
                                <td style="font-weight: 600; color: #0f172a;">AGENTE DE TRÁFICO</td>
                                <td>31/ago al 30/nov 2026</td>
                                <td>CERTIFICADO DE APROBACIÓN Y ESTUDIOS</td>
                                <td style="font-family: monospace; font-size: 11px;">CIAC TAMEP AGT 2026 CI 13084026 N° 001/26</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TARJETA 2: CARGA MASIVA DESDE EXCEL -->
        <!-- ========================================================================= -->
        <div x-show="filtroTarjeta === 'todas' || filtroTarjeta === 'masivo'" class="guia-card">
            
            <!-- Encabezado de la Tarjeta con Ícono Integrado al Título -->
            <div class="guia-card-header">
                <div class="guia-header-left">
                    <div class="guia-icon-box emerald">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                                2. Carga Masiva desde Planilla Excel
                            </h2>
                            <span class="guia-badge emerald">Importación por Lote</span>
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Registro de cohortes completas con validación de estructura de 13 columnas y bloqueo automático de duplicados.
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción de la Tarjeta -->
                <div class="guia-header-actions">
                    <button type="button" @click="expandirColumnas = !expandirColumnas" class="guia-btn guia-btn-secondary">
                        <span x-text="expandirColumnas ? 'Ocultar Columnas' : 'Ver 13 Columnas (A-M)'"></span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <button type="button" onclick="window.iniciarRutaTour('masivo')" class="guia-btn guia-btn-secondary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                        <span>Asistente en Pantalla</span>
                    </button>

                    <a href="{{ route('certificados.plantilla') }}" class="guia-btn guia-btn-emerald">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Descargar Plantilla (.xlsx)</span>
                    </a>
                </div>
            </div>

            <!-- Cuadrícula de 3 Sub-tarjetas de Pasos -->
            <div class="guia-steps-grid">
                
                <!-- Sub-tarjeta Paso 1 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num emerald">1</div>
                            <div class="guia-step-title">Descargar Modelo Oficial</div>
                        </div>
                        <p class="guia-step-desc">
                            Descargue el archivo <code>plantilla_certificados_ciac_tamep.xlsx</code>. Incluye los encabezados oficiales preformateados para las 3 especialidades.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Archivo Excel nativo (.xlsx)</div>
                        <div>&bull; Sin macros ni dependencias externas</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 2 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num emerald">2</div>
                            <div class="guia-step-title">Pegar Nómina de Estudiantes</div>
                        </div>
                        <p class="guia-step-desc">
                            Llene las filas respetando el orden de columnas A a M. Nombres, C.I., Código de Curso (DV/2026, etc.), Estado y Fecha son de llenado obligatorio.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; 13 columnas normadas</div>
                        <div>&bull; Fechas en formato AAAA-MM-DD</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 3 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num emerald">3</div>
                            <div class="guia-step-title">Importación con Validación</div>
                        </div>
                        <p class="guia-step-desc">
                            En el menú <strong>Certificados &rarr; Importar Excel</strong>, suba el archivo. El sistema procesa los registros e impide la inserción de carnets duplicados.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Generación de QRs masiva</div>
                        <div>&bull; Integridad de Libro Matriz garantizada</div>
                    </div>
                </div>

            </div>

            <!-- Panel Desplegable: Tabla de las 13 Columnas Oficiales -->
            <div x-show="expandirColumnas" class="guia-drawer">
                <span style="font-size: 11.5px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em;">
                    Especificación de las 13 Columnas Reglamentarias (A a M)
                </span>
                <div class="guia-table-wrapper">
                    <table class="guia-table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Col.</th>
                                <th>Encabezado</th>
                                <th>Carácter</th>
                                <th>Valores Aceptados</th>
                                <th>Ejemplo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">A</td>
                                <td style="font-weight: 600; color: #0f172a;">APELLIDO PATERNO</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Texto (mayúsculas)</td>
                                <td style="font-family: monospace;">CESPEDES</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">B</td>
                                <td style="font-weight: 600; color: #0f172a;">APELLIDO MATERNO</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Texto (vacío si no tiene)</td>
                                <td style="font-family: monospace;">LEDEZMA</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">C</td>
                                <td style="font-weight: 600; color: #0f172a;">NOMBRES</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td>Texto obligatorio</td>
                                <td style="font-family: monospace;">ARLETH</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">D</td>
                                <td style="font-weight: 600; color: #0f172a;">CARNET DE IDENTIDAD</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td>Numérico / Clave única</td>
                                <td style="font-family: monospace;">9895606</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">E</td>
                                <td style="font-weight: 600; color: #0f172a;">CÓDIGO CURSO</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td><code>DV/2026</code>, <code>TCP/2026</code> o <code>AGT/2026</code></td>
                                <td style="font-family: monospace;">DV/2026</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">F</td>
                                <td style="font-weight: 600; color: #0f172a;">NOMBRE CURSO</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td>Nombre oficial del programa</td>
                                <td>DESPACHADOR DE VUELO</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">G</td>
                                <td style="font-weight: 600; color: #0f172a;">PERIODO</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Rango de fechas</td>
                                <td>02/mar al 30/nov 2026</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">H</td>
                                <td style="font-weight: 600; color: #0f172a;">PROMEDIO FINAL</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Calificación numérica</td>
                                <td style="font-family: monospace;">88.5</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">I</td>
                                <td style="font-weight: 600; color: #0f172a;">ESTADO ACADÉMICO</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td><code>APROBADO</code> o <code>REPROBADO</code></td>
                                <td style="font-family: monospace;">APROBADO</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">J</td>
                                <td style="font-weight: 600; color: #0f172a;">TIPO DOCUMENTO</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Diploma o Certificado</td>
                                <td>DIPLOMA Y CERTIFICADO</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">K</td>
                                <td style="font-weight: 600; color: #0f172a;">REGISTRO LIBRO MATRIZ</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Folio legal</td>
                                <td style="font-family: monospace; font-size: 11px;">CIAC TAMEP DV 2026 CI 9895606 N° 001/26</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">L</td>
                                <td style="font-weight: 600; color: #0f172a;">N° CERT. ESTUDIOS</td>
                                <td style="color: #64748b;">Opcional</td>
                                <td>Folio correlativo</td>
                                <td style="font-family: monospace;">N° 001-DV 2026</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-family: monospace; font-weight: 700; color: #0f172a;">M</td>
                                <td style="font-weight: 600; color: #0f172a;">FECHA DE EMISIÓN</td>
                                <td style="font-weight: 700; color: #dc2626;">Requerido</td>
                                <td>Formato <code>AAAA-MM-DD</code></td>
                                <td style="font-family: monospace;">2026-12-18</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TARJETA 3: EXPORTACIÓN Y GENERACIÓN DE QRS -->
        <!-- ========================================================================= -->
        <div x-show="filtroTarjeta === 'todas' || filtroTarjeta === 'exportacion'" class="guia-card">
            
            <!-- Encabezado de la Tarjeta con Ícono Integrado al Título -->
            <div class="guia-card-header">
                <div class="guia-header-left">
                    <div class="guia-icon-box amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                                3. Clasificación y Exportación con Códigos QR
                            </h2>
                            <span class="guia-badge amber">Reportes y Nóminas</span>
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Filtros por cohorte y generación de libros Excel con imágenes QR físicas incrustadas y planillas A4 imprimibles.
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción de la Tarjeta -->
                <div class="guia-header-actions">
                    <button type="button" @click="expandirDetalleQr = !expandirDetalleQr" class="guia-btn guia-btn-secondary">
                        <span x-text="expandirDetalleQr ? 'Ocultar Detalle QR' : 'Ver Detalle OpenXML'"></span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <a href="{{ route('certificados.excel') }}" class="guia-btn guia-btn-amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Descargar Excel con QRs</span>
                    </a>

                    <a href="{{ route('certificados.planilla') }}" class="guia-btn guia-btn-secondary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Planilla A4</span>
                    </a>

                    <a href="{{ url('/admin/reportes') }}" class="guia-btn guia-btn-secondary">
                        <span>Ir a Reportes</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Cuadrícula de 3 Sub-tarjetas de Pasos -->
            <div class="guia-steps-grid">
                
                <!-- Sub-tarjeta Paso 1 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num amber">1</div>
                            <div class="guia-step-title">Clasificación por Filtro</div>
                        </div>
                        <p class="guia-step-desc">
                            En el <strong>Centro de Reportes</strong>, filtre la nómina por especialidad: Despachador de Vuelo, Tripulante de Cabina o Agente de Tráfico.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Filtro por curso y cohorte</div>
                        <div>&bull; Búsqueda instantánea de alumnos</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 2 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num amber">2</div>
                            <div class="guia-step-title">Incrustación de Imágenes QR</div>
                        </div>
                        <p class="guia-step-desc">
                            A diferencia de otros sistemas que solo exportan enlaces de texto, este módulo incrusta dibujos PNG físicos (500x500 px) en la <strong>Columna L</strong> vía OpenXML.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Alto de fila ajustado a 75pt</div>
                        <div>&bull; Visible sin conexión a internet</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 3 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num amber">3</div>
                            <div class="guia-step-title">Planilla A4 para Impresión</div>
                        </div>
                        <p class="guia-step-desc">
                            Genere la planilla imprimible preformateada en tamaño estándar A4 para resguardo físico de firmas, actas notariales y encuadernación institucional.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Formato A4 listo para imprimir</div>
                        <div>&bull; Sección para sellos y rúbricas</div>
                    </div>
                </div>

            </div>

            <!-- Panel Desplegable: Detalle Técnico OpenXML -->
            <div x-show="expandirDetalleQr" class="guia-drawer">
                <div style="padding: 14px 18px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12px; color: #475569; line-height: 1.5;">
                    <strong style="color: #0f172a; display: block; margin-bottom: 4px;">
                        Funcionamiento del Motor de Generación de Códigos QR Físicos:
                    </strong>
                    Cada código QR es renderizado en memoria como imagen PNG monocromática de 500x500 píxeles. Mediante el estándar OpenXML (Office Open XML Drawing), la imagen queda incrustada como objeto gráfico vinculado a las coordenadas exactas de la celda en la <strong>Columna L</strong>. El usuario puede abrir el archivo en cualquier versión de Microsoft Excel o LibreOffice sin requerir complementos adicionales ni conexión activa a internet.
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TARJETA 4: VERIFICACIÓN PÚBLICA Y SEGURIDAD -->
        <!-- ========================================================================= -->
        <div x-show="filtroTarjeta === 'todas' || filtroTarjeta === 'verificacion'" class="guia-card">
            
            <!-- Encabezado de la Tarjeta con Ícono Integrado al Título -->
            <div class="guia-card-header">
                <div class="guia-header-left">
                    <div class="guia-icon-box indigo">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                                4. Verificación Pública y Seguridad Criptográfica
                            </h2>
                            <span class="guia-badge indigo">Acreditación Oficial</span>
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 2px 0 0 0;">
                            Protocolo de validación digital para aerolíneas comerciales, autoridades de la DGAC y empleadores aeronáuticos.
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción de la Tarjeta -->
                <div class="guia-header-actions">
                    <button type="button" @click="expandirVerificacion = !expandirVerificacion" class="guia-btn guia-btn-secondary">
                        <span x-text="expandirVerificacion ? 'Ocultar Ficha de Muestra' : 'Ver Ficha de Muestra'"></span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; border-radius: 8px; background: rgba(5, 150, 105, 0.1); color: #059669; font-size: 11.5px; font-weight: 600; border: 1px solid rgba(5, 150, 105, 0.2);">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #059669; display: inline-block;"></span>
                        <span>Rate Limit Activo: 60 req/min</span>
                    </div>
                </div>
            </div>

            <!-- Cuadrícula de 3 Sub-tarjetas de Pasos -->
            <div class="guia-steps-grid">
                
                <!-- Sub-tarjeta Paso 1 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num indigo">1</div>
                            <div class="guia-step-title">Escaneo del Código QR</div>
                        </div>
                        <p class="guia-step-desc">
                            Cualquier teléfono inteligente con cámara o lector 2D lee la URL oficial encriptada que apunta al servidor seguro de CIAC TAMEP.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Compatible con Android e iOS</div>
                        <div>&bull; Redirección HTTPS directa</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 2 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num indigo">2</div>
                            <div class="guia-step-title">Ficha de Acreditación</div>
                        </div>
                        <p class="guia-step-desc">
                            La autoridad aeronáutica o aerolínea visualiza en tiempo real el titular, C.I., folio de Libro Matriz, especialidad y condición de aprobación.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Cotejo legal inmediato</div>
                        <div>&bull; Validez oficial certificada</div>
                    </div>
                </div>

                <!-- Sub-tarjeta Paso 3 -->
                <div class="guia-step-card">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="guia-step-header">
                            <div class="guia-step-num indigo">3</div>
                            <div class="guia-step-title">Seguridad y Resguardo</div>
                        </div>
                        <p class="guia-step-desc">
                            Cada certificado cuenta con un token criptográfico UUID v4 irrepetible y un limitador de tasa a 60 peticiones/minuto para evitar extracción no autorizada.
                        </p>
                    </div>
                    <div class="guia-step-bullets">
                        <div>&bull; Token criptográfico UUID v4</div>
                        <div>&bull; Blindaje anti-scraping y DoS</div>
                    </div>
                </div>

            </div>

            <!-- Panel Desplegable: Ficha de Muestra -->
            <div x-show="expandirVerificacion" class="guia-drawer">
                <div style="max-width: 420px; margin: 0 auto; padding: 18px; border-radius: 12px; background: #0f172a; color: #ffffff; border: 1px solid #334155; display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #334155; padding-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="{{ asset('images/logo-ciac.png') }}?v=2" alt="CIAC" style="height: 24px; width: auto; object-fit: contain;">
                            <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">CIAC TAMEP</span>
                        </div>
                        <span style="font-size: 10px; font-family: monospace; background: rgba(5, 150, 105, 0.2); color: #34d399; padding: 2px 8px; border-radius: 4px; font-weight: 700;">OFICIAL Y VIGENTE</span>
                    </div>

                    <div style="text-align: center; padding: 4px 0;">
                        <span style="font-size: 10px; color: #94a3b8; text-transform: uppercase;">Estudiante Acreditado</span>
                        <div style="font-size: 14px; font-weight: 800; color: #ffffff; text-transform: uppercase; margin-top: 2px;">ARLETH CESPEDES LEDEZMA</div>
                        <div style="font-size: 11.5px; font-family: monospace; color: #94a3b8; margin-top: 2px;">C.I. 9895606</div>
                    </div>

                    <div style="padding: 12px; border-radius: 8px; background: #1e293b; border: 1px solid #334155; font-size: 11.5px; display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8;">Programa:</span>
                            <span style="font-weight: 700; color: #60a5fa;">DESPACHADOR DE VUELO</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8;">Libro Matriz:</span>
                            <span style="font-family: monospace; font-size: 10.5px;">CIAC TAMEP DV 2026 N° 001/26</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8;">Condición:</span>
                            <span style="font-weight: 700; color: #34d399;">APROBADO ✓</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>