<?php

namespace App\Filament\Widgets;

use App\Models\Certificado;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CertificadosOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalAlumnos = Certificado::count();

        // Despachador de Vuelo
        $totalDV = Certificado::where(function ($q) {
            $q->where('curso_cer', 'like', '%DESPACHADOR%')
              ->orWhere('codigo_curso_cer', 'like', '%DV%');
        })->count();

        // Tripulante de Cabina
        $totalTCP = Certificado::where(function ($q) {
            $q->where('curso_cer', 'like', '%TRIPULANTE%')
              ->orWhere('codigo_curso_cer', 'like', '%TCP%');
        })->count();

        // Agente de Tráfico
        $totalAGT = Certificado::where(function ($q) {
            $q->where('curso_cer', 'like', '%TRÁFICO%')
              ->orWhere('curso_cer', 'like', '%TRAFICO%')
              ->orWhere('codigo_curso_cer', 'like', '%AGT%');
        })->count();

        return [
            Stat::make('Total Alumnos', $totalAlumnos)
                ->color('primary'),

            Stat::make('Despachador de Vuelo', $totalDV)
                ->color('info'),

            Stat::make('Tripulante de Cabina', $totalTCP)
                ->color('warning'),

            Stat::make('Agente de Tráfico', $totalAGT)
                ->color('success'),
        ];
    }
}
