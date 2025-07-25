import { contarInteresados } from '../../../BackEnd/APIFetchs/APIBackOffice.js';


    const respuesta = await contarInteresados();
    const total = respuesta.message;
    //ponemos el innerHTML para poder mostrar Solicitudes, sino se rompe todo el texto
    document.getElementById('solicitudesPendientes').innerHTML =`${total} <span>Solicitudes</span>`;
