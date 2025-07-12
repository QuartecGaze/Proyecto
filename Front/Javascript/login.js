    const formLogin = document.getElementById("form-login");
    const inputCi = document.getElementById("cedula");
    const inputContraseña = document.getElementById("password");
    const divError = document.getElementById("mensajeError");
    import { iniciarSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';

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
                        window.location.href = "../Admin/index.html";
                    } else if (data.rol === "Usuario") {
                        window.location.href = "../Usuario/index.html";
                    } else if (data.rol === "Interesado") {
                        window.location.href = "../Interesado/index.html";
                    } else {
                        alert("Rol no reconocido: " + data.rol);
                    }
                } else {
                    alert("Error al iniciar sesión");
                }
        } catch (error){
            divError.style.display = "block";
            divError.textContent = data.message;
            throw new Error(data?.message || `Error HTTP ${response.status}`);
        }
    })


    //CONSEGUIR EL IDIOMA YA ASIGNADO
    fetch('http://localhost/Proyecto/BackEnd/APITraduccion/ApiTraduccion.php?accion=getIdioma&pagina=login', {
    method: 'GET',
  })
  .then(async response => {
    const data = await response.json().catch(() => null);
    if (!response.ok) {
      throw new Error(data?.message || `Error HTTP ${response.status}`);
    }
    return data;
  })
  .then(data => {
      console.log('Respuesta del servidor:', data);

      for (const [clave, valor] of Object.entries(data.message)) {
        const elementos = document.querySelectorAll(`.${clave}`);

        elementos.forEach(el => {
          if (el.tagName.toLowerCase() === 'nav') {
            const ul = el.querySelector('ul');
            if (ul) {
              const items = valor.split(';');
              const lis = ul.querySelectorAll('li');

              lis.forEach((li, index) => {
                const a = li.querySelector('a');
                if (a) {
                  a.innerHTML = items[index] ?? '';
                }
              });
            }
          }

          else if (el.tagName.toLowerCase() === 'form') {
            const divs = el.querySelectorAll('div');
            const items = valor.split(';');

            divs.forEach((div, index) => {
              const label = div.querySelector('label');
              if (label) {
                label.innerHTML = items[index] ?? '';
              }
            });
          }

          else {
            el.innerHTML = valor;
          }
        });
      }
    })
    .catch(error => {
      console.error('Error en la solicitud GET:', error.message);
    });

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
