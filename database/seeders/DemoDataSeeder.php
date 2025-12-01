<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\SupervisorOperador;
use App\Models\SupervisorTecnico;
use App\Models\Operador;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('Nexora@2024'); // Contraseña unificada para pruebas

        // 1. Supervisor de Operadores
        $supOp = Empleado::create([
            'primerNombre' => 'Supervisor',
            'apellidoPaterno' => 'Operaciones',
            'ci' => '1000001',
            'numeroCelular' => '70000010',
            'emailCorporativo' => 'sup.operador@nexora.com',
            'passwordHash' => $password,
            'rol' => 'SupervisorOperador',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);
        SupervisorOperador::create(['idEmpleado' => $supOp->idEmpleado]);

        // 2. Supervisor Técnico
        $supTec = Empleado::create([
            'primerNombre' => 'Supervisor',
            'apellidoPaterno' => 'Tecnico',
            'ci' => '1000002',
            'numeroCelular' => '70000020',
            'emailCorporativo' => 'sup.tecnico@nexora.com',
            'passwordHash' => $password,
            'rol' => 'SupervisorTecnico',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);
        SupervisorTecnico::create([
            'idEmpleado' => $supTec->idEmpleado,
            'zonaGeograficaAsignada' => 'Zona Central'
        ]);

        // 3. Operador
        $operador = Empleado::create([
            'primerNombre' => 'Operador',
            'apellidoPaterno' => 'Demo',
            'ci' => '1000003',
            'numeroCelular' => '70000030',
            'emailCorporativo' => 'operador@nexora.com',
            'passwordHash' => $password,
            'rol' => 'Operador',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);
        Operador::create([
            'idEmpleado' => $operador->idEmpleado,
            'turno' => 'Mañana'
        ]);

        // 4. Técnico
        $tecnico = Empleado::create([
            'primerNombre' => 'Tecnico',
            'apellidoPaterno' => 'Demo',
            'ci' => '1000004',
            'numeroCelular' => '70000040',
            'emailCorporativo' => 'tecnico@nexora.com',
            'passwordHash' => $password,
            'rol' => 'Tecnico',
            'estado' => 'Activo',
            'fechaIngreso' => now(),
        ]);
        Tecnico::create([
            'idEmpleado' => $tecnico->idEmpleado,
            'especialidad' => 'Fibra Óptica',
            'estadoDisponibilidad' => 'Disponible',
            'latitud' => -16.5000,
            'longitud' => -68.1193
        ]);

        // 5. Cliente (Usuario)
        Usuario::create([
            'primerNombre' => 'Cliente',
            'apellidoPaterno' => 'Prueba',
            'ci' => '5000001',
            'numeroCelular' => '60000001',
            'email' => 'cliente@gmail.com',
            'passwordHash' => $password,
            'direccionTexto' => 'Av. 16 de Julio, El Prado',
            'estado' => 'Activo',
        ]);
    }
}
