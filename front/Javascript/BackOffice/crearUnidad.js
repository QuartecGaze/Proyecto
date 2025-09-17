// crearUnidad.js
import { crearUnidadHabitacional } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('formNormal');
  if (!formulario) {
    console.error('No se encontró #formNormal');
    return;
  }

  // Éxito global (ya existe en tu HTML)
  let mensajeExito = document.getElementById('mensajeExito');
  // Contenedor de error (lo creamos si no existe)
  let mensajeError = document.getElementById('mensajeError');

  // Dónde insertar mensajes si hay que crearlos
  const contenedorFormulario = document.querySelector('.contenedor-formulario') || formulario.parentElement || document.body;

  if (!mensajeExito) {
    mensajeExito = document.createElement('div');
    mensajeExito.id = 'mensajeExito';
    mensajeExito.className = 'mensaje-exito';
    mensajeExito.style.display = 'none';
    mensajeExito.innerHTML = `<i class="material-icons">check_circle</i><span>Operación exitosa.</span>`;
    contenedorFormulario.appendChild(mensajeExito);
  }

  if (!mensajeError) {
    mensajeError = document.createElement('div');
    mensajeError.id = 'mensajeError';
    mensajeError.className = 'mensaje-error';
    mensajeError.style.display = 'none';
    // si querés el mismo estilo con ícono:
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
    if (span) span.textContent = msg; else mensajeError.textContent = msg;
    mensajeError.style.display = 'flex';
    mensajeExito.style.display = 'none';
  };

  formulario.addEventListener('submit', async (e) => {
    e.preventDefault();

    const datos = {
      numeroPuerta: document.getElementById('numeroPuerta')?.value.trim() ?? '',
      pasillo: document.getElementById('pasillo')?.value.trim() ?? '',
      // El back espera "cantidadHabitaciones" (int)
      cantidadHabitaciones: parseInt(document.getElementById('habitaciones')?.value ?? '', 10)
    };

    if (!datos.numeroPuerta || !datos.pasillo || isNaN(datos.cantidadHabitaciones)) {
      setErr('Completá todos los campos requeridos.');
      return;
    }

    try {
      const r = await crearUnidadHabitacional(datos); // debe pegar a APIBackOffice.php?accion=crearUnidad

      if (r?.status === 'exito') {
        setOk('La unidad ha sido creada exitosamente.');
        // formulario.reset();
      } else {
        setErr('Error: ' + (r?.message || 'No se pudo crear la unidad'));
        if (r?.raw) console.error('HTML del back-end:', r.raw);
      }
    } catch (err) {
      console.error('Error al crear la unidad', err);
      setErr('Error del servidor');
    }
  });
});
