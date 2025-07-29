import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoUsuario = 'usuario.webp'; //asignamos una foto basica a los usuarios que aun no han registrado una propia

const fotoruta = "../../Recursos/FotosPerfil/";
const idSesion = await getIdSesion();
const data = await getUsuario(idSesion.message);
setDatos(data.message);


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