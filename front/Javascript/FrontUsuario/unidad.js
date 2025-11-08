// unidad.js (type="module")

/* ========== Imports ========== */
import { getUsuario, getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import {
  getUnidadHabitacional,
  getIntegrantesFamiliares,
  editarIntegranteFamiliar,
  ingresarIntegrantesFamiliares,
  eliminarIntegranteFamiliar
} from '../../../BackEnd/APIFetchs/APICooperativa.js';

/* ========== DOM básico ========== */
const nombreEls = document.querySelectorAll(".nombreUsuario");
const fotoEls = document.querySelectorAll(".fotoPerfil");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = "usuario.webp";

/* ========== DOM Unidad ========== */
const numeroUnidad = document.getElementById("numeroUnidad");
const estadoUnidad = document.getElementById("estadoUnidad");
const pasilloUnidad = document.getElementById("pasilloUnidad");
const habitacionesUnidad = document.getElementById("habitacionesUnidad");

/* ========== DOM Integrantes ========== */
const listaIntegrantes = document.getElementById("lista-integrantes");
const sinIntegrantes = document.getElementById("sin-integrantes");

/* ========== Botones / Modales / Formularios ========== */
const btnEditarUnidad = document.getElementById("btnEditarUnidad");
const btnAgregarIntegrante = document.getElementById("btnAgregarIntegrante");
const modalEditarUnidad = document.getElementById("modalEditarUnidad");
const modalIntegrante = document.getElementById("modalIntegrante");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const formEditarUnidad = document.getElementById("formEditarUnidad");
const formIntegrante = document.getElementById("formIntegrante");

/* ========== Helpers ========== */
const unwrap = res => (res && typeof res === "object" && "message" in res ? res.message : res);

const toast = (msg, tipo = "exito") => {
  const n = document.createElement("div");
  n.textContent = msg;
  n.style.cssText = `
    position: fixed; top: 20px; right: 20px; padding: 10px 14px; border-radius: 8px;
    color: #fff; background: ${tipo === "exito" ? "#2ecc71" : "#e74c3c"};
    font-weight: 600; z-index: 9999; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  `;
  document.body.appendChild(n);
  setTimeout(() => n.remove(), 2200);
};

const formatearFecha = f => (!f ? "" : new Date(f).toLocaleDateString("es-ES"));

/* ========== Estado global ========== */
let ID_PERSONA = null;
let unidadActual = null;
let integrantesActuales = [];
let integranteEditando = null;

/* ========== Inicio ========== */
document.addEventListener("DOMContentLoaded", async () => {
  try {
    ID_PERSONA = unwrap(await getIdSesion());
    if (!ID_PERSONA) return console.error("No se obtuvo ID de sesión");

    await cargarDatosUsuario(ID_PERSONA);
    await cargarDatosUnidad(ID_PERSONA);
    await cargarIntegrantes(ID_PERSONA);
    inicializarListeners();
  } catch (e) {
    console.error(e);
  }
});

/* ========== Cargas ========== */
async function cargarDatosUsuario(id) {
  const usuario = unwrap(await getUsuario(id));
  if (!usuario) return;
  nombreEls.forEach(el => (el.textContent = `${usuario.nombre} ${usuario.apellido}`));
  fotoEls.forEach(el => (el.src = fotoruta + (usuario.foto || fotoUsuario)));
}

async function cargarDatosUnidad(id) {
  const data = unwrap(await getUnidadHabitacional(id));
  const u = Array.isArray(data) ? data[0] : data;
  if (!u) return;

  unidadActual = u;
  numeroUnidad.textContent = u.nroPuerta ?? "—";
  estadoUnidad.textContent = u.estado ?? "—";
  pasilloUnidad.textContent = u.pasillo ?? "—";
  habitacionesUnidad.textContent = u.habitaciones ?? "—";
}

async function cargarIntegrantes(id) {
  const data = unwrap(await getIntegrantesFamiliares(id));
  integrantesActuales = Array.isArray(data) ? data : data ? [data] : [];
  renderIntegrantes(integrantesActuales);
}

/* ========== Render ========== */
function renderIntegrantes(list) {
  if (!listaIntegrantes) return;
  if (!list.length) {
    listaIntegrantes.innerHTML = "";
    sinIntegrantes.style.display = "block";
    return;
  }
  sinIntegrantes.style.display = "none";

  listaIntegrantes.innerHTML = list
    .map(
      i => `
      <tr>
        <td>${i.nombre ?? ""}</td>
        <td>${i.apellido ?? ""}</td>
        <td>${i.ci ?? ""}</td>
        <td>${i.email ?? ""}</td>
        <td>${formatearFecha(i.fechaNacimiento ?? i.fecha_nacimiento)}</td>
        <td>${i.genero ?? ""}</td>
        <td>
          <button class="boton-icono editar-integrante" data-id="${i.id}"><i class="material-icons">edit</i></button>
          <button class="boton-icono peligro eliminar-integrante" data-id="${i.id}"><i class="material-icons">delete</i></button>
        </td>
      </tr>`
    )
    .join("");

  listaIntegrantes.querySelectorAll(".editar-integrante").forEach(b =>
    b.addEventListener("click", () => editarIntegrante(b.dataset.id))
  );
  listaIntegrantes.querySelectorAll(".eliminar-integrante").forEach(b =>
    b.addEventListener("click", () => confirmarEliminacionIntegrante(b.dataset.id))
  );
}

/* ========== Modal handlers ========== */
function abrirModalAgregarIntegrante() {
  integranteEditando = null;
  formIntegrante.reset();
  document.getElementById("tituloModalIntegrante").textContent = "Agregar Integrante Familiar";
  modalIntegrante.style.display = "flex";
}

function editarIntegrante(id) {
  const i = integrantesActuales.find(x => String(x.id) === String(id));
  if (!i) return;
  integranteEditando = i;

  document.getElementById("tituloModalIntegrante").textContent = "Editar Integrante Familiar";
  document.getElementById("nombreIntegrante").value = i.nombre ?? "";
  document.getElementById("apellidoIntegrante").value = i.apellido ?? "";
  document.getElementById("dniIntegrante").value = i.ci ?? "";
  document.getElementById("emailIntegrante").value = i.email ?? "";
  document.getElementById("fechaNacimientoIntegrante").value =
    (i.fechaNacimiento ?? i.fecha_nacimiento ?? "").slice(0, 10);
  document.getElementById("generoIntegrante").value = i.genero ?? "";
  modalIntegrante.style.display = "flex";
}

function confirmarEliminacionIntegrante(id) {
  const i = integrantesActuales.find(x => String(x.id) === String(id));
  if (!i) return;
  integranteEditando = i;
  document.getElementById("mensajeConfirmacion").textContent = `¿Eliminar a ${i.nombre} ${i.apellido}?`;
  modalConfirmacion.style.display = "flex";
}

/* ========== Guardar / Eliminar ========== */
async function guardarIntegrante(e) {
  e.preventDefault();

  const nombre = document.getElementById("nombreIntegrante").value.trim();
  const apellido = document.getElementById("apellidoIntegrante").value.trim();
  const ci = document.getElementById("dniIntegrante").value.trim();
  const email = document.getElementById("emailIntegrante").value.trim();
  const fechaNacimiento = document.getElementById("fechaNacimientoIntegrante").value;
  const genero = document.getElementById("generoIntegrante").value;

  if (!nombre || !apellido || !ci || !genero) {
    toast("Completá todos los campos obligatorios", "error");
    return;
  }

  try {
    let resp;
    if (integranteEditando) {
      // EDITAR
      resp = await editarIntegranteFamiliar({
        idIntegrante: integranteEditando.id,
        ci,
        nombre,
        apellido,
        email,
        fechaNacimiento,
        genero
      });
    } else {
      // AGREGAR
      resp = await ingresarIntegrantesFamiliares({
        cantidadIntegrantes: 1,
        integrantes: [{ nombre, apellido, ci, email, fechaNacimiento, genero }]
      });
    }

    if (resp?.status === "exito") {
      toast(integranteEditando ? "Integrante actualizado" : "Integrante agregado");
      await cargarIntegrantes(ID_PERSONA);
      modalIntegrante.style.display = "none";
    } else {
      toast(resp?.message || "Error al guardar", "error");
    }
  } catch (err) {
    console.error("guardarIntegrante error:", err);
    toast("Error al guardar el integrante", "error");
  }
}

async function eliminarIntegrante() {
  if (!integranteEditando) return;
  try {
    const resp = await eliminarIntegranteFamiliar(integranteEditando.id);
    if (resp?.status === "exito") {
      toast("Integrante eliminado");
      await cargarIntegrantes(ID_PERSONA);
      modalConfirmacion.style.display = "none";
    } else {
      toast(resp?.message || "Error al eliminar", "error");
    }
  } catch (err) {
    console.error("eliminarIntegrante error:", err);
    toast("Error al eliminar el integrante", "error");
  }
}

/* ========== Inicializar ========== */
function inicializarListeners() {
  if (btnAgregarIntegrante) btnAgregarIntegrante.addEventListener("click", abrirModalAgregarIntegrante);
  if (formIntegrante) formIntegrante.addEventListener("submit", guardarIntegrante);
  const btnEliminar = document.getElementById("confirmarEliminacion");
  if (btnEliminar) btnEliminar.addEventListener("click", eliminarIntegrante);
}
