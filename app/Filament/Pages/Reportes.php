<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use App\Models\Certificado;
use App\Models\Curso;
use App\Http\Controllers\VerificacionCertificadoController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;

class Reportes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reportes y Clasificación';
    protected static ?int $navigationSort = 3;
    protected ?string $heading = 'Reportes y Clasificación CIAC TAMEP';
    protected ?string $subheading = 'Filtre y exporte a Excel los registros con sus códigos QR.';
    protected string $view = 'filament.pages.reportes';

    public function table(Table $table): Table
    {
        return $table
            ->query(Certificado::query()->where('estado_cer', 'activo'))
            ->defaultSort('curso_cer')
            ->columns([
                TextColumn::make('numero_registro')
                    ->label('N° Certificado')
                    ->state(fn (Certificado $record): string => $record->numeroRegistroNormalizado())
                    ->badge()
                    ->color('primary')
                    ->searchable(query: fn (Builder $query, string $search) => $query->where('registro_diploma_cer', 'like', "%{$search}%"))
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('registro_diploma_cer', $direction)),

                TextColumn::make('nombre_completo')
                    ->label('Estudiante')
                    ->state(fn (Certificado $record): string => $record->nombreCompleto())
                    ->searchable(['nombre_per_cer', 'apellido_paterno_cer', 'apellido_materno_cer'])
                    ->weight('bold')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('apellido_paterno_cer', $direction)),

                TextColumn::make('carnet_per_cer')
                    ->label('Carnet (CI)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('curso_cer')
                    ->label('Curso / Especialidad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('codigo_curso_cer')
                    ->label('Código Curso')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periodo_gestion_cer')
                    ->label('Periodo Académico')
                    ->toggleable(),

                TextColumn::make('estado_academico_cer')
                    ->label('Condición')
                    ->badge()
                    ->color(fn (string $state): string => match (strtoupper($state)) {
                        'APROBADO' => 'success',
                        'REPROBADO' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('codigo_cer')
                    ->label('Código QR')
                    ->copyable()
                    ->copyMessage('Código QR copiado al portapapeles')
                    ->url(fn (Certificado $record): string => $record->urlVerificacion())
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->weight('medium'),
            ])
            ->filters([
                Filter::make('curso_filtro')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Select::make('curso_cer')
                            ->label('Curso / Especialidad')
                            ->placeholder('Todos los cursos')
                            ->options(fn (): array => Curso::opcionesParaFiltro())
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('codigo_curso_cer', null)),

                        Select::make('codigo_curso_cer')
                            ->label('Código Curso')
                            ->placeholder('Todos los códigos')
                            ->options(function (Get $get): array {
                                $cursoSeleccionado = $get('curso_cer');

                                $codigosCert = Certificado::query()
                                    ->where('estado_cer', 'activo')
                                    ->whereNotNull('codigo_curso_cer')
                                    ->where('codigo_curso_cer', '!=', '')
                                    ->when(filled($cursoSeleccionado), fn (Builder $q) => $q->where('curso_cer', $cursoSeleccionado))
                                    ->distinct()
                                    ->orderBy('codigo_curso_cer')
                                    ->pluck('codigo_curso_cer', 'codigo_curso_cer')
                                    ->toArray();

                                $codigosCursos = Curso::query()
                                    ->whereNotNull('codigo_cur')
                                    ->where('codigo_cur', '!=', '')
                                    ->when(filled($cursoSeleccionado), fn (Builder $q) => $q->where('nombre_cur', $cursoSeleccionado))
                                    ->orderBy('codigo_cur')
                                    ->pluck('codigo_cur', 'codigo_cur')
                                    ->toArray();

                                $todos = array_merge($codigosCursos, $codigosCert);
                                ksort($todos);

                                return $todos;
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['curso_cer'] ?? null),
                                fn (Builder $q) => $q->where('curso_cer', $data['curso_cer'])
                            )
                            ->when(
                                filled($data['codigo_curso_cer'] ?? null),
                                fn (Builder $q) => $q->where('codigo_curso_cer', $data['codigo_curso_cer'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (filled($data['curso_cer'] ?? null)) {
                            $indicators['curso_cer'] = 'Curso: ' . $data['curso_cer'];
                        }
                        if (filled($data['codigo_curso_cer'] ?? null)) {
                            $indicators['codigo_curso_cer'] = 'Código Curso: ' . $data['codigo_curso_cer'];
                        }
                        return $indicators;
                    }),

                SelectFilter::make('gestion')
                    ->label('Gestión Académica')
                    ->placeholder('Todas las gestiones')
                    ->options(function (): array {
                        $years = Certificado::query()
                            ->selectRaw('YEAR(fecha_cer) as year')
                            ->whereNotNull('fecha_cer')
                            ->distinct()
                            ->orderByDesc('year')
                            ->pluck('year', 'year')
                            ->toArray();

                        $options = [];
                        foreach ($years as $y) {
                            $options[(string) $y] = "Gestión {$y}";
                        }

                        return $options;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return !empty($data['value'])
                            ? $query->whereYear('fecha_cer', $data['value'])
                            : $query;
                    }),

                SelectFilter::make('estado_academico_cer')
                    ->label('Condición')
                    ->placeholder('Todas las condiciones')
                    ->options([
                        'APROBADO' => 'Aprobado',
                        'REPROBADO' => 'Reprobado',
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->headerActions([
                Action::make('exportarExcelFiltrado')
                    ->label('Exportar Filtrados a Excel (.xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->tooltip('Descarga en Excel los estudiantes captados por el filtro actual junto con sus códigos QR generados')
                    ->action(function () {
                        $certificados = $this->getFilteredTableQuery()->get();
                        return $this->generarDescargaExcel($certificados, 'Filtrados');
                    }),
            ])
            ->bulkActions([
                BulkAction::make('exportarSeleccionados')
                    ->label('Exportar Seleccionados a Excel (.xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Collection $records) {
                        return $this->generarDescargaExcel($records, 'Seleccionados');
                    }),
            ]);
    }

    /**
     * Genera el archivo Excel oficial (.xlsx) con las imágenes físicas de los códigos QR incrustadas directamente en las celdas.
     */
    protected function generarDescargaExcel($certificados, string $sufijo = 'Filtrados')
    {
        $nombreArchivo = 'Listado_Alumnos_Con_QR_CIAC_TAMEP_' . date('Ymd_His') . '.xlsx';
        return \App\Services\ExcelConQrService::generarExcel(collect($certificados), $nombreArchivo);
    }
}
