<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders para llenar la base de datos con datos de prueba.
     * El orden es CRUCIAL debido a las llaves foráneas.
     */
    public function run(): void
    {
        $this->call([
            // 1. Catálogos (No dependen de nada más)
            // NOTA: Si tienes un seeder para SLA_POLITICA y CAT_TIPO_INCIDENTE, agrégalo aquí.
            
            // 2. Usuarios Base (Tablas Padre)
            AdminSeeder::class,                 // Crea al Gerente (necesario para el Supervisor)
            UsuarioSeeder::class,               // Crea a los Clientes (necesario para Reclamos)
            
            // 3. Empleados Hijos (Se construyen sobre la tabla 'empleados')
            SupervisorOperadorSeeder::class,    // Crea al Supervisor Operador
            OperadorPruebaSeeder::class,        // Crea al Operador de prueba
            TecnicoSeeder::class,               // Crea los 3 Técnicos
            SupervisorTecnicoSeeder::class,    // Crea al Supervisor de Técnicos
            
            // 4. Datos Transaccionales (Dependen de todo lo anterior)
            ReclamoSeeder::class,               // Crea los reclamos de prueba
        ]);
        
        echo "✅ Base de datos llenada exitosamente con datos de prueba.\n";
    }
}