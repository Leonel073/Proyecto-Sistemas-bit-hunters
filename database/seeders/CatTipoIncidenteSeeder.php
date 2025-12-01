<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatTipoIncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['idTipoIncidente' => 1, 'nombreIncidente' => 'Falla de Conexión', 'descripcion' => 'Problemas de conectividad total o parcial.'],
            ['idTipoIncidente' => 2, 'nombreIncidente' => 'Lentitud de Servicio', 'descripcion' => 'Velocidad por debajo de lo contratado.'],
            ['idTipoIncidente' => 3, 'nombreIncidente' => 'Instalación o Cambio de Servicio', 'descripcion' => 'Solicitudes de traslado o nueva instalación.'],
            ['idTipoIncidente' => 4, 'nombreIncidente' => 'Problema con Equipo (Router/Módem)', 'descripcion' => 'Fallas en el hardware proporcionado.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('cat_tipo_incidente')->updateOrInsert(
                ['idTipoIncidente' => $tipo['idTipoIncidente']],
                [
                    'nombreIncidente' => $tipo['nombreIncidente'],
                    'descripcion' => $tipo['descripcion']
                ]
            );
        }
        
        echo "✅ Tipos de incidente actualizados (1-4).\n";
    }
}
