<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    use HasFactory;

    protected $table = 'certificados';

    protected $primaryKey = 'id_cer';

    protected $fillable = [
        'codigo_cer',
        'nombre_per_cer',
        'apellido_per_cer',
        'apellido_paterno_cer',
        'apellido_materno_cer',
        'carnet_per_cer',
        'docente_cer',
        'curso_cer',
        'codigo_curso_cer',
        'periodo_gestion_cer',
        'promedio_final_cer',
        'estado_academico_cer',
        'tipo_documento_cer',
        'registro_diploma_cer',
        'registro_estudios_cer',
        'fecha_cer',
        'estado_cer',
        'id_usu_1',
    ];

    protected function casts(): array
    {
        return [
            'fecha_cer' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Certificado $certificado) {
            // Sincronizar apellido_per_cer si se definen los apellidos paterno y materno
            if (empty($certificado->apellido_per_cer) || filled($certificado->apellido_paterno_cer)) {
                $paterno = trim($certificado->apellido_paterno_cer ?? '');
                $materno = trim($certificado->apellido_materno_cer ?? '');
                $completo = trim("{$paterno} {$materno}");
                if (!empty($completo)) {
                    $certificado->apellido_per_cer = $completo;
                }
            }

            $cursoDb = !empty($certificado->curso_cer)
                ? Curso::buscarPorNombre($certificado->curso_cer, $certificado->codigo_curso_cer)
                : null;

            // Asignación automática de código de curso a partir del curso
            if (empty($certificado->codigo_curso_cer) && !empty($certificado->curso_cer)) {
                if ($cursoDb && filled($cursoDb->codigo_cur)) {
                    $certificado->codigo_curso_cer = $cursoDb->codigo_cur;
                } else {
                    $codes = config('courses.course_codes', []);
                    if (isset($codes[$certificado->curso_cer])) {
                        $certificado->codigo_curso_cer = $codes[$certificado->curso_cer];
                    }
                }
            }

            // Asignación automática de docente / autoridad por defecto si viene vacío
            if (empty($certificado->docente_cer) && !empty($certificado->curso_cer)) {
                if ($cursoDb && filled($cursoDb->docente_cur)) {
                    $certificado->docente_cer = $cursoDb->docente_cur;
                } else {
                    $teachers = config('courses.teachers', []);
                    $certificado->docente_cer = $teachers[$certificado->curso_cer] ?? 'Lic. MSc. Elsa Quenta Mamani';
                }
            }

            // Asignación automática de periodo si viene vacío
            if (empty($certificado->periodo_gestion_cer) && !empty($certificado->curso_cer)) {
                if ($cursoDb && filled($cursoDb->periodo_cur)) {
                    $certificado->periodo_gestion_cer = $cursoDb->periodo_cur;
                } else {
                    $periods = config('courses.periods', []);
                    if (isset($periods[$certificado->curso_cer])) {
                        $certificado->periodo_gestion_cer = $periods[$certificado->curso_cer];
                    }
                }
            }
        });

        static::created(function (Certificado $certificado) {
            AuditoriaLog::registrar(
                evento: 'CREACION',
                modulo: 'CERTIFICADOS',
                descripcion: "Creó certificado {$certificado->codigo_cer} para {$certificado->nombreCompleto()} ({$certificado->curso_cer})",
                registroId: $certificado->id_cer,
                codigoReferencia: $certificado->codigo_cer,
                valoresNuevos: $certificado->resumenAuditoria(),
            );
        });

        static::updated(function (Certificado $certificado) {
            $cambios = collect($certificado->getChanges())
                ->except(['updated_at', 'created_at'])
                ->toArray();

            if (empty($cambios)) {
                return;
            }

            $anteriores = [];
            $nuevos = [];
            foreach ($cambios as $campo => $valorNuevo) {
                $valorAnterior = $certificado->getOriginal($campo);
                if ($valorAnterior instanceof \DateTimeInterface) {
                    $valorAnterior = $valorAnterior->format('Y-m-d');
                }
                if ($valorNuevo instanceof \DateTimeInterface) {
                    $valorNuevo = $valorNuevo->format('Y-m-d');
                }
                if ((string) $valorAnterior === (string) $valorNuevo) {
                    continue;
                }
                $anteriores[$campo] = $valorAnterior;
                $nuevos[$campo] = $valorNuevo;
            }

            if (empty($nuevos)) {
                return;
            }

            $camposLegibles = array_map(
                fn (string $c) => AuditoriaLog::etiquetaCampo($c),
                array_keys($nuevos)
            );

            AuditoriaLog::registrar(
                evento: 'MODIFICACION',
                modulo: 'CERTIFICADOS',
                descripcion: "Modificó {$certificado->codigo_cer} ({$certificado->nombreCompleto()}): " . implode(', ', $camposLegibles),
                registroId: $certificado->id_cer,
                codigoReferencia: $certificado->codigo_cer,
                valoresAnteriores: $anteriores,
                valoresNuevos: $nuevos,
            );
        });

        static::deleted(function (Certificado $certificado) {
            AuditoriaLog::registrar(
                evento: 'ELIMINACION',
                modulo: 'CERTIFICADOS',
                descripcion: "Eliminó certificado {$certificado->codigo_cer} de {$certificado->nombreCompleto()} ({$certificado->curso_cer})",
                registroId: $certificado->id_cer,
                codigoReferencia: $certificado->codigo_cer,
                valoresAnteriores: $certificado->resumenAuditoria(),
            );
        });
    }

    /**
     * Resumen limpio de campos clave del certificado para guardar en la bitácora.
     */
    public function resumenAuditoria(): array
    {
        return [
            'codigo_cer' => $this->codigo_cer,
            'nombre_per_cer' => $this->nombre_per_cer,
            'apellido_paterno_cer' => $this->apellido_paterno_cer,
            'apellido_materno_cer' => $this->apellido_materno_cer,
            'carnet_per_cer' => $this->carnet_per_cer,
            'curso_cer' => $this->curso_cer,
            'codigo_curso_cer' => $this->codigo_curso_cer,
            'periodo_gestion_cer' => $this->periodo_gestion_cer,
            'promedio_final_cer' => $this->promedio_final_cer,
            'estado_academico_cer' => $this->estado_academico_cer,
            'registro_diploma_cer' => $this->registro_diploma_cer,
            'fecha_cer' => $this->fecha_cer ? $this->fecha_cer->format('Y-m-d') : null,
            'estado_cer' => $this->estado_cer,
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usu_1', 'id_usu');
    }

    public function apellidoCompleto(): string
    {
        $paterno = trim($this->apellido_paterno_cer ?? '');
        $materno = trim($this->apellido_materno_cer ?? '');
        $apellidos = trim("{$paterno} {$materno}");

        return !empty($apellidos) ? $apellidos : trim($this->apellido_per_cer ?? '');
    }

    public function nombreCompleto(): string
    {
        $apellidos = $this->apellidoCompleto();
        return trim("{$this->nombre_per_cer} {$apellidos}");
    }

    /**
     * Determina si el registro pertenece a Agente de Tráfico.
     */
    public function esAgenteTrafico(): bool
    {
        $curso = mb_strtoupper($this->curso_cer ?? '', 'UTF-8');
        $codigo = mb_strtoupper($this->codigo_curso_cer ?? '', 'UTF-8');

        return str_contains($curso, 'TRÁFICO') || str_contains($curso, 'TRAFICO') || str_contains($codigo, 'AGT');
    }

    /**
     * Extrae el número correlativo normalizado del registro de diploma o aprobación.
     * Ej: "CIAC TAMEP AGT 2026 CI 13084026 Nº 001/26" -> "Nº 001/26"
     */
    public function numeroRegistroNormalizado(): string
    {
        if (empty($this->registro_diploma_cer)) {
            return 'N/A';
        }

        if (preg_match('/(N[º°\x{00BA}\x{00B0}]?\s*\d+\/\d+)/ui', $this->registro_diploma_cer, $matches)) {
            return $matches[1];
        }

        return $this->registro_diploma_cer;
    }

    /**
     * Extrae el número correlativo normalizado del certificado de estudios.
     * Ej: "N° 001-AGT 2026" -> "N° 001"
     */
    public function numeroEstudiosNormalizado(): string
    {
        if (empty($this->registro_estudios_cer)) {
            return 'N/A';
        }

        if (preg_match('/(N[º°\x{00BA}\x{00B0}]?\s*\d+)/ui', $this->registro_estudios_cer, $matches)) {
            return $matches[1];
        }

        return $this->registro_estudios_cer;
    }

    /**
     * Etiqueta oficial del documento principal según el curso.
     */
    public function etiquetaDocumentoPrincipal(): string
    {
        return $this->esAgenteTrafico() ? 'Certificado de Aprobación' : 'Diploma';
    }

    /**
     * URL pública oficial de verificación pública para el código QR.
     * Siempre apunta al dominio de producción https://ciac.tamep.bo.
     */
    public function urlVerificacion(): string
    {
        $dominio = rtrim(config('app.qr_domain', env('QR_DOMAIN', 'https://ciac.tamep.bo')), '/');
        if (!str_starts_with($dominio, 'http')) {
            $dominio = 'https://' . $dominio;
        }

        return "{$dominio}/verificar/{$this->codigo_cer}";
    }
}
