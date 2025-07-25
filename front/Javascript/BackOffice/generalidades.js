import { getAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
const rol = document.getElementById("rolAdmin");
const fotoruta = "../../Recursos/FotosDePerfil/";
const idSesion = await getIdSesion();
const data = await getAdmin(idSesion.message);
setDatos(data.message);


function setDatos(data) {
    nombre.forEach(nombreDiv => {
        nombreDiv.textContent=data.nombre+" "+data.apellido;
    });
    
    foto.forEach(fotoDiv => {
        fotoDiv.src = fotoruta+data.foto;
    });
    rol.textContent = data.nivelPermisos; 
}