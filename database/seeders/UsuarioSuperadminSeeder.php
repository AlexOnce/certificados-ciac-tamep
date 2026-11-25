<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * ==============================================================
         * 1. VERIFICAR Y CREAR EL ROL SUPERADMINISTRADOR (id = 3)
         * ==============================================================
         */
        $rolSuperadmin = Rol::where('id_rol', 3)->first();

        if (! $rolSuperadmin) {
            // Crear el rol Superadministrador
            Rol::create([
                'id_rol' => 3,
                'nom_rol' => 'Superadministrador',
            ]);

            $this->command->info('✅ Rol Superadministrador creado (id: 3)');
        } else {
            $this->command->info('ℹ️ El rol Superadministrador ya existe (id: 3)');
        }

        /*
         * ==============================================================
         * 2. VERIFICAR Y ASIGNAR EL ROL SUPERADMIN AL USUARIO
         * ==============================================================
         */
        $email = 'admin@tamep.bo';
        $usuario = Usuario::where('correo_usu', $email)->first();

        if ($usuario) {
            // Actualizar el rol del usuario a Superadministrador (3) y asegurar contraseña
            $usuario->id_rol_1 = 3;
            $usuario->password_usu = 'admin1234';
            $usuario->estado_usu = true;
            $usuario->save();

            $this->command->info("✅ Usuario '{$email}' actualizado a Superadministrador (id_rol: 3)");
        } else {
            Usuario::create([
                'nombre_usu' => 'Administrador TAM-EP',
                'correo_usu' => $email,
                'password_usu' => 'admin1234',
                'estado_usu' => true,
                'id_rol_1' => 3,
            ]);
            $this->command->info("✅ Usuario '{$email}' creado como Superadministrador (id_rol: 3)");
        }

        /*
         * ==============================================================
         * 3. RESULTADO FINAL
         * ==============================================================
         */
        $this->command->info('✅ Seeder ejecutado correctamente');
    }
}
