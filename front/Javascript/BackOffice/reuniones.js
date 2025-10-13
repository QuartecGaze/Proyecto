// reuniones.js (estilo similar a crearUnidad.js)
import {
  crearReunion as apiCrearReunion,
  getReunionesPendientes as apiGetReunionesPendientes,
  getReunionesCompletadas as apiGetReunionesCompletadas,
  completarReunion as apiCompletarReunion,
  eliminarReunion as apiEliminarReunion,
  editarReunion as apiEditarReunion
} from '../../../BackEnd/APIFetchs/APIBackOffice.js';

document.addEventListener('DOMContentLoaded', () => {
  // === Helper DOM ===
  const $ = (sel, ctx = document) => ctx.querySelector(sel);

  // === Estado global ===
  let reuniones = [];
  let reunionEditando = null;

  // === DOM base ===
  const modalReunion   = $('#modalReunion');
  const modalDetalles  = $('#modalDetallesReunion');
  const btnCrearReunion = $('#btnCrearReunion');
  const btnCancelarReunion = $('#btnCancelarReunión') || $('#btnCancelarReunion'); // tolerante
  const btnCerrarDetalles = $('#btnCerrarDetalles');
  const btnEditarReunion  = $('#btnEditarReunion');
  const formReunion   = $('#formReunion');
  const filtroEstado  = $('#filtroEstado');
  const filtroFecha   = $('#filtroFecha');

  // === Contenedor de mensajes (similar a crearUnidad.js) ===
  const contenedorFormulario =
    $('.contenedor-formulario') ||
    formReunion?.parentElement ||
    document.body;

  // Crea/recicla mensajes
  let mensajeExito = $('#mensajeExito') || $('#msgReunionOk');
  if (!mensajeExito) {
    mensajeExito = document.createElement('div');
    mensajeExito.id = 'mensajeExito';
    mensajeExito.className = 'mensaje-exito';
    mensajeExito.style.display = 'none';
    mensajeExito.innerHTML = `<i class="material-icons">check_circle</i><span>Operación exitosa.</span>`;
    contenedorFormulario.appendChild(mensajeExito);
  }

  let mensajeError = $('#mensajeError') || $('#msgReunionErr');
  if (!mensajeError) {
    mensajeError = document.createElement('div');
    mensajeError.id = 'mensajeError';
    mensajeError.className = 'mensaje-error';
    mensajeError.style.display = 'none';
    mensajeError.innerHTML = `<i class="material-icons">error</i><span></span>`;
    contenedorFormulario.appendChild(mensajeError);
  }

  const setOk = (msg = 'Operación exitosa.') => {
    const span = mensajeExito.querySelector('span');
    if (span) span.textContent = msg;
    mensajeExito.style.display = 'flex';
    mensajeError.style.display = 'none';
  };

  const setErr = (msg = 'Error del servidor') => {
    const span = mensajeError.querySelector('span');
    if (span) span.textContent = msg;
    else mensajeError.textContent = msg;
    mensajeError.style.display = 'flex';
    mensajeExito.style.display = 'none';
  };

  const clearMsgs = () => {
    mensajeExito.style.display = 'none';
    mensajeError.style.display = 'none';
  };

  // === Mapeos ===
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
    if (v.includes('pend') || v.includes('curso')) return 'pendiente';  // Pendiente / En curso
    if (v.includes('final') || v.includes('compl')) return 'completada'; // Finalizada/Completada
    if (v.includes('cancel')) return 'cancelada'; // Cancelada
    return 'pendiente';
  }

  // === Eventos ===
  configurarEventListeners();
  cargarReuniones();

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

  // === Modales ===
  function abrirModalCrear() {
    reunionEditando = null;
    clearMsgs();
    $('#tituloModal').innerHTML = '<i class="material-icons">event</i> Crear Nueva Reunión';
    formReunion?.reset();
    if (modalReunion) modalReunion.style.display = 'flex';
  }
  function abrirModalEditar(reunion) {
    reunionEditando = reunion;
    clearMsgs();
    $('#tituloModal').innerHTML = '<i class="material-icons">edit</i> Editar Reunión';

    $('#tituloReunion').value      = reunion.titulo || '';
    $('#descripcionReunion').value = reunion.descripcion || '';
    $('#fechaReunion').value       = reunion.fecha || '';
    $('#horaReunion').value        = reunion.hora || '';
    $('#lugarReunion').value       = reunion.lugar || '';
    $('#tipoReunion').value        = reunion.tipo || 'general';

    if (modalReunion) modalReunion.style.display = 'flex';
  }
  function cerrarModalReunion() {
    if (modalReunion) modalReunion.style.display = 'none';
    reunionEditando = null;
    formReunion?.reset();
  }
  function abrirModalDetalles(reunion) {
    $('#detalleTitulo').textContent      = reunion.titulo;
    $('#detalleDescripcion').textContent = reunion.descripcion;
    $('#detalleFechaHora').textContent   = `${reunion.fecha} a las ${reunion.hora}`;
    $('#detalleLugar').textContent       = reunion.lugar;
    $('#detalleTipo').textContent        = obtenerTextoTipo(reunion.tipo);
    $('#detalleEstado').textContent      = obtenerTextoEstado(reunion.estado);
    $('#detalleAsistentes').textContent  = reunion.asistentes || 'Por confirmar';

    if (btnEditarReunion) {
      btnEditarReunion.onclick = () => {
        cerrarModalDetalles();
        abrirModalEditar(reunion);
      };
    }
    if (modalDetalles) modalDetalles.style.display = 'flex';
  }
  function cerrarModalDetalles() {
    if (modalDetalles) modalDetalles.style.display = 'none';
  }
  function editarReunionDesdeDetalles() {
    cerrarModalDetalles();
  }

  // === Helpers UI botones ===
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

  // === Construcción/validación UI ===
  function buildUIReunionFromForm() {
    return {
      id: reunionEditando ? reunionEditando.id : Date.now().toString(),
      titulo: $('#tituloReunion').value.trim(),
      descripcion: $('#descripcionReunion').value.trim(),
      fecha: $('#fechaReunion').value.trim(),   // YYYY-MM-DD
      hora: $('#horaReunion').value.trim(),     // HH:MM
      lugar: $('#lugarReunion').value.trim(),
      tipo: $('#tipoReunion').value.trim(),     // general|comision|...
      estado: 'pendiente',
      asistentes: 0,
      fechaCreacion: new Date().toISOString().split('T')[0]
    };
  }
  function validarReunionUI(r) {
    const faltan = [];
    if (!r.titulo) faltan.push('título');
    if (!r.fecha)  faltan.push('fecha');
    if (!r.hora)   faltan.push('hora');
    if (!r.lugar)  faltan.push('lugar');
    if (!r.tipo)   faltan.push('tipo');
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

  // === Guardar (crear/editar) ===
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
        // EDITAR
        const res = await apiEditarReunion({
          idReunion: String(reunionEditando.id),
          ...payload
        });
        if (!respuestaOk(res)) {
          throw new Error(res?.mensaje || res?.message || 'No se pudo editar la reunión en el servidor');
        }
        actualizarReunionLocal({ ...rUI, id: String(reunionEditando.id) });
        setOk(res?.mensaje || 'Reunión actualizada exitosamente');
      } else {
        // CREAR
        const res = await apiCrearReunion(payload);
        if (!respuestaOk(res)) {
          throw new Error(res?.mensaje || res?.message || 'No se pudo crear la reunión en el servidor');
        }
        const idBack =
          res?.idReunion ?? res?.ID_Reunion ?? res?.insertId ??
          res?.data?.idReunion ?? res?.data?.ID_Reunion ?? res?.data?.insertId ?? null;

        const nueva = { ...rUI, id: idBack ? String(idBack) : rUI.id };
        crearReunionLocal(nueva);
        setOk(res?.mensaje || 'Reunión creada exitosamente');
      }

      cerrarModalReunion();
    } catch (err) {
      console.error(err);
      setErr(err?.message || 'Error al guardar la reunión');
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

  // === Acciones: eliminar / completar ===
  async function eliminarReunion(id, ev) {
    try {
      const ok = confirm('¿Estás seguro de que deseas eliminar esta reunión?');
      if (!ok) return;

      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, true, 'Eliminar');

      const res = await apiEliminarReunion({ idReunion: id });
      const exito = (res?.estado || '').toLowerCase() === 'exito';
      if (!exito) throw new Error(res?.mensaje || 'No se pudo eliminar la reunión en el servidor');

      reuniones = reuniones.filter(r => r.id !== String(id));
      mostrarReuniones();
      setOk(res?.mensaje || 'Reunión eliminada correctamente');
    } catch (e) {
      console.error(e);
      setErr(e?.message || 'Error al eliminar la reunión');
    } finally {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, false, 'Eliminar');
    }
  }

  async function completarReunion(id, ev) {
    try {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, true, 'Completar');

      const res = await apiCompletarReunion({ idReunion: id });
      const exito = (res?.estado || '').toLowerCase() === 'exito';
      if (!exito) throw new Error(res?.mensaje || 'No se pudo completar la reunión en el servidor');

      const r = reuniones.find(x => x.id === String(id));
      if (r) r.estado = 'completada';
      mostrarReuniones();
      setOk(res?.mensaje || 'Reunión completada correctamente');
    } catch (e) {
      console.error(e);
      setErr(e?.message || 'Error al completar la reunión');
    } finally {
      const btn = ev?.currentTarget || ev?.target;
      setBtnActionLoading(btn, false, 'Completar');
    }
  }

  // === Normalización y extracción ===
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

  // === Render ===
  function mostrarReuniones() {
    const contPend = $('#reunionesPendientes');
    const contHist = $('#historialReuniones');
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

  // === Utilidad ===
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

  // === Carga inicial (pendientes + completadas) ===
  async function cargarReuniones() {
    try {
      clearMsgs();
      const [resPend, resComp] = await Promise.all([
        apiGetReunionesPendientes(),
        apiGetReunionesCompletadas()
      ]);

      const arrPend = extractReuniones(resPend).map(normalizeReunion);
      const arrComp = extractReuniones(resComp).map(normalizeReunion);

      reuniones = [...arrPend, ...arrComp];
      mostrarReuniones();

      if (!reuniones.length) setOk('No hay reuniones registradas');
    } catch (err) {
      console.error(err);
      setErr('No se pudieron cargar las reuniones desde el servidor');
    }
  }

  // === Expose global (para los onclick inline que ya usás) ===
  window.abrirModalDetalles = abrirModalDetalles;
  window.abrirModalEditar   = abrirModalEditar;
  window.completarReunion   = completarReunion;
  window.eliminarReunion    = eliminarReunion;
});
