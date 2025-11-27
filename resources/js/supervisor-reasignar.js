// supervisor-reasignar.js — Lógica para Reasignación y Ajuste de Gestión (Prioridad/Estado)

// Variables globales (inyectadas desde el Blade)
let reclamosData = window.reclamosData;
let operadoresData = window.operadoresData;

let reclamosTable;
let modal;
let selectedReclamo = null;

const API_GESTION_URL = '/supervisor/operadores/reclamo/'; // Base para /reclamo/{reclamo}/gestion

function initializeDOM() {
    reclamosTable = document.getElementById('reclamosTable');
    modal = document.getElementById('modal');
    // ... (otras inicializaciones)
}

function csrf() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

/**
 * Función robusta para hacer llamadas PUT/POST
 */
async function fetchApi(url, opts = {}) {
    const finalOpts = {
        ...opts,
        headers: {
            'X-CSRF-TOKEN': csrf(),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...opts.headers
        },
    };

    const res = await fetch(url, finalOpts);
    
    if (res.status === 401) {
        alert('Sesión expirada.');
        window.location.reload();
        return;
    }

    if (!res.ok) {
        const errorData = await res.json();
        throw new Error(errorData.message || 'Error en el servidor.');
    }
    
    return res.json();
}


// ------------------------------------------------------------------
// LÓGICA DEL MODAL DE REASIGNACIÓN Y AJUSTE (Combinado)
// ------------------------------------------------------------------

window.abrirModalReasignar = function(reclamoId) {
    selectedReclamo = reclamosData.find(r => r.idReclamo === reclamoId);
    if (!selectedReclamo) return;

    // Opciones de Estado y Prioridad (Basadas en ENUMs de la BD)
    const estados = ['Nuevo', 'Abierto', 'Asignado', 'En Proceso', 'Resuelto', 'Cerrado', 'Cancelado'];
    const prioridades = ['Baja', 'Media', 'Alta', 'Urgente'];

    let estadoOptions = estados.map(e => 
        `<option value="${e}" ${selectedReclamo.estado === e ? 'selected' : ''}>${e}</option>`
    ).join('');

    let prioridadOptions = prioridades.map(p => 
        `<option value="${p}" ${selectedReclamo.prioridad === p ? 'selected' : ''}>${p}</option>`
    ).join('');
    
    // Opciones de Operador
    let operadorOptions = `<option value="">Selecciona un operador</option>`;
    operadorOptions += operadoresData.map(op => {
        const nombreCompleto = `${op.primerNombre} ${op.apellidoPaterno}`;
        const selected = selectedReclamo.idOperador == op.idEmpleado ? 'selected' : '';
        return `<option value="${op.idEmpleado}" ${selected}>${nombreCompleto}${op.operador ? ` (${op.operador.turno})` : ''}</option>`;
    }).join('');


    const infoHtml = `
        <div class="info-card">
            <p><strong>ID:</strong> R-${selectedReclamo.idReclamo}</p>
            <p><strong>Título:</strong> ${selectedReclamo.titulo}</p>
            <p><strong>Cliente:</strong> ${selectedReclamo.usuario ? selectedReclamo.usuario.primerNombre : 'N/A'}</p>
        </div>
        
        <form id="reasignarGestionForm" onsubmit="event.preventDefault(); reasignarOperadorBackend(${selectedReclamo.idReclamo})">
            
            <h4 class="mt-4">Reasignar Operador</h4>
            <div class="form-group mb-3">
                <label class="form-label">Operador Destino</label>
                <select id="operadorSelect" class="form-control" required>
                    ${operadorOptions}
                </select>
            </div>
            
            <h4 class="mt-4">Ajuste de Gestión (Prioridad/Estado)</h4>
            
            <div class="form-row">
                <div class="form-group mb-3">
                    <label class="form-label">Prioridad</label>
                    <select id="prioridadSelect" class="form-control" required>
                        ${prioridadOptions}
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Estado</label>
                    <select id="estadoSelect" class="form-control" required>
                        ${estadoOptions}
                    </select>
                </div>
            </div>
            
            <!-- Campo oculto para la política SLA (obligatoria en la DB) -->
            <input type="hidden" id="slaSelect" value="${selectedReclamo.idPoliticaSLA}">
            
            <button type="submit" class="btn btn-warning mt-4 w-100">
                Guardar y Reasignar
            </button>
        </form>
    `;

    document.getElementById('modalTitle').textContent = `Gestionar Reclamo R-${selectedReclamo.idReclamo}`;
    document.getElementById('modalBody').innerHTML = infoHtml;
    modal.classList.remove('hidden');
}


async function reasignarOperadorBackend(reclamoId) {
    // Recoge todos los datos del modal
    const operadorId = document.getElementById('operadorSelect').value;
    const prioridad = document.getElementById('prioridadSelect').value;
    const estado = document.getElementById('estadoSelect').value;
    const idPoliticaSLA = document.getElementById('slaSelect').value; 
    
    if (!operadorId) { 
        alert('Por favor selecciona un operador de destino'); 
        return; 
    }

    try {
        // Enviar todos los campos de gestión junto con el ID del operador
        const res = await fetchApi(`/supervisor/operadores/reclamo/${reclamoId}/reasignar`, {
            method: 'PUT',
            body: JSON.stringify({ 
                idOperador: parseInt(operadorId),
                prioridad: prioridad,
                estado: estado,
                idPoliticaSLA: parseInt(idPoliticaSLA) 
            })
        });
        
        // Manejo de la respuesta
        const data = await res;
        alert(data.message || 'Operador reasignado y gestión actualizada correctamente.');
        closeModal();
        window.location.reload(); // Recargar la página para ver los cambios
    } catch(e) { 
        console.error('❌ Error:', e);
        alert(e.message || 'Error al reasignar operador'); 
    }
}

// ------------------------------------------------------------------
// LÓGICA DE RENDERIZADO DEL DASHBOARD (Usando los datos de PHP)
// ------------------------------------------------------------------

function renderDashboard() {
    // La lógica de renderDashboard se ejecuta inmediatamente al cargar el JS
    
    if (reclamosData && reclamosTable) {
        let html = ``;
        if (reclamosData.length === 0) {
            reclamosTable.innerHTML = `<div class="empty-state"><p>No hay reclamos pendientes de asignación.</p></div>`;
            return;
        }

        reclamosTable.innerHTML = `
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Cliente</th>
                        <th>Operador Actual</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    ${reclamosData.map(r => `
                        <tr>
                            <td>R-${r.idReclamo}</td>
                            <td>${r.titulo}</td>
                            <td>${r.usuario?.primerNombre || '—'}</td>
                            <td>${r.idOperador ? r.operador?.primerNombre : 'SIN ASIGNAR'}</td>
                            <td>${r.prioridad}</td>
                            <td>${r.estado}</td>
                            <td class="actions">
                                <form action="${window.route('supervisor.operadores.reasignar', r.idReclamo)}" method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="_token" value="${csrf()}">
                                    <input type="hidden" name="_method" value="PUT">
                                    
                                    <select name="idOperador" class="form-control" style="width:120px;" required>
                                        <option value="">Reasignar</option>
                                        ${operadoresData.map(op => `
                                            <option value="${op.idEmpleado}" ${r.idOperador === op.idEmpleado ? 'selected' : ''}>
                                                ${op.primerNombre} ${op.apellidoPaterno}
                                            </option>
                                        `).join('')}
                                    </select>
                                    <button type="submit" class="btn btn-action" style="background-color: #007bff;">Reasignar</button>
                                </form>
                                <button onclick="abrirModalReasignar(${r.idReclamo})" class="btn btn-action" style="background-color: #e0a800; margin-top: 5px;">
                                    Ajustar Gestión
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }
}

// Inicializar el DOM y el renderizado al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    initializeDOM();
    renderDashboard();
});

// Funciones globales
window.closeModal = function() { modal.classList.add('hidden'); }
window.abrirModalReasignar = abrirModalReasignar; 
window.reasignarOperadorBackend = reasignarOperadorBackend;
