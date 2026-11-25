<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->string('apellido_paterno_cer', 100)->nullable()->after('nombre_per_cer');
            $table->string('apellido_materno_cer', 100)->nullable()->after('apellido_paterno_cer');
            $table->string('codigo_curso_cer', 50)->nullable()->after('curso_cer');
            $table->string('periodo_gestion_cer', 150)->nullable()->after('codigo_curso_cer');
            $table->string('promedio_final_cer', 20)->nullable()->after('periodo_gestion_cer');
            $table->string('estado_academico_cer', 50)->default('APROBADO')->after('promedio_final_cer');
            $table->string('tipo_documento_cer', 150)->nullable()->after('estado_academico_cer');
            $table->string('registro_diploma_cer', 150)->nullable()->after('tipo_documento_cer');
            $table->string('registro_estudios_cer', 150)->nullable()->after('registro_diploma_cer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropColumn([
                'apellido_paterno_cer',
                'apellido_materno_cer',
                'codigo_curso_cer',
                'periodo_gestion_cer',
                'promedio_final_cer',
                'estado_academico_cer',
                'tipo_documento_cer',
                'registro_diploma_cer',
                'registro_estudios_cer',
            ]);
        });
    }
};
