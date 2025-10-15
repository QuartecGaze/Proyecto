import {
  crearReunion as apiCrearReunion,
  getReunionesPendientes as apiGetReunionesPendientes,
  getReunionesCompletadas as apiGetReunionesCompletadas,
  eliminarReunion as apiEliminarReunion,
  editarReunion as apiEditarReunion,
  completarReunion as apiCompletarReunion,
  getUsuariosAsistencias as apiGetUsuariosAsistencias,
  pasarAsistencia as apiPasarAsistencia
} from '../../../BackEnd/APIFetchs/APIBackOffice.js';


// ==========================
// Helpers DOM y Mensajes
// ==========================
const $  = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

function ensureMsgContainers() {
  const contenedor =
    $('.contenedor-formulario') ||
    $('#formReunion')?.parentElement ||
    document.body;

  let ok = $('#mensajeExito');
  if (!ok) {
    ok = document.createElement('div');
    ok.id = 'mensajeExito';
    ok.className = 'mensaje-exito';
    ok.style.display = 'none';
    ok.innerHTML = `<i class="material-icons">check_circle</i><span>Operación exitosa.</span>`;
    contenedor.appendChild(ok);
  }
  let err = $('#mensajeError');
  if (!err) {
    err = document.createElement('div');
    err.id = 'mensajeError';
    err.className = 'mensaje-error';
    err.style.display = 'none';
    err.innerHTML = `<i class="material-icons">error</i><span>Error</span>`;
    contenedor.appendChild(err);
  }
  return { ok, err };
}

const { ok: mensajeExito, err: mensajeError } = ensureMsgContainers();

const setOk  = (msg='Operación exitosa.') => {
  const span = mensajeExito.querySelector('span'); if (span) span.textContent = msg;
  mensajeExito.style.display = 'flex';
  mensajeError.style.display = 'none';
};
const setErr = (msg='Error del servidor') => {
  const span = mensajeError.querySelector('span'); if (span) span.textContent = msg;
  mensajeError.style.display = 'flex';
  mensajeExito.style.display = 'none';
};
const clearMsgs = () => {
  mensajeExito.style.display = 'none';
  mensajeError.style.display = 'none';
};

// ==========================
// Estado Global
// ==========================
let reuniones = [];          // [{id, titulo, descripcion, fecha, hora, lugar, tipo, estado, asistentes, ...}]
let reunionEditando = null;  // reunión actual en edición o null

// Flujo de asistencia
let flujoAsistencia = {
  idReunion: null,
  usuarios: [],        // [{idPersona, Nombre, Apellido, ci, foto, idUnidad, nroPuerta, pasillo}]
  i: 0,                // índice actual
  respuestas: []       // [{ID_Persona, Asistencia}]
};

// ==========================
// Selectores de UI
// ==========================
// Modales
const modalReunion   = $('#modalReunion');
const modalDetalles  = $('#modalDetallesReunión') || $('#modalDetallesReunion');
const modalAsistencia = $('#modalAsistencia');

// Form y filtros
const formReunion   = $('#formReunion');
const filtroEstado  = $('#filtroEstado');
const filtroFecha   = $('#filtroFecha');

// contenedores
const contPend = $('#reunionesPendientes');
const contHist = $('#historialReuniones');

// Botones sueltos (cabeceras de modales)
const btnCrearReunion    = $('#btnCrearReunion');
const btnCancelarReunion = $('#btnCancelarReunión') || $('#btnCancelarReunion');
const btnCerrarDetalles  = $('#btnCerrarDetalles');
const btnEditarReunion   = $('#btnEditarReunion');

// Modal Asistencia: elementos
const contadorAsistencia   = $('#contadorAsistencia');
const indicadorFalta       = $('#indicadorFalta');
const indicadorAsistencia  = $('#indicadorAsistencia');
const tarjetaSocio         = $('#tarjetaSocio');
const fotoSocio            = $('#fotoSocio');
const nombreSocio          = $('#nombreSocio');
const infoSocio            = $('#infoSocio');
const btnNoAsistio         = $('#btnNoAsistio');
const btnSiAsistio         = $('#btnSiAsistio');
const resumenAsistenciaBox = $('#resumenAsistencia');
const totalAsistieron      = $('#totalAsistieron');
const totalNoAsistieron    = $('#totalNoAsistieron');
const btnCancelarAsistencia= $('#btnCancelarAsistencia');
const btnGuardarAsistencia = $('#btnGuardarAsistencia');

// ==========================
// Mapeos (enum ↔ UI)
// ==========================
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
  if (v.includes('pend') || v.includes('curso')) return 'pendiente';   // Pendiente/En curso
  if (v.includes('final') || v.includes('compl')) return 'completada'; // Finalizada/Completada
  if (v.includes('cancel')) return 'cancelada';
  return 'pendiente';
}
function obtenerTextoEstado(estado) {
  const estados = { pendiente: 'Pendiente', completada: 'Completada', cancelada: 'Cancelada' };
  return estados[(estado || '').toLowerCase()] || estado || '';
}
function obtenerTextoTipo(tipo) {
  const tipos = { general:'General', comision:'Comisión', emergencia:'Emergencia', planificacion:'Planificación' };
  return tipos[(tipo || '').toLowerCase()] || tipo || '';
}

// ==========================
// Normalización de datos
// ==========================
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

  const asistenciasTexto =
    (f.asistencias && typeof f.asistencias === 'object' && f.asistencias.texto)
      ? f.asistencias.texto
      : (typeof f.asistencias === 'string' ? f.asistencias : null);

  return {
    id: String(id),
    titulo: f.titulo ?? f.Nombre ?? f.nombre ?? '(Sin título)',
    descripcion: f.descripcion ?? f.Descripcion ?? '',
    fecha,
    hora: f.hora ?? f.Hora ?? '',
    lugar: f.lugar ?? f.Lugar ?? '',
    tipo,
    estado: est,
    asistentes: f.asistentes ?? 0,       // lo dejo por compatibilidad
    asistenciasTexto,
    fechaCreacion: f.fecha_creacion ?? f.fechaCreacion ?? ''
  };
}

async function finalizarReunionDespuesDeGuardar(id) {
  const res = await apiCompletarReunion({ idReunion: Number(id) });
  const estado = (res?.estado || res?.status || '').toLowerCase();
  if (estado !== 'exito' && estado !== 'success') {
    throw new Error(res?.mensaje || res?.message || 'No se pudo completar la reunión');
  }

  // Actualizar estado local y UI
  const r = reuniones.find(x => x.id === String(id));
  if (r) r.estado = 'completada';
  mostrarReuniones();
}

// ==========================
// Construcción / validación UI
// ==========================
function buildUIReunionFromForm() {
  return {
    id: reunionEditando ? reunionEditando.id : Date.now().toString(),
    titulo: $('#tituloReunion').value.trim(),
    descripcion: $('#descripcionReunion').value.trim(),
    fecha: $('#fechaReunion').value.trim(),   // YYYY-MM-DD
    hora: $('#horaReunion').value.trim(),     // HH:MM
    lugar: $('#lugarReunion').value.trim(),
    tipo: $('#tipoReunion').value.trim(),     // general|comision|emergencia|planificacion
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
    estado === 'exito' || estado === 'success' ||
    res?.ok === true || res?.success === true ||
    codigo === 200 ||
    (!res?.error && estado === '')
  );
}

// ==========================
// Render
// ==========================
function crearTarjetaReunion(reunion) {
  const fRaw = (reunion.fecha || '').toString();
  const fechaISO = fRaw ? fRaw.slice(0, 10) : '';
  const fechaFormateada = fechaISO ? new Date(fechaISO + 'T00:00:00').toLocaleDateString('es-ES') : '';

  const lineaAsistencias = reunion.asistenciasTexto
    ? `<div class="info-item"><i class="material-icons">groups</i><span>${reunion.asistenciasTexto}</span></div>`
    // fallback viejo por si alguna reunión no trae el campo nuevo:
    : (reunion.asistentes != null
        ? `<div class="info-item"><i class="material-icons">group</i><span>${reunion.asistentes} asistentes</span></div>`
        : '');

  return `
    <div class="tarjeta-reunion ${reunion.estado} ${reunion.tipo === 'emergencia' ? 'emergencia' : ''}"
         data-action="abrir-detalles"
         data-id="${reunion.id}">
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
        ${lineaAsistencias}
      </div>
      <div class="descripcion-reunion">${reunion.descripcion || ''}</div>
      <div class="acciones-reunion">
        ${ (reunion.estado || '').toLowerCase() === 'pendiente' ? `
        <button class="btn-accion btn-editar" data-action="editar" data-id="${reunion.id}">
          <i class="material-icons">edit</i> Editar
        </button>
        <button class="btn-accion btn-completar" data-action="completar" data-id="${reunion.id}">
          <i class="material-icons">check</i> Completar
        </button>
        <button class="btn-accion btn-eliminar" data-action="eliminar" data-id="${reunion.id}">
          <i class="material-icons">delete</i> Eliminar
        </button>` : '' }
      </div>
    </div>
  `;
}


function aplicarFiltros(lista) {
  let res = [...lista];
  const mapPluralASingular = { pendientes: 'pendiente', completadas: 'completada', canceladas: 'cancelada' };
  const estadoFiltroRaw = (filtroEstado?.value || 'todas').toLowerCase();
  const estadoFiltro = mapPluralASingular[estadoFiltroRaw] || estadoFiltroRaw;

  if (estadoFiltro && estadoFiltro !== 'todas') {
    res = res.filter(r => (r.estado || '').toLowerCase() === estadoFiltro);
  }
  if (filtroFecha?.value) {
    const f = filtroFecha.value; // YYYY-MM-DD
    res = res.filter(r => ((r.fecha || '').toString().slice(0,10) === f));
  }
  return res;
}

function mostrarReuniones() {
  if (!contPend || !contHist) return;
  const lista = aplicarFiltros(reuniones);
  const pendientes = lista.filter(r => (r.estado || '').toLowerCase() === 'pendiente');
  const historial  = lista.filter(r => (r.estado || '').toLowerCase() !== 'pendiente');

  contPend.innerHTML = pendientes.length
    ? pendientes.map(crearTarjetaReunion).join('')
    : '<p class="sin-reuniones">No hay reuniones pendientes</p>';

  contHist.innerHTML = historial.length
    ? historial.map(crearTarjetaReunion).join('')
    : '<p class="sin-reuniones">No hay reuniones en el historial</p>';
}

// ==========================
// Modales (abrir/cerrar)
// ==========================
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

  const txtAsist = reunion.asistenciasTexto ?? (reunion.asistentes != null ? `${reunion.asistentes} asistentes` : '—');
  $('#detalleAsistentes').textContent  = txtAsist;

  if (modalDetalles) modalDetalles.style.display = 'flex';
}

function cerrarModalDetalles() { if (modalDetalles) modalDetalles.style.display = 'none'; }

function abrirModalAsistenciaUI() {
  if (!modalAsistencia) return;
  if (resumenAsistenciaBox) resumenAsistenciaBox.style.display = 'none';
  if (indicadorFalta) indicadorFalta.style.display = 'none';
  if (indicadorAsistencia) indicadorAsistencia.style.display = 'none';
  modalAsistencia.classList.add('mostrar');

  const onOverlay = (e) => { if (e.target === modalAsistencia) cerrarModalAsistenciaUI(); };
  modalAsistencia.addEventListener('click', onOverlay, { once: true });

  const onEsc = (e) => { if (e.key === 'Escape') { cerrarModalAsistenciaUI(); document.removeEventListener('keydown', onEsc); } };
  document.addEventListener('keydown', onEsc);
}
function cerrarModalAsistenciaUI() {
  if (!modalAsistencia) return;
  modalAsistencia.classList.remove('mostrar');
  if (indicadorFalta) indicadorFalta.style.display = 'none';
  if (indicadorAsistencia) indicadorAsistencia.style.display = 'none';
}

// ==========================
// Acciones (CRUD + asistencia)
// ==========================
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

    const btnSubmit = formReunion?.querySelector('button[type="submit"]');
    if (btnSubmit) { btnSubmit.disabled = true; btnSubmit.dataset.txt = btnSubmit.textContent; btnSubmit.textContent = 'Guardando…'; }

    if (reunionEditando) {
      const res = await apiEditarReunion({ idReunion: String(reunionEditando.id), ...payload });
      if (!respuestaOk(res)) throw new Error(res?.mensaje || res?.message || 'No se pudo editar la reunión');
      // actualizar local
      const nueva = { ...rUI, id: String(reunionEditando.id) };
      const idx = reuniones.findIndex(r => r.id === nueva.id);
      if (idx !== -1) { reuniones[idx] = { ...reuniones[idx], ...nueva }; }
      setOk(res?.mensaje || 'Reunión actualizada');
    } else {
      const res = await apiCrearReunion(payload);
      if (!respuestaOk(res)) throw new Error(res?.mensaje || res?.message || 'No se pudo crear la reunión');
      const idBack =
        res?.idReunion ?? res?.ID_Reunion ?? res?.insertId ??
        res?.data?.idReunion ?? res?.data?.ID_Reunion ?? res?.data?.insertId ?? null;
      const nueva = { ...rUI, id: idBack ? String(idBack) : rUI.id };
      reuniones.push(nueva);
      setOk(res?.mensaje || 'Reunión creada');
    }

    mostrarReuniones();
    cerrarModalReunion();
    if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.textContent = btnSubmit.dataset.txt || 'Guardar'; }
  } catch (err) {
    console.error(err);
    setErr(err?.message || 'Error al guardar la reunión');
    const btnSubmit = formReunion?.querySelector('button[type="submit"]');
    if (btnSubmit) { btnSubmit.disabled = false; btnSubmit.textContent = btnSubmit.dataset.txt || 'Guardar'; }
  }
}

async function eliminarReunionAction(id) {
  try {
    if (!confirm('¿Seguro que querés eliminar esta reunión?')) return;
    const res = await apiEliminarReunion({ idReunion: id });
    if (!respuestaOk(res)) throw new Error(res?.mensaje || 'No se pudo eliminar');
    reuniones = reuniones.filter(r => r.id !== String(id));
    mostrarReuniones();
    setOk(res?.mensaje || 'Reunión eliminada');
  } catch (e) {
    console.error(e);
    setErr(e?.message || 'Error al eliminar la reunión');
  }
}

async function completarReunionAction(id) {
  try {
    clearMsgs();
    const data = await apiGetUsuariosAsistencias();
    const lista =
      Array.isArray(data?.usuarios) ? data.usuarios :
      Array.isArray(data?.message?.usuarios) ? data.message.usuarios : [];

    if (!lista.length) { setErr('No hay usuarios para pasar asistencia'); return; }

    flujoAsistencia = { idReunion: String(id), usuarios: lista, i: 0, respuestas: [] };

    prepararListenersModalAsistencia();
    mostrarPasoAsistencia();

    btnGuardarAsistencia.dataset.idReunion = String(id);
    btnGuardarAsistencia.dataset.busy = "0";

    abrirModalAsistenciaUI();
  } catch (e) {
    console.error(e);
    setErr(e?.message || 'No se pudo iniciar el pase de asistencia');
  }
}



function prepararListenersModalAsistencia() {
  if (btnSiAsistio?.dataset.bound === '1') return;

  btnSiAsistio?.addEventListener('click', () => marcarYAvanzar(true));
  btnNoAsistio?.addEventListener('click', () => marcarYAvanzar(false));
  btnCancelarAsistencia?.addEventListener('click', () => cerrarModalAsistenciaUI());

  btnGuardarAsistencia?.addEventListener('click', async () => {
    const id = btnGuardarAsistencia.dataset.idReunion;
    if (!id) { setErr('No se encontró la reunión actual.'); return; }

    // anti doble click
    if (btnGuardarAsistencia.dataset.busy === "1") return;
    btnGuardarAsistencia.dataset.busy = "1";

    const originalText = btnGuardarAsistencia.textContent;
    btnGuardarAsistencia.disabled = true;
    btnGuardarAsistencia.textContent = 'Guardando...';

    try {
      const ok = await guardarAsistenciaEnServidor();
      if (!ok) return;

      await finalizarReunionDespuesDeGuardar(id);   // 👈 completa en el back y actualiza UI

      cerrarModalAsistenciaUI();                    // cerrar modal una vez
      delete btnGuardarAsistencia.dataset.idReunion;
      flujoAsistencia = { idReunion: null, usuarios: [], i: 0, respuestas: [] };
      setOk('Asistencia guardada y reunión completada');
    } catch (e) {
      console.error(e);
      setErr(e?.message || 'Error al completar la reunión');
    } finally {
      btnGuardarAsistencia.textContent = originalText;
      btnGuardarAsistencia.disabled = false;
      btnGuardarAsistencia.dataset.busy = "0";
    }
  });

  if (btnSiAsistio) btnSiAsistio.dataset.bound = '1';
}


function mostrarPasoAsistencia() {
  const { usuarios, i } = flujoAsistencia;
  const total = usuarios.length;

  if (i >= total) {
    const asistieron = flujoAsistencia.respuestas.filter(r => r.Asistencia === true).length;
    const faltaron   = flujoAsistencia.respuestas.filter(r => r.Asistencia === false).length;

    if (totalAsistieron) totalAsistieron.textContent = String(asistieron);
    if (totalNoAsistieron) totalNoAsistieron.textContent = String(faltaron);
    if (resumenAsistenciaBox) resumenAsistenciaBox.style.display = 'block';

    if (tarjetaSocio) tarjetaSocio.style.display = 'none';
    if (contadorAsistencia) contadorAsistencia.textContent = `Resumen (${total} socios)`;
    if (indicadorFalta) indicadorFalta.style.display = 'none';
    if (indicadorAsistencia) indicadorAsistencia.style.display = 'none';
    return;
  }

  if (resumenAsistenciaBox) resumenAsistenciaBox.style.display = 'none';
  if (tarjetaSocio) tarjetaSocio.style.display = 'grid';

  const u = usuarios[i];
  if (contadorAsistencia) contadorAsistencia.textContent = `Socio ${i + 1} de ${total}`;
  const nombre = `${u.Nombre ?? ''} ${u.Apellido ?? ''}`.trim() || 'Socio';
  if (nombreSocio) nombreSocio.textContent = nombre;

  // Foto
  const rutaFotos = '../../Recursos/FotosPerfil/';
  const archivo = (u.foto && String(u.foto).trim()) ? u.foto : 'usuario.webp';
  if (fotoSocio) { fotoSocio.src = `${rutaFotos}${archivo}`; fotoSocio.alt = `Foto de ${nombre}`; }

  const unidad = [
    u.idUnidad ? `Unidad: ${u.idUnidad}` : '',
    u.nroPuerta ? `Puerta: ${u.nroPuerta}` : '',
    u.pasillo ? `Pasillo: ${u.pasillo}` : ''
  ].filter(Boolean).join(' · ');
  if (infoSocio) infoSocio.innerHTML = `<p>CI: ${u.ci ?? '-'}<br>${unidad || '—'}</p>`;

  if (indicadorFalta) indicadorFalta.style.display = 'none';
  if (indicadorAsistencia) indicadorAsistencia.style.display = 'none';
}

function marcarYAvanzar(valor) {
  const { usuarios, i } = flujoAsistencia;
  if (i >= usuarios.length) return;

  if (indicadorAsistencia) indicadorAsistencia.style.display = valor ? 'inline-block' : 'none';
  if (indicadorFalta) indicadorFalta.style.display = valor ? 'none' : 'inline-block';

  const idp = Number(usuarios[i].idPersona || usuarios[i].ID_Persona);
  flujoAsistencia.respuestas.push({ ID_Persona: idp, Asistencia: !!valor });

  setTimeout(() => {
    flujoAsistencia.i++;
    mostrarPasoAsistencia();
  }, 180);
}

async function guardarAsistenciaEnServidor() {
  try {
    if (!flujoAsistencia?.idReunion) { setErr('No se encontró la reunión actual'); return false; }

    // Normaliza (ID_Persona número, Asistencia 0/1, sin duplicados)
    const map = new Map();
    for (const r of (flujoAsistencia.respuestas || [])) {
      const idp = Number(r.ID_Persona ?? r.idPersona);
      if (!Number.isFinite(idp)) continue;
      map.set(idp, { ID_Persona: idp, Asistencia: r.Asistencia ? 1 : 0 });
    }
    const Asistencias = Array.from(map.values());
    if (!Asistencias.length) { setErr('No hay registros de asistencia para guardar'); return false; }

    const payload = {
      idReunion: Number(flujoAsistencia.idReunion),
      Asistencias
    };

    const res = await apiPasarAsistencia(payload);
    const estado = (res?.estado || res?.status || '').toLowerCase();
    if (estado !== 'exito' && estado !== 'success') {
      throw new Error(res?.mensaje || res?.message || 'No se pudo guardar la asistencia');
    }

    // si tu back no manda "guardados", igual consideramos éxito cuando status = exito
    const guardados = Number(res?.message?.guardados ?? res?.guardados ?? Asistencias.length);
    setOk(`Asistencia guardada (guardados: ${guardados})`);
    return true;
  } catch (e) {
    console.error(e);
    setErr(e?.message || 'Error al guardar la asistencia');
    return false;
  }
}


// ==========================
// Carga inicial
// ==========================
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

// ==========================
// Delegación de eventos
// ==========================
document.addEventListener('DOMContentLoaded', () => {
  // Abrir/Cancelar modal crear
  btnCrearReunion?.addEventListener('click', abrirModalCrear);
  btnCancelarReunion?.addEventListener('click', cerrarModalReunion);

  // Detalles: cerrar
  btnCerrarDetalles?.addEventListener('click', cerrarModalDetalles);

  // Guardar (crear/editar)
  formReunion?.addEventListener('submit', guardarReunion);

  // Filtros
  filtroEstado?.addEventListener('change', mostrarReuniones);
  filtroFecha?.addEventListener('change', mostrarReuniones);

  // Cerrar modales por click fuera
  modalReunion?.addEventListener('click', (e) => { if (e.target === modalReunion) cerrarModalReunion(); });
  modalDetalles?.addEventListener('click', (e) => { if (e.target === modalDetalles) cerrarModalDetalles(); });

  // Esc
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { cerrarModalReunion(); cerrarModalDetalles(); }
  });

  // Delegación en contenedores de tarjetas
  [contPend, contHist].forEach(cont => {
    cont?.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const action = btn.dataset.action;
      const id = btn.dataset.id || btn.closest('.tarjeta-reunion')?.dataset.id;
      if (!action) return;

      // Evita que abrir-detalles interfiera con botones
      if (action !== 'abrir-detalles') e.stopPropagation();

      const reunion = reuniones.find(r => r.id === String(id));
      if (action === 'abrir-detalles' && reunion)   abrirModalDetalles(reunion);
      if (action === 'editar' && reunion)          abrirModalEditar(reunion);
      if (action === 'eliminar' && id)             eliminarReunionAction(id);
      if (action === 'completar' && id)            completarReunionAction(id);
    });
  });

  // Si hay botón "Editar" dentro del modal de detalles (opcional)
  btnEditarReunion?.addEventListener('click', () => {
    // Busca por el título mostrado (si no tienes ID a mano en el modal)
    const titulo = $('#detalleTitulo')?.textContent?.trim();
    const r = reuniones.find(x => x.titulo === titulo);
    if (r) { cerrarModalDetalles(); abrirModalEditar(r); }
  });

  // Go!
  cargarReuniones();
});
