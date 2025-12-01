@extends('layouts.panel')

@section('title', 'Historial de Migraciones de DB')

@section('content')

    <h2 style="color: #34495e;">💾 Historial de Migraciones</h2>

    <p>Lista de todas las migraciones ejecutadas en la base de datos, agrupadas por lote (Batch).</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="background-color: #3498db; color: white;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Migración</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Lote (Batch)</th>
            </tr>
        </thead>
        <tbody>
            @php $currentBatch = null; @endphp
            @foreach ($migrations as $migration)
                @if ($migration->batch !== $currentBatch)
                    <tr style="background-color: #f5f5f5; font-weight: bold;">
                        <td colspan="2" style="padding: 8px 10px; border: 1px solid #ddd; text-align: center;">LOTE #{{ $migration->batch }}</td>
                    </tr>
                    @php $currentBatch = $migration->batch; @endphp
                @endif
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $migration->migration }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $migration->batch }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection