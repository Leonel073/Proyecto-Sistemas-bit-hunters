<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $emailAdmin = 'admin@nexora.com'; // El correo fijo de tu admin
        $now = Carbon::now();

        // 1. Verificamos si ya existe para no duplicarlo
        $existe = DB::table('empleados')->where('emailCorporativo', $emailAdmin)->first();

        if (!$existe) {
            
            // A. Crear el Empleado Base
            $idAdmin = DB::table('empleados')->insertGetId([
                'primerNombre' => 'Super',
                'apellidoPaterno' => 'Administrador',
                'ci' => '0000000',          // CI ficticio
                'numeroCelular' => '00000000', // Celular ficticio
                'emailCorporativo' => $emailAdmin,
                'passwordHash' => Hash::make('Admin123'), // <--- TU CONTRASEÑA FIJA
                'rol' => 'Gerente',
                'estado' => 'Activo',
                'fechaIngreso' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            // B. Asignar el Rol de Gerente de Soporte (Tabla Hija)
            // CAMBIO AQUÍ: Agrega la 's' para que diga 'gerentes_soporte'
            DB::table('gerentes_soporte')->insert([
                'idEmpleado' => $idAdmin,
                'nivelAutoridad' => 'Total'
            ]);

            $this->command->info('Usuario Administrador creado correctamente.');
            
        } else {
            $this->command->warn('El Administrador ya existe. No se hicieron cambios.');
        }
    }
}