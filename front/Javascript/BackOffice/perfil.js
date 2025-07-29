import { getAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { subirFoto } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const email = document.getElementById("emailAdmin");
const telefono = document.getElementById("telefonoAdmin");
const creacion = document.getElementById("creacionAdmin");
const nivelPermisos = document.getElementById("nivelPermisosAdmin");
const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
const rol = document.getElementById("rolAdmin");
const editarDatos = document.querySelectorAll("boton-cambiar-datos");
const inputFoto = document.getElementById("subir-foto");

const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = 'usuario.webp'; //asignamos una foto basica a los usuarios que aun no han registrado una propia
const idSesion = await getIdSesion();
const data = await getAdmin(idSesion.message);
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
    rol.textContent = data.nivelPermisos; 
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
