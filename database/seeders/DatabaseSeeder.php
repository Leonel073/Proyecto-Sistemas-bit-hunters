<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsuarioSeeder; // Asegúrate de importar todas las clases

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tablas Padre (Usuarios)
        $this->call(UsuarioSeeder::class);
        
        // 2. Empleados y Roles (Dependen de Usuarios o son Tablas Padre)
        $this->call(AdminSeeder::class);
        $this->call(TecnicoSeeder::class);
        $this->call(OperadorPruebaSeeder::class);

        // 3. Tablas Hijo y Datos de Prueba (Dependen de la existencia de Usuarios, SLAs, etc.)
        // Nota: ReclamoSeeder crea sus propias políticas SLA y tipos de incidente antes de los reclamos.
        $this->call(ReclamoSeeder::class); 
    }
}