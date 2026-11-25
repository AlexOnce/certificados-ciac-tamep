<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cursos')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->dropUnique('cursos_nombre_cur_unique');
                $table->index('nombre_cur');
                $table->unique('codigo_cur');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cursos')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->dropUnique(['codigo_cur']);
                $table->dropIndex(['nombre_cur']);
                $table->unique('nombre_cur');
            });
        }
    }
};
