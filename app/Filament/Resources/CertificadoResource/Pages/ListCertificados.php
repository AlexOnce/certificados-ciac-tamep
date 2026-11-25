<?php

namespace App\Filament\Resources\CertificadoResource\Pages;

use App\Filament\Resources\CertificadoResource;
use App\Models\Certificado;
use App\Models\Curso;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use OpenSpout\Reader\XLSX\Reader;

class ListCertificados extends ListRecords
{
    protected static string $resource = CertificadoResource::class;

    /**
     * Pestañas superiores dinámicas:
     * - Gestión actual (Todos)
     * - Una pestaña automática por cada curso activo creado en "Cursos CIAC"
     * - Histórico Completo
     */
    public function getTabs(): array
    {
        $currentYear = (int) date('Y');

        $tabs = [
            "gestion_{$currentYear}" => Tab::make("Gestión {$currentYear} (Todos)")
                ->icon('heroicon-o-calendar')
                ->badge(Certificado::whereYear('fecha_cer', $currentYear)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereYear('fecha_cer', $currentYear)),
        ];

        try {
            if (Schema::hasTable('cursos')) {
                $cursos = Curso::query()
                    ->where('activo_cur', true)
                    ->orderBy('nombre_cur')
                    ->orderBy('codigo_cur')
                    ->get();

                $conteoNombres = $cursos->countBy('nombre_cur');

                foreach ($cursos as $curso) {
                    $tieneHomonimos = ($conteoNombres[$curso->nombre_cur] ?? 0) > 1;
                    $nombreBonito = Str::title(mb_strtolower($curso->nombre_cur, 'UTF-8'));
                    $etiqueta = $tieneHomonimos && filled($curso->codigo_cur)
                        ? "{$nombreBonito} ({$curso->codigo_cur})"
                        : $nombreBonito;

                    $tabs["curso_{$curso->id_cur}"] = Tab::make($etiqueta)
                        ->icon('heroicon-o-book-open')
                        ->badge($curso->cantidadCertificados($tieneHomonimos))
                        ->badgeColor('warning')
                        ->modifyQueryUsing(fn (Builder $query) => $curso->aplicarFiltroCertificados($query, $tieneHomonimos));
                }
            }
        } catch (\Throwable) {
            // Fallback silencioso si no existe la tabla
        }

        $tabs['historico'] = Tab::make('Histórico Completo')
            ->icon('heroicon-o-archive-box')
            ->badge(Certificado::count())
            ->badgeColor('gray');

        return $tabs;
    }

    public function getDefaultActiveTab(): string | int | null
    {
        $cursoId = request()->query('curso');
        if (filled($cursoId)) {
            $tabKey = "curso_{$cursoId}";
            if (array_key_exists($tabKey, $this->getCachedTabs())) {
                return $tabKey;
            }
        }

        $currentYear = (int) date('Y');
        if (Certificado::whereYear('fecha_cer', $currentYear)->exists()) {
            return "gestion_{$currentYear}";
        }

        return array_key_first($this->getCachedTabs());
    }

    public function updatedActiveTab(): void
    {
        parent::updatedActiveTab();

        if (str_starts_with((string) ($this->activeTab ?? ''), 'curso_')) {
            unset($this->tableFilters['curso_cer']);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\CreateAction::make()
                    ->label('Nuevo Certificado Individual')
                    ->icon('heroicon-o-user-plus'),

                Actions\Action::make('registroMultiple')
                    ->label('Registro Múltiple (Carga Excel)')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->modalHeading('Registro Múltiple de Alumnos - CIAC TAMEP')
                    ->modalDescription('Carga masiva desde planilla Excel oficial con generación automática de QRs.')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('Validar e Importar Alumnos')
                    ->form([
                        Placeholder::make('guia_registro_multiple')
                            ->hiddenLabel()
                            ->content(new HtmlString('
                                <div style="background-color: rgba(25, 73, 156, 0.05); border: 1px solid rgba(25, 73, 156, 0.25); border-radius: 10px; padding: 16px; margin-bottom: 8px;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #19499C; font-weight: 700; font-size: 14px;">
                                        <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Indicaciones para el Registro Masivo:</span>
                                    </div>
                                    <ol style="margin-left: 20px; line-height: 1.7; font-size: 13px; color: #374151;">
                                        <li><strong>Paso 1:</strong> Descargue la <a href="' . route('certificados.plantilla') . '" style="color: #19499C; font-weight: 700; text-decoration: underline;" target="_blank">Plantilla Oficial Excel (.xlsx)</a> con las columnas requeridas por el CIAC.</li>
                                        <li><strong>Paso 2:</strong> Complete los datos de cada estudiante (C.I., nombres, especialidad, libro matriz, etc.).</li>
                                        <li><strong>Paso 3:</strong> Suba el archivo en el selector inferior y presione <em>"Validar e Importar Alumnos"</em>.</li>
                                    </ol>
                                    <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid rgba(25, 73, 156, 0.15); font-size: 12px; color: #64748b;">
                                        💡 <em>El sistema generará automáticamente los códigos QR únicos oficiales y verificará que no existan duplicados antes de guardar.</em>
                                    </div>
                                </div>
                            ')),

                        FileUpload::make('archivo')
                            ->label('Archivo Excel (.xlsx)')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->maxSize(10240)
                            ->disk('public')
                            ->required(),
                    ])
                    ->action(function (array $data, ListRecords $livewire) {
                        $filePath = Storage::disk('public')->path($data['archivo']);
                        $reader = new Reader();
                        $reader->open($filePath);

                        $erroresArray = [];
                        $certificadosAInsertar = [];
                        
                        // Llave de unicidad: Carnet + Código de Curso
                        $certificadosExistentes = Certificado::select('carnet_per_cer', 'codigo_curso_cer')->get()
                            ->map(fn($c) => trim($c->carnet_per_cer) . '|' . trim($c->codigo_curso_cer))
                            ->toArray();
                        $llavesEnEsteExcel = [];

                        $docentePorDefecto = 'Lic. MSc. Elsa Quenta Mamani';
                        $adminId = auth()->user()?->id_usu ?? 1;

                        foreach ($reader->getSheetIterator() as $sheet) {
                            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                                if ($rowIndex === 1) continue; 

                                $cells = $row->toArray();
                                if (empty(trim($cells[0] ?? '')) && empty(trim($cells[2] ?? '')) && empty(trim($cells[3] ?? ''))) {
                                    continue; 
                                }

                                $paterno = mb_strtoupper(preg_replace('/\s+/', ' ', trim($cells[0] ?? '')), 'UTF-8');
                                $materno = mb_strtoupper(preg_replace('/\s+/', ' ', trim($cells[1] ?? '')), 'UTF-8');
                                $nombres = mb_strtoupper(preg_replace('/\s+/', ' ', trim($cells[2] ?? '')), 'UTF-8');
                                $carnet = mb_strtoupper(preg_replace('/\s+/', '', trim($cells[3] ?? '')), 'UTF-8');
                                $codCurso = mb_strtoupper(preg_replace('/\s+/', ' ', trim($cells[4] ?? '')), 'UTF-8');
                                $nombreCurso = mb_strtoupper(preg_replace('/\s+/', ' ', trim($cells[5] ?? '')), 'UTF-8');
                                $periodo = trim($cells[6] ?? '');
                                $promedio = trim($cells[7] ?? '1');
                                $estadoAcad = mb_strtoupper(trim($cells[8] ?? 'APROBADO'), 'UTF-8');
                                $tipoDoc = trim($cells[9] ?? 'DIPLOMA Y CERTIFICADO DE ESTUDIOS');
                                $regDiploma = trim($cells[10] ?? '');
                                $regEstudios = trim($cells[11] ?? '');
                                $fechaRaw = trim($cells[12] ?? '');

                                $erroresFila = [];

                                if (empty($nombres)) {
                                    $erroresFila[] = "Nombres requeridos";
                                }
                                if (empty($carnet)) {
                                    $erroresFila[] = "Nro. de Carnet requerido";
                                }

                                $llaveUnica = $carnet . '|' . $codCurso;
                                if (in_array($llaveUnica, $certificadosExistentes) || in_array($llaveUnica, $llavesEnEsteExcel)) {
                                    $carnetHtml = htmlspecialchars($carnet, ENT_QUOTES, 'UTF-8');
                                    $codCursoHtml = htmlspecialchars($codCurso, ENT_QUOTES, 'UTF-8');
                                    $erroresFila[] = "El alumno con carnet {$carnetHtml} ya tiene registro para el curso {$codCursoHtml}";
                                } else {
                                    $llavesEnEsteExcel[] = $llaveUnica;
                                }

                                $fechaEmision = '2026-12-18';
                                if (!empty($fechaRaw)) {
                                    $parsedTime = strtotime($fechaRaw);
                                    if ($parsedTime) {
                                        $fechaEmision = date('Y-m-d', $parsedTime);
                                    }
                                }

                                if (!empty($erroresFila)) {
                                    $erroresArray[] = [
                                        'fila' => $rowIndex,
                                        'errores' => '• ' . implode('<br>• ', $erroresFila)
                                    ];
                                } else {
                                    $codigoUnico = 'CERT-' . now()->format('Y') . '-' . Str::upper(Str::random(8));

                                    $certificadosAInsertar[] = [
                                        'nombre_per_cer' => $nombres,
                                        'apellido_paterno_cer' => $paterno,
                                        'apellido_materno_cer' => $materno,
                                        'apellido_per_cer' => trim("{$paterno} {$materno}"),
                                        'carnet_per_cer' => $carnet,
                                        'codigo_curso_cer' => $codCurso,
                                        'curso_cer' => $nombreCurso,
                                        'periodo_gestion_cer' => $periodo,
                                        'promedio_final_cer' => $promedio,
                                        'estado_academico_cer' => $estadoAcad ?: 'APROBADO',
                                        'tipo_documento_cer' => $tipoDoc,
                                        'registro_diploma_cer' => $regDiploma,
                                        'registro_estudios_cer' => $regEstudios,
                                        'docente_cer' => $docentePorDefecto,
                                        'fecha_cer' => $fechaEmision,
                                        'estado_cer' => 'activo',
                                        'codigo_cer' => $codigoUnico,
                                        'id_usu_1' => $adminId,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];
                                }
                            }
                        }

                        $reader->close();
                        Storage::disk('public')->delete($data['archivo']);

                        if (count($erroresArray) > 0) {
                            $html = '<div style="font-size: 14px; text-align: left; color: #374151;">';
                            $html .= '<p style="margin-bottom: 12px; text-align: center;">Por seguridad, la importación ha sido abortada.</p>';
                            $html .= '<div style="max-height: 220px; overflow-y: auto; background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px;">';
                            
                            foreach ($erroresArray as $err) {
                                $html .= '<div style="margin-bottom: 8px; border-bottom: 1px solid #fecaca; padding-bottom: 8px;">';
                                $html .= '<strong style="color: #dc2626; font-size: 15px;">Fila Excel: ' . $err['fila'] . '</strong><br>';
                                $html .= '<span style="color: #991b1b; font-size: 13px;">' . $err['errores'] . '</span>';
                                $html .= '</div>';
                            }

                            $html .= '</div></div>';

                            $livewire->dispatch('swal', 
                                title: 'Se encontraron ' . count($erroresArray) . ' errores',
                                html: $html,
                                icon: 'error'
                            );
                            
                            return;
                        }

                        if (!empty($certificadosAInsertar)) {
                            Certificado::insert($certificadosAInsertar);

                            $total = count($certificadosAInsertar);
                            $codigos = array_column($certificadosAInsertar, 'codigo_cer');
                            \App\Models\AuditoriaLog::registrar(
                                evento: 'CREACION',
                                modulo: 'CERTIFICADOS',
                                descripcion: "Carga masiva Excel: se registraron {$total} certificados nuevos",
                                codigoReferencia: "EXCEL ({$total} registros)",
                                valoresNuevos: [
                                    'cantidad' => $total,
                                    'codigos' => implode(', ', array_slice($codigos, 0, 25)) . ($total > 25 ? ' ...' : ''),
                                ],
                            );
                            
                            $livewire->dispatch('swal', 
                                title: 'Importación Exitosa',
                                html: 'Se han registrado <b>' . $total . '</b> estudiantes y certificados CIAC limpiamente en el sistema.',
                                icon: 'success'
                            );
                        }
                    }),

                Actions\Action::make('descargarPlantilla')
                    ->label('Descargar Plantilla Oficial (.xlsx)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(route('certificados.plantilla'))
                    ->openUrlInNewTab(),
            ])
            ->label('Nuevo Registro')
            ->icon('heroicon-o-plus-circle')
            ->color('primary')
            ->button(),

            Actions\ActionGroup::make([
                Actions\Action::make('exportarExcelQr')
                    ->label('Exportar Todo con Códigos QR (Excel)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(route('certificados.excel'))
                    ->openUrlInNewTab(),

                Actions\Action::make('irReportes')
                    ->label('Filtrar y Exportar por Curso (Reportes)')
                    ->icon('heroicon-o-funnel')
                    ->url('/admin/reportes'),
            ])
            ->label('Exportaciones')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->button(),
        ];
    }
}
