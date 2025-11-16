import { getUsuarios } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIntegrantesFamiliares } from '../../../BackEnd/APIFetchs/APICooperativa.js';

// ---------- Helpers ----------
const fmtMon = v => `$ ${Number(v ?? 0).toLocaleString('es-UY')}`;

// Función de sanitización para texto
const sanitizeHTML = (str) => {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#x27;');
};

// Función para sanitizar atributos (src, etc.)
const sanitizeAttribute = (str) => {
    if (str == null) return '';
    return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#x27;');
};

// ---------- Elementos de UI ----------
const contenedor       = document.querySelector(".contenedor-socios");
const modal            = document.getElementById('modalUsuario');
const modalAvatar      = document.getElementById('modalAvatar');
const tablaFamiliares  = document.getElementById('tablaFamiliares');

// Spans para ver datos (solo lectura)
const spans = {
  nombre:          document.getElementById('modalNombre'),
  apellido:        document.getElementById('modalApellido'),
  cedula:          document.getElementById('modalCedula'),
  fechaNacimiento: document.getElementById('modalFechaNacimiento'),
  direccion:       document.getElementById('modalDireccion'),
  email:           document.getElementById('modalEmail'),
  telefono:        document.getElementById('modalTelefono'),
  fechaRegistro:   document.getElementById('modalFechaRegistro'),

  horasTotales:    document.getElementById('modalHorasTotales'),
  horasActual:     document.getElementById('modalHorasActual'),
  saldo:           document.getElementById('modalSaldo'),
};

// ---------- Estado ----------
let usuarios      = [];
let usuarioActual = null;

// ---------- Carga inicial ----------
try {
  const dataUsuarios = await getUsuarios();
  usuarios = Array.isArray(dataUsuarios?.message)
    ? dataUsuarios.message
    : Object.values(dataUsuarios?.message ?? {});

  renderTarjetas(usuarios);
  vincularBotonesAbrirModal();
} catch (err) {
  console.error("Error al cargar usuarios:", err);
}

// ---------- Render tarjetas ----------
function renderTarjetas(lista) {
  if (!contenedor) return;
  contenedor.innerHTML = "";

  lista.forEach((usuario, index) => {
    const fotoPath = usuario.foto
      ? `../../Recursos/FotosPerfil/${sanitizeAttribute(usuario.foto)}`
      : '../../Recursos/FotosPerfil/usuario.webp';

    const direccion = usuario?.direccion || '—';
    const horasTot  = Number(usuario?.horasTrabajadasTotal?.Total_Horas ?? 0);
    const horasAct  = Number(usuario?.horasTrabajadasSemana ?? 0);
    const horasPlan = Number(usuario?.totalHorasATrabajar ?? 0);
    const totalDebe = Number(usuario?.totalDebe ?? 0);

    const claseDeuda = totalDebe <= 0 ? 'green' : 'red';
    const claseHoras = (horasPlan <= 0)
      ? 'gray'
      : (horasAct >= horasPlan ? 'green' : 'red');

    contenedor.innerHTML += `
      <div class="etiqueta">
        <div class="card">
          <div class="card-header">
            <img src="${fotoPath}" alt="Usuario" class="avatar">
            <div class="info">
              <h3>${sanitizeHTML(usuario.nombre ?? '')} ${sanitizeHTML(usuario.apellido ?? '')}</h3>
              <p>${sanitizeHTML(direccion)}</p>
            </div>
          </div>
          <div class="card-footer">
            <span class="tag gray">${horasTot} Horas Trabajadas Totales</span>
            <span class="tag ${claseHoras}">${horasAct}/${horasPlan}</span>
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

// ---------- Vincular eventos para abrir/cerrar modal ----------
function vincularBotonesAbrirModal() {
  // Abrir modal al hacer click en el ojo
  document.querySelectorAll('.actions button.boton-ver-usuario').forEach(boton => {
    boton.addEventListener('click', async function () {
      const index = Number(this.getAttribute('data-index'));
      usuarioActual = usuarios[index] ?? null;
      if (!usuarioActual) return;
      await poblarModal(usuarioActual);
      abrirModal();
    });
  });

  // Cerrar modal (botones con clase .cerrar-modal, incluyendo la X y el botón "Cerrar")
  document.querySelectorAll('.cerrar-modal').forEach(btn => {
    btn.addEventListener('click', cerrarModal);
  });

  // Cerrar clickeando fuera
  window.addEventListener('click', function (e) {
    if (e.target === modal) cerrarModal();
  });

  // Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
      cerrarModal();
    }
  });
}

// ---------- Modal: poblar ----------
async function poblarModal(usuario) {
  if (!modal || !modalAvatar) return;

  // Foto
  modalAvatar.src = usuario.foto
    ? `../../Recursos/FotosPerfil/${sanitizeAttribute(usuario.foto)}`
    : '../../Recursos/FotosPerfil/usuario.webp';

  // Datos básicos
  spans.nombre.textContent          = sanitizeHTML(usuario.nombre ?? '—');
  spans.apellido.textContent        = sanitizeHTML(usuario.apellido ?? '—');
  spans.cedula.textContent          = sanitizeHTML(usuario.ci ?? '—');
  spans.fechaNacimiento.textContent = sanitizeHTML(usuario.fechaNacimiento || 'No proporcionada');
  spans.direccion.textContent       = sanitizeHTML(usuario.direccion || 'No proporcionada');
  spans.email.textContent           = sanitizeHTML(usuario.email || 'No proporcionado');
  spans.fechaRegistro.textContent   = sanitizeHTML(usuario.fechaIngreso || 'No proporcionada');

  // Teléfono (puede venir como array o como string)
  spans.telefono.innerHTML = '';
  if (Array.isArray(usuario.telefono)) {
    if (usuario.telefono.length === 0) {
      spans.telefono.textContent = 'No proporcionado';
    } else {
      usuario.telefono.forEach(t => {
        spans.telefono.innerHTML += `${sanitizeHTML(t)}<br>`;
      });
    }
  } else {
    spans.telefono.textContent = sanitizeHTML(usuario.telefono || 'No proporcionado');
  }

  // Integrantes familiares
  tablaFamiliares.innerHTML = '';

  let familiaresRaw =
    Array.isArray(usuario.integrantesFamiliares) && usuario.integrantesFamiliares.length
      ? usuario.integrantesFamiliares
      : (await getIntegrantesFamiliares(usuario.idPersona))?.message ?? [];

  // Normalizador de claves
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
          <td>${sanitizeHTML(int.nombre)}</td>
          <td>${sanitizeHTML(int.apellido)}</td>
          <td>${sanitizeHTML(int.ci)}</td>
          <td>${sanitizeHTML(int.email)}</td>
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

  // Colores de horas (reutilizo clases tag/green/red/gray de las tarjetas)
  spans.horasActual.classList.remove('green', 'red', 'gray', 'tag');
  spans.horasActual.classList.add('tag');
  if (horasPlan <= 0) {
    spans.horasActual.classList.add('gray');
  } else if (horasSemana >= horasPlan) {
    spans.horasActual.classList.add('green');
  } else {
    spans.horasActual.classList.add('red');
  }

  // Colorear saldo del modal (usa tus clases .monto.positivo / .monto.negativo si existen)
  spans.saldo.classList.add('monto');
  spans.saldo.classList.toggle('positivo', totalDebe <= 0);
  spans.saldo.classList.toggle('negativo', totalDebe > 0);
}

// ---------- Modal: abrir/cerrar ----------
function abrirModal() {
  if (!modal) return;
  modal.style.display = 'flex';
}

function cerrarModal() {
  if (!modal) return;
  modal.style.display = 'none';
}
