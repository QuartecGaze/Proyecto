// crearUnidad.js
import { crearUnidadHabitacional } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { crearUnidadHabitacionalPersonalizada } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

document.addEventListener('DOMContentLoaded', () => {
  const formNormal = document.getElementById('formNormal');
  const formPersonalizada = document.getElementById('formPersonalizada');

  if (!formNormal && !formPersonalizada) {
    console.error('No se encontró #formNormal ni #formPersonalizada');
    return;
  }

  const $ = (sel, ctx = document) => ctx.querySelector(sel);

  // Contenedor para mensajes
  const contenedorFormulario =
    document.querySelector('.contenedor-formulario') ||
    formNormal?.parentElement ||
    formPersonalizada?.parentElement ||
    document.body;

  // Mensajes (se crean si no existen)
  let mensajeExito = document.getElementById('mensajeExito');
  if (!mensajeExito) {
    mensajeExito = document.createElement('div');
    mensajeExito.id = 'mensajeExito';
    mensajeExito.className = 'mensaje-exito';
    mensajeExito.style.display = 'none';
    mensajeExito.innerHTML = `<i class="material-icons">check_circle</i><span>Operación exitosa.</span>`;
    contenedorFormulario.appendChild(mensajeExito);
  }

  let mensajeError = document.getElementById('mensajeError');
  if (!mensajeError) {
    mensajeError = document.createElement('div');
    mensajeError.id = 'mensajeError';
    mensajeError.className = 'mensaje-error';
    mensajeError.style.display = 'none';
    mensajeError.innerHTML = `<i class="material-icons">error</i><span></span>`;
    contenedorFormulario.appendChild(mensajeError);
  }

  const setOk = (msg = '¡Unidad creada con éxito!') => {
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

  // --- Unidad Normal ---
  if (formNormal) {
    formNormal.addEventListener('submit', async (e) => {
      e.preventDefault();

      const datos = {
        numeroPuerta: $('#numeroPuerta')?.value.trim() ?? '',
        pasillo: $('#pasillo')?.value.trim() ?? '',
        cantidadHabitaciones: parseInt($('#habitaciones')?.value ?? '', 10),
      };

      if (!datos.numeroPuerta || !datos.pasillo || isNaN(datos.cantidadHabitaciones)) {
        setErr('Completá todos los campos requeridos.');
        return;
      }

      try {
        const r = await crearUnidadHabitacional(datos);
        if (r?.status === 'exito') {
          setOk('La unidad ha sido creada exitosamente.');
        } else {
          setErr('Error: ' + (r?.message || 'No se pudo crear la unidad'));
          if (r?.raw) console.error('HTML del back-end:', r.raw);
        }
      } catch (err) {
        console.error('Error al crear la unidad', err);
        setErr('Error del servidor');
      }
    });
  }

  // --- Unidad Personalizada ---
  if (formPersonalizada) {
    formPersonalizada.addEventListener('submit', async (e) => {
      e.preventDefault();

      const datos = {
        numeroPuerta: $('#ciNumeroPuerta')?.value.trim() ?? '',
        pasillo: $('#ciPasillo')?.value.trim() ?? '',
        ci: $('#ci')?.value.trim() ?? '',
      };

      if (!datos.numeroPuerta || !datos.pasillo || !datos.ci) {
        setErr('Completá todos los campos requeridos.');
        return;
      }

      try {
        const r = await crearUnidadHabitacionalPersonalizada(datos);
        if (r?.status === 'exito') {
          setOk(' La unidad personalizada ha sido creada exitosamente.');
        } else {
          setErr('Error: ' + (r?.message || 'No se pudo crear la unidad personalizada'));
          if (r?.raw) console.error('HTML del back-end:', r.raw);
        }
      } catch (err) {
        console.error('Error al crear la unidad personalizada', err);
        setErr('Error del servidor');
      }
    });
  }
});
