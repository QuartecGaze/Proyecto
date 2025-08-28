import { subirComprobante } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const inputComprobante = document.getElementById("file-pago");
const comprobanteBtn = document.getElementById("comprobante");


//TENGO QUE MANDAR EL idComprobante del boton junto al comprobante
comprobanteBtn.addEventListener("click", async function (){
    const comprobante = inputComprobante.files[0];
    const formData = new FormData();
    formData.append('comprobante', comprobante);
    const data = subirComprobante(formData)
    if(data.status = "exito"){
        fileInfoPago.parentElement.classList.add("cargado");
    }
} );

inputComprobante.addEventListener('change', function (e) {
    if (this.files.length > 0) {
        fileInfoPago.textContent = this.files[0].name;
        fileInfoPago.parentElement.classList.add('tiene-archivo');
    }
});
