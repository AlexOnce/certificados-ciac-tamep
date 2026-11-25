<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificadoResource\Pages;
use App\Models\Certificado;
use App\Models\Curso;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema as DbSchema;
use Illuminate\Support\Str;

class CertificadoResource extends Resource
{
    protected static ?string $model = Certificado::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Certificados CIAC';
    protected static ?string $modelLabel = 'certificado';
    protected static ?string $pluralModelLabel = 'certificados';
    protected static ?int $navigationSort = 1;

    /**
     * Genera automáticamente los sub-ítems de cada curso activo debajo de "Certificados CIAC" en el sidebar.
     */
    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        if (!static::canViewAny()) {
            return $items;
        }

        // Mantener el ítem padre activo solo cuando no hay un curso específico seleccionado
        if (isset($items[0])) {
            $items[0]->isActiveWhen(
                fn (): bool => request()->routeIs(static::getRouteBaseName() . '.*') && request()->missing('curso')
            );
        }

        try {
            if (!DbSchema::hasTable('cursos')) {
                return $items;
            }

            $cursos = Curso::query()
                ->where('activo_cur', true)
                ->orderBy('nombre_cur')
                ->orderBy('codigo_cur')
                ->get();

            $conteoNombres = $cursos->countBy('nombre_cur');
            $orden = 1;

            foreach ($cursos as $curso) {
                $tieneHomonimos = ($conteoNombres[$curso->nombre_cur] ?? 0) > 1;
                $nombreBonito = Str::title(mb_strtolower($curso->nombre_cur, 'UTF-8'));
                $etiqueta = $tieneHomonimos && filled($curso->codigo_cur)
                    ? "{$nombreBonito} ({$curso->codigo_cur})"
                    : $nombreBonito;

                $cantidad = $curso->cantidadCertificados($tieneHomonimos);
                $cursoId = (string) $curso->id_cur;

                $items[] = NavigationItem::make($etiqueta)
                    ->group(static::getNavigationGroup())
                    ->parentItem(static::getNavigationLabel())
                    ->sort($orden++)
                    ->badge($cantidad > 0 ? (string) $cantidad : null, 'primary')
                    ->url(static::getUrl('index', ['curso' => $curso->id_cur]))
                    ->isActiveWhen(
                        fn (): bool => request()->routeIs(static::getRouteBaseName() . '.index')
                            && (string) request()->query('curso') === $cursoId
                    );
            }
        } catch (\Throwable) {
            // Si la tabla cursos aún no existe, se muestran solo los ítems base
        }

        return $items;
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISOS DEL MÓDULO CERTIFICADOS
    |--------------------------------------------------------------------------
    */

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->check();
    }

    public static function canEdit($record): bool
    {
        return auth()->check();
    }

    /**
     * El Registrador NO puede eliminar certificados.
     * Solo Administrador y Superadministrador pueden eliminar.
     */
    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Registro Oficial de Certificado Académico CIAC TAMEP')
                    ->description('Complete los datos del estudiante, curso y registro oficial. Los campos están distribuidos a lo ancho de toda la pantalla con dimensiones cómodas y legibles.')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        // --- FILA 1: DATOS DEL ESTUDIANTE (12 COLUMNAS) ---
                        TextInput::make('apellido_paterno_cer')
                            ->label('Apellido Paterno')
                            ->maxLength(100)
                            ->placeholder('Ej: FLORES')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state ?? '', 'UTF-8'))
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('apellido_materno_cer')
                            ->label('Apellido Materno')
                            ->maxLength(100)
                            ->placeholder('Ej: ROJAS')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state ?? '', 'UTF-8'))
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('nombre_per_cer')
                            ->label('Nombres del Estudiante')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Ej: BISMARCK')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state ?? '', 'UTF-8'))
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('carnet_per_cer')
                            ->label('Carnet de Identidad (C.I.)')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('Ej: 9911964')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state ?? '', 'UTF-8'))
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        // --- FILA 2: DATOS DEL CURSO Y CONDICIÓN (12 COLUMNAS) ---
                        Select::make('curso_cer')
                            ->label('Curso / Especialidad CIAC')
                            ->options(fn ($state): array => Curso::opcionesSelector(is_string($state) ? $state : null))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateHydrated(function (Select $component, $state, ?Certificado $record) {
                                if (blank($state) || ctype_digit((string) $state)) {
                                    return;
                                }
                                $curso = Curso::buscarPorNombre($state, $record?->codigo_curso_cer);
                                if ($curso) {
                                    $component->state((string) $curso->id_cur);
                                }
                            })
                            ->dehydrateStateUsing(function ($state): string {
                                if (ctype_digit((string) $state)) {
                                    $curso = Curso::find((int) $state);
                                    if ($curso) {
                                        return $curso->nombre_cur;
                                    }
                                }
                                return mb_strtoupper(trim((string) $state), 'UTF-8');
                            })
                            ->createOptionForm([
                                TextInput::make('nombre_cur')
                                    ->label('Nombre del Curso / Especialidad')
                                    ->required()
                                    ->maxLength(150)
                                    ->placeholder('Ej: TRIPULANTE DE CABINA')
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase;']),
                                TextInput::make('codigo_cur')
                                    ->label('Código del Curso (único por gestión/semestre)')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('Ej: TCP II/2026')
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase;']),
                                TextInput::make('periodo_cur')
                                    ->label('Periodo y Gestión')
                                    ->maxLength(150)
                                    ->placeholder('Ej: 02/mar al 30/nov 2026'),
                                TextInput::make('docente_cur')
                                    ->label('Gerente Académico / Autoridad')
                                    ->default('Lic. MSc. Elsa Quenta Mamani')
                                    ->maxLength(150),
                                TextInput::make('tipo_documento_cur')
                                    ->label('Tipo de Documento Expedido')
                                    ->default('DIPLOMA Y CERTIFICADO DE ESTUDIOS')
                                    ->maxLength(180)
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase;']),
                            ])
                            ->createOptionUsing(function (array $data): string {
                                $codigo = mb_strtoupper(trim($data['codigo_cur']), 'UTF-8');
                                $nombre = mb_strtoupper(trim($data['nombre_cur']), 'UTF-8');
                                $curso = Curso::updateOrCreate(
                                    ['codigo_cur' => $codigo],
                                    [
                                        'nombre_cur' => $nombre,
                                        'periodo_cur' => $data['periodo_cur'] ?? null,
                                        'docente_cur' => $data['docente_cur'] ?? 'Lic. MSc. Elsa Quenta Mamani',
                                        'tipo_documento_cur' => filled($data['tipo_documento_cur'] ?? null) ? mb_strtoupper(trim($data['tipo_documento_cur']), 'UTF-8') : 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                                        'activo_cur' => true,
                                    ]
                                );
                                return (string) $curso->id_cur;
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $curso = Curso::buscarPorNombre(is_scalar($state) ? (string) $state : null);

                                if ($curso) {
                                    if (filled($curso->codigo_cur)) {
                                        $set('codigo_curso_cer', $curso->codigo_cur);
                                    }
                                    if (filled($curso->docente_cur)) {
                                        $set('docente_cer', $curso->docente_cur);
                                    }
                                    if (filled($curso->periodo_cur)) {
                                        $set('periodo_gestion_cer', $curso->periodo_cur);
                                    }
                                    if (filled($curso->tipo_documento_cur)) {
                                        $set('tipo_documento_cer', $curso->tipo_documento_cur);
                                    }
                                    return;
                                }

                                $codes = config('courses.course_codes', []);
                                if (isset($codes[$state])) {
                                    $set('codigo_curso_cer', $codes[$state]);
                                }
                                $teachers = config('courses.teachers', []);
                                if (isset($teachers[$state])) {
                                    $set('docente_cer', $teachers[$state]);
                                }
                                $periods = config('courses.periods', []);
                                if (isset($periods[$state])) {
                                    $set('periodo_gestion_cer', $periods[$state]);
                                }
                                $docs = config('courses.document_types', []);
                                if (isset($docs[$state])) {
                                    $set('tipo_documento_cer', $docs[$state]);
                                }
                            })
                            ->columnSpan(['default' => 12, 'lg' => 3]),

                        TextInput::make('codigo_curso_cer')
                            ->label('Código del Curso')
                            ->placeholder('Ej: DV/2026, TCP/2026, TCP II/2026, AGT/2026')
                            ->maxLength(50)
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        TextInput::make('periodo_gestion_cer')
                            ->label('Periodo y Gestión Académica')
                            ->placeholder('Ej: 02/mar al 30/nov 2026')
                            ->maxLength(150)
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('promedio_final_cer')
                            ->label('Promedio Final')
                            ->default('1')
                            ->placeholder('Ej: 1')
                            ->maxLength(20)
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        Select::make('estado_academico_cer')
                            ->label('Condición Académica')
                            ->options([
                                'APROBADO' => 'APROBADO',
                                'REPROBADO' => 'REPROBADO',
                                'PENDIENTE' => 'PENDIENTE',
                            ])
                            ->default('APROBADO')
                            ->required()
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        // --- FILA 3: AUTORIDAD Y TIPO DE DOCUMENTO (12 COLUMNAS) ---
                        TextInput::make('docente_cer')
                            ->label('Gerente Académico / Autoridad CIAC')
                            ->default(fn () => config('courses.teachers.DESPACHADOR DE VUELO', 'Lic. MSc. Elsa Quenta Mamani'))
                            ->required()
                            ->maxLength(150)
                            ->columnSpan(['default' => 12, 'lg' => 6]),

                        TextInput::make('tipo_documento_cer')
                            ->label('Tipo de Documento Expedido')
                            ->default('DIPLOMA Y CERTIFICADO DE ESTUDIOS')
                            ->maxLength(150)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state ?? '', 'UTF-8'))
                            ->columnSpan(['default' => 12, 'lg' => 6]),

                        // --- FILA 4: REGISTRO OFICIAL Y CÓDIGO QR (12 COLUMNAS) ---
                        TextInput::make('registro_diploma_cer')
                            ->label('N° Registro Oficial / Libro Matriz')
                            ->placeholder('Ej: CIAC TAMEP DV 2026 CI 9895606 N° 001/26')
                            ->maxLength(150)
                            ->helperText('Formato oficial de Diploma o Certificado de Aprobación.')
                            ->columnSpan(['default' => 12, 'lg' => 3]),

                        TextInput::make('registro_estudios_cer')
                            ->label('N° Certificado de Estudios')
                            ->placeholder('Ej: N° 001-DV 2026, N° 001-TCP 2026, N° 001-AGT 2026')
                            ->maxLength(150)
                            ->helperText('Número correlativo de Certificado de Calificaciones / Estudios.')
                            ->columnSpan(['default' => 12, 'lg' => 3]),

                        TextInput::make('codigo_cer')
                            ->label('Código Único QR')
                            ->default(fn (): string => 'CERT-' . now()->format('Y') . '-' . Str::upper(Str::random(8)))
                            ->required()
                            ->maxLength(40)
                            ->helperText('Identificador único vinculado a la URL pública.')
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        DatePicker::make('fecha_cer')
                            ->label('Fecha de Emisión')
                            ->default(now()->format('Y-m-d'))
                            ->required()
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        Select::make('estado_cer')
                            ->label('Estado de Validez')
                            ->options([
                                'activo' => 'Activo / Válido',
                                'anulado' => 'Anulado',
                            ])
                            ->default('activo')
                            ->required()
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 2]),

                        Hidden::make('id_usu_1')->default(fn (): int => auth()->user()?->id_usu ?? 1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('codigo_cer')
                    ->label('Código QR')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('nombre_per_cer')
                    ->label('Estudiante')
                    ->formatStateUsing(fn (Certificado $record): string => $record->nombreCompleto())
                    ->searchable(['nombre_per_cer', 'apellido_paterno_cer', 'apellido_materno_cer', 'apellido_per_cer'])
                    ->wrap()
                    ->sortable(),

                TextColumn::make('carnet_per_cer')
                    ->label('C.I.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('curso_cer')
                    ->label('Curso')
                    ->description(fn (Certificado $record): ?string => $record->codigo_curso_cer ? "Cód: {$record->codigo_curso_cer}" : null)
                    ->searchable(['curso_cer', 'codigo_curso_cer'])
                    ->wrap()
                    ->sortable(),

                TextColumn::make('gestion_academica')
                    ->label('Gestión')
                    ->state(function (Certificado $record): string {
                        if (!$record->fecha_cer) {
                            return 'N/A';
                        }
                        $mes = (int) $record->fecha_cer->format('m');
                        $semestre = $mes <= 6 ? 'I' : 'II';
                        return "{$semestre} - {$record->fecha_cer->format('Y')}";
                    })
                    ->badge()
                    ->color('primary')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('fecha_cer', $direction)),

                TextColumn::make('estado_academico_cer')
                    ->label('Condición')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'APROBADO' => 'success',
                        'REPROBADO' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('curso_cer')
                    ->label('Curso')
                    ->options(fn (): array => Curso::opcionesParaFiltro())
                    ->visible(function ($livewire): bool {
                        $activeTab = (string) ($livewire->activeTab ?? '');
                        return !str_starts_with($activeTab, 'curso_');
                    }),

                SelectFilter::make('gestion')
                    ->label('Gestión Académica')
                    ->options(function () {
                        $years = Certificado::selectRaw('YEAR(fecha_cer) as year')
                            ->whereNotNull('fecha_cer')
                            ->distinct()
                            ->orderByDesc('year')
                            ->pluck('year', 'year')
                            ->toArray();

                        $options = [];
                        foreach ($years as $y) {
                            $options[$y] = "Gestión $y";
                        }
                        return $options;
                    })
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereYear('fecha_cer', $data['value']);
                        }
                    }),

                SelectFilter::make('semestre')
                    ->label('Semestre')
                    ->options([
                        'I' => 'Semestre I (Ene - Jun)',
                        'II' => 'Semestre II (Jul - Dic)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (($data['value'] ?? null) === 'I') {
                            $query->whereMonth('fecha_cer', '<=', 6);
                        } elseif (($data['value'] ?? null) === 'II') {
                            $query->whereMonth('fecha_cer', '>=', 7);
                        }
                    }),

                SelectFilter::make('estado_academico_cer')
                    ->label('Estado Académico')
                    ->options([
                        'APROBADO' => 'APROBADO',
                        'REPROBADO' => 'REPROBADO',
                    ]),
            ])
            ->recordActions([
                Action::make('obtener_qr')
                    ->label('QR')
                    ->tooltip('Ver / Descargar Código QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->button()
                    ->size('xs')
                    ->modalHeading(fn (Certificado $record): string => "Código QR Oficial - {$record->nombreCompleto()}")
                    ->modalDescription(fn (Certificado $record): string => "Descargue el código QR en alta calidad (PNG) o vectorial (SVG) para insertar en el certificado físico.")
                    ->modalContent(fn (Certificado $record) => view('certificados.modal_qr', ['certificado' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),

                Action::make('verificar')
                    ->label('Verificar')
                    ->tooltip('Abrir página de verificación pública')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->iconButton()
                    ->url(fn (Certificado $record): string => $record->urlVerificacion())
                    ->openUrlInNewTab(),

                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar certificado'),

                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Eliminar certificado')
                    ->visible(fn (): bool => auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificados::route('/'),
            'create' => Pages\CreateCertificado::route('/create'),
            'edit' => Pages\EditCertificado::route('/{record}/edit'),
        ];
    }
}
