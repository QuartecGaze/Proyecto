import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */

export function getComprobantes(){
        const data = apiRequest('/APICooperativa/APICooperativa.php?accion=getComprobantes&id=' + id , 'POST');
        return data;
}

export function subirComprobante(comprobante, id) {
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=subirComprobante&id=` + id, 'POST', comprobante);
}

export function subirHoras(data){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=subirHoras`, "POST", data);
}
export function getCooperativa(id) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getCooperativa&id=' + id, 'GET');
}
export function getPagos(id) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getPagos&id=' + id, 'GET');
}
export function getHorasTrabajadas(id) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getHorasTrabajadas&id=' + id, 'GET');
}
export function editarHoras(data){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=editarHoras`, "POST", data);
}
export function borrarHoras(data){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=borrarHoras`, "POST", data);
}
export function ingresarIntegrantesFamiliares(data){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=ingresarIntegrantesFamiliares`, "POST", data);
}
export function subirFalta(data){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=subirFalta`, "POST", data);
}
export function getIntegrantesFamiliares(id) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getIntegrantesFamiliares&id=' + id, 'GET');
}
export function eliminarIntegranteFamiliar(idIntegrante) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=eliminarIntegranteFamiliar', 'POST', idIntegrante);
}
export function getReunionesTerminadas() {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getReunionesTerminadas', 'GET');
}
export function getReunionesPendientes() {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getReunionesPendientes', 'GET');
}
export function getHorasTrabajadasUsuarios(){
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=getHorasTrabajadasUsuarios`, "GET");
}
export function getUnidadHabitacional(id) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=getUnidadHabitacional&id=' + id, 'GET');
}
export function editarIntegranteFamiliar(data) {
        return apiRequest('/APICooperativa/APICooperativa.php?accion=editarIntegranteFamiliar', 'POST', data);
}
export function getEstadisticas(id) {
        return apiRequest(`/APICooperativa/APICooperativa.php?accion=getEstadisticas&id=` + id, 'GET');
}
