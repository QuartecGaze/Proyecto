import {
  crearReunion as apiCrearReunion,
  getReunionesPendientes as apiGetReunionesPendientes,
  getReunionesCompletadas as apiGetReunionesCompletadas,
  completarReunion as apiCompletarReunion,
  eliminarReunion as apiEliminarReunion,
  editarReunion as apiEditarReunion            // <— NUEVO
} from '../../../BackEnd/APIFetchs/APIBackOffice.js';


  // === ESTADO GLOBAL ===
  let reuniones = [];
  let reunionEditando = null;
  
  // === MAPEO TIPO (select -> ENUM back) ===
  function mapTipoAEnum(valorSelect) {
    const mapa = {
      general: 'General',
      comision: 'Comisión',
      emergencia: 'Emergencia',
      planificacion: 'Planificacion'
    };
    return mapa[valorSelect] ?? 'General';
  }
  function mapEnumTipoAUI(valorBack) {
    const v = (valorBack || '').toLowerCase();
    if (v.startsWith('general')) return 'general';
    if (v.startsWith('comisión') || v.startsWith('comision')) return 'comision';
    if (v.startsWith('emergencia')) return 'emergencia';
    if (v.startsWith('planificacion') || v.startsWith('planificación')) return 'planificacion';
    return 'general';
  }
  function mapEstadoAUI(estadoBack) {
    const v = (estadoBack || '').toLowerCase();
    if (v.includes('pend') || v.includes('curso')) return 'pendiente'; // Pendiente / En curso
    if (v.includes('final') || v.includes('compl')) return 'completada'; // Finalizada
    if (v.includes('cancel')) return 'cancelada'; // Cancelada
    return 'pendiente';
  }
  
  // === DOM ===
  const modalReunion = document.getElementById('modalReunion');
  const modalDetalles = document.getElementById('modalDetallesReunion');
  const btnCrearReunion = document.getElementById('btnCrearReunion');
  const btnCancelarReunion = document.getElementById('btnCancelarReunion');
  const btnCerrarDetalles = document.getElementById('btnCerrarDetalles');
  const btnEditarReunion = document.getElementById('btnEditarReunion');
  const formReunion = document.getElementById('formReunion');
  const filtroEstado = document.getElementById('filtroEstado');
  const filtroFecha = document.getElementById('filtroFecha');
  
  // Mensajes (al estilo del módulo de horas)
  const msgOk = document.querySelector('#formReunion .mensaje-exito') || document.getElementById('msgReunionOk');
  const msgErr = document.querySelector('#formReunion .mensaje-error') || document.getElementById('msgReunionErr');
  
  function showOk(texto) {
    if (msgOk) { msgOk.textContent = texto; msgOk.style.display = 'block'; }
    if (msgErr) msgErr.style.display = 'none';
  }
  function showErr(texto) {
    if (msgErr) { msgErr.textContent = texto; msgErr.style.display = 'block'; }
    if (msgOk) msgOk.style.display = 'none';
  }
  function clearMsgs() {
    if (msgOk) msgOk.style.display = 'none';
    if (msgErr) msgErr.style.display = 'none';
  }
  
  // === EVENTOS ===
  document.addEventListener('DOMContentLoaded', () => {
    cargarReuniones();
    configurarEventListeners();
  });
  
  function configurarEventListeners() {
    // Modal crear/editar reunión
    btnCrearReunion?.addEventListener('click', abrirModalCrear);
    btnCancelarReunion?.addEventListener('click', cerrarModalReunion);
  
    // Modal detalles
    btnCerrarDetalles?.addEventListener('click', cerrarModalDetalles);
    btnEditarReunion?.addEventListener('click', editarReunionDesdeDetalles);
  
    // Formulario (envío al back)
    formReunion?.addEventListener('submit', guardarReunion);
  
    // Filtros
    filtroEstado?.addEventListener('change', filtrarReunionesYRender);
    filtroFecha?.addEventListener('change', filtrarReunionesYRender);
  
    // Cerrar modales al click fuera
    modalReunion?.addEventListener('click', (e) => { if (e.target === modalReunion) cerrarModalReunion(); });
    modalDetalles?.addEventListener('click', (e) => { if (e.target === modalDetalles) cerrarModalDetalles(); });
  
    // Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        cerrarModalReunion();
        cerrarModalDetalles();
      }
    });
  }
  
  // === MODALES ===
  function abrirModalCrear() {
    reunionEditando = null;
    clearMsgs();
    document.getElementById('tituloModal').innerHTML = '<i class="material-icons">event</i> Crear Nueva Reunión';
    formReunion?.reset();
    modalReunion.style.display = 'flex';
  }
  function abrirModalEditar(reunion) {
    reunionEditando = reunion;
    clearMsgs();
    document.getElementById('tituloModal').innerHTML = '<i class="material-icons">edit</i> Editar Reunión';
  
    document.getElementById('tituloReunion').value = reunion.titulo || '';
    document.getElementById('descripcionReunion').value = reunion.descripcion || '';
    document.getElementById('fechaReunion').value = reunion.fecha || '';
    document.getElementById('horaReunion').value = reunion.hora || '';
    document.getElementById('lugarReunion').value = reunion.lugar || '';
    document.getElementById('tipoReunion').value = reunion.tipo || 'general';
  
    modalReunion.style.display = 'flex';
  }
  function cerrarModalReunion() {
    modalReunion.style.display = 'none';
    reunionEditando = null;
    formReunion?.reset();
  }
  function abrirModalDetalles(reunion) {
    document.getElementById('detalleTitulo').textContent = reunion.titulo;
    document.getElementById('detalleDescripcion').textContent = reunion.descripcion;
    document.getElementById('detalleFechaHora').textContent = `${reunion.fecha} a las ${reunion.hora}`;
    document.getElementById('detalleLugar').textContent = reunion.lugar;
    document.getElementById('detalleTipo').textContent = obtenerTextoTipo(reunion.tipo);
    document.getElementById('detalleEstado').textContent = obtenerTextoEstado(reunion.estado);
    document.getElementById('detalleAsistentes').textContent = reunion.asistentes || 'Por confirmar';
  
    btnEditarReunion.onclick = () => {
      cerrarModalDetalles();
      abrirModalEditar(reunion);
    };
  
    modalDetalles.style.display = 'flex';
  }
  function cerrarModalDetalles() {
    modalDetalles.style.display = 'none';
  }
  function editarReunionDesdeDetalles() {
    cerrarModalDetalles();
  }
  
  // === CRUD ===
  function setBtnLoading(on) {
    const btnSubmit = formReunion?.querySelector('button[type="submit"]');
    if (!btnSubmit) return;
    btnSubmit.disabled = on;
    btnSubmit.dataset.originalText ??= btnSubmit.textContent;
    btnSubmit.textContent = on ? 'Guardando…' : btnSubmit.dataset.originalText;
  }

  function setBtnActionLoading(btn, on, labelIdle) {
    if (!btn) return;
    btn.disabled = on;
    if (on) {
      btn.dataset.originalText ??= btn.textContent;
      btn.textContent = 'Procesando…';
    } else {
      btn.textContent = labelIdle ?? btn.dataset.originalText ?? btn.textContent;
    }
  }

  
  function buildUIReunionFromForm() {
    return {
      id: reunionEditando ? reunionEditando.id : Date.now().toString(),
      titulo: document.getElementById('tituloReunion').value.trim(),
      descripcion: document.getElementById('descripcionReunion').value.trim(),
      fecha: document.getElementById('fechaReunion').value.trim(),   // YYYY-MM-DD
      hora: document.getElementById('horaReunion').value.trim(),     // HH:MM
      lugar: document.getElementById('lugarReunion').value.trim(),
      tipo: document.getElementById('tipoReunion').value.trim(),     // general|comision|...
      estado: 'pendiente',
      asistentes: 0,
      fechaCreacion: new Date().toISOString().split('T')[0]
    };
  }
  function validarReunionUI(r) {
    const faltan = [];
    if (!r.titulo) faltan.push('título');
    if (!r.fecha) faltan.push('fecha');
    if (!r.hora) faltan.push('hora');
    if (!r.lugar) faltan.push('lugar');
    if (!r.tipo) faltan.push('tipo');
    if (faltan.length) throw new Error(`Faltan: ${faltan.join(', ')}.`);
  }

  function respuestaOk(res) {
    const estado = (res?.estado || res?.status || '').toString().toLowerCase();
    const codigo = Number(res?.codigo ?? res?.code ?? 0);
    return (
      estado === 'exito' ||
      estado === 'success' ||
      res?.ok === true ||
      res?.success === true ||
      codigo === 200 ||
      (!res?.error && estado === '')
    );
  }

  
 async function guardarReunion(e) {
  e.preventDefault();
  clearMsgs();

  const rUI = buildUIReunionFromForm();
  try {
    validarReunionUI(rUI);

    const payload = {
      titulo: rUI.titulo,
      descripcion: rUI.descripcion,
      fecha: rUI.fecha,
      hora: rUI.hora,
      lugar: rUI.lugar,
      tipoDeReunion: mapTipoAEnum(rUI.tipo)
    };

    setBtnLoading(true);

    if (reunionEditando) {
      // === EDITAR ===
      const res = await apiEditarReunion({
        idReunion: String(reunionEditando.id),
        ...payload
      });

      if (!respuestaOk(res)) {
        throw new Error(res?.mensaje || res?.message || 'No se pudo editar la reunión en el servidor');
      }

      // Actualizo estado local (o podés hacer await cargarReuniones(); si preferís refetch)
      actualizarReunionLocal({ ...rUI, id: String(reunionEditando.id) });
      showOk(res?.mensaje || 'Reunión actualizada exitosamente');

    } else {
      // === CREAR ===
      const res = await apiCrearReunion(payload);
      if (!respuestaOk(res)) {
        throw new Error(res?.mensaje || res?.message || 'No se pudo crear la reunión en el servidor');
      }

      // Si el back devuelve ID, úsalo
      const idBack =
        res?.idReunion ?? res?.ID_Reunion ?? res?.insertId ??
        res?.data?.idReunion ?? res?.data?.ID_Reunion ?? res?.data?.insertId ?? null;

      const nueva = { ...rUI, id: idBack ? String(idBack) : rUI.id };
      crearReunionLocal(nueva);
      showOk(res?.mensaje || 'Reunión creada exitosamente');
    }

    cerrarModalReunion();
  } catch (err) {
    console.error(err);
    showErr(err?.message || 'Error al guardar la reunión');
  } finally {
    setBtnLoading(false);
  }
}

  
  // === Operaciones locales ===
  function crearReunionLocal(reunion) {
    reuniones.push(reunion);
    mostrarReuniones();
  }
  function actualizarReunionLocal(reunion) {
    const idx = reuniones.findIndex(r => r.id === reunion.id);
    if (idx !== -1) {
      reuniones[idx] = { ...reuniones[idx], ...reunion };
      mostrarReuniones();
    }
  }

  async function eliminarReunion(id, ev) {
    try {
      const ok = confirm('¿Estás seguro de que deseas eliminar esta reunión?');
      if (!ok) return;
  
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, true, 'Eliminar');
  
      // Llamada al backend
      const res = await apiEliminarReunion({ idReunion: id });
      const exito = (res?.estado || '').toLowerCase() === 'exito';
      if (!exito) throw new Error(res?.mensaje || 'No se pudo eliminar la reunión en el servidor');
  
      // Refresco UI local
      reuniones = reuniones.filter(r => r.id === String(id) ? false : true);
      mostrarReuniones();
      showOk(res?.mensaje || 'Reunión eliminada correctamente');
    } catch (e) {
      console.error(e);
      showErr(e?.message || 'Error al eliminar la reunión');
    } finally {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, false, 'Eliminar');
    }
  }
  
  async function completarReunion(id, ev) {
    try {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, true, 'Completar');
  
      // Llamada al backend
      const res = await apiCompletarReunion({ idReunion: id });
      const exito = (res?.estado || '').toLowerCase() === 'exito';
      if (!exito) throw new Error(res?.mensaje || 'No se pudo completar la reunión en el servidor');
  
      // Actualizo estado local y re-render
      const r = reuniones.find(x => x.id === String(id));
      if (r) r.estado = 'completada';
      mostrarReuniones();
      showOk(res?.mensaje || 'Reunión completada correctamente');
    } catch (e) {
      console.error(e);
      showErr(e?.message || 'Error al completar la reunión');
    } finally {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, false, 'Completar');
    }
  }

  
  
  // === HELPERS DE NORMALIZACIÓN ===
  function extractReuniones(res) {
    if (Array.isArray(res)) return res;
    if (Array.isArray(res?.reuniones)) return res.reuniones;
    if (Array.isArray(res?.data?.reuniones)) return res.data.reuniones;
    if (Array.isArray(res?.message?.reuniones)) return res.message.reuniones;
    return [];
  }
  function normalizeReunion(f) {
    const id   = f.idReunion ?? f.ID_Reunion ?? f.id ?? Date.now();
    const tipo = mapEnumTipoAUI(f.tipoDeReunion ?? f.Tipo_Reunion ?? f.tipo);
    const est  = mapEstadoAUI(f.estado ?? f.Estado_Reunion ?? f.estado_reunion);
    const fechaRaw = (f.fecha ?? f.Fecha ?? '').toString();
    const fecha = fechaRaw ? fechaRaw.slice(0, 10) : '';
    return {
      id: String(id),
      titulo: f.titulo ?? f.Nombre ?? f.nombre ?? '(Sin título)',
      descripcion: f.descripcion ?? f.Descripcion ?? '',
      fecha,
      hora: f.hora ?? f.Hora ?? '',
      lugar: f.lugar ?? f.Lugar ?? '',
      tipo,
      estado: est,
      asistentes: f.asistentes ?? 0,
      fechaCreacion: f.fecha_creacion ?? f.fechaCreacion ?? ''
    };
  }
  
  // === RENDER ===
  function mostrarReuniones() {
    const contPend = document.getElementById('reunionesPendientes');
    const contHist = document.getElementById('historialReuniones');
    if (!contPend || !contHist) return;
  
    const list = filtrarReuniones();
    const pendientes = list.filter(r => (r.estado || '').toLowerCase() === 'pendiente');
    const historial  = list.filter(r => (r.estado || '').toLowerCase() !== 'pendiente');
  
    contPend.innerHTML = pendientes.length
      ? pendientes.map(crearTarjetaReunion).join('')
      : '<p class="sin-reuniones">No hay reuniones pendientes</p>';
  
    contHist.innerHTML = historial.length
      ? historial.map(crearTarjetaReunion).join('')
      : '<p class="sin-reuniones">No hay reuniones en el historial</p>';
  }
  function filtrarReunionesYRender() { mostrarReuniones(); }
  
  function crearTarjetaReunion(reunion) {
    const fRaw = (reunion.fecha || '').toString();
    const fechaISO = fRaw ? fRaw.slice(0, 10) : '';
    const fechaFormateada = fechaISO ? new Date(fechaISO + 'T00:00:00').toLocaleDateString('es-ES') : '';
  
    return `
      <div class="tarjeta-reunion ${reunion.estado} ${reunion.tipo === 'emergencia' ? 'emergencia' : ''}"
           onclick="abrirModalDetalles(${JSON.stringify(reunion).replace(/"/g, '&quot;')})">
        <div class="cabecera-reunion">
          <div>
            <div class="titulo-reunion">${reunion.titulo}</div>
            <span class="badge-estado badge-${reunion.estado}">${obtenerTextoEstado(reunion.estado)}</span>
          </div>
        </div>
        <div class="info-reunion">
          <div class="info-item"><i class="material-icons">event</i><span>${fechaFormateada}</span></div>
          <div class="info-item"><i class="material-icons">schedule</i><span>${reunion.hora || ''}</span></div>
          <div class="info-item"><i class="material-icons">place</i><span>${reunion.lugar || ''}</span></div>
          <div class="info-item"><i class="material-icons">group</i><span>${reunion.asistentes ?? 0} asistentes</span></div>
        </div>
        <div class="descripcion-reunion">${reunion.descripcion || ''}</div>
        <div class="acciones-reunion">
          ${ (reunion.estado || '').toLowerCase() === 'pendiente' ? `
          <button class="btn-accion btn-editar"
          onclick="event.stopPropagation(); abrirModalEditar(${JSON.stringify(reunion).replace(/"/g, '&quot;')})">
          <i class="material-icons">edit</i> Editar
        </button>
        
        <button class="btn-accion btn-completar"
          onclick="event.stopPropagation(); completarReunion('${reunion.id}', event)">
          <i class="material-icons">check</i> Completar
        </button>
        
        <button class="btn-accion btn-eliminar"
          onclick="event.stopPropagation(); eliminarReunion('${reunion.id}', event)">
          <i class="material-icons">delete</i> Eliminar
        </button>        
          ` : '' }
        </div>
      </div>
    `;
  }
  
  // === UTILIDAD ===
  // El <select> del HTML usa valores en plural: pendientes/completadas/canceladas.
  function filtrarReuniones() {
    let list = [...reuniones];
  
    const mapPluralASingular = {
      pendientes: 'pendiente',
      completadas: 'completada',
      canceladas: 'cancelada'
    };
    const estadoFiltroRaw = (filtroEstado?.value || 'todas').toLowerCase();
    const estadoFiltro = mapPluralASingular[estadoFiltroRaw] || estadoFiltroRaw;
  
    if (estadoFiltro && estadoFiltro !== 'todas') {
      list = list.filter(r => (r.estado || '').toLowerCase() === estadoFiltro);
    }
    if (filtroFecha?.value) {
      const f = filtroFecha.value; // YYYY-MM-DD
      list = list.filter(r => ((r.fecha || '').toString().slice(0,10) === f));
    }
    return list;
  }
  function obtenerTextoEstado(estado) {
    const estados = { pendiente: 'Pendiente', completada: 'Completada', cancelada: 'Cancelada' };
    return estados[(estado || '').toLowerCase()] || estado || '';
  }
  function obtenerTextoTipo(tipo) {
    const tipos = { general: 'General', comision: 'Comisión', emergencia: 'Emergencia', planificacion: 'Planificación' };
    return tipos[(tipo || '').toLowerCase()] || tipo || '';
  }
  
  // === CARGA INICIAL (trae PENDIENTES + COMPLETADAS del BACK) ===
  async function cargarReuniones() {
    try {
      clearMsgs();
  
      const [resPend, resComp] = await Promise.all([
        apiGetReunionesPendientes(),
        apiGetReunionesCompletadas()
      ]);
  
      const arrPend = extractReuniones(resPend).map(normalizeReunion);
      const arrComp = extractReuniones(resComp).map(normalizeReunion);
  
      // Mezclo todo; el render separa Pendientes vs Historial
      reuniones = [...arrPend, ...arrComp];
  
      mostrarReuniones();
  
      if (!reuniones.length) showOk('No hay reuniones registradas');
    } catch (err) {
      console.error(err);
      showErr('No se pudieron cargar las reuniones desde el servidor');
    }
  }
  
  // === EXPOSE GLOBAL PARA onclicks inline ===
  window.abrirModalDetalles = abrirModalDetalles;
  window.abrirModalEditar = abrirModalEditar;
  window.completarReunion = completarReunion;
  window.eliminarReunion = eliminarReunion;
  