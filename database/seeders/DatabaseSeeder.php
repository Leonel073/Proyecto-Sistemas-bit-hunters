<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\GerenteSoporte;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear SuperAdmin y Gerente (Cuentas Principales)
        $this->createAdminAccounts();

        // 2. Ejecutar Seeders de Datos
        $this->call([
            CatTipoIncidenteSeeder::class,
            SlaPoliticaSeeder::class,
            ZonaSeeder::class,
            DemoDataSeeder::class, // Datos de prueba (Usuarios, Empleados)
        ]);
    }

    private function createAdminAccounts()
    {
        $password = Hash::make('Nexora@2024');

        // SuperAdmin
        if (!Empleado::where('rol', 'SuperAdmin')->exists()) {
            Empleado::create([
                'primerNombre' => 'Super',
                'apellidoPaterno' => 'Admin',
                'apellidoMaterno' => 'Sistema',
                'ci' => '0000001',
                'numeroCelular' => '70000001',
                'emailCorporativo' => 'admin@nexora.com',
                'passwordHash' => $password,
                'rol' => 'SuperAdmin',
                'estado' => 'Activo',
                'fechaIngreso' => now(),
            ]);
        }

        // Gerente
        if (!Empleado::where('rol', 'Gerente')->exists()) {
            $gerente = Empleado::create([
                'primerNombre' => 'Gerente',
                'apellidoPaterno' => 'General',
                'apellidoMaterno' => 'Soporte',
                'ci' => '0000002',
                'numeroCelular' => '70000002',
                'emailCorporativo' => 'gerente@nexora.com',
                'passwordHash' => $password,
                'rol' => 'Gerente',
                'estado' => 'Activo',
                'fechaIngreso' => now(),
            ]);
            
            GerenteSoporte::create(['idEmpleado' => $gerente->idEmpleado]);
        }
    }
}