import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { subirFoto } from '../../../BackEnd/APIFetchs/APIUsuario.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const email = document.getElementById("emailUsuario");
const telefono = document.getElementById("telefonoUsuario");
const direccion = document.getElementById("direccionUsuario");
const cumple = document.getElementById("cumpleUsuario");
const fechaIngreso = document.getElementById("fechaIngreso");
const editarDatos = document.querySelectorAll("boton-cambiar-datos");
const inputFoto = document.getElementById("subir-foto");
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

    email.textContent = data.email;
    telefono.textContent = data.telefono; 
    direccion.textContent = data.direccion; //todavia no se trae hay que traerlo de unidad habitacional
    cumple.textContent = data.fechaNacimiento;
    fechaIngreso.textContent = data.fechaIngreso;



}

inputFoto.addEventListener('change', function (e) {
        if (this.files.length > 0) {
            const foto = inputFoto.files[0];
            const formData = new FormData();
            formData.append('foto', foto);
            const data = subirFoto(formData);
        }
    });
