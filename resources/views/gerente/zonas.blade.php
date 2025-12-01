@extends('layouts.dashboard')

@section('title', 'Zonas/Comunidades - Admin')

@section('header')
    Administración <span class="text-indigo-600 text-lg font-medium ml-2">| Zonas y Comunidades</span>
@endsection

@push('styles')
    @vite(['resources/css/gerente.css'])
    <style>
        .zona-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .zona-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .zona-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .zona-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }
        .zona-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-primary-custom:hover { opacity: 0.9; }
        .btn-danger-custom {
            background: #ef4444;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
    </style>
@endpush

@section('content')
    <div class="zona-container">
        <div class="zona-header">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Zonas y Comunidades
                </h1>
                <p class="text-white/90">Gestión de zonas geográficas y comunidades rurales</p>
            </div>
            <button onclick="openModal()" class="btn-primary-custom">
                <i class="fas fa-plus mr-2"></i>
                Nueva Zona
            </button>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @forelse($zonas as $zona)
            <div class="zona-card">
                <div class="zona-card-header">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $zona->nombreZona }}</h3>
                        <span class="badge {{ $zona->estado == 'Activo' ? 'badge-active' : 'badge-inactive' }}">
                            {{ $zona->estado }}
                        </span>
                        @if($zona->descripcion)
                            <p class="text-gray-600 mt-2">{{ $zona->descripcion }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button onclick="editModal({{ $zona->idZona }}, '{{ addslashes($zona->nombreZona) }}', '{{ addslashes($zona->descripcion ?? '') }}', '{{ $zona->estado }}')" class="btn-primary-custom" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('gerente.zonas.destroy', $zona->idZona) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta zona?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-custom">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="zona-card text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">No hay zonas registradas</p>
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4" id="modalTitle">Nueva Zona</h2>
            <form id="zonaForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="zonaId">
                
                <div class="form-group">
                    <label>Nombre de la Zona/Comunidad</label>
                    <input type="text" name="nombreZona" id="nombreZona" required placeholder="Ej: Pacajes, Ingavi">
                </div>
                
                <div class="form-group">
                    <label>Descripción (Opcional)</label>
                    <textarea name="descripcion" id="descripcion" placeholder="Descripción de la zona o comunidad"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeModal()" style="padding: 0.75rem 1.5rem; background: #e5e7eb; border-radius: 0.5rem; border: none; cursor: pointer;">Cancelar</button>
                    <button type="submit" class="btn-primary-custom">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Nueva Zona';
            document.getElementById('zonaForm').action = '{{ route("gerente.zonas.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('zonaForm').reset();
            document.getElementById('zonaId').value = '';
        }

        function editModal(id, nombre, descripcion, estado) {
            document.getElementById('modal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Editar Zona';
            document.getElementById('zonaForm').action = '{{ route("gerente.zonas.update", "") }}/' + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('zonaId').value = id;
            document.getElementById('nombreZona').value = nombre;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('estado').value = estado;
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('active');
        }

        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
@endsection


