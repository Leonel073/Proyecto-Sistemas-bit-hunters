<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        // Contraseña segura (Mayúscula, Minúscula, Número, Caracter)
        $passwordSegura = Hash::make('Tecnico.2025!');

        // Datos de los 3 técnicos
        $datos_tecnicos = [
            [
                'email' => 'tecnico1@nexora.test',
                'primerNombre' => 'Juan',
                'apellidoPaterno' => 'Perez',
                'ci' => '8888888',
                'celular' => '77771111',
                'especialidad' => 'Redes e Internet'
            ],
            [
                'email' => 'tecnico2@nexora.test',
                'primerNombre' => 'Ana',
                'apellidoPaterno' => 'Gomez',
                'ci' => '8888889',
                'celular' => '77772222',
                'especialidad' => 'Hardware y Equipos'
            ]
        ];

        foreach ($datos_tecnicos as $dato) {
            // 1. Verificar si existe el empleado por email
            $existe = DB::table('empleados')->where('emailCorporativo', $dato['email'])->first();

            if (!$existe) {
                // A. Crear EMPLEADO (Padre)
                $idEmpleado = DB::table('empleados')->insertGetId([
                    'primerNombre'     => $dato['primerNombre'],
                    'apellidoPaterno'  => $dato['apellidoPaterno'],
                    'ci'               => $dato['ci'],
                    'numeroCelular'    => $dato['celular'],
                    'emailCorporativo' => $dato['email'],
                    'passwordHash'     => $passwordSegura,
                    'rol'              => 'Tecnico', // Rol correcto
                    'estado'           => 'Activo',
                    'fechaIngreso'     => $now,
                    'created_at'       => $now,
                    'updated_at'       => $now
                ]);

                // B. Crear TECNICO (Hija)
                DB::table('tecnicos')->insert([
                    'idEmpleado'         => $idEmpleado,
                    'especialidad'       => $dato['especialidad'],
                    'estadoDisponibilidad' => 'Disponible'
                ]);

                echo "✅ Técnico creado: " . $dato['primerNombre'] . "\n";
            } else {
                echo "⚠️ El técnico " . $dato['primerNombre'] . " ya existe.\n";
            }
        }
    }
}