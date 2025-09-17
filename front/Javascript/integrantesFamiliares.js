import { ingresarIntegrantesFamiliares } from '../../BackEnd/APIFetchs/APICooperativa.js';

const $ = (sel, ctx=document) => ctx.querySelector(sel);
const $$ = (sel, ctx=document) => Array.from(ctx.querySelectorAll(sel));

const selectCantidad = $('#cantidad-personas');
const tabsHeader = $('.tabs-header');           
const tabsContent = $('.tabs-content');         
const btnGuardar = $('.btn-guardar');

if (tabsHeader) tabsHeader.style.display = 'none';

let lista = $('#personas-list');
if (!lista) {
  lista = document.createElement('div');
  lista.id = 'personas-list';
  tabsContent.innerHTML = ''; 
  tabsContent.appendChild(lista);
}

function crearBloquePersona(idx) {
  const hoy = new Date().toISOString().slice(0,10); //para no poder poner fechas del futuro

  const contenedor = document.createElement('section');
  contenedor.className = 'persona-bloque card';
  contenedor.dataset.index = idx;

  contenedor.innerHTML = `
    <h3 class="persona-titulo">Persona ${idx}</h3>
    <div class="form-grid">
      <div class="form-group">
        <label>Nombre</label>
        <input id="nombre${idx}" type="text" placeholder="Ingrese nombre"
               title="Ingresa solo tu primer nombre" required
               autocomplete="given-name">
      </div>

      <div class="form-group">
        <label>Apellido</label>
        <input id="apellido${idx}" type="text" placeholder="Ingrese apellido"
               title="Ingresa tu apellido completo" required
               autocomplete="family-name">
      </div>

      <div class="form-group">
        <label>Cédula</label>
        <input id="ci${idx}" type="text" placeholder="Ej: 12345678"
               required pattern="^\\d{7,8}$"
               inputmode="numeric" autocomplete="off"
               title="Debe contener 7 u 8 dígitos numéricos sin puntos ni guiones">
      </div>

      <div class="form-group">
        <label>Fecha de Nacimiento</label>
        <input id="fecha${idx}" type="date" max="${hoy}">
      </div>

      <div class="form-group">
        <label>Género</label>
        <select id="genero${idx}" required>
          <option value="">Seleccione...</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
        </select>
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input id="telefono${idx}" type="tel" placeholder="099 XXX XXX"
               name="telefono" pattern="[0-9]{9,12}" inputmode="tel"
               title="Ingresa tu número (9 a 12 dígitos sin espacios)">
      </div>
    </div>
    <hr>
  `;
  return contenedor;
}



function renderCantidad(nro) {
  lista.innerHTML = '';
  const cantidad = Number(nro);
  for (let i = 1; i <= cantidad; i++) {
    lista.appendChild(crearBloquePersona(i));
  }
}

(function ensureZeroOption(){
  if (![...selectCantidad.options].some(o => o.value === '0')) {
    const opt = new Option('0', '0');
    selectCantidad.insertBefore(opt, selectCantidad.firstChild);
  }
})();

selectCantidad.addEventListener('change', e => {
  renderCantidad(e.target.value);
});

renderCantidad(selectCantidad.value);






// ------ Envío con bloqueo por validaciones nativas ------
btnGuardar.addEventListener('click', async (e) => {
  e.preventDefault();

  const bloques = $$('.persona-bloque', lista);

  // 1) Bloqueo si hay algún campo inválido (required/pattern/min/max)
  const campos = $$('.persona-bloque input, .persona-bloque select', lista);
  for (const el of campos) {
    if (!el.checkValidity()) {
      el.reportValidity(); // muestra el tooltip nativo del navegador
      el.focus();
      return; // corta el envío
    }
  }

  // 2) Si todo OK, armo el payload
  const personas = bloques.map(b => {
    const i = b.dataset.index;
    const nombre   = document.getElementById('nombre'   + i).value.trim();
    const apellido = document.getElementById('apellido' + i).value.trim();
    const ci       = document.getElementById('ci'       + i).value.trim();
    const fechaNacimiento = document.getElementById('fecha' + i).value.trim();
    const genero   = document.getElementById('genero'   + i).value.trim();
    const telefono = document.getElementById('telefono' + i).value.trim();

    return { nombre, apellido, ci, fechaNacimiento, genero, telefono };
  });

  const datos = {
    cantidadIntegrantes: personas.length,
    integrantes: personas
  };

  try {
    const resp = await ingresarIntegrantesFamiliares(datos);
    if (resp?.status === 'exito') {
      alert('Integrantes enviados');
    } else {
      alert('Error: ' + (resp?.message || 'No se pudo registrar'));
    }
  } catch (err) {
    console.error('Error al enviar integrantes', err);
    alert('Error del servidor');
  }
});
