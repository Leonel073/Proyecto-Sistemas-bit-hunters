<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SupervisorOperadorSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        // Contraseña segura: Supervisor.2025!
        $passwordSegura = Hash::make('Supervisor.2025!'); 
        
        $email = 'supervisor@nexora.com';
        $ci = '1111111';

        // 1. Verificar si ya existe el empleado por CI (o email)
        $existe = DB::table('empleados')->where('ci', $ci)->first();

        if (!$existe) {
            
            // A. Crear el EMPLEADO (Tabla Padre)
            $idEmpleado = DB::table('empleados')->insertGetId([
                'primerNombre'     => 'Sandra',
                'segundoNombre'    => 'Isabel',
                'apellidoPaterno'  => 'Rojas',
                'apellidoMaterno'  => 'Perez',
                'ci'               => $ci,
                'numeroCelular'    => '70001000',
                'emailCorporativo' => $email, // <--- LOGIN
                'passwordHash'     => $passwordSegura, // Contraseña validada
                'rol'              => 'SupervisorOperador', // Rol clave
                'estado'           => 'Activo',
                'fechaIngreso'     => $now->subDays(30), // Fecha en el pasado
                'created_at'       => $now,
                'updated_at'       => $now
            ]);

            // B. Crear el SUPERVISOR_OPERADOR (Tabla Hija)
            // Asumo que tu tabla se llama 'supervisores_operadores' (plural)
            DB::table('supervisores_operadores')->insert([
                'idEmpleado'  => $idEmpleado, // Usamos el ID que acabamos de crear
                'areaEnfoque' => 'Rendimiento'
            ]);

            $this->command->info("✅ Supervisor de Operadores 'Sandra Rojas' creado.");
        } else {
            // Si ya existe, actualizamos la contraseña por seguridad
            DB::table('empleados')
                ->where('ci', $ci)
                ->update(['passwordHash' => $passwordSegura]);
                
            $this->command->warn("⚠️ Supervisor de Operadores ya existe. Contraseña actualizada.");
        }
    }
}