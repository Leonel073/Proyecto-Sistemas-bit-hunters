<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Tecnico; // Importar el modelo Técnico
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos una transacción para asegurar la integridad de las dos inserciones (Empleado y Técnico).
        DB::transaction(function () {

            // --- 1. Crear la cuenta de Gerente ---
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

            // --- 2. Crear la cuenta de Operador ---
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

            // --- 3. CUENTA TÉCNICO (COPIANDO EXACTAMENTE TU CÓDIGO TINKER) ---
            
            // PASO 1: Crear el Empleado Base y capturar la instancia
            // Usamos 'bcrypt()' explícitamente como en tu código funcional.
            $empleadoTecnico = Empleado::create([
                'primerNombre' => 'Carlos',
                'apellidoPaterno' => 'Tecnico',
                'apellidoMaterno' => null, 
                'ci' => '11223344',
                'numeroCelular' => '70000001',
                'emailCorporativo' => 'tecnico@nexora.test',
                'passwordHash' => bcrypt('Tecnico123!'), // Contraseña: 'password'
                'rol' => 'Tecnico', 
                'estado' => 'Activo',
                'fechaIngreso' => now(),
            ]);

            // PASO 2: Crear el Perfil de Técnico (para el estado de disponibilidad)
            Tecnico::create([
                // Usamos la propiedad de clave primaria de tu modelo
                'idEmpleado' => $empleadoTecnico->idEmpleado, 
                'especialidad' => 'General',
                'estadoDisponibilidad' => 'Disponible', 
            ]);


        });
    }
}