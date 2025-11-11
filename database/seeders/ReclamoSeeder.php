<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reclamo;
use App\Models\CatTipoIncidente;
use App\Models\SlaPolitica;

class ReclamoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener tipos de incidente y SLA política
        $tipoConexion = CatTipoIncidente::firstOrCreate(
            ['idTipoIncidente' => 1],
            ['nombreIncidente' => 'Conexión de Internet']
        );

        $tipoEquipo = CatTipoIncidente::firstOrCreate(
            ['idTipoIncidente' => 2],
            ['nombreIncidente' => 'Equipo de Cómputo']
        );

        $tipoSoftware = CatTipoIncidente::firstOrCreate(
            ['idTipoIncidente' => 3],
            ['nombreIncidente' => 'Software']
        );

        $slaPolitica = SlaPolitica::firstOrCreate(
            ['idPoliticaSLA' => 1],
            [
                'nombrePolitica' => 'SLA Estándar',
                'prioridad' => 'Media',
                'tiempoMaxSolucionHoras' => 24,
                'estaActiva' => true
            ]
        );

        // Crear reclamos de prueba
        Reclamo::create([
            'idUsuario' => 2, // Usuario de prueba
            'idOperador' => null, // Sin operador asignado
            'idTecnicoAsignado' => null,
            'idPoliticaSLA' => $slaPolitica->idPoliticaSLA,
            'idTipoIncidente' => $tipoConexion->idTipoIncidente,
            'idCausaRaiz' => null,
            'titulo' => 'Internet no funciona en oficina',
            'descripcionDetallada' => 'La conexión de internet en la oficina se ha caído y no podemos acceder a los sistemas.',
            'solucionTecnica' => null,
            'estado' => 'Nuevo',
            'prioridad' => 'Alta',
            'latitudIncidente' => -16.5,
            'longitudIncidente' => -68.15,
        ]);

        Reclamo::create([
            'idUsuario' => 2,
            'idOperador' => null,
            'idTecnicoAsignado' => null,
            'idPoliticaSLA' => $slaPolitica->idPoliticaSLA,
            'idTipoIncidente' => $tipoEquipo->idTipoIncidente,
            'idCausaRaiz' => null,
            'titulo' => 'Computadora no enciende',
            'descripcionDetallada' => 'La computadora de mi escritorio no enciende desde esta mañana.',
            'solucionTecnica' => null,
            'estado' => 'Nuevo',
            'prioridad' => 'Media',
            'latitudIncidente' => -16.5,
            'longitudIncidente' => -68.15,
        ]);

        Reclamo::create([
            'idUsuario' => 2,
            'idOperador' => null,
            'idTecnicoAsignado' => null,
            'idPoliticaSLA' => $slaPolitica->idPoliticaSLA,
            'idTipoIncidente' => $tipoSoftware->idTipoIncidente,
            'idCausaRaiz' => null,
            'titulo' => 'Error en aplicación de facturación',
            'descripcionDetallada' => 'La aplicación de facturación muestra un error al intentar generar reportes.',
            'solucionTecnica' => null,
            'estado' => 'Nuevo',
            'prioridad' => 'Media',
            'latitudIncidente' => -16.5,
            'longitudIncidente' => -68.15,
        ]);

        Reclamo::create([
            'idUsuario' => 2,
            'idOperador' => null,
            'idTecnicoAsignado' => null,
            'idPoliticaSLA' => $slaPolitica->idPoliticaSLA,
            'idTipoIncidente' => $tipoConexion->idTipoIncidente,
            'idCausaRaiz' => null,
            'titulo' => 'VPN no conecta',
            'descripcionDetallada' => 'No puedo conectarme a la VPN corporativa desde casa.',
            'solucionTecnica' => null,
            'estado' => 'Nuevo',
            'prioridad' => 'Alta',
            'latitudIncidente' => -16.5,
            'longitudIncidente' => -68.15,
        ]);

        echo "✅ Se crearon 4 reclamos de prueba\n";
    }
}
