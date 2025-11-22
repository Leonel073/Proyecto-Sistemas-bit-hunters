<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        // Tu nueva contraseña
        $passwordComun = Hash::make('Usu1234@'); 

        // USUARIO 1: Juan Perez
        DB::table('usuarios')->updateOrInsert(
            ['ci' => '8888888'], // 1. BUSCAMOS POR EL CI (que es único)
            [
                // 2. DATOS A INSERTAR O ACTUALIZAR
                'primerNombre'      => 'Juan',
                'segundoNombre'     => 'Carlos',
                'apellidoPaterno'   => 'Perez',
                'apellidoMaterno'   => 'Mamani',
                'numeroCelular'     => '70008888',
                'email'             => 'juan.perez@gmail.com',
                'passwordHash'      => $passwordComun, // Se actualiza la pass
                'direccionTexto'    => 'Av. 6 de Marzo, Zona Villa Bolivar',
                'estado'            => 'Activo',
                'fechaRegistro'     => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]
        );

        // USUARIO 2: Maria Quispe
        DB::table('usuarios')->updateOrInsert(
            ['ci' => '9999999'], // 1. BUSCAMOS POR EL CI
            [
                'primerNombre'      => 'Maria',
                'segundoNombre'     => null,
                'apellidoPaterno'   => 'Quispe',
                'apellidoMaterno'   => 'Flores',
                'numeroCelular'     => '60009999',
                'email'             => 'maria.quispe@hotmail.com',
                'passwordHash'      => $passwordComun, // Se actualiza la pass
                'direccionTexto'    => 'Calle 2, Zona Ciudad Satélite',
                'estado'            => 'Bloqueado',
                'fechaRegistro'     => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]
        );
    }
}