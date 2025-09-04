import { subirHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';
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


const sesion = await getIdSesion();
const id = Number(sesion.message);
const dataUsuario = await getUsuario(id);
setDatosUsuario(dataUsuario.message);

const dataCoop = await getHorasTrabajadas(id);
setDatosCooperativa(dataCoop.message);

function setDatosUsuario(data) {
  nombre.forEach(n => { n.textContent = `${data.nombre} ${data.apellido}`; });
  foto.forEach(f => {
    f.src = fotoruta + (data.foto && data.foto !== '' ? data.foto : fotoUsuario);
  });
}

function setDatosCooperativa(data) {
  horasTrabajadas.textContent = data.horasTrabajadas;
  objetivoHoras.textContent = data.horasObjetivo;
  Progreso(data.horasObjetivo, data.horasTrabajadas);
  renderHoras(data);
}

function Progreso(objetivo, trabajadas) {
  if (!barraDeProgreso) return;
  const objetivoHoras = Number(objetivo) || 0;
  const trabajadasHoras = Number(trabajadas) || 0;
  const porcentaje = objetivoHoras > 0
    ? Math.min(100, Math.max(0, (trabajadasHoras / objetivoHoras) * 100))
    : 0;
  const pct = porcentaje.toFixed(2);
  barraDeProgreso.style.width = pct + '%';
  barraEl?.setAttribute('aria-valuenow', pct);
}

// --- Envío de horas ---
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

      // refrescar cooperativa
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

    const fecha = c.fechaDeRegistro;
    const diaDeLaSemana = c.diaDeLaSemana;
    const horas = c.horasTrabajadas;
    

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${fecha}</td>
      <td>${diaDeLaSemana}</td>
      <td>${horas}</td>
      <td>
          <button class="boton-icono" title="Editar">
            <i class="material-icons">edit</i>
          </button>
      </td>
      <td>
        <button class="boton-icono" title="Eliminar">
          <i class="material-icons">delete</i>
        </button>
      </td>
    `;
    
    tbody.appendChild(tr);
  }
}