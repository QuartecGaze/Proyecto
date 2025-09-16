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
  const wrap = document.createElement('section');
  wrap.className = 'persona-bloque card';
  wrap.dataset.index = idx;

  wrap.innerHTML = `
    <h3 class="persona-titulo">Persona ${idx}</h3>
    <div class="form-grid">
      <div class="form-group">
        <label class="required-field">Nombre</label>
        <input type="text" name="personas[${idx}][nombre]" placeholder="Ingrese nombre" required>
      </div>

      <div class="form-group">
        <label class="required-field">Apellido</label>
        <input type="text" name="personas[${idx}][apellido]" placeholder="Ingrese apellido" required>
      </div>

      <div class="form-group">
        <label class="required-field">Cédula de Identidad</label>
        <input type="text" name="personas[${idx}][ci]" placeholder="Ej: 12345678" required>
      </div>

      <div class="form-group">
        <label class="required-field">Fecha de Nacimiento</label>
        <input type="date" name="personas[${idx}][fechaNacimiento]" required>
      </div>

      <div class="form-group">
        <label class="required-field">Género</label>
        <select name="personas[${idx}][genero]" required>
          <option value="">Seleccione...</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
          <option value="Otro">Otro</option>
        </select>
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input type="tel" name="personas[${idx}][telefono]" placeholder="099 XXX XXX">
      </div>
    </div>
    <hr>
  `;
  return wrap;
}

function renderCantidad(n) {
  lista.innerHTML = '';
  const cantidad = Math.max(0, Math.min(10, Number(n) || 0));
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

// === Envío al back ===
btnGuardar.addEventListener('click', async (e) => {
  e.preventDefault();

  const bloques = $$('.persona-bloque', lista);
  const personas = bloques.map(b => {
    const i = b.dataset.index;
    const get = (name) => b.querySelector(`[name="personas[${i}][${name}]"]`)?.value?.trim() || '';
    return {
      nombre:          get('nombre'),
      apellido:        get('apellido'),
      ci:              get('ci'),
      fechaNacimiento: get('fechaNacimiento'),
      genero:          get('genero'),
      telefono:        get('telefono')
    };
  });

  // Payload que espera el endpoint (el idPersona lo saca de la sesión en el back)
  const payload = {
    cantidadIntegrantes: personas.length,
    integrantes: personas
  };

  try {
    const resp = await ingresarIntegrantesFamiliares(payload); // POST JSON a ...?accion=ingresarIntegrantesFamiliares
    console.log('Respuesta back:', resp);

    if (resp?.status === 'exito' || resp?.status === 'parcial') {
      alert(resp.message || 'Integrantes enviados');
      // opcional: limpiar
      // selectCantidad.value = '0';
      // renderCantidad(0);
    } else {
      alert('Error: ' + (resp?.message || 'No se pudo registrar'));
    }
  } catch (err) {
    console.error('Error al enviar integrantes', err);
    alert('Error del servidor');
  }
});
