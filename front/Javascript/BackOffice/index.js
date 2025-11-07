import { cantidadPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

// Función para escapar HTML y prevenir XSS
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

const respuesta = await cantidadPendientes();
const datos = respuesta.message;
document.getElementById('solicitudesPendientes').innerHTML = `${escapeHtml(datos.interesados)} <span>Solicitudes</span>`;
document.getElementById('faltasEnEspera').textContent = escapeHtml(datos.faltas);
document.getElementById('comprobantesPendientes').textContent = escapeHtml(datos.comprobantes);