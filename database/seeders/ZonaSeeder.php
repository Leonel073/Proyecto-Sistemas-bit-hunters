<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zona;

class ZonaSeeder extends Seeder
{
    public function run()
    {
        $zonas = [
            ['nombreZona' => 'Zona Norte', 'descripcion' => 'Sector norte de la ciudad, incluye barrios residenciales.'],
            ['nombreZona' => 'Zona Sur', 'descripcion' => 'Sector sur, área comercial y residencial.'],
            ['nombreZona' => 'Zona Este', 'descripcion' => 'Sector este, zona industrial y nuevos desarrollos.'],
            ['nombreZona' => 'Zona Oeste', 'descripcion' => 'Sector oeste, área universitaria y parques.'],
            ['nombreZona' => 'Centro Histórico', 'descripcion' => 'Casco viejo y centro administrativo.'],
        ];

        foreach ($zonas as $zona) {
            Zona::firstOrCreate(
                ['nombreZona' => $zona['nombreZona']],
                $zona
            );
        }
    }
}
