import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { subirFoto } from '../../../BackEnd/APIFetchs/APIUsuario.js';
//import { actualizarUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js'; //Esto no esta hecho todavia por lo tanto no funciona

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const email = document.getElementById("emailUsuario");
const telefono = document.getElementById("telefonoUsuario");
const direccion = document.getElementById("direccionUsuario");
const direccionDisplay = document.getElementById("direccionUsuarioDisplay");
const cumple = document.getElementById("cumpleUsuario");
const fechaIngreso = document.getElementById("fechaIngreso");
const botonCambiarDatos = document.querySelector(".boton-cambiar-datos");
const inputFoto = document.getElementById("subir-foto");
const formularioEditar = document.getElementById("formulario-editar-datos");
const infoSoloLectura = document.getElementById("info-solo-lectura");
const fotoUsuario = 'usuario.webp';

const fotoruta = "../../Recursos/FotosPerfil/";
const idSesion = await getIdSesion();
localStorage.setItem("idSesion", idSesion.message);
const data = await getUsuario(idSesion.message);
setDatos(data.message);

function formatearFecha(fechaString) {
    if (!fechaString) return '';
    const fecha = new Date(fechaString);
    return fecha.toISOString().split('T')[0];
}

function formatearFechaParaMostrar(fechaString) {
    if (!fechaString) return '';
    const fecha = new Date(fechaString);
    const opciones = { day: 'numeric', month: 'long', year: 'numeric' };
    return fecha.toLocaleDateString('es-ES', opciones);
}

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

    email.textContent = data.email;
    telefono.textContent = data.telefono; 
    direccion.textContent = data.direccion;
    direccionDisplay.textContent = data.direccion;
    cumple.textContent = formatearFechaParaMostrar(data.fechaNacimiento);
    fechaIngreso.textContent = formatearFechaParaMostrar(data.fechaIngreso);

    // Llenar los campos del formulario de edición
    document.getElementById('nombreInput').value = data.nombre || '';
    document.getElementById('apellidoInput').value = data.apellido || '';
    document.getElementById('emailInput').value = data.email || '';
    document.getElementById('telefonoInput').value = data.telefono || '';
    document.getElementById('fechaNacimientoInput').value = formatearFecha(data.fechaNacimiento);
}

// Función para alternar entre modo visualización y edición
botonCambiarDatos.addEventListener('click', function() {
    infoSoloLectura.style.display = 'none';
    formularioEditar.style.display = 'block';
});

// Cancelar edición
document.querySelector('.boton-cancelar').addEventListener('click', function() {
    formularioEditar.style.display = 'none';
    infoSoloLectura.style.display = 'block';
});

// Guardar cambios
formularioEditar.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const datosActualizados = {
        nombre: document.getElementById('nombreInput').value,
        apellido: document.getElementById('apellidoInput').value,
        email: document.getElementById('emailInput').value,
        telefono: document.getElementById('telefonoInput').value,
        fechaNacimiento: document.getElementById('fechaNacimientoInput').value
        // NOTA: La dirección NO se incluye aquí para que no se pueda cambiar
    };

    try {
        const resultado = await actualizarUsuario(idSesion.message, datosActualizados);
        
        if (resultado.success) {
            // Actualizar la visualización con los nuevos datos
            const dataActualizada = await getUsuario(idSesion.message);
            setDatos(dataActualizada.message);
            
            // Volver al modo visualización
            formularioEditar.style.display = 'none';
            infoSoloLectura.style.display = 'block';
            
            alert('Datos actualizados correctamente');
        } else {
            alert('Error al actualizar los datos: ' + resultado.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al actualizar los datos');
    }
});

inputFoto.addEventListener('change', function (e) {
    if (this.files.length > 0) {
        const foto = inputFoto.files[0];
        const formData = new FormData();
        formData.append('foto', foto);
        const data = subirFoto(formData);
    }
});