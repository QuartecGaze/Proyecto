import { subirHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';
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

//MODAL EDITAR HORAS
const modalEditarHoras = document.getElementById('modalEditarHoras');
const btnCerrarX = document.getElementById('cancelarModal');      // botón X del header
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
  horasTrabajadas.textContent = data.horasTrabajadas;
  objetivoHoras.textContent = data.horasObjetivo;
  Progreso(data.horasObjetivo, data.horasTrabajadas);
  renderHoras(data);
}

function Progreso(objetivo, trabajadas) {
  if (!barraDeProgreso) return;
  const objetivoHorasNum = Number(objetivo) || 0;
  const trabajadasHorasNum = Number(trabajadas) || 0;
  const porcentaje = objetivoHorasNum > 0
    ? Math.min(100, Math.max(0, (trabajadasHorasNum / objetivoHorasNum) * 100))
    : 0;
  const pct = porcentaje.toFixed(2);
  barraDeProgreso.style.width = pct + '%';
  barraEl?.setAttribute('aria-valuenow', pct);
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
