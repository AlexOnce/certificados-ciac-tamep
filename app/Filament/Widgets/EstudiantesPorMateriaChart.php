<?php

namespace App\Filament\Widgets;

use App\Models\Certificado;
use Filament\Widgets\ChartWidget;

class EstudiantesPorMateriaChart extends ChartWidget
{
    protected ?string $heading = 'Índice de Estudiantes por Materia / Especialidad';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $cursos = [
            'Despachador de Vuelo (DV)' => Certificado::where(function ($q) {
                $q->where('curso_cer', 'like', '%DESPACHADOR%')
                  ->orWhere('codigo_curso_cer', 'like', '%DV%');
            })->count(),

            'Tripulante de Cabina (TCP)' => Certificado::where(function ($q) {
                $q->where('curso_cer', 'like', '%TRIPULANTE%')
                  ->orWhere('codigo_curso_cer', 'like', '%TCP%');
            })->count(),

            'Agente de Tráfico (AGT)' => Certificado::where(function ($q) {
                $q->where('curso_cer', 'like', '%TRÁFICO%')
                  ->orWhere('curso_cer', 'like', '%TRAFICO%')
                  ->orWhere('codigo_curso_cer', 'like', '%AGT%');
            })->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Alumnos Certificados',
                    'data' => array_values($cursos),
                    'backgroundColor' => [
                        '#19499C', // Azul CIAC TAM-EP
                        '#FFCD05', // Amarillo TAM-EP
                        '#0EA5E9', // Celeste Aeronáutico
                    ],
                    'borderColor' => [
                        '#0F2750',
                        '#B45309',
                        '#0369A1',
                    ],
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => array_keys($cursos),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 2,
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
