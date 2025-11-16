const formLogin = document.getElementById("form-login");
const inputCi = document.getElementById("cedula");
const inputContraseña = document.getElementById("password");
const divError = document.getElementById("mensajeError");
const togglePassword = document.querySelectorAll(".toggle-password");

import { iniciarSesion, getIdSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { getAdmin } from '../../BackEnd/APIFetchs/APIBackOffice.js';
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
        console.log("RESP LOGIN:", data);

        if (data.status === "exito") {
            localStorage.setItem("token", data.message);

            if (data.rol === "Admin") {
                try {
                    const sesion = await getIdSesion();
                    console.log("RESP ID SESION:", sesion);

                    if (sesion.status === "exito") {
                        const idPersona = sesion.message;

                        const adminResp = await getAdmin(idPersona);
                        console.log("RESP GETADMIN:", adminResp);

                        if (adminResp.status === "exito") {
                            const nivel = (adminResp.message.nivelPermisos || "").trim();

                            if (nivel === "Operador") {
                                window.location.href = "../Operador/index.php";
                                return;
                            } else if (nivel === "Admin") {
                                window.location.href = "../Admin/index.php";
                                return;
                            } else {
                                window.location.href = "../Admin/index.php";
                                return;
                            }
                        }
                    }

                    window.location.href = "../Admin/index.php";
                    return;
                } catch (e) {
                    console.error("Error obteniendo nivelPermisos:", e);
                    window.location.href = "../Admin/index.php";
                    return;
                }
            }

            if (data.rol === "Usuario") {
                window.location.href = "../Usuario/index.php";
            } else if (data.rol === "Interesado") {
                window.location.href = "../Interesado/index.php";
            } else if (data.rol === "Operador") {
                window.location.href = "../Operador/index.php";
            } else {
                alert("Rol no reconocido: " + data.rol);
            }

        } else {
            divError.style.display = "block";
            divError.textContent = data.message;
        }
    } catch (error) {
        console.error("error en la api: ", error);
        throw new Error("error en la api: " + error.message);
    }
});

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

const dataIdioma = await getIdioma("login");
aplicarIdioma(dataIdioma);
