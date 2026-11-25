<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Datos base del sistema de certificados (roles, administrador y estudiantes CIAC).
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            UsuarioSuperadminSeeder::class,
            CiacEstudiantesSeeder::class,
        ]);
    }
}
