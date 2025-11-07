import { asignarPagoMensual } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarPagoPersonalizado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

// Función para escapar HTML y prevenir XSS
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

const formularioPagoMensual = document.getElementById('form-pago-mensual');
const formularioPagoPersonalizado = document.getElementById('form-pago-personalizado');
const mensajeExito = formularioPagoMensual.querySelector('.mensaje-exito');
const mensajeError = formularioPagoMensual.querySelector('.mensaje-error');
const mensajeExitoPersonalizado = formularioPagoPersonalizado.querySelector('.mensaje-exito');
const mensajeErrorPersonalizado = formularioPagoPersonalizado.querySelector('.mensaje-error');

//Asignar Pago Mensual
formularioPagoMensual.addEventListener('submit', async (e) => {
    e.preventDefault();

    const monto = document.getElementById('monto-general').value;

    const datos = {
        montoPagoMensual: monto
    };

    try {
        const respuesta = await asignarPagoMensual(datos);
        if (respuesta.status === 'exito') {
            mensajeExito.style.display = 'block';
            mensajeError.style.display = 'none';
        } else {
            mensajeExito.style.display = 'none';
            mensajeError.style.display = 'block';
            mensajeError.textContent = 'Error: ' + escapeHtml(respuesta.message);
        }
    } catch (error) {
        console.error('Error al asignar pago mensual', error);
        mensajeExito.style.display = 'none';
        mensajeError.style.display = 'block';
        mensajeError.textContent = 'Error del servidor';
    }
});

//Asignar Pago Personalizado
formularioPagoPersonalizado.addEventListener('submit', async (e) => {
  e.preventDefault();

  const ci = document.getElementById('cedula').value.trim();
  const monto = document.getElementById('monto-personalizado').value;
  const motivo = document.getElementById('motivo').value.trim();

  const datos = {
    ci: ci,
    montoPagoPersonalizado: monto,
    motivoPagoPersonalizado: motivo
  };

  try {
    const respuesta = await asignarPagoPersonalizado(datos);
    if (respuesta.status === 'exito') {
      mensajeExitoPersonalizado.style.display = 'block';
      mensajeErrorPersonalizado.style.display = 'none';
    } else {
      mensajeExitoPersonalizado.style.display = 'none';
      mensajeErrorPersonalizado.style.display = 'block';
      mensajeErrorPersonalizado.textContent = 'Error: ' + escapeHtml(respuesta.message);
    }
  } catch (error) {
    console.error('Error al asignar pago personalizado', error);
    mensajeExitoPersonalizado.style.display = 'none';
    mensajeErrorPersonalizado.style.display = 'block';
    mensajeErrorPersonalizado.textContent = 'Error del servidor';
  }
});