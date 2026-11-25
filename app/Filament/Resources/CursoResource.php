<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoResource\Pages;
use App\Models\Curso;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CursoResource extends Resource
{
    protected static ?string $model = Curso::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Cursos CIAC';
    protected static ?string $modelLabel = 'curso';
    protected static ?string $pluralModelLabel = 'cursos';
    protected static ?int $navigationSort = 2;

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
                Section::make('Datos del Curso / Especialidad CIAC')
                    ->description('Registre aquí los cursos oficiales. Al crear un curso, aparecerá automáticamente en el formulario de Certificados, filtros y planillas QR.')
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema([
                        TextInput::make('nombre_cur')
                            ->label('Nombre del Curso / Especialidad')
                            ->required()
                            ->maxLength(150)
                            ->placeholder('Ej: TRIPULANTE DE CABINA')
                            ->helperText('Puede repetirse en distintas gestiones o semestres; se diferencia por el Código del Curso.')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => mb_strtoupper(trim($state ?? ''), 'UTF-8'))
                            ->columnSpan(['default' => 12, 'lg' => 6]),

                        TextInput::make('codigo_cur')
                            ->label('Código del Curso')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('Ej: DV/2026, TCP/2026, TCP II/2026')
                            ->helperText('Identificador único de la edición anual o semestral.')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? mb_strtoupper(trim($state), 'UTF-8') : null)
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('periodo_cur')
                            ->label('Periodo y Gestión por Defecto')
                            ->maxLength(150)
                            ->placeholder('Ej: 02/mar al 30/nov 2026')
                            ->columnSpan(['default' => 12, 'sm' => 6, 'lg' => 3]),

                        TextInput::make('docente_cur')
                            ->label('Gerente Académico / Autoridad por Defecto')
                            ->default('Lic. MSc. Elsa Quenta Mamani')
                            ->maxLength(150)
                            ->placeholder('Ej: Lic. MSc. Elsa Quenta Mamani')
                            ->columnSpan(['default' => 12, 'lg' => 5]),

                        TextInput::make('tipo_documento_cur')
                            ->label('Tipo de Documento Expedido por Defecto')
                            ->default('DIPLOMA Y CERTIFICADO DE ESTUDIOS')
                            ->maxLength(180)
                            ->placeholder('Ej: DIPLOMA Y CERTIFICADO DE ESTUDIOS')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase;'])
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? mb_strtoupper(trim($state), 'UTF-8') : null)
                            ->columnSpan(['default' => 12, 'lg' => 5]),

                        Toggle::make('activo_cur')
                            ->label('Curso Activo')
                            ->helperText('Si se desactiva, no aparecerá para nuevos certificados pero se conservará el historial.')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(['default' => 12, 'lg' => 2]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('nombre_cur', 'asc')
            ->columns([
                TextColumn::make('nombre_cur')
                    ->label('Curso / Especialidad')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('codigo_cur')
                    ->label('Código')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periodo_cur')
                    ->label('Periodo por Defecto')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('docente_cur')
                    ->label('Autoridad Académica')
                    ->toggleable()
                    ->wrap(),

                TextColumn::make('tipo_documento_cur')
                    ->label('Documento Expedido')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

                IconColumn::make('activo_cur')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('activo_cur')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar curso'),

                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Eliminar curso')
                    ->visible(fn (): bool => auth()->user()?->isSuperAdmin() || auth()->user()?->isAdmin()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCursos::route('/'),
            'create' => Pages\CreateCurso::route('/create'),
            'edit' => Pages\EditCurso::route('/{record}/edit'),
        ];
    }
}
