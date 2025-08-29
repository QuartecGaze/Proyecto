import { getCooperativa } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const pagosAtrasados = document.querySelectorAll(".pagosAtrasadosCantidad");
const pagosAtrasadosMonto = document.querySelectorAll(".pagosAtrasadosTotal");

const id = 3;
const data = await getCooperativa(id);

setDatos(data.message);


function setDatos(data) {
    pagosAtrasados.textContent = data.pagosAtrasados;
    pagosAtrasadosMonto.textContent = data.pagosAtrasadosDinero; 
    /*
    direccion.textContent = data.direccion; //todavia no se trae hay que traerlo de unidad habitacional
    cumple.textContent = data.fechaNacimiento;
    fechaIngreso.textContent = data.fechaIngreso;

*/

}

