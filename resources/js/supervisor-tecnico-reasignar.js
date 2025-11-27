// supervisor-tecnico-reasignar.js — Lógica para Reasignación de Reclamos entre Técnicos

// Variables globales (inyectadas desde el Blade)
let reclamosData = window.reclamosData || [];
let tecnicosData = window.tecnicosData || [];

let statsContainer;
let reclamosTable;
let modal;
let modalTitle;
let modalBody;
let selectedReclamo = null;

function initializeDOM() {
    statsContainer = document.getElementById('statsContainer');
    reclamosTable = document.getElementById('reclamosTable');
    modal = document.getElementById('modal');
    modalTitle = document.getElementById('modalTitle');
    modalBody = document.getElementById('modalBody');
    
    console.log('✅ DOM elements initialized');
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
        const errorData = await res.json().catch(() => ({}));
        throw new Error(errorData.message || 'Error en el servidor.');
    }
    
    return res.json();
}

// ------------------------------------------------------------------
// LÓGICA DEL MODAL DE REASIGNACIÓN Y AJUSTE (Combinado)
// ------------------------------------------------------------------

window.abrirModalReasignar = function(reclamoId) {
    selectedReclamo = reclamosData.find(r => r.idReclamo === reclamoId);
    if (!selectedReclamo) {
        alert('Reclamo no encontrado');
        return;
    }

    // Opciones de Estado y Prioridad (Basadas en ENUMs de la BD)
    const estados = ['Nuevo', 'Abierto', 'Asignado', 'En Proceso', 'Resuelto', 'Cerrado', 'Cancelado'];
    const prioridades = ['Baja', 'Media', 'Alta', 'Urgente'];

    let estadoOptions = estados.map(e => 
        `<option value="${e}" ${selectedReclamo.estado === e ? 'selected' : ''}>${e}</option>`
    ).join('');

    let prioridadOptions = prioridades.map(p => 
        `<option value="${p}" ${selectedReclamo.prioridad === p ? 'selected' : ''}>${p}</option>`
    ).join('');
    
    // Opciones de Técnico
    let tecnicoOptions = `<option value="">Selecciona un técnico</option>`;
    tecnicoOptions += tecnicosData.map(tec => {
        const nombreCompleto = `${tec.primerNombre} ${tec.apellidoPaterno}`;
        const selected = selectedReclamo.idTecnicoAsignado == tec.idEmpleado ? 'selected' : '';
        const especialidad = tec.tecnico?.especialidad ? ` (${tec.tecnico.especialidad})` : '';
        return `<option value="${tec.idEmpleado}" ${selected}>${nombreCompleto}${especialidad}</option>`;
    }).join('');

    const clienteNombre = selectedReclamo.usuario 
        ? `${selectedReclamo.usuario.primerNombre || ''} ${selectedReclamo.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
        : 'N/A';
    const tecnicoActual = selectedReclamo.tecnico 
        ? `${selectedReclamo.tecnico.primerNombre || ''} ${selectedReclamo.tecnico.apellidoPaterno || ''}`.trim() || 'Sin asignar'
        : 'Sin asignar';
    const operadorNombre = selectedReclamo.operador 
        ? `${selectedReclamo.operador.primerNombre || ''} ${selectedReclamo.operador.apellidoPaterno || ''}`.trim() || 'N/A'
        : 'N/A';

    const infoHtml = `
        <div class="info-card" style="padding: 15px; background: #f8f9fa; border-radius: 4px; margin-bottom: 20px;">
            <p><strong>ID:</strong> R-${selectedReclamo.idReclamo}</p>
            <p><strong>Título:</strong> ${selectedReclamo.titulo}</p>
            <p><strong>Cliente:</strong> ${clienteNombre}</p>
            <p><strong>Operador:</strong> ${operadorNombre}</p>
            <p><strong>Técnico Actual:</strong> ${tecnicoActual}</p>
            <p><strong>Estado:</strong> ${selectedReclamo.estado}</p>
            <p><strong>Prioridad:</strong> ${selectedReclamo.prioridad}</p>
        </div>
        
        <form id="reasignarGestionForm" onsubmit="event.preventDefault(); reasignarTecnicoBackend(${selectedReclamo.idReclamo})">
            
            <h4 class="mt-4">Reasignar Técnico</h4>
            <div class="form-group mb-3">
                <label class="form-label">Técnico Destino</label>
                <select id="tecnicoSelect" class="form-control" required>
                    ${tecnicoOptions}
                </select>
            </div>
            
            <h4 class="mt-4">Ajuste de Gestión (Prioridad/Estado)</h4>
            
            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group mb-3" style="flex: 1;">
                    <label class="form-label">Prioridad</label>
                    <select id="prioridadSelect" class="form-control" required>
                        ${prioridadOptions}
                    </select>
                </div>
                
                <div class="form-group mb-3" style="flex: 1;">
                    <label class="form-label">Estado</label>
                    <select id="estadoSelect" class="form-control" required>
                        ${estadoOptions}
                    </select>
                </div>
            </div>
            
            <!-- Campo oculto para la política SLA (obligatoria en la DB) -->
            <input type="hidden" id="slaSelect" value="${selectedReclamo.idPoliticaSLA || 1}">
            
            <button type="submit" class="btn btn-warning mt-4 w-100" style="background-color: #ffc107; color: #000; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
                Guardar y Reasignar
            </button>
        </form>
    `;

    modalTitle.textContent = `Gestionar Reclamo R-${selectedReclamo.idReclamo}`;
    modalBody.innerHTML = infoHtml;
    modal.classList.remove('hidden');
}

async function reasignarTecnicoBackend(reclamoId) {
    // Recoge todos los datos del modal
    const tecnicoId = document.getElementById('tecnicoSelect').value;
    const prioridad = document.getElementById('prioridadSelect').value;
    const estado = document.getElementById('estadoSelect').value;
    const idPoliticaSLA = document.getElementById('slaSelect').value; 
    
    if (!tecnicoId) { 
        alert('Por favor selecciona un técnico de destino'); 
        return; 
    }

    try {
        // Enviar todos los campos de gestión junto con el ID del técnico
        const data = await fetchApi(`/supervisor/tecnicos/reclamo/${reclamoId}/reasignar`, {
            method: 'PUT',
            body: JSON.stringify({ 
                idTecnico: parseInt(tecnicoId),
                prioridad: prioridad,
                estado: estado,
                idPoliticaSLA: parseInt(idPoliticaSLA) 
            })
        });
        
        // Manejo de la respuesta
        alert(data.message || 'Técnico reasignado y gestión actualizada correctamente.');
        closeModal();
        window.location.reload(); // Recargar la página para ver los cambios
    } catch(e) { 
        console.error('❌ Error:', e);
        alert(e.message || 'Error al reasignar técnico'); 
    }
}

// ------------------------------------------------------------------
// LÓGICA DE RENDERIZADO DEL DASHBOARD (Usando los datos de PHP)
// ------------------------------------------------------------------

function renderStats(reclamos) {
    const total = reclamos.length;
    const enProceso = reclamos.filter(r => r.estado === 'En Proceso').length;
    const asignados = reclamos.filter(r => r.estado === 'Asignado').length;
    const urgentes = reclamos.filter(r => r.prioridad === 'Urgente').length;

    statsContainer.innerHTML = `
        <div class="stat-card">
            <span class="label">Total Asignados</span>
            <div class="value">${total}</div>
        </div>
        <div class="stat-card">
            <span class="label">En Proceso</span>
            <div class="value" style="color:#2563eb">${enProceso}</div>
        </div>
        <div class="stat-card">
            <span class="label">Asignados</span>
            <div class="value" style="color:#ea580c">${asignados}</div>
        </div>
        <div class="stat-card">
            <span class="label">Urgentes</span>
            <div class="value" style="color:#dc2626">${urgentes}</div>
        </div>
    `;
}

function getPrioridadBadge(prio) {
    const cls = {
        'Baja': 'badge-baja',
        'Media': 'badge-media',
        'Alta': 'badge-alta',
        'Urgente': 'badge-urgente'
    }[prio] || 'badge-media';
    return `<span class="badge ${cls}">${prio}</span>`;
}

function getEstadoBadge(estado) {
    const map = {
        'Nuevo': '<span class="badge badge-secondary">Nuevo</span>',
        'Abierto': '<span class="badge badge-default">Abierto</span>',
        'Asignado': '<span class="badge badge-green">Asignado</span>',
        'En Proceso': '<span class="badge badge-yellow">En Proceso</span>',
        'Resuelto': '<span class="badge badge-green">Resuelto</span>',
        'Cerrado': '<span class="badge badge-gray-outline">Cerrado</span>',
        'Cancelado': '<span class="badge badge-red">Cancelado</span>'
    };
    return map[estado] || map['Nuevo'];
}

function formatDate(dt) {
    try { 
        return new Date(dt).toLocaleDateString('es-BO', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }); 
    } catch(e) { 
        return dt; 
    }
}

function renderDashboard() {
    if (!reclamosData || reclamosData.length === 0) {
        reclamosTable.innerHTML = `<div class="empty-state" style="padding: 40px; text-align: center; color: #666;"><p>No hay reclamos asignados a técnicos para reasignar.</p></div>`;
        return;
    }

    renderStats(reclamosData);

    let html = `
        <table class="users-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">ID</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Título</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Cliente</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Técnico Actual</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Prioridad</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Estado</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Fecha</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Acción</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    reclamosData.forEach(r => {
        const tecnicoActual = r.tecnico 
            ? `${r.tecnico.primerNombre || ''} ${r.tecnico.apellidoPaterno || ''}`.trim() || 'Sin asignar'
            : 'Sin asignar';
        const clienteNombre = r.usuario 
            ? `${r.usuario.primerNombre || ''} ${r.usuario.apellidoPaterno || ''}`.trim() || 'N/A'
            : 'N/A';
        
        html += `
            <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 10px;">R-${r.idReclamo}</td>
                <td style="padding: 10px;">${r.titulo}</td>
                <td style="padding: 10px;">${clienteNombre}</td>
                <td style="padding: 10px;">${tecnicoActual}</td>
                <td style="padding: 10px;">${getPrioridadBadge(r.prioridad)}</td>
                <td style="padding: 10px;">${getEstadoBadge(r.estado)}</td>
                <td style="padding: 10px;">${formatDate(r.fechaCreacion)}</td>
                <td style="padding: 10px;" class="actions">
                    <button class="btn btn-action" onclick="abrirModalReasignar(${r.idReclamo})" style="background-color: #e0a800; color: #000; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer;">
                        Reasignar
                    </button>
                </td>
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    reclamosTable.innerHTML = html;
}

// Inicializar el DOM y el renderizado al cargar la página
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initializeDOM();
        renderDashboard();
    });
} else {
    initializeDOM();
    renderDashboard();
}

// Funciones globales
window.closeModal = function() { 
    if (modal) {
        modal.classList.add('hidden'); 
    }
    selectedReclamo = null; 
}

window.abrirModalReasignar = abrirModalReasignar;
window.reasignarTecnicoBackend = reasignarTecnicoBackend;

export { renderDashboard };

