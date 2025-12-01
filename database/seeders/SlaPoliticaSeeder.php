<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SlaPolitica;

class SlaPoliticaSeeder extends Seeder
{
    public function run()
    {
        $politicas = [
            [
                'nombrePolitica' => 'Emergencia Crítica',
                'prioridad' => 'Urgente',
                'tiempoMaxSolucionHoras' => 4,
                'estaActiva' => 1,
            ],
            [
                'nombrePolitica' => 'Prioridad Alta',
                'prioridad' => 'Alta',
                'tiempoMaxSolucionHoras' => 24,
                'estaActiva' => 1,
            ],
            [
                'nombrePolitica' => 'Atención Normal',
                'prioridad' => 'Media',
                'tiempoMaxSolucionHoras' => 48,
                'estaActiva' => 1,
            ],
            [
                'nombrePolitica' => 'Baja Prioridad',
                'prioridad' => 'Baja',
                'tiempoMaxSolucionHoras' => 72,
                'estaActiva' => 1,
            ],
        ];

        foreach ($politicas as $politica) {
            SlaPolitica::updateOrCreate(
                ['prioridad' => $politica['prioridad']], // Evita duplicados por prioridad
                $politica
            );
        }
    }
}
