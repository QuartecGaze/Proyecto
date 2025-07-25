import { getUsuario } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';
const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const email = document.getElementById("emailUsuario");
const telefono = document.getElementById("telefonoUsuario");
const direccion = document.getElementById("direccionUsuario");
const cumple = document.getElementById("cumpleUsuario");

const fotoruta = "../../Recursos/FotosDePerfil/";
const idSesion = await getIdSesion();
const data = await getUsuario(idSesion.message);
setDatos(data.message);


function setDatos(data) {
    nombre.forEach(nombreDiv => {
        nombreDiv.textContent=data.nombre+" "+data.apellido;
    });
    
    foto.forEach(fotoDiv => {
        fotoDiv.src = fotoruta+data.foto;
    });

    email.textContent = data.email;
    telefono.textContent = data.telefono;
    direccion.textContent = data.direccion; //todavia no se trae hay que traerlo de unidad habitacional
    cumple.textContent = data.fechaNacimiento;



}