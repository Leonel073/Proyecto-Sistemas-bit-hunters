<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear una cuenta de Gerente para testing/administración
        Empleado::create([
            'primerNombre' => 'Admin',
            'segundoNombre' => null,
            'apellidoPaterno' => 'Sistema',
            'apellidoMaterno' => null,
            'ci' => '1234567',
            'numeroCelular' => '7123456789',
            'emailCorporativo' => 'admin@nexora.test',
            'passwordHash' => Hash::make('Admin123!'), // Contraseña: Admin123!
            'rol' => 'Gerente',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);

        // Crear una cuenta de Operador de ejemplo
        Empleado::create([
            'primerNombre' => 'Juan',
            'segundoNombre' => null,
            'apellidoPaterno' => 'Operador',
            'apellidoMaterno' => null,
            'ci' => '7654321',
            'numeroCelular' => '7987654321',
            'emailCorporativo' => 'operador@nexora.test',
            'passwordHash' => Hash::make('Operador123!'), // Contraseña: Operador123!
            'rol' => 'Operador',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);

        // Crear una cuenta de Técnico de ejemplo
        Empleado::create([
            'primerNombre' => 'Carlos',
            'segundoNombre' => null,
            'apellidoPaterno' => 'Técnico',
            'apellidoMaterno' => null,
            'ci' => '9876543',
            'numeroCelular' => '7555443322',
            'emailCorporativo' => 'tecnico@nexora.test',
            'passwordHash' => Hash::make('Tecnico123!'), // Contraseña: Tecnico123!
            'rol' => 'Tecnico',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);
    }
}
