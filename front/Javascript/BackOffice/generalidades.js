import { getAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
const rol = document.getElementById("rolAdmin");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = 'usuario.webp'; //asignamos una foto basica a los usuarios que aun no han registrado una propia
const idSesion = await getIdSesion();
const cambiarUsuario = document.getElementById("boton-cambiar-sesion");
const data = await getAdmin(idSesion.message);

setDatos(data.message);

cambiarUsuario.addEventListener('click', function () {
  window.location.href = "../Usuario/index.php";
});


function setDatos(data) {
  nombre.forEach(nombreDiv => {
    nombreDiv.textContent = data.nombre + " " + data.apellido;
  });

  foto.forEach(fotoDiv => {
    if (data.foto == null || data.foto === '') {
      fotoDiv.src = fotoruta + fotoUsuario;
    } else {
      fotoDiv.src = fotoruta + data.foto;
    }
  });
  rol.textContent = data.nivelPermisos;
}

document.querySelectorAll(".item-menu > a").forEach(boton => {
  boton.addEventListener("click", function (e) {
    // Evita que redireccione si tiene submenu
    if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
      e.preventDefault();
      this.parentElement.classList.toggle("open");
    }
  });
});

// Funcionalidad del menú hamburguesa para admin
const botonHamburguesa = document.getElementById('botonHamburguesa');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleMenu() {
  botonHamburguesa.classList.toggle('activo');
  sidebar.classList.toggle('activo');
  overlay.classList.toggle('activo');
  document.body.style.overflow = sidebar.classList.contains('activo') ? 'hidden' : 'auto';
}

botonHamburguesa.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu);

// Cerrar menú al hacer clic en un enlace (en móviles)
document.querySelectorAll('.item-menu a').forEach(enlace => {
  enlace.addEventListener('click', (e) => {
    // si tiene un submenú, no cierres el menú
    if (enlace.classList.contains('submenu-toggle')) {
      // Evita que se cierre el menú
      e.preventDefault(); // si querés evitar la navegación
      enlace.parentElement.classList.toggle('abierto'); // despliega el submenú
      return;
    }

    // Si es un enlace normal, cierra el menú en móviles
    if (window.innerWidth <= 768) {
      toggleMenu();
    }
  });
});

// Ajustar el menú al cambiar el tamaño de la ventana
window.addEventListener('resize', () => {
  if (window.innerWidth > 768) {
    botonHamburguesa.classList.remove('activo');
    sidebar.classList.remove('activo');
    overlay.classList.remove('activo');
    document.body.style.overflow = 'auto';
  }
});

// Funcionalidad del modal
const modal = document.getElementById('modalReunion');
const cerrarModal = document.querySelector('.cerrar-modal');
const botonCerrarModal = document.getElementById('botonCerrarModal');

if (cerrarModal) {
  cerrarModal.addEventListener('click', () => {
    modal.style.display = 'none';
  });
}

if (botonCerrarModal) {
  botonCerrarModal.addEventListener('click', () => {
    modal.style.display = 'none';
  });
}

// Cerrar modal al hacer clic fuera del contenido
window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});