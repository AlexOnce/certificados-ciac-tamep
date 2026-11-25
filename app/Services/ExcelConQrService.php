<?php

namespace App\Services;

use App\Http\Controllers\VerificacionCertificadoController;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ExcelConQrService
{
    /**
     * Genera un archivo Microsoft Excel (.xlsx) nativo con las imágenes físicas de los códigos QR
     * incrustadas directamente dentro de las celdas correspondientes (OpenXML Drawing).
     */
    public static function generarExcel(Collection $certificados, ?string $nombreArchivo = null): StreamedResponse
    {
        $archivosQr = VerificacionCertificadoController::asegurarArchivosQrEnDisco($certificados);

        if (!$nombreArchivo) {
            $nombreArchivo = 'Listado_Alumnos_Con_QR_CIAC_TAMEP_' . date('Ymd_His') . '.xlsx';
        }

        $tempDir = storage_path('app/temp_excel');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . DIRECTORY_SEPARATOR . 'ciac_export_' . uniqid() . '.xlsx';
        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }

        $zip = new ZipArchive();
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'No se pudo crear el contenedor OpenXML para Excel.');
        }

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Default Extension="png" ContentType="image/png"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rootRels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $wb = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Alumnos y QRs CIAC TAMEP" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $wb);

        // 5. xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="3">
    <font><sz val="10"/><name val="Segoe UI"/><family val="2"/></font>
    <font><b/><sz val="10"/><name val="Segoe UI"/><family val="2"/></font>
    <font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/><family val="2"/></font>
  </fonts>
  <fills count="4">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF19499C"/></patternFill></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FFF8FAFC"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color rgb="FFCBD5E1"/></left>
      <right style="thin"><color rgb="FFCBD5E1"/></right>
      <top style="thin"><color rgb="FFCBD5E1"/></top>
      <bottom style="thin"><color rgb="FFCBD5E1"/></bottom>
    </border>
  </borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <cellXfs count="5">
    <!-- 0: normal left -->
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment vertical="center"/>
    </xf>
    <!-- 1: header Azul CIAC -->
    <xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center" wrapText="1"/>
    </xf>
    <!-- 2: normal center -->
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <!-- 3: bold center -->
    <xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <!-- 4: bold left -->
    <xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1">
      <alignment vertical="center"/>
    </xf>
  </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/_rels/sheet1.xml.rels
        $sheetRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/>
</Relationships>';
        $zip->addFromString('xl/worksheets/_rels/sheet1.xml.rels', $sheetRels);

        // Columnas oficiales
        $headers = [
            'N°',
            'N° CERTIFICADO',
            'APELLIDO PATERNO',
            'APELLIDO MATERNO',
            'NOMBRES',
            'CARNET (CI)',
            'CURSO / ESPECIALIDAD',
            'CÓDIGO CURSO',
            'PERIODO Y GESTIÓN',
            'CONDICIÓN',
            'CÓDIGO QR ÚNICO',
            'CÓDIGO QR (IMAGEN FÍSICA)',
            'ENLACE VERIFICACIÓN PÚBLICA',
        ];

        $colsXml = '<cols>
  <col min="1" max="1" width="6" customWidth="1"/>
  <col min="2" max="2" width="22" customWidth="1"/>
  <col min="3" max="3" width="18" customWidth="1"/>
  <col min="4" max="4" width="18" customWidth="1"/>
  <col min="5" max="5" width="24" customWidth="1"/>
  <col min="6" max="6" width="14" customWidth="1"/>
  <col min="7" max="7" width="26" customWidth="1"/>
  <col min="8" max="8" width="16" customWidth="1"/>
  <col min="9" max="9" width="24" customWidth="1"/>
  <col min="10" max="10" width="14" customWidth="1"/>
  <col min="11" max="11" width="18" customWidth="1"/>
  <col min="12" max="12" width="16" customWidth="1"/>
  <col min="13" max="13" width="45" customWidth="1"/>
</cols>';

        $sheetData = '<sheetData>';

        // Fila 1: Cabecera
        $sheetData .= '<row r="1" ht="28" customHeight="1">';
        $colLetters = ['A','B','C','D','E','F','G','H','I','J','K','L','M'];
        foreach ($headers as $idx => $h) {
            $ref = $colLetters[$idx] . '1';
            $val = htmlspecialchars($h, ENT_XML1, 'UTF-8');
            $sheetData .= "<c r=\"{$ref}\" s=\"1\" t=\"inlineStr\"><is><t>{$val}</t></is></c>";
        }
        $sheetData .= '</row>';

        $drawingXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $drawingRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';

        $imgIdx = 1;
        foreach ($certificados as $i => $cert) {
            $rowNum = $i + 2;
            // Altura 75pt para que la imagen física del QR quede perfectamente encuadrada en la celda
            $sheetData .= "<row r=\"{$rowNum}\" ht=\"75\" customHeight=\"1\">";

            $qrInfo = $archivosQr[$cert->id_cer] ?? [];

            $values = [
                $i + 1,
                $cert->numeroRegistroNormalizado(),
                $cert->apellido_paterno_cer ?? '',
                $cert->apellido_materno_cer ?? '',
                $cert->nombre_per_cer ?? '',
                $cert->carnet_per_cer ?? '',
                $cert->curso_cer ?? '',
                $cert->codigo_curso_cer ?? '',
                $cert->periodo_gestion_cer ?? '',
                $cert->estado_academico_cer ?? 'APROBADO',
                $cert->codigo_cer ?? '',
                '', // Columna L (Índice 11) reservada para la imagen del QR incrustada
                $qrInfo['url_verificar'] ?? '',
            ];

            foreach ($values as $idx => $v) {
                $ref = $colLetters[$idx] . $rowNum;
                $style = '0';
                if ($idx === 0 || $idx === 5 || $idx === 7 || $idx === 9) $style = '2'; // normal centrado
                elseif ($idx === 1 || $idx === 10) $style = '3'; // negrita centrado
                elseif ($idx === 2 || $idx === 3 || $idx === 4) $style = '4'; // negrita izquierda

                // Mitigación de Inyección de Fórmulas en Excel (=, +, -, @)
                $vStr = (string)$v;
                if ($idx > 0 && isset($vStr[0]) && in_array($vStr[0], ['=', '+', '-', '@'], true)) {
                    $vStr = "'" . $vStr;
                }

                $val = htmlspecialchars($vStr, ENT_XML1, 'UTF-8');
                $sheetData .= "<c r=\"{$ref}\" s=\"{$style}\" t=\"inlineStr\"><is><t>{$val}</t></is></c>";
            }
            $sheetData .= '</row>';

            // Incrustación de la imagen del código QR en la columna L (Índice 11)
            if (!empty($qrInfo['ruta_completa']) && file_exists($qrInfo['ruta_completa'])) {
                $mediaFile = "qr_img_{$imgIdx}.png";
                $zip->addFile($qrInfo['ruta_completa'], "xl/media/{$mediaFile}");

                $rId = "rId{$imgIdx}";
                $drawingRels .= "<Relationship Id=\"{$rId}\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/image\" Target=\"../media/{$mediaFile}\"/>";

                $rowFrom = $rowNum - 1;
                $rowTo = $rowNum;
                $drawingXml .= '
  <xdr:twoCellAnchor editAs="oneCell">
    <xdr:from>
      <xdr:col>11</xdr:col>
      <xdr:colOff>80000</xdr:colOff>
      <xdr:row>' . $rowFrom . '</xdr:row>
      <xdr:rowOff>40000</xdr:rowOff>
    </xdr:from>
    <xdr:to>
      <xdr:col>12</xdr:col>
      <xdr:colOff>-80000</xdr:colOff>
      <xdr:row>' . $rowTo . '</xdr:row>
      <xdr:rowOff>-40000</xdr:rowOff>
    </xdr:to>
    <xdr:pic>
      <xdr:nvPicPr>
        <xdr:cNvPr id="' . $imgIdx . '" name="QR_' . htmlspecialchars($cert->codigo_cer) . '"/>
        <xdr:cNvPicPr><a:picLocks noChangeAspect="1"/></xdr:cNvPicPr>
      </xdr:nvPicPr>
      <xdr:blipFill>
        <a:blip r:embed="' . $rId . '"/>
        <a:stretch><a:fillRect/></a:stretch>
      </xdr:blipFill>
      <xdr:spPr>
        <a:xfrm><a:off x="0" y="0"/><a:ext cx="850000" cy="850000"/></a:xfrm>
        <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
      </xdr:spPr>
    </xdr:pic>
    <xdr:clientData/>
  </xdr:twoCellAnchor>';

                $imgIdx++;
            }
        }

        $sheetData .= '</sheetData>';
        $drawingXml .= '</xdr:wsDr>';
        $drawingRels .= '</Relationships>';

        $zip->addFromString('xl/drawings/drawing1.xml', $drawingXml);
        $zip->addFromString('xl/drawings/_rels/drawing1.xml.rels', $drawingRels);

        $sheet1 = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  ' . $colsXml . '
  ' . $sheetData . '
  <drawing r:id="rId1"/>
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet1);

        $zip->close();

        return response()->streamDownload(function () use ($tempFile) {
            if (file_exists($tempFile)) {
                readfile($tempFile);
                @unlink($tempFile);
            }
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
