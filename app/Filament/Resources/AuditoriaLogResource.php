<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditoriaLogResource\Pages;
use App\Models\AuditoriaLog;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditoriaLogResource extends Resource
{
    protected static ?string $model = AuditoriaLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Historial / Auditoría';
    protected static ?string $modelLabel = 'registro de auditoría';
    protected static ?string $pluralModelLabel = 'historial de auditoría';
    protected static ?int $navigationSort = 90;

    /**
     * Solo Superadministrador y Administrador pueden ver la bitácora.
     */
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    /**
     * Bitácora estrictamente de solo lectura (nadie puede crear, editar ni borrar registros).
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha y Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('evento')
                    ->label('Evento')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LOGIN' => 'info',
                        'LOGOUT' => 'gray',
                        'CREACION' => 'success',
                        'MODIFICACION' => 'warning',
                        'ELIMINACION' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'LOGIN' => 'Inicio de Sesión',
                        'LOGOUT' => 'Cierre de Sesión',
                        'CREACION' => 'Creación',
                        'MODIFICACION' => 'Modificación',
                        'ELIMINACION' => 'Eliminación',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('modulo')
                    ->label('Módulo')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'ACCESO' ? 'primary' : 'gray')
                    ->sortable(),

                TextColumn::make('usuario_nombre')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('descripcion')
                    ->label('Detalle de la Acción')
                    ->description(fn (AuditoriaLog $record): ?string => $record->codigo_referencia ? "Ref: {$record->codigo_referencia}" : null)
                    ->searchable(['descripcion', 'codigo_referencia'])
                    ->wrap(),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->fontFamily('mono')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('evento')
                    ->label('Tipo de Evento')
                    ->options([
                        'LOGIN' => 'Inicio de Sesión',
                        'LOGOUT' => 'Cierre de Sesión',
                        'CREACION' => 'Creación de Certificado',
                        'MODIFICACION' => 'Modificación de Certificado',
                        'ELIMINACION' => 'Eliminación de Certificado',
                    ]),

                SelectFilter::make('modulo')
                    ->label('Módulo')
                    ->options([
                        'ACCESO' => 'Accesos (Login / Logout)',
                        'CERTIFICADOS' => 'Certificados',
                    ]),

                SelectFilter::make('usuario_nombre')
                    ->label('Usuario')
                    ->options(fn (): array => AuditoriaLog::query()
                        ->whereNotNull('usuario_nombre')
                        ->distinct()
                        ->orderBy('usuario_nombre')
                        ->pluck('usuario_nombre', 'usuario_nombre')
                        ->toArray()),
            ])
            ->recordActions([
                Action::make('ver_detalle')
                    ->label('Detalle')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->iconButton()
                    ->tooltip('Ver detalle completo del registro')
                    ->modalHeading(fn (AuditoriaLog $record): string => "Detalle de Auditoría #{$record->id_aud}")
                    ->modalContent(fn (AuditoriaLog $record) => view('filament.auditoria.detalle', ['registro' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditoriaLogs::route('/'),
        ];
    }
}
