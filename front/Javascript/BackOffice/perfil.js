import { getAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const email = document.getElementById("emailAdmin");
const telefono = document.getElementById("telefonoAdmin");
const creacion = document.getElementById("creacionAdmin");
const nivelPermisos = document.getElementById("nivelPermisosAdmin");
const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
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
    nivelPermisos.textContent = data.nivelPermisos;
    creacion.textContent = data.fechaIngreso; 
    telefono.textContent = data.telefono;
    email.textContent = data.email;
}