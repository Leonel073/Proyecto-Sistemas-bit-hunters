// operador.js — usa fetch a endpoints Laravel para obtener/actuar sobre reclamos

let operadorNombreEl;
let statsContainer;
let nuevosReclamosTable;
let misCasosTable;
let modal;
let modalTitle;
let modalBody;

let selectedReclamo = null;

function initializeDOM() {
  operadorNombreEl = document.getElementById('operadorNombre');
  statsContainer = document.getElementById('statsContainer');
  nuevosReclamosTable = document.getElementById('nuevosReclamosTable');
  misCasosTable = document.getElementById('misCasosTable');
  modal = document.getElementById('modal');
  modalTitle = document.getElementById('modalTitle');
  modalBody = document.getElementById('modalBody');
  
  console.log('✅ DOM elements initialized');
}

function csrf() {
  return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

async function fetchJson(url, opts = {}) {
  const res = await fetch(url, opts);
  if (!res.ok) throw new Error('HTTP ' + res.status);
  return res.json();
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', async () => {
    initializeDOM();
    await renderDashboard();
  });
} else {
  initializeDOM();
  renderDashboard().catch(console.error);
}

async function renderDashboard() {
  try {
    console.log('🔄 Iniciando renderDashboard...');
    const [nuevos, mis] = await Promise.all([
      fetchJson('/operador/reclamos/nuevos'),
      fetchJson('/operador/reclamos/mis')
    ]);

    console.log('✅ Datos obtenidos:', { nuevos: nuevos.length, mis: mis.length });
    renderStats(nuevos, mis);
    renderNuevosReclamos(nuevos);
    renderMisCasos(mis);
  } catch (e) {
    console.error('❌ Error en renderDashboard:', e);
  }
}

function renderStats(nuevos, mis) {
  const pendientes = mis.filter(c => c.estado === 'Abierto' && !c.idTecnicoAsignado).length;
  const urgentes = nuevos.filter(r => r.prioridad === 'Urgente').length + mis.filter(r => r.prioridad === 'Urgente').length;

  statsContainer.innerHTML = `
    <div class="stat-card">
      <span class="label">Casos Nuevos</span>
      <div class="value">${nuevos.length}</div>
    </div>
    <div class="stat-card">
      <span class="label">Mis Casos</span>
      <div class="value" style="color:#2563eb">${mis.length}</div>
    </div>
    <div class="stat-card">
      <span class="label">Pendientes Asignar</span>
      <div class="value" style="color:#ea580c">${pendientes}</div>
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
  try { return new Date(dt).toLocaleDateString('es-BO'); } catch(e) { return dt; }
}

function renderNuevosReclamos(reclamos) {
  console.log('📋 renderNuevosReclamos:', reclamos.length);
  if (!reclamos || reclamos.length === 0) {
    nuevosReclamosTable.innerHTML = `<div class="empty-state"><p>No hay casos nuevos</p></div>`;
    return;
  }

  let html = ``;
  reclamos.forEach(r => {
    html += `
      <div class="reclamo-row">
        <h3>R-${r.idReclamo} — ${r.titulo}</h3>
        <p>${r.descripcionDetallada ? r.descripcionDetallada.substring(0, 200) : '—'}</p>
        <p><strong>Usuario:</strong> ${r.usuario?.primerNombre ?? r.nombre ?? '—'}</p>
        <p><strong>Prioridad:</strong> ${getPrioridadBadge(r.prioridad)}</p>
        <button class="btn btn-primary" onclick="tomarCaso(${r.idReclamo})">Tomar Caso</button>
      </div>
    `;
  });
  nuevosReclamosTable.innerHTML = html;
}

function renderMisCasos(casos) {
  console.log('📋 renderMisCasos:', casos.length);
  if (!casos || casos.length === 0) {
    misCasosTable.innerHTML = `<div class="empty-state"><p>No tienes casos asignados</p></div>`;
    return;
  }

  let html = ``;
  casos.forEach(r => {
    const tecnicoNombre = r.tecnicoNombre ?? 'Sin asignar';
    html += `
      <div class="reclamo-row">
        <h3>R-${r.idReclamo} — ${r.titulo}</h3>
        <p><strong>Estado:</strong> ${r.estado} | <strong>Prioridad:</strong> ${r.prioridad}</p>
        <p>${r.descripcionDetallada}</p>
        <p><strong>Usuario:</strong> ${r.usuario?.primerNombre ?? r.nombre ?? '—'}</p>
        <p><strong>Técnico:</strong> ${tecnicoNombre}</p>
        <button class="btn btn-primary" onclick="abrirModal(${r.idReclamo})">Ver / Asignar</button>
      </div>
    `;
  });
  misCasosTable.innerHTML = html;
}

async function tomarCaso(id) {
  try {
    console.log('🖱️ tomarCaso clicked:', id);
    const res = await fetch(`/operador/reclamo/tomar/${id}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json' }
    });
    
    if (!res.ok) {
      if (res.status === 401) {
        alert('Tu sesión ha expirado. Por favor inicia sesión nuevamente.');
        window.location.href = '/login';
        return;
      }
      const data = await res.json();
      throw new Error(data.message || 'No se pudo tomar el caso');
    }
    
    const data = await res.json();
    alert(data.message || 'Caso asignado a tu panel');
    await renderDashboard();
  } catch (e) { 
    console.error('❌ Error:', e);
    alert(e.message); 
  }
}

async function abrirModal(id) {
  try {
    const mis = await fetchJson('/operador/reclamos/mis');
    const nuevos = await fetchJson('/operador/reclamos/nuevos');
    const all = mis.concat(nuevos);
    selectedReclamo = all.find(r => r.idReclamo === id);
    if (!selectedReclamo) return;

    let tecnicos = [];
    try { 
      tecnicos = await fetchJson('/operador/tecnicos'); 
    } catch(e) { 
      console.error('Error al obtener técnicos:', e);
    }

    let infoHtml = `<div class="card"><div class="card-header"><h3 class="card-title">Información del Caso</h3></div><div class="card-content">`;
    infoHtml += `<div><p class="text-muted">Cliente</p><p>${selectedReclamo.usuario?.primerNombre ?? selectedReclamo.nombre ?? '—'}</p></div>`;
    infoHtml += `<div><p class="text-muted">Título</p><p>${selectedReclamo.titulo}</p></div>`;
    infoHtml += `<div><p class="text-muted">Descripción</p><p>${selectedReclamo.descripcionDetallada}</p></div>`;
    infoHtml += `</div></div>`;

    if (!selectedReclamo.idTecnicoAsignado) {
      let options = `<option value="">Selecciona un técnico</option>`;
      if (tecnicos && tecnicos.length > 0) {
        options += tecnicos.map(t => `<option value="${t.idEmpleado}">${t.primerNombre} ${t.apellidoPaterno}</option>`).join('');
      }
      infoHtml += `<div class="card"><div class="card-header"><h3 class="card-title">Asignar a Técnico de Campo</h3></div><div class="card-content"><div class="form-group"><label class="form-label">Seleccionar Técnico</label><select id="tecnicoSelect" class="form-select">${options}</select></div><div class="form-group"><label class="form-label">Instrucciones</label><textarea id="comentarioInput" class="form-textarea" placeholder="Agrega instrucciones para el técnico..."></textarea></div><button class="btn btn-primary" onclick="asignarTecnicoBackend(${selectedReclamo.idReclamo})">Asignar Técnico</button></div></div>`;
    } else {
      infoHtml += `<div class="card"><div class="card-content"><p>Asignado a: ${selectedReclamo.tecnicoNombre}</p></div></div>`;
    }

    modalTitle.textContent = `Gestionar Caso R-${selectedReclamo.idReclamo}`;
    modalBody.innerHTML = infoHtml;
    modal.classList.remove('hidden');
  } catch (e) { console.error(e); }
}

async function asignarTecnicoBackend(reclamoId) {
  const tecnicoId = document.getElementById('tecnicoSelect').value;
  const comentario = document.getElementById('comentarioInput').value.trim();
  if (!tecnicoId || !comentario) { alert('Selecciona técnico y escribe instrucciones'); return; }

  try {
    const res = await fetch(`/operador/reclamo/asignar-tecnico/${reclamoId}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json' },
      body: JSON.stringify({ idTecnico: parseInt(tecnicoId), comentario })
    });
    
    if (!res.ok) {
      if (res.status === 401) {
        alert('Tu sesión ha expirado. Por favor inicia sesión nuevamente.');
        window.location.href = '/login';
        return;
      }
      const data = await res.json();
      throw new Error(data.message || 'Error al asignar técnico');
    }
    
    const data = await res.json();
    alert(data.message || 'Técnico asignado correctamente');
    modal.classList.add('hidden');
    await renderDashboard();
  } catch(e) { 
    console.error('❌ Error:', e);
    alert(e.message); 
  }
}

function closeModal() { 
  modal.classList.add('hidden'); 
  selectedReclamo = null; 
}

// Hacer funciones accesibles desde atributos inline (onclick) ya que Vite carga como módulo
window.tomarCaso = tomarCaso;
window.abrirModal = abrirModal;
window.asignarTecnicoBackend = asignarTecnicoBackend;
window.closeModal = closeModal;
window.renderDashboard = renderDashboard;

export { renderDashboard };
