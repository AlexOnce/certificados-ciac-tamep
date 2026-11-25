<?php

namespace App\Filament\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Response;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Panel de control';

    protected static ?string $title = 'Panel de control CIAC TAMEP';

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadTemplate')
                ->label('Descargar Plantilla CIAC')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return Response::streamDownload(function () {
                        $writer = new Writer();
                        $writer->openToFile('php://output');

                        $border = new Border(
                            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
                            new BorderPart(Border::TOP, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
                            new BorderPart(Border::LEFT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID),
                            new BorderPart(Border::RIGHT, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                        );

                        $headerStyle = (new Style())
                            ->setFontBold()
                            ->setFontColor(Color::WHITE)
                            ->setBackgroundColor('19499C')
                            ->setBorder($border);

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
                            'FECHA DE EMISION'
                        ];
                        $writer->addRow(Row::fromValues($headers, $headerStyle));

                        $styleCeleste = (new Style())->setBackgroundColor('E8F4F8')->setBorder($border);
                        $styleBlanco = (new Style())->setBackgroundColor('FFFFFF')->setBorder($border);

                        $ejemplos = [
                            ['CESPEDES', 'LEDEZMA', 'ARLETH', '9895606', 'DV/2026', 'DESPACHADOR DE VUELO', '02/mar al 30/nov 2026', '1', 'APROBADO', 'DIPLOMA Y CERTIFICADO DE ESTUDIOS', 'CIAC TAMEP DV 2026 CI 9895606 N° 001/26', 'N° 001-DV 2026', '2026-12-18'],
                            ['ALIAGA', 'CONDORI', 'KATERIN MILENA', '11066284', 'TCP/2026', 'TRIPULANTE DE CABINA', '02/mar al 30/oct 2026', '1', 'APROBADO', 'DIPLOMA Y CERTIFICADO DE APROBACIÓN', 'CIAC TAMEP TCP 2026 CI 11066284 N° 001/26', 'N° 001-TCP 2026', '2026-12-18'],
                            ['APAZA', 'MAMANI', 'LIZ LEYDE', '13084026', 'AGT/2026', 'AGENTE DE TRÁFICO', '31/ago al 30/nov 2026', '1', 'APROBADO', 'CERTIFICADO DE APROBACIÓN Y CERTIFICADO DE ESTUDIOS', 'CIAC TAMEP AGT 2026 CI 13084026 Nº 001/26', 'N° 001-AGT 2026', '2026-12-18'],
                        ];

                        foreach ($ejemplos as $idx => $row) {
                            $style = ($idx % 2 !== 0) ? $styleCeleste : $styleBlanco;
                            $writer->addRow(Row::fromValues($row, $style));
                        }

                        $writer->close();
                    }, 'Plantilla_Alumnos_Aprobados_CIAC_TAMEP.xlsx', [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
                }),
        ];
    }
}
