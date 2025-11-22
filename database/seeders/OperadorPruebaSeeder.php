<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OperadorPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        // CONTRASEÑA SEGURA: Cumple con Mayúscula, Minúscula, Número y Caracter
        $passwordSegura = Hash::make('Operador.2025!'); 
        
        $email = 'operador.prueba@nexora.com';

        // 1. Verificar si ya existe para no duplicar
        $existe = DB::table('empleados')->where('emailCorporativo', $email)->first();

        if (!$existe) {
            // A. Crear el EMPLEADO
            $idEmpleado = DB::table('empleados')->insertGetId([
                'primerNombre'     => 'Roberto',
                'apellidoPaterno'  => 'Gomez',
                'ci'               => '55566677',
                'numeroCelular'    => '71122334',
                'emailCorporativo' => $email,
                'passwordHash'     => $passwordSegura, // <--- Contraseña validada
                'rol'              => 'Operador',
                'estado'           => 'Activo',
                'fechaIngreso'     => $now,
                'created_at'       => $now,
                'updated_at'       => $now
            ]);

            // B. Crear el OPERADOR
            DB::table('operadores')->insert([
                'idEmpleado' => $idEmpleado,
                'turno'      => 'Mañana'
            ]);

            $this->command->info("Operador 'Roberto' creado con contraseña segura.");
        } else {
            // Si ya existe, le actualizamos la contraseña para que puedas entrar
            DB::table('empleados')
                ->where('emailCorporativo', $email)
                ->update(['passwordHash' => $passwordSegura]);
                
            $this->command->warn("El operador ya existía. Se actualizó su contraseña a: Operador.2025!");
        }
    }
}