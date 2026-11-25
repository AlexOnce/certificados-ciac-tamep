<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_cur';

    protected $fillable = [
        'nombre_cur',
        'codigo_cur',
        'periodo_cur',
        'docente_cur',
        'tipo_documento_cur',
        'activo_cur',
    ];

    protected $casts = [
        'activo_cur' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Curso $curso) {
            if (filled($curso->nombre_cur)) {
                $curso->nombre_cur = mb_strtoupper(trim($curso->nombre_cur), 'UTF-8');
            }
            if (filled($curso->codigo_cur)) {
                $curso->codigo_cur = mb_strtoupper(trim($curso->codigo_cur), 'UTF-8');
            }
            if (filled($curso->tipo_documento_cur)) {
                $curso->tipo_documento_cur = mb_strtoupper(trim($curso->tipo_documento_cur), 'UTF-8');
            }
        });
    }

    /**
     * Retorna los cursos activos para el selector del formulario de Certificados:
     * [id_cur => 'NOMBRE DEL CURSO (CÓDIGO)'] permitiendo cursos repetidos en distintas gestiones/semestres.
     */
    public static function opcionesSelector(?string $valorActual = null): array
    {
        $opciones = [];

        try {
            if (Schema::hasTable('cursos')) {
                $cursos = static::query()
                    ->where('activo_cur', true)
                    ->orderBy('nombre_cur')
                    ->orderBy('codigo_cur')
                    ->get();

                foreach ($cursos as $curso) {
                    $etiqueta = filled($curso->codigo_cur)
                        ? "{$curso->nombre_cur} ({$curso->codigo_cur})"
                        : $curso->nombre_cur;
                    $opciones[(string) $curso->id_cur] = $etiqueta;
                }
            }
        } catch (\Throwable) {
            // Fallback seguro
        }

        if (empty($opciones)) {
            $opciones = config('courses.options', []);
        }

        if (filled($valorActual) && !isset($opciones[$valorActual])) {
            $opciones[$valorActual] = $valorActual;
        }

        return $opciones;
    }

    /**
     * Retorna los nombres únicos de cursos activos como ['NOMBRE' => 'NOMBRE'].
     */
    public static function opcionesActivas(): array
    {
        try {
            if (Schema::hasTable('cursos')) {
                $cursos = static::query()
                    ->where('activo_cur', true)
                    ->orderBy('nombre_cur')
                    ->distinct()
                    ->pluck('nombre_cur', 'nombre_cur')
                    ->toArray();

                if (!empty($cursos)) {
                    return $cursos;
                }
            }
        } catch (\Throwable) {
            // Fallback seguro a config/courses.php
        }

        return config('courses.options', []);
    }

    /**
     * Retorna todos los nombres únicos de cursos (activos e históricos) para filtros y planillas.
     */
    public static function opcionesParaFiltro(): array
    {
        $opciones = [];

        try {
            if (Schema::hasTable('cursos')) {
                $opciones = static::query()
                    ->orderBy('nombre_cur')
                    ->distinct()
                    ->pluck('nombre_cur', 'nombre_cur')
                    ->toArray();
            }
        } catch (\Throwable) {
            $opciones = config('courses.options', []);
        }

        if (empty($opciones)) {
            $opciones = config('courses.options', []);
        }

        return $opciones;
    }

    /**
     * Busca un curso por ID, código o nombre.
     */
    public static function buscarPorNombre(?string $nombre, ?string $codigo = null): ?self
    {
        if (blank($nombre) && blank($codigo)) {
            return null;
        }

        try {
            if (Schema::hasTable('cursos')) {
                if (filled($codigo)) {
                    $porCodigo = static::query()
                        ->where('codigo_cur', mb_strtoupper(trim($codigo), 'UTF-8'))
                        ->first();
                    if ($porCodigo) {
                        return $porCodigo;
                    }
                }

                if (filled($nombre)) {
                    if (ctype_digit((string) $nombre)) {
                        $porId = static::query()->find((int) $nombre);
                        if ($porId) {
                            return $porId;
                        }
                    }

                    $limpio = mb_strtoupper(trim($nombre), 'UTF-8');
                    return static::query()
                        ->where('nombre_cur', $limpio)
                        ->orderByDesc('activo_cur')
                        ->orderByDesc('id_cur')
                        ->first();
                }
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    /**
     * Aplica el filtro de certificados correspondiente a este curso.
     * Si existen varios cursos activos con el mismo nombre (distintas gestiones/semestres),
     * filtra de forma exacta por código; si el nombre es único, coincide por código o nombre.
     */
    public function aplicarFiltroCertificados(Builder $query, bool $tieneHomonimos = false): Builder
    {
        if ($tieneHomonimos && filled($this->codigo_cur)) {
            return $query->where('codigo_curso_cer', $this->codigo_cur);
        }

        return $query->where(function (Builder $q) {
            if (filled($this->codigo_cur)) {
                $q->where('codigo_curso_cer', $this->codigo_cur)
                    ->orWhere('curso_cer', $this->nombre_cur);
            } else {
                $q->where('curso_cer', $this->nombre_cur);
            }
        });
    }

    /**
     * Cuenta los certificados registrados que pertenecen a este curso.
     */
    public function cantidadCertificados(bool $tieneHomonimos = false): int
    {
        return $this->aplicarFiltroCertificados(Certificado::query(), $tieneHomonimos)->count();
    }
}
