// socios.js - Página Socios (listar tarjetas, abrir modal, editar y guardar)
// Dependencias (ya las tenías):
import { getUsuarios } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { actualizarUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIntegrantesFamiliares } from '../../../BackEnd/APIFetchs/APICooperativa.js';

// ---------- Helpers ----------
const fmtMon = v => `$ ${Number(v ?? 0).toLocaleString('es-UY')}`;

// ---------- Elementos de UI ----------
const contenedor = document.querySelector(".contenedor-socios");

const modal = document.getElementById('modalUsuario');
const modalAvatar = document.getElementById('modalAvatar');
const tablaFamiliares = document.getElementById('tablaFamiliares');

const btnEditar   = document.getElementById('btnEditarUsuario');
const btnGuardar  = document.getElementById('btnGuardarCambios');
const btnCancelar = document.getElementById('btnCancelarEdicion');

// Spans para ver datos
const spans = {
  nombre: document.getElementById('modalNombre'),
  apellido: document.getElementById('modalApellido'),
  cedula: document.getElementById('modalCedula'),
  fechaNacimiento: document.getElementById('modalFechaNacimiento'),
  direccion: document.getElementById('modalDireccion'),
  email: document.getElementById('modalEmail'),
  telefono: document.getElementById('modalTelefono'),
  fechaRegistro: document.getElementById('modalFechaRegistro'),

  horasTotales: document.getElementById('modalHorasTotales'),
  horasActual: document.getElementById('modalHorasActual'),
  saldo: document.getElementById('modalSaldo'),
};

// Inputs para editar
const inputs = {
  nombre: document.getElementById('inputNombre'),
  apellido: document.getElementById('inputApellido'),
  cedula: document.getElementById('inputCedula'),
  fechaNacimiento: document.getElementById('inputFechaNacimiento'),
  direccion: document.getElementById('inputDireccion'),
  email: document.getElementById('inputEmail'),
  telefono: document.getElementById('inputTelefono'),
};

// ---------- Estado ----------
let usuarios = [];
let usuarioActual = null;   // objeto completo del usuario abierto
let telefonoActual = [];    // array de teléfonos para no parsear HTML

// ---------- Carga inicial ----------
const dataUsuarios = await getUsuarios();
usuarios = Array.isArray(dataUsuarios?.message)
  ? dataUsuarios.message
  : Object.values(dataUsuarios?.message ?? {});

renderTarjetas(usuarios);
vincularBotonesAbrirModal();

// ---------- Render tarjetas ----------
function renderTarjetas(lista) {
    if (!contenedor) return;
    contenedor.innerHTML = "";
  
    lista.forEach((usuario, index) => {
      const fotoPath = usuario.foto
        ? `../../Recursos/FotosPerfil/${usuario.foto}`
        : '../../Recursos/FotosPerfil/usuario.webp';
  
      const direccion = usuario?.direccion || '—';
      const horasTot  = Number(usuario?.horasTrabajadasTotal?.Total_Horas ?? 0);
      const horasAct  = Number(usuario?.horasTrabajadasSemana ?? 0);
      const horasPlan = Number(usuario?.totalHorasATrabajar ?? 0);
      const totalDebe = Number(usuario?.totalDebe ?? 0);
  
      const claseDeuda = totalDebe <= 0 ? 'green' : 'red'; // <=0 verde, >0 rojo
  
      contenedor.innerHTML += `
        <div class="etiqueta">
          <div class="card">
            <div class="card-header">
              <img src="${fotoPath}" alt="Usuario" class="avatar">
              <div class="info">
                <h3>${usuario.nombre ?? ''} ${usuario.apellido ?? ''}</h3>
                <p>${direccion}</p>
              </div>
            </div>
            <div class="card-footer">
              <span class="tag gray">${horasTot} Horas Trabajadas Totales</span>
              <span class="tag red">${horasAct}/${horasPlan}</span>
              <span class="tag ${claseDeuda}">${fmtMon(totalDebe)}</span>
            </div>
          </div>
          <div class="actions">
            <button data-index="${index}" class="boton-ver-usuario">
              <i class="material-icons" style="font-size: 40px;">visibility</i>
            </button>
          </div>
        </div>
      `;
    });
  }
  

// ---------- Vincular eventos para abrir modal ----------
function vincularBotonesAbrirModal() {
  document.querySelectorAll('.actions button.boton-ver-usuario').forEach(boton => {
    boton.addEventListener('click', async function () {
      const index = Number(this.getAttribute('data-index'));
      usuarioActual = usuarios[index] ?? null;
      if (!usuarioActual) return;
      await poblarModal(usuarioActual);
      abrirModal();
    });
  });

  // Cerrar modal (botones con clase .cerrar-modal)
  document.querySelectorAll('.cerrar-modal').forEach(btn => {
    btn.addEventListener('click', cerrarModal);
  });

  // Cerrar clickeando fuera
  window.addEventListener('click', function (e) {
    if (e.target === modal) cerrarModal();
  });

  // Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.style.display === 'flex') cerrarModal();
  });

  // Edición
  btnEditar?.addEventListener('click', activarModoEdicion);
  btnCancelar?.addEventListener('click', desactivarModoEdicion);
  btnGuardar?.addEventListener('click', guardarCambios);
}

// ---------- Modal: poblar ----------
async function poblarModal(usuario) {
  // Foto
  modalAvatar.src = usuario.foto
    ? `../../Recursos/FotosPerfil/${usuario.foto}`
    : '../../Recursos/FotosPerfil/usuario.webp';

  // Datos básicos
  spans.nombre.textContent = usuario.nombre ?? '—';
  spans.apellido.textContent = usuario.apellido ?? '—';
  spans.cedula.textContent = usuario.ci ?? '—';
  spans.fechaNacimiento.textContent = usuario.fechaNacimiento || 'No proporcionada';
  spans.direccion.textContent = usuario.direccion || 'No proporcionada';
  spans.email.textContent = usuario.email || 'No proporcionado';
  spans.fechaRegistro.textContent = usuario.fechaIngreso || 'No proporcionada';

  // Teléfonos (array)
  spans.telefono.innerHTML = '';
  telefonoActual = Array.isArray(usuario.telefono) ? usuario.telefono : [];
  telefonoActual.forEach(t => spans.telefono.innerHTML += `${t}<br>`);

  // Integrantes familiares: uso lo que venga, si no hay, lo busco
tablaFamiliares.innerHTML = '';

let familiaresRaw = Array.isArray(usuario.integrantesFamiliares) && usuario.integrantesFamiliares.length
  ? usuario.integrantesFamiliares
  : (await getIntegrantesFamiliares(usuario.idPersona))?.message ?? [];

// Normalizador de claves (acepta Nombre/Apellido/CI/Email o nombre/apellido/ci/email)
const normFam = (i = {}) => ({
  nombre:   i.nombre   ?? i.Nombre   ?? '',
  apellido: i.apellido ?? i.Apellido ?? '',
  ci:       i.ci       ?? i.CI       ?? '',
  email:    i.email    ?? i.Email    ?? '',
});

const familiares = familiaresRaw.map(normFam);

if (familiares.length === 0) {
  tablaFamiliares.innerHTML = `
    <tr>
      <td colspan="4" style="text-align:center; color:#777;">Sin integrantes familiares</td>
    </tr>`;
} else {
  familiares.forEach(int => {
    tablaFamiliares.innerHTML += `
      <tr>
        <td>${int.nombre}</td>
        <td>${int.apellido}</td>
        <td>${int.ci}</td>
        <td>${int.email}</td>
      </tr>`;
  });
}


  // Estadísticas
const horasTotales = Number(usuario?.horasTrabajadasTotal?.Total_Horas ?? 0);
const horasSemana  = Number(usuario?.horasTrabajadasSemana ?? 0);
const horasPlan    = Number(usuario?.totalHorasATrabajar ?? 0);
const totalDebe    = Number(usuario?.totalDebe ?? 0);

spans.horasTotales.textContent = horasTotales;
spans.horasActual.textContent  = `${horasSemana}/${horasPlan}`;
spans.saldo.textContent        = fmtMon(totalDebe);

// Colorear saldo del modal (usa tus clases .monto.positivo / .monto.negativo)
spans.saldo.classList.add('monto'); // asegura que aplique el CSS existente
spans.saldo.classList.toggle('positivo', totalDebe <= 0);
spans.saldo.classList.toggle('negativo', totalDebe > 0);


  // Aseguramos que el modo edición esté apagado cada vez que se abre
  desactivarModoEdicion();
}

// ---------- Modal: abrir/cerrar ----------
function abrirModal() {
  modal.style.display = 'flex';
}

function cerrarModal() {
  modal.style.display = 'none';
}

// ---------- Edición ----------
function activarModoEdicion() {
  // Mostrar inputs, ocultar spans
  Object.keys(spans).forEach(key => {
    if (inputs[key]) {
      spans[key].style.display = 'none';
      inputs[key].style.display = 'block';
      // Para fecha, si viene AAAA-MM-DD se coloca directo; si viene DD/MM/AAAA, habría que convertir.
      inputs[key].value = spans[key].textContent === 'No proporcionada' ? '' : spans[key].textContent;
    }
  });

  // Teléfonos al input (separados por coma)
  if (inputs.telefono) inputs.telefono.value = telefonoActual.join(', ');

  btnEditar.style.display = 'none';
  btnGuardar.style.display = 'block';
  btnCancelar.style.display = 'block';
}

function desactivarModoEdicion() {
  // Ocultar inputs, mostrar spans
  Object.keys(spans).forEach(key => {
    if (inputs[key]) {
      spans[key].style.display = 'block';
      inputs[key].style.display = 'none';
    }
  });

  btnEditar.style.display = 'block';
  btnGuardar.style.display = 'none';
  btnCancelar.style.display = 'none';
}

// ---------- Guardar ----------
async function guardarCambios() {
  if (!usuarioActual) return;

  // Parseo teléfonos si se editan
  const nuevosTels = inputs.telefono && inputs.telefono.style.display === 'block'
    ? inputs.telefono.value.split(',').map(t => t.trim()).filter(Boolean)
    : telefonoActual;

  const payload = {
    id: usuarioActual.idPersona,
    nombre: inputs.nombre.value?.trim(),
    apellido: inputs.apellido.value?.trim(),
    ci: inputs.cedula.value?.trim(),
    fechaNacimiento: inputs.fechaNacimiento.value?.trim(),
    direccion: inputs.direccion.value?.trim(),
    email: inputs.email.value?.trim(),
    telefono: nuevosTels,
  };

  // Enviar a API
  try {
    await actualizarUsuario(payload);

    // Reflejar cambios en UI (spans y tarjeta en memoria)
    spans.nombre.textContent = payload.nombre || '—';
    spans.apellido.textContent = payload.apellido || '—';
    spans.cedula.textContent = payload.ci || '—';
    spans.fechaNacimiento.textContent = payload.fechaNacimiento || 'No proporcionada';
    spans.direccion.textContent = payload.direccion || 'No proporcionada';
    spans.email.textContent = payload.email || 'No proporcionado';
    spans.telefono.innerHTML = '';
    payload.telefono.forEach(t => spans.telefono.innerHTML += `${t}<br>`);

    // Actualizo objeto en memoria (por si se reabre el modal)
    Object.assign(usuarioActual, {
      nombre: payload.nombre,
      apellido: payload.apellido,
      ci: payload.ci,
      fechaNacimiento: payload.fechaNacimiento,
      direccion: payload.direccion,
      email: payload.email,
      telefono: payload.telefono,
    });

    // Opcional: refrescar tarjetas mínimamente (solo nombre/dirección visual)
    // Más simple: re-render completo
    renderTarjetas(usuarios);
    vincularBotonesAbrirModal();

    desactivarModoEdicion();
    cerrarModal();
  } catch (e) {
    console.error('Error al actualizar usuario:', e);
    alert('No se pudieron guardar los cambios.');
  }
}


/*
document.addEventListener('DOMContentLoaded', function () {
    // Elementos del modal
    const modal = document.getElementById('modalUsuario');
    const btnEditar = document.getElementById('btnEditarUsuario');
    const btnGuardar = document.getElementById('btnGuardarCambios');
    const btnCancelar = document.getElementById('btnCancelarEdicion');
    const btnCerrar = document.getElementById('btnCerrarModal');

    // Elementos de visualización (spans)
    const spans = {
        nombre: document.getElementById('modalNombre'),
        cedula: document.getElementById('modalCedula'),
        fechaNacimiento: document.getElementById('modalFechaNacimiento'),
        direccion: document.getElementById('modalDireccion'),
        email: document.getElementById('modalEmail'),
        telefono: document.getElementById('modalTelefono')
    };

    // Elementos de entrada (inputs)
    const inputs = {
        nombre: document.getElementById('inputNombre'),
        cedula: document.getElementById('inputCedula'),
        fechaNacimiento: document.getElementById('inputFechaNacimiento'),
        direccion: document.getElementById('inputDireccion'),
        email: document.getElementById('inputEmail'),
        telefono: document.getElementById('inputTelefono')
    };

    // Datos originales (para restaurar en caso de cancelar)
    let datosOriginales = {};

    // Función para formatear fecha de DD/MM/AAAA a AAAA-MM-DD (para input type="date")
    function formatearFechaParaInput(fecha) {
        if (!fecha) return '';

        const partes = fecha.split('/');
        if (partes.length === 3) {
            return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        }
        return fecha;
    }

    // Función para formatear fecha de AAAA-MM-DD a DD/MM/AAAA (para mostrar)
    function formatearFechaParaMostrar(fecha) {
        if (!fecha) return '';

        const partes = fecha.split('-');
        if (partes.length === 3) {
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }
        return fecha;
    }

    // Función para activar el modo edición
    function activarModoEdicion() {
        // Guardar datos originales
        guardarDatosOriginales();

        // Ocultar spans y mostrar inputs
        Object.keys(spans).forEach(key => {
            if (inputs[key]) {
                spans[key].style.display = 'none';
                inputs[key].style.display = 'block';

                // Caso especial para fecha de nacimiento
                if (key === 'fechaNacimiento') {
                    inputs[key].value = formatearFechaParaInput(spans[key].textContent);
                } else {
                    inputs[key].value = spans[key].textContent;
                }
            }
        });

        // Cambiar visibilidad de botones
        btnEditar.style.display = 'none';
        btnGuardar.style.display = 'block';
        btnCancelar.style.display = 'block';
    }

    // Función para desactivar el modo edición
    function desactivarModoEdicion() {
        // Ocultar inputs y mostrar spans
        Object.keys(spans).forEach(key => {
            if (inputs[key]) {
                spans[key].style.display = 'block';
                inputs[key].style.display = 'none';
            }
        });

        // Cambiar visibilidad de botones
        btnEditar.style.display = 'block';
        btnGuardar.style.display = 'none';
        btnCancelar.style.display = 'none';
    }

    // Función para guardar datos originales
    function guardarDatosOriginales() {
        datosOriginales = {
            nombre: spans.nombre.textContent,
            cedula: spans.cedula.textContent,
            fechaNacimiento: spans.fechaNacimiento.textContent,
            direccion: spans.direccion.textContent,
            email: spans.email.textContent,
            telefono: spans.telefono.textContent
        };
    }

    // Función para restaurar datos originales
    function restaurarDatosOriginales() {
        spans.nombre.textContent = datosOriginales.nombre;
        spans.cedula.textContent = datosOriginales.cedula;
        spans.fechaNacimiento.textContent = datosOriginales.fechaNacimiento;
        spans.direccion.textContent = datosOriginales.direccion;
        spans.email.textContent = datosOriginales.email;
        spans.telefono.textContent = datosOriginales.telefono;
    }

    // Función para validar datos antes de guardar
    function validarDatos() {
        let errores = [];

        // Validar nombre (no vacío)
        if (!inputs.nombre.value.trim()) {
            errores.push('El nombre no puede estar vacío');
        }

        // Validar cédula (no vacía)
        if (!inputs.cedula.value.trim()) {
            errores.push('La cédula no puede estar vacía');
        }

        // Validar fecha de nacimiento
        if (!inputs.fechaNacimiento.value) {
            errores.push('La fecha de nacimiento es obligatoria');
        } else {
            // Validar que la fecha no sea futura
            const fechaNacimiento = new Date(inputs.fechaNacimiento.value);
            const hoy = new Date();
            if (fechaNacimiento > hoy) {
                errores.push('La fecha de nacimiento no puede ser futura');
            }

            // Validar edad mínima (por ejemplo, 18 años)
            const edadMinima = new Date();
            edadMinima.setFullYear(edadMinima.getFullYear() - 18);
            if (fechaNacimiento > edadMinima) {
                errores.push('El socio debe ser mayor de 18 años');
            }
            
            // Validar edad máxima (por ejemplo, 100 años)
            const edadMaxima = new Date();
            edadMaxima.setFullYear(edadMaxima.getFullYear() - 100);
            if (fechaNacimiento < edadMaxima) {
                errores.push('La fecha de nacimiento no es válida');
            }
        }

        // Validar email (formato básico)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (inputs.email.value && !emailRegex.test(inputs.email.value)) {
            errores.push('El formato del email no es válido');
        }
        return errores;
    }

    // Función para calcular edad a partir de la fecha de nacimiento
    function calcularEdad(fechaNacimiento) {
        const nacimiento = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }
        return edad;
    }

    // Función para guardar cambios
    function guardarCambios() {
        // Validar datos antes de guardar
        const errores = validarDatos();
        if (errores.length > 0) {
            alert('Errores en el formulario:\n' + errores.join('\n'));
            return;
        }
        // Actualizar spans con los valores de los inputs
        spans.nombre.textContent = inputs.nombre.value;
        spans.cedula.textContent = inputs.cedula.value;
        spans.fechaNacimiento.textContent = formatearFechaParaMostrar(inputs.fechaNacimiento.value);
        spans.direccion.textContent = inputs.direccion.value;
        spans.email.textContent = inputs.email.value;
        spans.telefono.textContent = inputs.telefono.value;

        // Calcular y mostrar edad (opcional)
        const edad = calcularEdad(inputs.fechaNacimiento.value);
        console.log(`Edad calculada: ${edad} años`);

        // Aquí iría la lógica para enviar los datos al servidor
        const datosParaGuardar = {
            nombre: inputs.nombre.value,
            cedula: inputs.cedula.value,
            fechaNacimiento: inputs.fechaNacimiento.value,
            direccion: inputs.direccion.value,
            email: inputs.email.value,
            telefono: inputs.telefono.value
        };

        console.log('Datos guardados:', datosParaGuardar);
        // Desactivar modo edición
        desactivarModoEdicion();
        // Mostrar mensaje de éxito
        alert('Los cambios se han guardado correctamente');
    }

    // Event Listeners
    btnEditar.addEventListener('click', activarModoEdicion);
    btnGuardar.addEventListener('click', guardarCambios);
    btnCancelar.addEventListener('click', function () {
        restaurarDatosOriginales();
        desactivarModoEdicion();
    });

    // Cerrar modal
    btnCerrar.addEventListener('click', function () {
        // Si estamos en modo edición, cancelar antes de cerrar
        if (btnGuardar.style.display !== 'none') {
            restaurarDatosOriginales();
            desactivarModoEdicion();
        }
        modal.style.display = 'none';
    });

    // Cerrar modal al hacer clic en la X
    document.querySelector('.cerrar-modal').addEventListener('click', function () {
        // Si estamos en modo edición, cancelar antes de cerrar
        if (btnGuardar.style.display !== 'none') {
            restaurarDatosOriginales();
            desactivarModoEdicion();
        }
        modal.style.display = 'none';
    });

    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            // Si estamos en modo edición, cancelar antes de cerrar
            if (btnGuardar.style.display !== 'none') {
                restaurarDatosOriginales();
                desactivarModoEdicion();
            }
            modal.style.display = 'none';
        }
    });

    // Permitir enviar formulario con Enter y cancelar con Escape
    document.addEventListener('keydown', function (event) {
        // Solo si estamos en modo edición
        if (btnGuardar.style.display !== 'none') {
            if (event.key === 'Enter' && event.ctrlKey) {
                guardarCambios();
            } else if (event.key === 'Escape') {
                restaurarDatosOriginales();
                desactivarModoEdicion();
            }
        }
    });
});



// MODAL - Funcionalidad para abrir y cerrar el modal de usuario

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalUsuario');
    const btnCerrar = document.getElementById('btnCerrarModal');

    function abrirModal() {
        modal.style.display = 'flex';
        desactivarModoEdicion();
    }

    function cerrarModal() {
        modal.style.display = 'none';
    }

    function desactivarModoEdicion() {
        const btnEditar = document.getElementById('btnEditarUsuario');
        const btnGuardar = document.getElementById('btnGuardarCambios');
        const btnCancelar = document.getElementById('btnCancelarEdicion');
        document.querySelectorAll('.campo-editable').forEach(input => {
            input.style.display = 'none';
        });
        document.querySelectorAll('.info-completa span').forEach(span => {
            span.style.display = 'block';
        });
        if (btnEditar) btnEditar.style.display = 'block';
        if (btnGuardar) btnGuardar.style.display = 'none';
        if (btnCancelar) btnCancelar.style.display = 'none';
    }

    document.querySelectorAll('.actions button').forEach(boton => {
        boton.addEventListener('click', function () {
            console.log('Abriendo modal de usuario');
            abrirModal();
        });
    });

    btnCerrar.addEventListener('click', cerrarModal);
    document.querySelector('.cerrar-modal').addEventListener('click', cerrarModal);

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            cerrarModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display === 'flex') {
            cerrarModal();
        }
    });
});

/*/
