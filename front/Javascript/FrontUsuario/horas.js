import { subirHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { subirFalta } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { editarHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { borrarHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getHorasTrabajadas } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = "usuario.webp";
const formularioHoras = document.querySelector('.formulario-horas');
const mensajeExitoHoras = formularioHoras.querySelector('.mensaje-exito');
const mensajeErrorHoras = formularioHoras.querySelector('.mensaje-error');
const horasTrabajadas = document.getElementById("horasTrabajadas");
const objetivoHoras = document.getElementById("horasObjetivo");
const barraDeProgreso = document.getElementById('progresoHoras');
const barraEl = barraDeProgreso?.parentElement;
const horasRestantesEl = document.getElementById("horasRestantes");
const porcentajeProgresoEl = document.getElementById("porcentajeProgreso");

// Filtros
const filtroSemanaEl = document.getElementById('filtro-semana');
const filtroDiaEl    = document.getElementById('filtro-mes');

// Estado base (historial completo sin filtrar)
let horasData = [];

//MODAL EDITAR HORAS
const modalEditarHoras = document.getElementById('modalEditarHoras');
const btnCerrarX = document.getElementById('cerrarModal');      // botón X del header
const btnCancelarEdicion = document.getElementById('cancelarEdicion'); // botón "Cancelar" del footer
const formEditarHoras = document.getElementById('formEditarHoras');
const inputFechaEditar = document.getElementById('fechaEditar');
const inputHorasEditar = document.getElementById('horasEditar');

//aca guardamos el id de la fila que vamos a editar (por eso let)
let idHorasSeleccionado = null;

function abrirModalEditar({ idHoras, fecha, horas }) {
  idHorasSeleccionado = idHoras ?? null;
  if (fecha) inputFechaEditar.value = fecha;
  if (horas != null) inputHorasEditar.value = horas;
  modalEditarHoras.style.display = 'flex';
}

function cerrarModalEditar() {
  modalEditarHoras.style.display = 'none';
  idHorasSeleccionado = null;
  formEditarHoras.reset();
}

//CERRAR MODAL
btnCerrarX?.addEventListener('click', cerrarModalEditar);
btnCancelarEdicion?.addEventListener('click', cerrarModalEditar);
modalEditarHoras?.addEventListener('click', (e) => { if (e.target === modalEditarHoras) cerrarModalEditar(); });
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModalEditar(); });

const sesion = await getIdSesion();
const id = Number(sesion.message);
const dataUsuario = await getUsuario(id);
setDatosUsuario(dataUsuario.message);

const dataCoop = await getHorasTrabajadas(id);
setDatosCooperativa(dataCoop.message);

function setDatosUsuario(data) {
  nombre.forEach(n => { n.textContent = `${data.nombre} ${data.apellido}`; });
  foto.forEach(f => { f.src = fotoruta + (data.foto && data.foto !== '' ? data.foto : fotoUsuario); });
}

function setDatosCooperativa(data) {
  // Totales/resumen (sin filtrar)
  horasTrabajadas.textContent = Number(data.horasTrabajadas ?? 0);
  objetivoHoras.textContent   = Number(data.horasObjetivo   ?? 0);
  if (horasRestantesEl) horasRestantesEl.textContent = Math.max(0, Number(data.horasRestantes ?? 0));

  const pct = Number(data.porcentaje ?? 0);
  Progreso(data.horasObjetivo, data.horasTrabajadas, pct);

  // Poblar select de semanas con lo que viene del backend
  poblarSelectSemanas(data.semanas || []);

  // Guardar historial completo para filtros
  horasData = Object.values(data?.horas ?? {});
  applyFiltersAndRender();
}




function Progreso(objetivo, trabajadas, porcentajeAPI) {
  if (!barraDeProgreso) return;

  let porcentaje = (typeof porcentajeAPI === 'number')
    ? porcentajeAPI
    : (() => {
        const obj = Number(objetivo)   || 0;
        const tra = Number(trabajadas) || 0;
        return obj > 0 ? Math.min(100, Math.max(0, (tra / obj) * 100)) : 0;
      })();

  // Redondeo visible (enteros o 2 decimales, como prefieras)
  const pctStr = `${Math.round(porcentaje)}%`;

  barraDeProgreso.style.width = `${porcentaje.toFixed(2)}%`;
  barraEl?.setAttribute('aria-valuenow', porcentaje.toFixed(2));
  if (porcentajeProgresoEl) porcentajeProgresoEl.textContent = pctStr;
}

//CARGAR HORAS
formularioHoras.addEventListener('submit', async (e) => {
  e.preventDefault();

  const horas = document.getElementById('horas').value;
  const datos = { horas };

  try {
    const respuesta = await subirHoras(datos);
    if (respuesta.status === 'exito') {
      mensajeExitoHoras.style.display = 'block';
      mensajeErrorHoras.style.display = 'none';
      formularioHoras.reset();

      const actualizado = await getHorasTrabajadas(id);
      setDatosCooperativa(actualizado.message);
    } else {
      mensajeExitoHoras.style.display = 'none';
      mensajeErrorHoras.style.display = 'block';
      mensajeErrorHoras.textContent = 'Error: ' + respuesta.message;
    }
  } catch (error) {
    console.error('Error al subir horas', error);
    mensajeExitoHoras.style.display = 'none';
    mensajeErrorHoras.style.display = 'block';
    mensajeErrorHoras.textContent = 'Error del servidor';
  }
});
// Form de Faltas (solo dentro de la pestaña Faltas para no confundir con el de horas)
const formularioFaltas = document.querySelector('#contenido-faltas .formulario-horas');
const msgExitoFalta = formularioFaltas?.querySelector('.mensaje-exito');
const msgErrorFalta = formularioFaltas?.querySelector('.mensaje-error');
// CARGAR FALTA
formularioFaltas?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const horas_faltadas = Number(document.getElementById('horas_faltadas').value);
  const tipo_comp = document.getElementById('tipo_compensacion').value;
  const motivo = (document.getElementById('motivo_falta').value || '').trim();

  // Validaciones básicas
  if (!Number.isFinite(horas_faltadas) || horas_faltadas < 1 || horas_faltadas > 12) {
    msgExitoFalta.style.display = 'none';
    msgErrorFalta.style.display = 'block';
    msgErrorFalta.textContent = 'Horas inválidas (1–12).';
    return;
  }
  if (!tipo_comp) {
    msgExitoFalta.style.display = 'none';
    msgErrorFalta.style.display = 'block';
    msgErrorFalta.textContent = 'Seleccioná un tipo de compensación.';
    return;
  }
  if (!motivo) {
    msgExitoFalta.style.display = 'none';
    msgErrorFalta.style.display = 'block';
    msgErrorFalta.textContent = 'Indicá un motivo de la falta.';
    return;
  }

  // Mapear valores del select a los literales EXACTOS del ENUM de MySQL/API
  // <option value="exoneracion"> → 'Exoneracion'
  // <option value="compensacion_monetaria"> → 'Pago compensatorio'
  const compensacion = (tipo_comp === 'exoneracion')
    ? 'Exoneracion'
    : (tipo_comp === 'compensacion_monetaria')
      ? 'Pago compensatorio'
      : null;

  if (!compensacion) {
    msgExitoFalta.style.display = 'none';
    msgErrorFalta.style.display = 'block';
    msgErrorFalta.textContent = 'Compensación inválida.';
    return;
  }

  const payload = {
    horas: horas_faltadas,
    compensacion,
    motivo
  };

  try {
    const resp = await subirFalta(payload);
    if (resp?.status === 'exito') {
      msgErrorFalta.style.display = 'none';
      msgExitoFalta.style.display = 'block';
      formularioFaltas.reset();

      // Refrescar resumen/progreso, por si la exoneración impacta en objetivo o estado semanal
      const actualizado = await getHorasTrabajadas(id);
      setDatosCooperativa(actualizado.message);
    } else {
      msgExitoFalta.style.display = 'none';
      msgErrorFalta.style.display = 'block';
      msgErrorFalta.textContent = 'Error: ' + (resp?.message ?? 'No se pudo registrar la falta.');
    }
  } catch (err) {
    console.error('Error al subir falta', err);
    msgExitoFalta.style.display = 'none';
    msgErrorFalta.style.display = 'block';
    msgErrorFalta.textContent = 'Error del servidor';
  }
});

// Calcula ISO week number (1..53) desde 'YYYY-MM-DD'
function isoWeekFromDateStr(yyyy_mm_dd) {
  if (!yyyy_mm_dd) return null;
  const [y, m, d] = yyyy_mm_dd.split('-').map(Number);
  const date = new Date(Date.UTC(y, m - 1, d));
  // Jueves de la semana ISO
  date.setUTCDate(date.getUTCDate() + 4 - (date.getUTCDay() || 7));
  const yearStart = new Date(Date.UTC(date.getUTCFullYear(), 0, 1));
  const weekNo = Math.ceil((((date - yearStart) / 86400000) + 1) / 7);
  return weekNo; // número de semana
}

// Mapea el value del select de "día" al nombre que viene del back
function mapDiaSelectValueToNombre(value) {
  if (!value) return null; // "Todos"
  // Tus opciones actuales tienen valores raros (11, 10, 9). Mapeo defensivo:
  const mapaRaro = { '1': 'Lunes', '2': 'Martes', '3': 'Miercoles', '4': 'Jueves' , '5': 'Viernes', '6': 'Sabado', '7': 'Domingo'};
  if (mapaRaro[value]) return mapaRaro[value];

  // Si más adelante usás valores legibles:
  // 'lunes' -> 'Lunes', etc.
  const normalizados = {
    'lunes': 'Lunes',
    'martes': 'Martes',
    'miercoles': 'Miercoles',
    'jueves': 'Jueves',
    'viernes': 'Viernes',
    'sabado': 'Sabado',
    'sábado': 'Sabado',
    'domingo': 'Domingo'
  };
  const v = (value + '').toLowerCase();
  return normalizados[v] ?? null;
}

function applyFiltersAndRender() {
  const semanaIni = (filtroSemanaEl?.value ?? '').trim(); // '' = todas; si no, 'YYYY-MM-DD'
  const diaSel    = (filtroDiaEl?.value ?? '').trim();    // '' = todos

  // Día seleccionado -> nombre que viene del back ('Lunes', 'Martes', ...)
  const mapaRaro = { '11': 'Lunes', '10': 'Martes', '9': 'Miercoles', '1': 'Lunes', '2': 'Martes', '3': 'Miercoles', '4':'Jueves','5':'Viernes','6':'Sabado','7':'Domingo' };
  const diaNombre = (()=>{
    if (!diaSel) return null;
    if (mapaRaro[diaSel]) return mapaRaro[diaSel];
    const normalizados = { 'lunes':'Lunes','martes':'Martes','miercoles':'Miercoles','jueves':'Jueves','viernes':'Viernes','sabado':'Sabado','sábado':'Sabado','domingo':'Domingo' };
    return normalizados[(diaSel+'').toLowerCase()] ?? null;
  })();

  // Rango de semana (si hay selección)
  const semanaFin = semanaIni ? addDays(semanaIni, 6) : null;

  const listaFiltrada = (horasData || []).filter(item => {
    // item.fechaDeRegistro: 'YYYY-MM-DD'; item.diaDeLaSemana: 'Lunes', ...
    if (semanaIni) {
      const f = item.fechaDeRegistro;
      if (f < semanaIni || f > semanaFin) return false;
    }
    if (diaNombre && item.diaDeLaSemana !== diaNombre) return false;
    return true;
  });

  renderHorasFromList(listaFiltrada);
}

filtroSemanaEl?.addEventListener('change', applyFiltersAndRender);
filtroDiaEl?.addEventListener('change', applyFiltersAndRender);


function addDays(yyyy_mm_dd, days) {
  const [y, m, d] = yyyy_mm_dd.split('-').map(Number);
  const dt = new Date(Date.UTC(y, m - 1, d));
  dt.setUTCDate(dt.getUTCDate() + days);
  const y2 = dt.getUTCFullYear();
  const m2 = String(dt.getUTCMonth() + 1).padStart(2, '0');
  const d2 = String(dt.getUTCDate()).padStart(2, '0');
  return `${y2}-${m2}-${d2}`;
}

function formatDM(yyyy_mm_dd) {
  const [y, m, d] = yyyy_mm_dd.split('-');
  return `${d}/${m}`; // dd/mm
}

function poblarSelectSemanas(semanas = []) {
  if (!filtroSemanaEl) return;
  filtroSemanaEl.innerHTML = '<option value="">Todas</option>';

  semanas.forEach(s => {
    // asumimos que s = { id, fecha } donde fecha es el lunes de la semana
    const ini = s.fecha;
    const fin = addDays(ini, 6);
    const opt = document.createElement('option');
    // usamos la FECHA como value para poder filtrar por rango en el front
    opt.value = ini;                 // ej: "2025-09-29"
    opt.textContent = `${formatDM(ini)} - ${formatDM(fin)}`; // ej: "29/09 - 05/10"
    // guardamos el id por si más adelante querés refetchear por id:
    opt.dataset.idSemana = s.id;
    filtroSemanaEl.appendChild(opt);
  });
}

function renderHorasFromList(lista) {
  const tbody = document.querySelector('.tabla-horas tbody');
  if (!tbody) return;

  // Reemplazo el tbody por un clon vacío para limpiar listeners previos
  const nuevoTbody = tbody.cloneNode(false);

  if (!lista?.length) {
    // sin filas
  } else {
    for (const c of lista) {
      const fecha = c.fechaDeRegistro;   // yyyy-mm-dd
      const diaDeLaSemana = c.diaDeLaSemana;
      const horas = c.horasTrabajadas;
      const idHoras = c.idHoras;

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${fecha}</td>
        <td>${diaDeLaSemana}</td>
        <td>${horas}</td>
        <td>
          <button class="boton-icono editar" title="Editar"
            data-id="${idHoras}" data-fecha="${fecha}" data-horas="${horas}">
            <i class="material-icons">edit</i>
          </button>
        </td>
        <td>
          <button class="boton-icono" title="Eliminar" data-id="${idHoras}">
            <i class="material-icons">delete</i>
          </button>
        </td>
      `;
      nuevoTbody.appendChild(tr);
    }
  }

  // Delegación de eventos (se agrega 1 sola vez por render)
  nuevoTbody.addEventListener('click', (e) => {
    const btnEditar = e.target.closest('button.editar');
    if (btnEditar) {
      abrirModalEditar({
        idHoras: btnEditar.dataset.id,
        fecha: btnEditar.dataset.fecha,
        horas: btnEditar.dataset.horas
      });
      return;
    }

    const btnEliminar = e.target.closest('button[title="Eliminar"]');
    if (btnEliminar) {
      handleEliminar(btnEliminar);
      return;
    }
  });

  // Reemplazo en el DOM
  tbody.parentNode.replaceChild(nuevoTbody, tbody);
}

// Mantengo tu lógica de delete en una función separada
async function handleEliminar(btnEliminar) {
  const idHoras = btnEliminar.dataset.id || btnEliminar.getAttribute('data-id');
  if (!idHoras) return;

  const ok = confirm('¿Seguro que querés borrar estas horas? Esta acción no se puede deshacer.');
  if (!ok) return;

  btnEliminar.disabled = true;
  try {
    const resp = await borrarHoras({ idHoras });
    if (resp?.status === 'exito') {
      const actualizado = await getHorasTrabajadas(id);
      setDatosCooperativa(actualizado.message); // esto re-carga horasData y vuelve a aplicar filtros
    } else {
      alert('Error: ' + (resp?.message ?? 'No se pudo borrar las horas.'));
    }
  } catch (err) {
    console.error('Error al borrar horas', err);
    alert('Error del servidor');
  } finally {
    btnEliminar.disabled = false;
  }
}


function renderHoras(dataMessage) {
  const tbody = document.querySelector('.tabla-horas tbody');
  if (!tbody) return;

  const lista = Object.values(dataMessage?.horas ?? {});
  if (!lista.length) { tbody.innerHTML = ''; return; }

  tbody.innerHTML = '';
  for (const c of lista) {
    const fecha = c.fechaDeRegistro;         // yyyy-mm-dd
    const diaDeLaSemana = c.diaDeLaSemana;
    const horas = c.horasTrabajadas;
    const idHoras = c.idHoras;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${fecha}</td>
      <td>${diaDeLaSemana}</td>
      <td>${horas}</td>
      <td>
        <button class="boton-icono editar" title="Editar"
          data-id="${idHoras}" data-fecha="${fecha}" data-horas="${horas}">
          <i class="material-icons">edit</i>
        </button>
      </td>
      <td>
        <button class="boton-icono" title="Eliminar" data-id="${idHoras}">
          <i class="material-icons">delete</i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  }

  //ABRIR MODAL
  tbody.addEventListener('click', (e) => {
    const btnEditar = e.target.closest('button.editar');
    if (!btnEditar) return;

    abrirModalEditar({
      idHoras: btnEditar.dataset.id,
      fecha: btnEditar.dataset.fecha,
      horas: btnEditar.dataset.horas
    });
  });
  
tbody.addEventListener('click', async (e) => {
  const btnEliminar = e.target.closest('button[title="Eliminar"]');
  if (!btnEliminar) return;

  const idHoras = btnEliminar.dataset.id || btnEliminar.getAttribute('data-id');
  if (!idHoras) return;

  const ok = confirm('¿Seguro que querés borrar estas horas? Esta acción no se puede deshacer.');
  if (!ok) return;

  btnEliminar.disabled = true; // feedback mientras borra
  try {
    const resp = await borrarHoras({ idHoras });
    if (resp?.status === 'exito') {
      // refrescar la tabla
      const actualizado = await getHorasTrabajadas(id);
      setDatosCooperativa(actualizado.message);
    } else {
      alert('Error: ' + (resp?.message ?? 'No se pudo borrar las horas.'));
    }
  } catch (err) {
    console.error('Error al borrar horas', err);
    alert('Error del servidor');
  } finally {
    btnEliminar.disabled = false; //para que no se abra 50 veces el coso
  }
});
}

//LLAMAR AL BACK
formEditarHoras?.addEventListener('submit', async (e) => {
  e.preventDefault();
  if (!idHorasSeleccionado) {
    alert('No se encontró el registro a editar.');
    return;
  }

  const horas = Number(inputHorasEditar.value);
  const fecha = inputFechaEditar.value;

  if (!Number.isFinite(horas) || horas < 1 || horas > 12) {
    alert('Por favor ingrese horas válidas');
    return;
  }

  const datos = {
    idHoras: idHorasSeleccionado,
    horas, 
    fecha
  };

  try {
    const respuesta = await editarHoras(datos);
    if (respuesta.status === 'exito') {
      cerrarModalEditar();
      const actualizado = await getHorasTrabajadas(id);
      setDatosCooperativa(actualizado.message);
      // Podés mostrar un toast si querés
    } else {
      alert('Error: ' + (respuesta.message ?? 'No se pudo editar las horas.'));
    }
  } catch (error) {
    console.error('Error al editar horas', error);
    alert('Error del servidor');
  }
});
