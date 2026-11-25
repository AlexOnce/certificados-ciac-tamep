<?php

namespace App\Filament\Resources\AuditoriaLogResource\Pages;

use App\Filament\Resources\AuditoriaLogResource;
use App\Models\AuditoriaLog;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAuditoriaLogs extends ListRecords
{
    protected static string $resource = AuditoriaLogResource::class;

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->icon('heroicon-o-list-bullet')
                ->badge(AuditoriaLog::count()),

            'accesos' => Tab::make('Accesos (Login/Logout)')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->badge(AuditoriaLog::where('modulo', 'ACCESO')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('modulo', 'ACCESO')),

            'certificados' => Tab::make('Cambios en Certificados')
                ->icon('heroicon-o-document-text')
                ->badge(AuditoriaLog::where('modulo', 'CERTIFICADOS')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('modulo', 'CERTIFICADOS')),
        ];
    }
}
