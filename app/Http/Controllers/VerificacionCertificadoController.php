<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class VerificacionCertificadoController extends Controller
{
    public function __invoke(string $codigo)
    {
        $certificado = Certificado::query()->where('codigo_cer', $codigo)->first();

        return view('certificados.verificar', compact('certificado'));
    }

    /**
     * Genera la imagen del Código QR en formato PNG (alta resolución) o SVG (vectorial).
     * Si se pasa ?download=1, fuerza la descarga del archivo con nombre del estudiante.
     */
    public function qr(string $codigo, Request $request)
    {
        $certificado = Certificado::query()->where('codigo_cer', $codigo)->firstOrFail();
        $format = strtolower($request->query('format', 'png'));
        $download = $request->boolean('download', false);

        // QR con tamaño de 500px para máxima definición en certificados físicos impresos
        $qrCode = new QrCode(
            data: $certificado->urlVerificacion(),
            size: 500,
            margin: 15
        );

        $codeSlug = Str::slug($certificado->codigo_curso_cer, '_');
        $nombreLimpio = Str::slug($certificado->nombreCompleto() . '_' . $certificado->carnet_per_cer . '_' . $codeSlug, '_');
        $filename = "QR_{$nombreLimpio}.{$format}";

        if ($format === 'svg') {
            $content = (new SvgWriter())->write($qrCode)->getString();
            $mime = 'image/svg+xml';
        } else {
            $content = (new PngWriter())->write($qrCode)->getString();
            $mime = 'image/png';
        }

        $headers = [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ];

        if ($download) {
            $headers['Content-Disposition'] = "attachment; filename=\"{$filename}\"";
        }

        return response($content, 200, $headers);
    }

    /**
     * Genera la planilla imprimible de códigos QR (stickers / etiquetas para diplomas).
     * Permite filtrar por curso y gestión para imprimir por lotes en hoja A4.
     */
    public function planilla(Request $request)
    {
        $query = Certificado::query()->where('estado_cer', 'activo');

        if ($request->filled('curso')) {
            $query->where('curso_cer', $request->query('curso'));
        }

        if ($request->filled('gestion')) {
            $query->whereYear('fecha_cer', $request->query('gestion'));
        } else {
            $query->whereYear('fecha_cer', 2026);
        }

        $certificados = $query->orderBy('curso_cer')->orderBy('nombre_per_cer')->get();
        $cursoSeleccionado = $request->query('curso');
        $gestionSeleccionada = $request->query('gestion', '2026');

        return view('certificados.planilla', compact('certificados', 'cursoSeleccionado', 'gestionSeleccionada'));
    }

    /**
     * Asegura que todos los archivos PNG de los códigos QR estén generados físicamente en disco.
     * Retorna un mapa con el nombre y ruta local de cada certificado.
     */
    public static function asegurarArchivosQrEnDisco($certificados): array
    {
        $dir = public_path('storage/qrs_certificados');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $archivos = [];

        foreach ($certificados as $c) {
            $codeSlug = Str::slug($c->codigo_curso_cer, '_');
            $nombreSlug = Str::slug($c->nombreCompleto() . '_' . $c->carnet_per_cer . '_' . $codeSlug, '_');
            $fileName = "QR_{$nombreSlug}.png";
            $filePath = $dir . DIRECTORY_SEPARATOR . $fileName;

            $urlVerificacion = $c->urlVerificacion();

            if (!file_exists($filePath)) {
                $qrCode = new QrCode(data: $urlVerificacion, size: 500, margin: 15);
                $png = (new PngWriter())->write($qrCode)->getString();
                file_put_contents($filePath, $png);
            }

            $archivos[$c->id_cer] = [
                'nombre_archivo' => $fileName,
                'ruta_completa' => $filePath,
                'url_descarga' => route('certificados.qr', ['codigo' => $c->codigo_cer, 'format' => 'png', 'download' => 1]),
                'url_verificar' => $urlVerificacion,
            ];
        }

        return $archivos;
    }

    /**
     * Exporta el listado completo oficial a Excel con las columnas exactas para la imprenta / CIAC:
     * Datos del alumno, N° correlativo, nombre del archivo PNG, ruta local y enlace directo al QR.
     */
    public function exportarExcelQr(Request $request)
    {
        $query = Certificado::query()->where('estado_cer', 'activo');

        if ($request->filled('curso')) {
            $query->where('curso_cer', $request->query('curso'));
        }

        if ($request->filled('gestion')) {
            $query->whereYear('fecha_cer', $request->query('gestion'));
        } else {
            $query->whereYear('fecha_cer', 2026);
        }

        $certificados = $query->orderBy('curso_cer')->orderBy('carnet_per_cer')->get();
        $nombreArchivo = 'Listado_Alumnos_Con_QR_CIAC_TAMEP_' . date('Ymd_His') . '.xlsx';

        return \App\Services\ExcelConQrService::generarExcel($certificados, $nombreArchivo);
    }

    /**
     * Descarga la plantilla oficial en Excel (.xlsx) para la carga masiva de alumnos aprobados.
     */
    public function descargarPlantilla()
    {
        return Response::streamDownload(function () {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $headerStyle = (new Style())
                ->setFontBold()
                ->setFontColor(Color::WHITE)
                ->setBackgroundColor('19499C');

            $headers = [
                'APELLIDO PATERNO',
                'APELLIDO MATERNO',
                'NOMBRES',
                'CARNET DE IDENTIDAD',
                'CODIGO DEL CURSO',
                'NOMBRE DEL CURSO',
                'PERIODO Y GESTION',
                'PROMEDIO FINAL',
                'ESTADO ACADEMICO',
                'TIPO DE DOCUMENTO EXPEDIDO',
                'REGISTRO DIPLOMA O CERTIFICADO',
                'REGISTRO CERTIFICADO DE ESTUDIOS',
                'FECHA DE EMISION (AAAA-MM-DD)',
            ];
            $writer->addRow(Row::fromValues($headers, $headerStyle));

            $ejemplo1 = [
                'CESPEDES',
                'LEDEZMA',
                'ARLETH',
                '9895606',
                'DV/2026',
                'DESPACHADOR DE VUELO',
                '02/mar al 30/nov 2026',
                '1',
                'APROBADO',
                'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                'CIAC TAMEP DV 2026 CI 9895606 N° 001/26',
                'N° 001-DV 2026',
                '2026-12-18',
            ];
            $writer->addRow(Row::fromValues($ejemplo1));

            $ejemplo2 = [
                'ALIAGA',
                'CONDORI',
                'KATERIN MILENA',
                '11066284',
                'TCP/2026',
                'TRIPULANTE DE CABINA',
                '02/mar al 30/oct 2026',
                '1',
                'APROBADO',
                'DIPLOMA Y CERTIFICADO DE APROBACIÓN',
                'CIAC TAMEP TCP 2026 CI 11066284 N° 001/26',
                'N° 001-TCP 2026',
                '2026-12-18',
            ];
            $writer->addRow(Row::fromValues($ejemplo2));

            $ejemplo3 = [
                'APAZA',
                'MAMANI',
                'LIZ LEYDE',
                '13084026',
                'AGT/2026',
                'AGENTE DE TRÁFICO',
                '31/ago al 30/nov 2026',
                '1',
                'APROBADO',
                'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS',
                'CIAC TAMEP AGT 2026 CI 13084026 Nº 001/26',
                'N° 001-AGT 2026',
                '2026-12-18',
            ];
            $writer->addRow(Row::fromValues($ejemplo3));

            $writer->close();
        }, 'Plantilla_Alumnos_Aprobados_CIAC_TAMEP.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
