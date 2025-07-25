    const formRegistro = document.getElementById("formRegistro");
    const inputEmail = document.getElementById("email")
    const inputCi = document.getElementById("cedula");
    const inputNombre = document.getElementById("nombre");
    const inputApellido = document.getElementById("apellido");
    const inputPassword = document.getElementById("password");
    const inputConfirmarPass = document.getElementById("confirmar-password");
    const inputTelefono = document.getElementById("telefono");
    const togglePassword = document.querySelectorAll(".toggle-password");
    const divError = document.getElementById("mensajeError");
    import { registrarUsuario } from '../../BackEnd/APIFetchs/APIUsuario.js';
    import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
    import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
    
    formRegistro.addEventListener("submit", async function (event) {
        event.preventDefault();
        const datos = {
            ci: inputCi.value,
                email: inputEmail.value,
                nombre: inputNombre.value,
                apellido: inputApellido.value,
                contraseña: inputPassword.value,
                confirmarContraseña: inputConfirmarPass.value,
                telefono: inputTelefono.value
        };
        
        try{
        const data = await registrarUsuario(datos);

        if(data.status === "exito"){
            window.location.href = "../Landing Page/login.html"
        } else {
          divError.style.display = "block";
          divError.textContent = data.message;
        }
        } catch(error){
            
        }

    })

    togglePassword.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const input = toggle.previousElementSibling;
            
            if (input.type === "password") {
                input.type = "text";
                toggle.textContent = "visibility_off";
            } else {
                input.type = "password";
                toggle.textContent = "visibility";
            }
        });
    });
    

    //CONSEGUIR EL IDIOMA YA ASIGNADO
    const data = await getIdioma("registro");
    aplicarIdioma(data);

