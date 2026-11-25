<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';
    protected $primaryKey = 'id_aud';

    public const UPDATED_AT = null;

    protected $fillable = [
        'id_usu',
        'usuario_nombre',
        'evento',
        'modulo',
        'registro_id',
        'codigo_referencia',
        'descripcion',
        'valores_anteriores',
        'valores_nuevos',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
        'created_at' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usu', 'id_usu');
    }

    /**
     * Registra un evento de auditoría de forma segura sin interrumpir el flujo principal.
     */
    public static function registrar(
        string $evento,
        string $modulo,
        string $descripcion,
        ?int $registroId = null,
        ?string $codigoReferencia = null,
        ?array $valoresAnteriores = null,
        ?array $valoresNuevos = null,
        ?Usuario $usuario = null
    ): void {
        try {
            if (!Schema::hasTable('auditoria_logs')) {
                return;
            }

            $user = $usuario ?? auth()->user();
            $usuarioNombre = null;

            if ($user instanceof Usuario) {
                $nombre = trim(($user->nombre_usu ?? '') . ' ' . ($user->apellido_usu ?? ''));
                $usuarioNombre = $nombre !== ''
                    ? "{$nombre} ({$user->email_usu})"
                    : ($user->email_usu ?? "Usuario #{$user->id_usu}");
            }

            $request = app()->bound('request') ? request() : null;

            static::create([
                'id_usu' => $user?->getAuthIdentifier(),
                'usuario_nombre' => $usuarioNombre ?? 'Sistema / Consola',
                'evento' => strtoupper($evento),
                'modulo' => strtoupper($modulo),
                'registro_id' => $registroId,
                'codigo_referencia' => $codigoReferencia,
                'descripcion' => mb_substr($descripcion, 0, 255),
                'valores_anteriores' => $valoresAnteriores,
                'valores_nuevos' => $valoresNuevos,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // Nunca interrumpir operaciones críticas por un fallo de bitácora
        }
    }

    /**
     * Nombres legibles en español para los campos de certificados en el detalle de cambios.
     */
    public static function etiquetaCampo(string $campo): string
    {
        $mapa = [
            'codigo_cer' => 'Código QR',
            'nombre_per_cer' => 'Nombres',
            'apellido_paterno_cer' => 'Apellido Paterno',
            'apellido_materno_cer' => 'Apellido Materno',
            'apellido_per_cer' => 'Apellidos',
            'carnet_per_cer' => 'C.I.',
            'curso_cer' => 'Curso / Especialidad',
            'codigo_curso_cer' => 'Código del Curso',
            'periodo_gestion_cer' => 'Periodo y Gestión',
            'promedio_final_cer' => 'Promedio Final',
            'estado_academico_cer' => 'Condición Académica',
            'tipo_documento_cer' => 'Tipo de Documento',
            'registro_diploma_cer' => 'Registro Diploma / Certificado',
            'registro_estudios_cer' => 'Registro Cert. Estudios',
            'docente_cer' => 'Autoridad Académica',
            'fecha_cer' => 'Fecha de Emisión',
            'estado_cer' => 'Estado de Validez',
            'cantidad' => 'Cantidad Importada',
            'codigos' => 'Códigos Generados',
        ];

        return $mapa[$campo] ?? $campo;
    }
}
