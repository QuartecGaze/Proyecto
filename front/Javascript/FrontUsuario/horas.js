import { subirHoras } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const formularioHoras = document.querySelector('.formulario-horas');
const mensajeExitoHoras = formularioHoras.querySelector('.mensaje-exito');
const mensajeErrorHoras = formularioHoras.querySelector('.mensaje-error');

formularioHoras.addEventListener('submit', async (e) => {
  e.preventDefault();

  const horas = document.getElementById('horas').value;

  // Array asociativo como pediste
  const datos = {
    horas: horas
  };

  try {
    const respuesta = await subirHoras(datos);
    if (respuesta.status === 'exito') {
      mensajeExitoHoras.style.display = 'block';
      mensajeErrorHoras.style.display = 'none';
      formularioHoras.reset();
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