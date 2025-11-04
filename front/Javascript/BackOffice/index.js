import { cantidadPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

    const respuesta = await cantidadPendientes();
    const datos = respuesta.message;
    document.getElementById('solicitudesPendientes').innerHTML = `${datos.interesados} <span>Solicitudes</span>`;
    document.getElementById('faltasEnEspera').innerHTML = datos.faltas;
    document.getElementById('comprobantesPendientes').innerHTML = datos.comprobantes;

