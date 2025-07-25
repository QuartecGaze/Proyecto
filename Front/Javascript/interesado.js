//Conseguir la id de sesion
const estadoEntrevista = document.getElementById("entrevista");
const estadoPago = document.getElementById("pago-inicial");
const estadoAntecedentes = document.getElementById("antecedentes");
const fechaEntrevista = document.getElementById("fecha-entrevista");
const nombrePersona = document.getElementById("nombre");
const comprobanteBtn = document.getElementById("comprobante");
const antecedentesBtn = document.getElementById("antecedentesBtn");
const inputComprobante = document.getElementById("file-pago");
const inputAntecedentes = document.getElementById("file-antecedentes");
const fileInfoPago = document.getElementById('file-info-pago');
const fileInfoAntecedentes = document.getElementById('file-info-antecedentes');
import { getIdSesion } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { getInteresado } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { subirAntecedentes } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { subirComprobante } from '../../BackEnd/APIFetchs/APIUsuario.js';




const idSesion = await getIdSesion();
const data = await getInteresado(idSesion.message);
setDatos(data);



function setDatos(data) {
    estadoEntrevista.classList.remove('aprobado', 'rechazado', 'pendiente');
    estadoPago.classList.remove('aprobado', 'rechazado', 'pendiente');
    estadoAntecedentes.classList.remove('aprobado', 'rechazado', 'pendiente');

    nombrePersona.textContent = "- " + data.message.nombre + " " + data.message.apellido;
    // Cambiar los estados de cada div según el estado correspondiente
    actualizarEstado(estadoEntrevista, data.message.estadoEntrevista);
    actualizarEstado(estadoPago, data.message.estadoPagoInicial);
    actualizarEstado(estadoAntecedentes, data.message.estadoAntecedentes);
    
    //usando el operador logico OR, al ser null logramos que se muestre como (vacio)
    const fecha = data.message.fechaEntrevista || "";
    const hora = data.message.horaEntrevista || "";
    const monto = data.message.montoPagoInicial || "";

    fileInfoPago.classList.remove('tiene-archivo');
    fileInfoAntecedentes.classList.remove('tiene-archivo');

    fileInfoPago.parentElement.classList.add("input" + data.message.estadoPagoInicial);
    fileInfoAntecedentes.parentElement.classList.add("input" + data.message.estadoAntecedentes);

    if(estado.message.pagoInicial == "Aprobado" || estado.message.pagoInicial == "Pendiente"){
        fileInfoPago.textContent = "Comprobante subido";
    }

    document.getElementById("fechaEntrevista").textContent = `Fecha: ${fecha} a las ${hora}`;
    document.getElementById("montoPago").textContent = `Monto a abonar: $${monto}`;
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

//Event lisener pasa subir los archivos

antecedentesBtn.addEventListener("click", async function (){
        const antecedentes = inputAntecedentes.files[0];
        const formData = new FormData();
        formData.append('antecedentes', antecedentes);
        subirAntecedentes(formData)
        if(respuesta.status = "exito"){
            
        }
    } );

  comprobanteBtn.addEventListener("click", async function (){
        const comprobante = inputComprobante.files[0];
        const formData = new FormData();
        formData.append('comprobante', comprobante);

        subirComprobante(formData)

        if(respuesta.status = "exito"){
            
        }
    } );





     inputAntecedentes.addEventListener('change', function (e) {

        if (this.files.length > 0) {
            fileInfoAntecedentes.textContent = this.files[0].name;
            fileInfoAntecedentes.parentElement.classList.add('tiene-archivo');
        }
    });


    inputComprobante.addEventListener('change', function (e) {
        if (this.files.length > 0) {
            fileInfoPago.textContent = this.files[0].name;
            fileInfoPago.parentElement.classList.add('tiene-archivo');
        }
    });
