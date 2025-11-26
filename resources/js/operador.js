// operador.js — usa fetch a endpoints Laravel para obtener/actuar sobre reclamos

let operadorNombreEl;
let statsContainer;
let nuevosReclamosTable;
let misCasosTable;
let nuevosCasosContainer;
let misCasosContainer;
let modal;
let modalTitle;
let modalBody;
let countNuevosEl;
let countMisCasosEl;
let countNuevosSmallEl;
let countMisCasosSmallEl;

let selectedReclamo = null;

function initializeDOM() {
  operadorNombreEl = document.getElementById('operadorNombre');
  statsContainer = document.getElementById('statsContainer');
  nuevosReclamosTable = document.getElementById('nuevosReclamosTable');
  misCasosTable = document.getElementById('misCasosTable');
  nuevosCasosContainer = document.getElementById('nuevosCasosContainer');
  misCasosContainer = document.getElementById('misCasosContainer');
  modal = document.getElementById('modal');
  modalTitle = document.getElementById('modalTitle');
  modalBody = document.getElementById('modalBody');
  countNuevosEl = document.getElementById('countNuevos');
  countMisCasosEl = document.getElementById('countMisCasos');
  countNuevosSmallEl = document.getElementById('countNuevosSmall');
  countMisCasosSmallEl = document.getElementById('countMisCasosSmall');
  
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
    setupNavHandlers();
    await renderDashboard();
    // permitir cerrar modal con Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeModal();
    });
  });
} else {
  initializeDOM();
  setupNavHandlers();
  renderDashboard().catch(console.error).then(() => {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeModal();
    });
  });
}

function setupNavHandlers() {
  // Detectar elementos de nav que usan .nav-item o .nav-link
  const items = Array.from(document.querySelectorAll('.nav-item[data-section], .nav-link[data-section]'));
  items.forEach(a => {
    a.addEventListener('click', async (e) => {
      e.preventDefault();
      const section = a.getAttribute('data-section');
      switchSection(section);
    });
  });
}

async function switchSection(name) {
  // Toggle active class
  document.querySelectorAll('.nav-item, .nav-link').forEach(n => n.classList.remove('active'));
  const active = document.querySelector('[data-section="' + name + '"]');
  if (active) active.classList.add('active');

  // Sections
  const sections = ['dashboard', 'casos', 'reportes'];
  sections.forEach(s => {
    const el = document.getElementById(s + 'Section');
    if (!el) return;
    if (s === name) el.classList.remove('hidden'); else el.classList.add('hidden');
  });

  // Cargar datos según la sección
  if (name === 'dashboard') {
    await renderDashboard();
  } else if (name === 'casos') {
    await loadCasosView();
  } else if (name === 'reportes') {
    // por ahora placeholder
    console.log('Sección reportes');
  }
}

async function loadCasosView() {
  try {
    const [nuevos, mis] = await Promise.all([
      fetchJson('/operador/reclamos/nuevos'),
      fetchJson('/operador/reclamos/mis')
    ]);

    // Render simple listas en los contenedores específicos
    if (nuevosCasosContainer) {
      if (!nuevos || nuevos.length === 0) {
        nuevosCasosContainer.innerHTML = '<div class="empty-state"><p>No hay casos nuevos</p></div>';
      } else {
        nuevosCasosContainer.innerHTML = '';
        nuevos.forEach(r => {
          const div = document.createElement('div');
          div.className = 'reclamo-row';
          div.innerHTML = `<h4>R-${r.idReclamo} — ${r.titulo}</h4><p>${r.descripcionDetallada?.substring(0,150) ?? ''}</p><p>${getPrioridadBadge(r.prioridad)} ${getEstadoBadge(r.estado)}</p><div style="margin-top:.5rem"><button class="btn btn-primary" onclick="tomarCaso(${r.idReclamo})">Tomar</button></div>`;
          nuevosCasosContainer.appendChild(div);
        });
      }
    }

    if (misCasosContainer) {
      if (!mis || mis.length === 0) {
        misCasosContainer.innerHTML = '<div class="empty-state"><p>No tienes casos asignados</p></div>';
      } else {
        misCasosContainer.innerHTML = '';
        mis.forEach(r => {
          const div = document.createElement('div');
          div.className = 'reclamo-row';
          div.innerHTML = `<h4>R-${r.idReclamo} — ${r.titulo}</h4><p>${r.descripcionDetallada?.substring(0,150) ?? ''}</p><p>${getPrioridadBadge(r.prioridad)} ${getEstadoBadge(r.estado)}</p><div style="margin-top:.5rem"><button class="btn btn-primary" onclick="abrirModal(${r.idReclamo})">Ver / Asignar</button></div>`;
          misCasosContainer.appendChild(div);
        });
      }
    }

    // actualizar contadores pequeños si existen
    if (countNuevosSmallEl) countNuevosSmallEl.textContent = (nuevos || []).length;
    if (countMisCasosSmallEl) countMisCasosSmallEl.textContent = (mis || []).length;

  } catch (e) {
    console.error('Error loadCasosView:', e);
  }
}

async function renderDashboard() {
  try {
    console.log('🔄 Iniciando renderDashboard...');
    const [nuevos, mis] = await Promise.all([
      fetchJson('/operador/reclamos/nuevos'),
      fetchJson('/operador/reclamos/mis')
    ]);

    console.log('✅ Datos obtenidos:', { nuevos: nuevos.length, mis: mis.length });
    
    // Actualizar contadores
    if (countNuevosEl) countNuevosEl.textContent = nuevos.length;
    if (countMisCasosEl) countMisCasosEl.textContent = mis.length;
    if (countNuevosSmallEl) countNuevosSmallEl.textContent = nuevos.length;
    if (countMisCasosSmallEl) countMisCasosSmallEl.textContent = mis.length;
    
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

  if (!statsContainer) return;

  statsContainer.innerHTML = `
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-envelope-open"></i></div>
      <span class="label">Casos Nuevos</span>
      <div class="value">${nuevos.length}</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-tasks"></i></div>
      <span class="label">Mis Casos</span>
      <div class="value" style="color:#4f46e5">${mis.length}</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
      <span class="label">Pendientes Asignar</span>
      <div class="value" style="color:#ea580c">${pendientes}</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
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
  if (!nuevosReclamosTable) return;
  
  if (!reclamos || reclamos.length === 0) {
    nuevosReclamosTable.innerHTML = `<div class="empty-state"><p>✓ No hay casos nuevos</p></div>`;
    return;
  }

  let html = ``;
  reclamos.forEach(r => {
    html += `
      <div class="reclamo-row">
        <div class="reclamo-header">
          <h3>R-${r.idReclamo} — ${r.titulo}</h3>
          <div>${getPrioridadBadge(r.prioridad)}</div>
        </div>
        <p class="reclamo-desc">${r.descripcionDetallada ? r.descripcionDetallada.substring(0, 200) : '—'}</p>
        <p class="reclamo-meta"><strong>👤 Usuario:</strong> ${r.usuario?.primerNombre ?? r.nombre ?? '—'}</p>
        <button class="btn btn-primary" onclick="tomarCaso(${r.idReclamo})">
          <i class="fas fa-hand-paper"></i> Tomar Caso
        </button>
      </div>
    `;
  });
  nuevosReclamosTable.innerHTML = html;
}

function renderMisCasos(casos) {
  console.log('📋 renderMisCasos:', casos.length);
  if (!misCasosTable) return;
  
  if (!casos || casos.length === 0) {
    misCasosTable.innerHTML = `<div class="empty-state"><p>✓ No tienes casos asignados</p></div>`;
    return;
  }

  let html = ``;
  casos.forEach(r => {
    const tecnicoNombre = r.tecnicoNombre ?? 'Sin asignar';
    html += `
      <div class="reclamo-row">
        <div class="reclamo-header">
          <h3>R-${r.idReclamo} — ${r.titulo}</h3>
          <div>${getPrioridadBadge(r.prioridad)}</div>
        </div>
        <p class="reclamo-meta">
          <strong>📊 Estado:</strong> ${r.estado} | 
          <strong>👥 Técnico:</strong> <span class="tech-badge">${tecnicoNombre}</span>
        </p>
        <p class="reclamo-desc">${r.descripcionDetallada}</p>
        <p class="reclamo-meta"><strong>👤 Usuario:</strong> ${r.usuario?.primerNombre ?? r.nombre ?? '—'}</p>
        <button class="btn btn-primary" onclick="abrirModal(${r.idReclamo})">
          <i class="fas fa-eye"></i> Ver / Asignar
        </button>
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

    let infoHtml = `
      <div class="modal-section">
        <h4 class="modal-section-title">📋 Información del Caso</h4>
        <div class="modal-info-grid">
          <div class="info-item">
            <span class="info-label">👤 Cliente</span>
            <p class="info-value">${selectedReclamo.usuario?.primerNombre ?? selectedReclamo.nombre ?? '—'}</p>
          </div>
          <div class="info-item">
            <span class="info-label">🎯 Título</span>
            <p class="info-value">${selectedReclamo.titulo}</p>
          </div>
          <div class="info-item full-width">
            <span class="info-label">📝 Descripción</span>
            <p class="info-value">${selectedReclamo.descripcionDetallada}</p>
          </div>
          <div class="info-item">
            <span class="info-label">⚡ Prioridad</span>
            <p class="info-value">${getPrioridadBadge(selectedReclamo.prioridad)}</p>
          </div>
          <div class="info-item">
            <span class="info-label">📊 Estado</span>
            <p class="info-value">${getEstadoBadge(selectedReclamo.estado)}</p>
          </div>
        </div>
      </div>
    `;

    if (!selectedReclamo.idTecnicoAsignado) {
      let options = `<option value="">Selecciona un técnico...</option>`;
      if (tecnicos && tecnicos.length > 0) {
        options += tecnicos.map(t => `<option value="${t.idEmpleado}">${t.primerNombre} ${t.apellidoPaterno}</option>`).join('');
      }
      infoHtml += `
        <div class="modal-section">
          <h4 class="modal-section-title">🔧 Asignar a Técnico</h4>
          <div class="form-group">
            <label class="form-label">Seleccionar Técnico</label>
            <select id="tecnicoSelect" class="form-select">${options}</select>
          </div>
          <div class="form-group">
            <label class="form-label">Instrucciones para el Técnico</label>
            <textarea id="comentarioInput" class="form-textarea" placeholder="Agrega instrucciones específicas..."></textarea>
          </div>
          <button class="btn btn-primary" onclick="asignarTecnicoBackend(${selectedReclamo.idReclamo})" style="width: 100%;">
            <i class="fas fa-check-circle"></i> Asignar Técnico
          </button>
        </div>
      `;
    } else {
      infoHtml += `
        <div class="modal-section">
          <h4 class="modal-section-title">✅ Caso Asignado</h4>
          <p>Asignado a: <strong>${selectedReclamo.tecnicoNombre}</strong></p>
        </div>
      `;
    }

    modalTitle.textContent = `Caso R-${selectedReclamo.idReclamo}`;
    if (document.getElementById('modalSubtitle')) {
      document.getElementById('modalSubtitle').textContent = selectedReclamo.titulo;
    }
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
window.switchSection = switchSection;

export { renderDashboard };
