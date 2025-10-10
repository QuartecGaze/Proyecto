import { cargarAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const formAdmin = document.getElementById("cargarAdmin");


formAdmin.addEventListener("submit", async function (event) {
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const ci = document.getElementById("cedula").value.trim();
    const contraseña = document.getElementById("password").value.trim();
    var nivelPermisos = document.querySelector('input[name="nivel_permisos"]:checked')?.value;
        event.preventDefault();
        if(nivelPermisos == "Admin"){
            nivelPermisos = 2;
        } else {
            nivelPermisos = 1;
        }
        const datos = {
                ci: ci,
                email: email,
                telefono: telefono,
                nombre: nombre,
                apellido: apellido,
                contraseña: contraseña,
                nivelPermisos: nivelPermisos,
            };
            console.log(datos);
        try {
            const data = await cargarAdmin(datos);
            if (data.status == "exito") {
                document.getElementById("mensajeExito").innerText = "Admin creado con éxito";
                document.getElementById("mensajeExito").style.display = "block";
                document.getElementById("mensajeError").style.display = "none";
            } else {
                document.getElementById("mensajeError").innerText = data.message || "Error al crear el admin";
                document.getElementById("mensajeError").style.display = "block";
                document.getElementById("mensajeExito").style.display = "none";
            }
        } catch (error){
            throw new Error("error en la api: " + error.message);
        }
    })