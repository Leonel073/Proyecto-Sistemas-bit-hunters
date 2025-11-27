<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SupervisorTecnicoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        // Contraseña segura: SupervisorT.2025!
        $passwordSegura = Hash::make('SupervisorT.2025!'); 
        
        $email = 'supt.tecnico@nexora.com';
        $ci = '2000000'; // CI único

        // 1. Verificar si ya existe
        $existe = DB::table('empleados')->where('emailCorporativo', $email)->first();

        if (!$existe) {
            // A. Crear el EMPLEADO (Tabla Padre)
            $idEmpleado = DB::table('empleados')->insertGetId([
                'primerNombre'     => 'Tomas',
                'segundoNombre'    => 'David',
                'apellidoPaterno'  => 'Mamani',
                'apellidoMaterno'  => 'Lopez',
                'ci'               => $ci,
                'numeroCelular'    => '70002000',
                'emailCorporativo' => $email, // <--- LOGIN
                'passwordHash'     => $passwordSegura, 
                'rol'              => 'SupervisorTecnico', // Rol clave
                'estado'           => 'Activo',
                'fechaIngreso'     => $now->subDays(30),
                'created_at'       => $now,
                'updated_at'       => $now
            ]);

            // B. Crear el SUPERVISOR_TECNICOS (Tabla Hija)
            // Asumo que tu tabla se llama 'supervisores_tecnicos'
            DB::table('supervisores_tecnicos')->insert([
                'idEmpleado'            => $idEmpleado, // Usamos el ID que acabamos de crear
                'zonaGeograficaAsignada' => 'Zona 1 - El Alto'
            ]);

            $this->command->info("✅ Supervisor de Técnicos 'Tomas Mamani' creado.");
        } else {
            // Si ya existe, actualizamos la contraseña por seguridad
            DB::table('empleados')
                ->where('ci', $ci)
                ->update(['passwordHash' => $passwordSegura]);
                
            $this->command->warn("⚠️ Supervisor de Técnicos ya existe. Contraseña actualizada.");
        }
    }
}