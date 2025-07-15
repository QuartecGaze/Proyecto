    const formRegistro = document.getElementById("formRegistro");
    const inputEmail = document.getElementById("email")
    const inputCi = document.getElementById("cedula");
    const inputNombre = document.getElementById("nombre");
    const inputPassword = document.getElementById("password");
    const inputConfirmarPass = document.getElementById("confirmar-password");
    const inputTelefono = document.getElementById("telefono");
    const divError = document.getElementById("mensajeError");
    import { registrarUsuario } from '../../BackEnd/APIFetchs/APIUsuario.js';
    import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
    import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
    
    formRegistro.addEventListener("submit", async function (event) {
        const nombreCompleto = inputNombre.value.trim().split(/\s+/);
        event.preventDefault();
        const datos = {
            ci: inputCi.value,
                email: email.value,
                nombre: nombreCompleto[0],
                apellido: nombreCompleto[1],
                contraseña: inputPassword.value,
                confirmarContraseña: inputConfirmarPass.value,
                telefono: inputTelefono.value
        }
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

    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = passwordField.nextElementSibling;
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.textContent = "visibility_off";
        } else {
            passwordField.type = "password";
            toggleIcon.textContent = "visibility";
        }
    }
    //CONSEGUIR EL IDIOMA YA ASIGNADO
    const data = await getIdioma("registro");
    aplicarIdioma(data);

