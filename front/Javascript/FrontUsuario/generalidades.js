import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoUsuario = 'usuario.webp'; //asignamos una foto basica a los usuarios que aun no han registrado una propia
const cambiarSesion = document.getElementById("boton-cambiar-sesion");
const fotoruta = "../../Recursos/FotosPerfil/";
const idSesion = await getIdSesion();
const data = await getUsuario(idSesion.message);
setDatos(data.message);

if(data.message.rol != "Admin"){
  cambiarSesion.style.display = "none";
}
cambiarSesion.addEventListener('click', function(){
   window.location.href = "http://localhost:8888/Proyecto/front/Admin/index.php";
});


function setDatos(data) {
    nombre.forEach(nombreDiv => {
        nombreDiv.textContent=data.nombre+" "+data.apellido;
    });
    
    foto.forEach(fotoDiv => {
        if (data.foto == null || data.foto === '') {
          fotoDiv.src = fotoruta + fotoUsuario;
        } else {
          fotoDiv.src = fotoruta + data.foto;
        }
    });
}