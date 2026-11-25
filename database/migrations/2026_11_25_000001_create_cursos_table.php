<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cursos')) {
            Schema::create('cursos', function (Blueprint $table) {
                $table->id('id_cur');
                $table->string('nombre_cur', 150)->unique();
                $table->string('codigo_cur', 50)->nullable();
                $table->string('periodo_cur', 150)->nullable();
                $table->string('docente_cur', 150)->nullable()->default('Lic. MSc. Elsa Quenta Mamani');
                $table->string('tipo_documento_cur', 180)->nullable()->default('DIPLOMA Y CERTIFICADO DE ESTUDIOS');
                $table->boolean('activo_cur')->default(true);
                $table->timestamps();
            });
        }

        // Sembrar los cursos iniciales desde config/courses.php si la tabla está vacía
        if (DB::table('cursos')->count() === 0) {
            $now = now();
            $options = config('courses.options', []);
            $codes = config('courses.course_codes', []);
            $teachers = config('courses.teachers', []);
            $periods = config('courses.periods', []);
            $docs = config('courses.document_types', []);

            foreach ($options as $nombre) {
                DB::table('cursos')->insert([
                    'nombre_cur' => mb_strtoupper(trim($nombre), 'UTF-8'),
                    'codigo_cur' => $codes[$nombre] ?? null,
                    'periodo_cur' => $periods[$nombre] ?? null,
                    'docente_cur' => $teachers[$nombre] ?? 'Lic. MSc. Elsa Quenta Mamani',
                    'tipo_documento_cur' => $docs[$nombre] ?? 'DIPLOMA Y CERTIFICADO DE ESTUDIOS',
                    'activo_cur' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
