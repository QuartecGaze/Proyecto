import { getAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { subirFoto } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const email = document.getElementById("emailAdmin");
const telefono = document.getElementById("telefonoAdmin");
const creacion = document.getElementById("creacionAdmin");
const nivelPermisos = document.getElementById("nivelPermisosAdmin");
const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
const editarDatos = document.querySelectorAll("boton-cambiar-datos");
const inputFoto = document.getElementById("subir-foto");

const fotoruta = "../../Recursos/FotosPerfil/";
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

inputFoto.addEventListener('change', function (e) {
        if (this.files.length > 0) {
            const foto = inputFoto.files[0];
            const formData = new FormData();
            formData.append('foto', foto);
            const data = subirFoto(formData);
        }
    });
