    const formLogin = document.getElementById("form-login");
    const inputCi = document.getElementById("cedula");
    const inputContraseña = document.getElementById("password");
    const divError = document.getElementById("mensajeError");
    const togglePassword = document.querySelectorAll(".toggle-password");
    import { iniciarSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';
    import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
    import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';

    formLogin.addEventListener("submit", async function (event) {
        event.preventDefault();
        const datos = {
                ci: inputCi.value,
                contraseña: inputContraseña.value,
            };
        try {
            const data = await iniciarSesion(datos);

            if (data.status === "exito") {
                    if (data.rol === "Admin") {
                        window.location.href = "../Admin/index.php";
                    } else if (data.rol === "Usuario") {
                        window.location.href = "../Usuario/index.php";
                    } else if (data.rol === "Interesado") {
                        window.location.href = "../Interesado/index.php";
                    } else {
                        alert("Rol no reconocido: " + data.rol);
                    }
                } else {
                    divError.style.display = "block";
                    divError.textContent = data.message;
                }
        } catch (error){
            throw new Error("error en la api: " + error.message);
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
    const data = await getIdioma("login");
    aplicarIdioma(data);