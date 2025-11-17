import { getUsuario, getIdSesion, subirFoto, actualizarUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getEstadisticas } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const email = document.getElementById("emailUsuario");
const telefono = document.getElementById("telefonoUsuario");
const direccion = document.getElementById("direccionUsuario");
const direccionDisplay = document.getElementById("direccionUsuarioDisplay");
const cumple = document.getElementById("cumpleUsuario");
const fechaIngreso = document.getElementById("fechaIngresoUsuario");
const botonCambiarDatos = document.querySelector(".boton-cambiar-datos");
const inputFoto = document.getElementById("subir-foto");
const botonCambiarFoto = document.getElementById("btnCambiarFoto");
const formularioEditar = document.getElementById("formulario-editar-datos");
const infoSoloLectura = document.getElementById("info-solo-lectura");
const fotoUsuario = 'usuario.webp';

// NUEVO: referencias a estadísticas
const horasTotalesEl = document.getElementById('horasTotales');
const pagosTotalesEl = document.getElementById('pagosTotales');
const antiguedadEl   = document.getElementById('antiguedadUsuario');

// Utilidades
const fotoruta = "../../Recursos/FotosPerfil/";

function formatearFecha(fechaString) {
  if (!fechaString) return '';
  const fecha = new Date(fechaString);
  if (isNaN(fecha)) return '';
  return fecha.toISOString().split('T')[0];
}

function formatearFechaParaMostrar(fechaString) {
  if (!fechaString) return '';
  const fecha = new Date(fechaString);
  if (isNaN(fecha)) return '';
  const opciones = { day: 'numeric', month: 'long', year: 'numeric' };
  return fecha.toLocaleDateString('es-ES', opciones);
}

function n(v) { return Number(v ?? 0); }
function fmtNum(v) { return n(v).toLocaleString('es-UY'); }
function fmtMonUYU(v) { return `$ ${n(v).toLocaleString('es-UY')}`; } // por si luego querés montos

function setDatos(data) {
  nombre.forEach(el => { el.textContent = `${data.nombre ?? ''} ${data.apellido ?? ''}`.trim(); });
  foto.forEach(img => {
    img.src = (data.foto == null || data.foto === '') ? (fotoruta + fotoUsuario) : (fotoruta + data.foto);
    img.alt = 'Foto de perfil';
  });

  email.textContent = data.email ?? '';
  telefono.textContent = data.telefono ?? '';
  direccion.textContent = data.direccion ?? '';
  direccionDisplay.textContent = data.direccion ?? '';
  cumple.textContent = formatearFechaParaMostrar(data.fechaNacimiento);
  fechaIngreso.textContent = data.fechaIngreso ?? '';

  // Campos del formulario
  document.getElementById('nombreInput').value = data.nombre ?? '';
  document.getElementById('apellidoInput').value = data.apellido ?? '';
  document.getElementById('emailInput').value = data.email ?? '';
  document.getElementById('telefonoInput').value = data.telefono ?? '';
  document.getElementById('fechaNacimientoInput').value = formatearFecha(data.fechaNacimiento);
}

async function cargarPerfilYEstadisticas() {
  const idSesion = await getIdSesion();
  localStorage.setItem("idSesion", idSesion.message);

  const usuario = await getUsuario(idSesion.message);
  setDatos(usuario.message);

  try {
    const r = await getEstadisticas(idSesion.message);
    const est = r?.message ?? r;
    horasTotalesEl.textContent = fmtNum(est.horasTotal ?? 0) + ' Horas';
    pagosTotalesEl.textContent = fmtNum(est.pagosTotal ?? 0) + ' $';
    antiguedadEl.textContent   = est.antiguedad ?? '—';
  } catch (e) {
    horasTotalesEl.textContent = '—';
    pagosTotalesEl.textContent = '—';
    antiguedadEl.textContent   = '—';
    console.error('Error cargando estadísticas', e);
  }
}

botonCambiarDatos.addEventListener('click', function () {
  infoSoloLectura.style.display = 'none';
  formularioEditar.style.display = 'block';
});

document.querySelector('.boton-cancelar').addEventListener('click', function () {
  formularioEditar.style.display = 'none';
  infoSoloLectura.style.display = 'block';
});

// Guardar cambios
formularioEditar.addEventListener('submit', async function (e) {
  e.preventDefault();
  const datos = {
    nombre: document.getElementById('nombreInput').value,
    apellido: document.getElementById('apellidoInput').value,
    email: document.getElementById('emailInput').value,
    telefono: document.getElementById('telefonoInput').value,
    fechaNacimiento: document.getElementById('fechaNacimientoInput').value
    // Dirección no editable
  };
  try {
    const resultado = await actualizarUsuario(datos);
    if (resultado.status === 'exito') {
      const idSesion = localStorage.getItem("idSesion");
      const dataActualizada = await getUsuario(idSesion);
      setDatos(dataActualizada.message ?? dataActualizada);
      formularioEditar.style.display = 'none';
      infoSoloLectura.style.display = 'block';
    } else {
      alert('Error al actualizar los datos: ' + (resultado.message ?? 'Desconocido'));
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Error al actualizar los datos');
  }
});

// Subir foto
botonCambiarFoto.addEventListener('click', function () {
  if (inputFoto) {
    inputFoto.click();
  }
});

inputFoto.addEventListener('change', async function () {
  if (this.files.length > 0) {
    const archivoFoto = this.files[0];
    const formData = new FormData();
    formData.append('foto', archivoFoto);

    try {
      await subirFoto(formData);
      // Refrescamos la foto del usuario
      const idSesion = localStorage.getItem("idSesion");
      const usuario = await getUsuario(idSesion);
      setDatos(usuario.message ?? usuario);
    } catch (e) {
      console.error('Error al subir foto', e);
      alert('No se pudo subir la foto');
    }
  }
});


// Carga inicial
await cargarPerfilYEstadisticas();
