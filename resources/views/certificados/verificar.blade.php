<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $certificado ? $certificado->curso_cer . ' - Verificación Oficial' : 'Verificación de Certificado' }} -
        CIAC TAMEP</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ciac.png') }}?v=2">
    <style>
        :root {
            --azul-tam-dark: #0A1C38;
            --azul-tam-primario: #19499C;
            --azul-tam-card: #0F2750;
            --azul-tam-cell: #153568;
            --dorado-tam: #FFCD05;
            --verde-aprobado: #4ADE80;
            --rojo-anulado: #F87171;
            --texto-blanco: #FFFFFF;
            --texto-secundario: #94A3B8;
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --font-mono: 'Consolas', 'Roboto Mono', 'SF Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            min-height: 100vh;
            background-color: var(--azul-tam-dark);
            /* Patrón institucional con marcas sutiles 'CIAC TAMEP' */
            background-image:
                radial-gradient(circle at 50% 12%, rgba(25, 73, 156, 0.45) 0%, rgba(10, 28, 56, 0.98) 100%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='100' viewBox='0 0 180 100'%3E%3Ctext x='25' y='42' fill='%23ffffff' fill-opacity='0.035' font-family='sans-serif' font-weight='800' font-size='14' letter-spacing='1.5' transform='rotate(-22 25 42)'%3ECIAC TAMEP%3C/text%3E%3Ctext x='110' y='90' fill='%23ffffff' fill-opacity='0.035' font-family='sans-serif' font-weight='800' font-size='14' letter-spacing='1.5' transform='rotate(-22 110 90)'%3ECIAC TAMEP%3C/text%3E%3C/svg%3E");
            background-repeat: repeat;
            color: var(--texto-blanco);
            font-family: var(--font-main);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 24px 16px 20px 16px;
        }

        .main-wrapper {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 1. Cabecera Institucional Oficial (Logos Transparentes) */
        .inst-header {
            width: 100%;
            text-align: center;
            margin-bottom: 22px;
        }

        .logos-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            margin-bottom: 14px;
        }

        .logo-box {
            width: 74px;
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: transparent;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.45));
        }

        .logo-box.logo-box-ciac {
            width: auto;
            height: 74px;
            background: transparent;
        }

        .logo-box.logo-box-ciac img {
            width: auto;
            max-width: 140px;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.45));
        }

        .logo-divider {
            width: 1.5px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, rgba(255, 205, 5, 0.6), transparent);
        }

        .inst-title {
            font-size: 13.5px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #ffffff;
            text-transform: uppercase;
            line-height: 1.35;
        }

        .inst-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: var(--dorado-tam);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* 2. Tarjeta Oficial de Certificación Digital */
        .cert-card {
            width: 100%;
            background: var(--azul-tam-card);
            border-radius: 14px;
            border: 1px solid rgba(255, 205, 5, 0.35);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 30px rgba(25, 73, 156, 0.25);
            overflow: hidden;
            position: relative;
        }

        .cert-card.anulado {
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 30px rgba(239, 68, 68, 0.2);
        }

        /* Franja superior dorada TAM */
        .card-gold-bar {
            height: 4px;
            background: linear-gradient(90deg, #D4AF37 0%, var(--dorado-tam) 50%, #D4AF37 100%);
        }

        .card-gold-bar.anulado {
            background: linear-gradient(90deg, #991B1B 0%, var(--rojo-anulado) 50%, #991B1B 100%);
        }

        .card-body {
            padding: 22px 20px;
        }

        /* Barra Superior Formal del Registro */
        .card-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 11px;
        }

        .meta-label {
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--dorado-tam);
            text-transform: uppercase;
        }

        .meta-code {
            color: var(--texto-secundario);
            font-family: var(--font-mono);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Sección del Curso / Programa */
        .course-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .course-caption {
            font-size: 10px;
            font-weight: 700;
            color: var(--texto-secundario);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .course-title {
            font-size: 22px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .course-code-badge {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            font-size: 11.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 1px;
        }

        /* Titular / Estudiante */
        .student-highlight-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 13px 16px;
            margin-bottom: 16px;
            text-align: center;
        }

        .field-sublabel {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--texto-secundario);
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .student-fullname {
            font-size: 17.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            line-height: 1.3;
        }

        /* Cuadrícula de Datos Oficiales 2x2 */
        .data-grid-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .grid-cell {
            background: var(--azul-tam-cell);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cell-label {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--dorado-tam);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .cell-value {
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.3px;
            font-family: var(--font-mono);
        }

        .cell-value.gold-number {
            color: var(--dorado-tam);
            font-size: 16px;
        }

        .cell-value.aprobado {
            color: var(--verde-aprobado);
            letter-spacing: 1px;
        }

        .cell-value.anulado {
            color: var(--rojo-anulado);
            letter-spacing: 1px;
        }

        .cell-value.periodo {
            font-size: 13px;
            font-family: var(--font-main);
            font-weight: 700;
            line-height: 1.35;
        }

        /* Nota al pie de la credencial */
        .card-footer-note {
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            font-size: 10.5px;
            color: var(--texto-secundario);
            letter-spacing: 0.4px;
        }

        /* Alerta de Documento Anulado / No Encontrado */
        .alert-card-message {
            background: rgba(220, 38, 38, 0.18);
            border: 1px solid rgba(248, 113, 113, 0.45);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            margin-bottom: 16px;
            font-size: 13px;
            line-height: 1.5;
            color: #FECACA;
        }

        /* 3. Pie Institucional General */
        .footer-inst {
            width: 100%;
            text-align: center;
            padding-top: 22px;
            margin-top: auto;
        }

        .footer-ciac {
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.85);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .footer-tamep {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--dorado-tam);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .footer-pais {
            font-size: 9.5px;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        @media (max-width: 380px) {
            .logos-container {
                gap: 12px;
            }

            .logo-box {
                width: 62px;
                height: 62px;
            }

            .logo-box.logo-box-ciac {
                width: auto;
                height: 62px;
            }

            .inst-title {
                font-size: 12px;
            }

            .course-title {
                font-size: 19px;
            }

            .student-fullname {
                font-size: 16px;
            }

            .cell-value {
                font-size: 13.5px;
            }
        }
    </style>
</head>

<body>

    <div class="main-wrapper">

        <!-- 1. Cabecera Institucional Oficial con Dualidad TAM-EP y CIAC -->
        <header class="inst-header">
            <div class="logos-container">
                <!-- Logo TAM Empresa Pública -->
                <div class="logo-box" title="Transporte Aéreo Militar Empresa Pública">
                    <img src="{{ asset('images/logo-tam.png') }}" alt="Logo TAMEP">
                </div>

                <div class="logo-divider"></div>

                <!-- Logo CIAC -->
                <div class="logo-box logo-box-ciac" title="Centro de Instrucción de Aeronáutica Civil">
                    <img src="{{ asset('images/logo-ciac.png') }}?v=2" alt="Emblema CIAC">
                </div>
            </div>

            <h1 class="inst-title">Centro de Instrucción de Aeronáutica Civil</h1>
            <h2 class="inst-subtitle">Transporte Aéreo Militar Empresa Pública</h2>
        </header>

        @if ($certificado && $certificado->estado_cer === 'activo')

            <!-- 2. Tarjeta Oficial de Certificación Digital -->
            <main class="cert-card">
                <!-- Franja dorada de distinción institucional TAM -->
                <div class="card-gold-bar"></div>

                <div class="card-body">

                    <!-- Barra Superior Formal del Registro -->
                    <div class="card-meta-bar">
                        <span class="meta-label">Acreditación Académica Oficial</span>
                        <span class="meta-code">{{ $certificado->codigo_cer }}</span>
                    </div>

                    <!-- Cabecera del Curso / Especialidad -->
                    <div class="course-section course-header">
                        <div class="course-caption">PROGRAMA ACADÉMICO</div>
                        <h3 class="course-title">{{ $certificado->curso_cer }}</h3>
                        @if(!empty($certificado->codigo_curso_cer))
                            <span class="course-code-badge">{{ $certificado->codigo_curso_cer }}</span>
                        @endif
                    </div>

                    <!-- Titular de la Certificación -->
                    <div class="student-highlight-box">
                        <div class="field-sublabel">ESTUDIANTE TITULAR</div>
                        <div class="student-fullname">{{ $certificado->nombreCompleto() }}</div>
                    </div>

                    <!-- Datos Oficiales en Cuadrícula 2x2 -->
                    <div class="data-grid-two">
                        <div class="grid-cell">
                            <span class="cell-label">CÉDULA DE IDENTIDAD</span>
                            <span class="cell-value">{{ $certificado->carnet_per_cer }}</span>
                        </div>

                        <div class="grid-cell">
                            <span class="cell-label">N° DE CERTIFICADO</span>
                            <span class="cell-value gold-number">{{ $certificado->numeroRegistroNormalizado() }}</span>
                        </div>

                        <div class="grid-cell">
                            <span class="cell-label">CONDICIÓN ACADÉMICA</span>
                            <span class="cell-value aprobado">{{ $certificado->estado_academico_cer ?: 'APROBADO' }}</span>
                        </div>

                        <div class="grid-cell">
                            <span class="cell-label">PERIODO DEL CURSO</span>
                            <span
                                class="cell-value periodo">{{ $certificado->periodo_gestion_cer ?: 'Gestión 2026' }}</span>
                        </div>
                    </div>

                    <!-- Nota Institucional al pie de la tarjeta -->
                    <div class="card-footer-note">
                        Registro oficial autenticado en el libro de actas académicas del CIAC TAMEP
                    </div>

                </div>
            </main>

        @elseif ($certificado)

            <!-- Caso Documento Anulado -->
            <main class="cert-card anulado">
                <div class="card-gold-bar anulado"></div>

                <div class="card-body">
                    <div class="card-meta-bar">
                        <span class="meta-label" style="color: var(--rojo-anulado);">Control de Validez</span>
                        <span class="meta-code">{{ $certificado->codigo_cer }}</span>
                    </div>

                    <div class="course-header">
                        <div class="course-caption">PROGRAMA ACADÉMICO</div>
                        <h3 class="course-title">{{ $certificado->curso_cer }}</h3>
                    </div>

                    <div class="alert-card-message">
                        Este certificado ha sido <strong>ANULADO O DADO DE BAJA</strong> en los registros oficiales del CIAC
                        TAMEP. Carece de validez legal y académica.
                    </div>

                    <div class="student-highlight-box">
                        <div class="field-sublabel">ESTUDIANTE TITULAR</div>
                        <div class="student-fullname">{{ $certificado->nombreCompleto() }}</div>
                    </div>

                    <div class="data-grid-two">
                        <div class="grid-cell">
                            <span class="cell-label">CÉDULA DE IDENTIDAD</span>
                            <span class="cell-value">{{ $certificado->carnet_per_cer }}</span>
                        </div>
                        <div class="grid-cell">
                            <span class="cell-label">CONDICIÓN ACADÉMICA</span>
                            <span class="cell-value anulado">ANULADO</span>
                        </div>
                    </div>
                </div>
            </main>

        @else

            <!-- Caso Registro No Encontrado -->
            <main class="cert-card anulado">
                <div class="card-gold-bar anulado"></div>

                <div class="card-body">
                    <div class="card-meta-bar">
                        <span class="meta-label" style="color: var(--rojo-anulado);">Consulta de Registro</span>
                        <span class="meta-code">NO REGISTRADO</span>
                    </div>

                    <div class="course-header">
                        <h3 class="course-title">DOCUMENTO NO ENCONTRADO</h3>
                    </div>

                    <div class="alert-card-message">
                        El código consultado no coincide con ningún certificado emitido en la base de datos oficial del
                        Centro de Instrucción de Aeronáutica Civil (CIAC TAMEP).
                    </div>
                </div>
            </main>

        @endif

    </div>

    <!-- 3. Pie Institucional General -->
    <footer class="footer-inst">
        <div class="footer-ciac">CENTRO DE INSTRUCCIÓN DE AERONÁUTICA CIVIL</div>
        <div class="footer-tamep">TRANSPORTE AÉREO MILITAR EMPRESA PÚBLICA - TAMEP</div>
        <div class="footer-pais">ESTADO PLURINACIONAL DE BOLIVIA</div>
    </footer>

</body>

</html>