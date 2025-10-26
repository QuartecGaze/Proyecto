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

cambiarUsuario.addEventListener('click', function(){
   window.location.href = "http://localhost/Proyecto/front/Usuario/index.php";
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