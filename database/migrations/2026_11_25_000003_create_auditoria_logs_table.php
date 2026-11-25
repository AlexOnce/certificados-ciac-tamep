<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('auditoria_logs')) {
            Schema::create('auditoria_logs', function (Blueprint $table) {
                $table->id('id_aud');
                $table->unsignedBigInteger('id_usu')->nullable()->index();
                $table->string('usuario_nombre', 150)->nullable();
                $table->string('evento', 30)->index(); // LOGIN, LOGOUT, CREACION, MODIFICACION, ELIMINACION
                $table->string('modulo', 50)->index(); // ACCESO, CERTIFICADOS
                $table->unsignedBigInteger('registro_id')->nullable()->index();
                $table->string('codigo_referencia', 100)->nullable()->index();
                $table->string('descripcion', 255);
                $table->json('valores_anteriores')->nullable();
                $table->json('valores_nuevos')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_logs');
    }
};
