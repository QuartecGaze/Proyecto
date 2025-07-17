<!DOCTYPE html>
<html lang="es">

<head>
    <?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Interesado', 'Admin']);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Registro</title>
    <link rel="stylesheet" href="../Css/estilosInteresado.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <header class="header-registro">
        <div class="contenedor-header">
            <div class="logo">
                <a href="../Landing Page/index.html">
                    <img src="../../Fotos/logo.webp" alt="Logo Cooperativa">
                </a>
                <span>Cooperativa Nombre</span>
            </div>
        </div>
    </header>

    <main>
        <h1>Formulario de ingreso a la cooperativa <br> <p id="nombre">- Nombre</p></h1>

        <h2>1. Entrevista Agendada</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="entrevista">Estado-Entrevista</span>
            </div>
            <div id="fechaEntrevista">Fecha y hora de la entrevista</div>
            <div id="Observaciones">Observaciones: Entrevista presencial en sede cooperativa. Dirección Av.Gral Rivera 3729 bis, 11600 Montevideo</div>
        </div>

        <div class="divider"></div>

        <h2>2. Subir Antecedentes</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="antecedentes">Estado-Antecedentes</span>
            </div>
            <div class="upload-section">
                <div>Haz clic para subir PDF de antecedentes</div>
                <div class="file-info">Ningún archivo seleccionado</div>
            </div>
        </div>

        <div class="divider"></div>

        <h2>3. Comprobante de Pago Inicial</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="pago-inicial">Estado-PagoInicial</span>
            </div>
            <div class="cantidad" id="montoPago">Monto-PagoInicial</div>
            <div class="upload-section">
                <div>Haz clic para subir tu comprobante</div>
                <div class="file-info">Ningún archivo seleccionado</div>
            </div>
        </div>
    </main>

    <footer class="footer-registro">
        <p>&copy; 2025 Cooperativa Nombre. Todos los derechos reservados.</p>
    </footer>
</body>
<script>
    //Conseguir la id de sesion
    const estadoEntrevista = document.getElementById("entrevista");
    const estadoPago= document.getElementById("pago-inicial");
    const estadoAntecedentes = document.getElementById("antecedentes");
    const fechaEntrevista = document.getElementById("fecha-entrevista");
    const nombrePersona = document.getElementById("nombre");
    let idSesion = null;

    fetch(`http://localhost/Proyecto/BackEnd/APIUsuarios/ApiUsuarios.php?accion=getIdSesion`, {
        method: 'GET',
    })
    .then(async response => {
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.message || `Error HTTP ${response.status}`);
    }
    console.log('Respuesta del servidor (interesado):', data);
    getInteresado(data.message);
})
.catch(error => {
    console.error('Error en la solicitud:', error.message);
});

    //hacer el fetch con la id
    function getInteresado(id){
        fetch(`http://localhost/Proyecto/BackEnd/APIUsuarios/ApiUsuarios.php?accion=getInteresado&id=` + id, {
        method: 'GET',
    })
    .then(async response => {
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.message || `Error HTTP ${response.status}`);
    }
    console.log('Respuesta del servidor (interesado):', data);
    setDatos(data);
})
.catch(error => {
    console.error('Error en la solicitud:', error.message);
});
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
    }
    //Funcion para asignar los valores correspondientes
    function setDatos(data) {
        estadoEntrevista.classList.remove('aprobado', 'rechazado', 'pendiente');
        estadoPago.classList.remove('aprobado', 'rechazado', 'pendiente');
        estadoAntecedentes.classList.remove('aprobado', 'rechazado', 'pendiente');

        nombrePersona.textContent = "- " + data.message.nombre + " " + data.message.apellido;
        // Cambiar los estados de cada div según el estado correspondiente
        actualizarEstado(estadoEntrevista, data.message.estadoEntrevista);
        actualizarEstado(estadoPago, data.message.estadoPagoInicial);
        actualizarEstado(estadoAntecedentes, data.message.estadoAntecedentes);
        
        document.getElementById("fechaEntrevista").textContent = `Fecha: ${data.message.fechaEntrevista} a las ${data.message.horaEntrevista}`;
        document.getElementById("montoPago").textContent = `Monto a abonar: $${data.message.montoPagoInicial}`;
    }

// Función para actualizar el estado de cada div
function actualizarEstado(element, estado) {
    switch (estado.toLowerCase()) {
        case 'pendiente':
            element.textContent = 'Pendiente';
            element.classList.add('pendiente');
            break;
        case 'rechazado':
            element.textContent = 'Rechazado';
            element.classList.add('rechazado');
            break;
        case 'aprobado':
            element.textContent = 'Aprobado';
            element.classList.add('aprobado');
            break;
        case 'en espera':
            element.textContent = 'En espera';
            element.classList.add('espera');
            break;
        default:
            element.textContent = 'Desconocido';
            element.style.backgroundColor = 'gray';  // Color predeterminado
            break;
    }
}

</script>
</html>