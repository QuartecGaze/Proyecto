    const formRegistro = document.getElementById("formRegistro");
    const inputEmail = document.getElementById("email")
    const inputCi = document.getElementById("cedula");
    const inputNombre = document.getElementById("nombre");
    const inputPassword = document.getElementById("password");
    const inputConfirmarPass = document.getElementById("confirmar-password");
    const inputTelefono = document.getElementById("telefono");
    const divError = document.getElementById("mensajeError");
    import { registrarUsuario } from '../../BackEnd/APIFetchs/APIUsuario';
    formRegistro.addEventListener("submit", async function (event) {
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
        } catch(error){
            divError.style.display = "block";
            divError.textContent = data.message;
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
    fetch('http://localhost/Proyecto/BackEnd/APITraduccion/ApiTraduccion.php?accion=getIdioma&pagina=registro', {
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
  const labels = el.querySelectorAll('label');
  const items = valor.split(';');

  labels.forEach((label, index) => {
    label.innerHTML = items[index] ?? '';
  });
}

          else {
            el.innerHTML = valor;
          }
        });
      }
    })
    .catch(error => {
      console.error('Error en la solicitud POST:', error.message);
    });


