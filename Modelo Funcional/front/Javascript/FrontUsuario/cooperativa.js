import { getCooperativa } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';

// ---------- Funciones de sanitización ----------
const sanitizeHTML = (str) => {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#x27;');
};

const sanitizeAttribute = (str) => {
    if (str == null) return '';
    return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#x27;');
};

/// --- DOM Generalidades (usuario) ---
const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = "usuario.webp"; // fallback

/// --- DOM Cooperativa (dashboard) ---
const pagosAtrasadosCantidad = document.getElementById("pagosAtrasadosCantidad");
const pagosAtrasadosTotal = document.getElementById("pagosAtrasadosTotal");
const cardPagosAtrasados = document.getElementById("cardPagosAtrasados");
const horasTrabajadas = document.getElementById("horasTrabajadas");
const objetivoHoras = document.getElementById("horasObjetivo");
const porcentajeFaltas = document.getElementById("porcentajeFaltas");

// opcional: barra de progreso si existe en index
const barraDeProgreso = document.getElementById('progresoHoras');
const barraEl = barraDeProgreso?.parentElement;

// --- Obtener id persona desde sesión ---
const sesion = await getIdSesion();               // {status:'exito', message:'3'}
const id = Number(sesion.message);

// --- Cargar usuario + cooperativa ---
const usr = await getUsuario(id);
setDatosUsuario(usr.message);

const coop = await getCooperativa(id);
setDatosCooperativa(coop.message);

function setDatosUsuario(data) {
  nombre.forEach(n => { n.textContent = sanitizeHTML(`${data.nombre} ${data.apellido}`); });
  foto.forEach(f => {
    f.src = fotoruta + (data.foto && data.foto !== '' ? sanitizeAttribute(data.foto) : fotoUsuario);
  });
}

function setDatosCooperativa(data) {
  if (horasTrabajadas) horasTrabajadas.textContent = sanitizeHTML(data.horasTrabajadas);
  if (objetivoHoras) objetivoHoras.textContent = sanitizeHTML(data.horasObjetivo);
  if (porcentajeFaltas) porcentajeFaltas.textContent = sanitizeHTML(data.porcentajeFaltas + "%");
  if (pagosAtrasadosCantidad) {
    pagosAtrasadosCantidad.textContent = sanitizeHTML(data.pagosAtrasados);
    if (data.pagosAtrasados > 0) {
      pagosAtrasadosCantidad.classList.add("atrasado");
      cardPagosAtrasados?.classList.add("atrasado");
    } else {
      pagosAtrasadosCantidad.classList.remove("atrasado");
      cardPagosAtrasados?.classList.remove("atrasado");
    }
  }
  if (pagosAtrasadosTotal) {
    pagosAtrasadosTotal.textContent = "Total: $" + sanitizeHTML(data.pagosAtrasadosDinero);
  }

  // si hay barra de progreso en index, actualizarla
  Progreso(data.horasObjetivo, data.horasTrabajadas);
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