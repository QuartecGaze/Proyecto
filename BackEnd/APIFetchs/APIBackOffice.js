import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function Interesados() {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=Interesados', 'GET');
}

export function aprobarEstado(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=aprobarEstado', 'POST', data);
}

export function rechazarEstado(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=rechazarEstado', 'POST', data);
}

export function getInteresados() {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=getInteresados', 'GET');
}

export function rechazarInteresado(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=rechazarInteresado', 'POST', data);
}

export function aprobarInteresado(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=aprobarInteresado', 'POST', data);
}

export function asignarEntrevista(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=asignarEntrevista', 'POST', data);
}

export function asignarPagoInicial(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=asignarPagoInicial', 'POST', data);
}

export function getAdmin(id){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getAdmin&id=` + id, 'GET');
}

export function getIdSesion(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getIdSesion`, "GET");
}

export function contarInteresados(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=contarInteresados`, "GET");
}

export function subirFoto(foto){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=subirFoto', 'POST', foto);
}