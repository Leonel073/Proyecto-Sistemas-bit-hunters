<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;

class TecnicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos de técnicos a crear
        $datos_tecnicos = [
            [
                'email' => 'tecnico1@nexora.test',
                'nombre' => 'Técnico Uno',
                'apellido' => 'Sistema',
                'ci' => '8888888',
                'celular' => '77771111',
                'especialidad' => 'Redes e Internet'
            ],
            [
                'email' => 'tecnico2@nexora.test',
                'nombre' => 'Técnico Dos',
                'apellido' => 'Sistema',
                'ci' => '8888889',
                'celular' => '77772222',
                'especialidad' => 'Hardware y Equipos'
            ],
            [
                'email' => 'tecnico3@nexora.test',
                'nombre' => 'Técnico Tres',
                'apellido' => 'Sistema',
                'ci' => '8888890',
                'celular' => '77773333',
                'especialidad' => 'Software'
            ]
        ];

        // Crear o obtener empleados técnicos
        foreach ($datos_tecnicos as $dato) {
            $empleado = Empleado::firstOrCreate(
                ['emailCorporativo' => $dato['email']],
                [
                    'primerNombre' => $dato['nombre'],
                    'apellidoPaterno' => $dato['apellido'],
                    'ci' => $dato['ci'],
                    'numeroCelular' => $dato['celular'],
                    'passwordHash' => Hash::make('Tecnico123!'),
                    'rol' => 'Tecnico',
                    'estado' => 'Activo',
                    'fechaIngreso' => now()
                ]
            );

            // Crear o obtener registro técnico
            Tecnico::firstOrCreate(
                ['idEmpleado' => $empleado->idEmpleado],
                [
                    'especialidad' => $dato['especialidad'],
                    'estadoDisponibilidad' => 'Disponible'
                ]
            );
        }

        echo "✅ Se crearon/obtuvieron 3 técnicos de prueba\n";
    }
}

