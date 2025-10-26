import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */

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

export function asignarPagoMensual(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=asignarPagoMensual', 'POST', data);
}

export function asignarPagoPersonalizado(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=asignarPagoPersonalizado', 'POST', data);
}
export function getPagosPendientes(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getPagosPendientes`, "GET");
}
export function aprobarPago(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=aprobarPago', 'POST', data);
}
export function rechazarPago(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=rechazarPago', 'POST', data);
}

export function crearUnidadHabitacional(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=crearUnidad', 'POST', data);
}
export function crearUnidadHabitacionalPersonalizada(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=crearUnidadConCI', 'POST', data);
}
export function cargarAdmin(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=cargarAdmin', 'POST', data);
}
export function crearReunion(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=crearReunion', 'POST', data);
}
export function getReunionesPendientes(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getReunionesPendientes`, "GET");
}
export function getReunionesCompletadas(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getReunionesCompletadas`, "GET");
}
export function completarReunion(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=completarReunion', 'POST', data);
}
export function eliminarReunion(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=eliminarReunion', 'POST', data);
}
export function editarReunion(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=editarReunion', 'POST', data);
}
export function pasarAsistencia(data){
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=pasarAsistencia', 'POST', data);
}
export function getUsuariosAsistencias(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getUsuariosAsistencias`, "GET");
}
export function getFaltasPendientes(){
    return apiRequest(`/APIBackOffice/ApiBackOffice.php?accion=getFaltasPendientes`, "GET");
}
export function getUsuarios(){
    return apiRequest(`/APIBackoffice/ApiBackoffice.php?accion=getUsuarios`, 'GET');
}

export function aprobarFalta(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=aprobarFalta', 'POST', data);
}

export function rechazarFalta(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=rechazarFalta', 'POST', data);
}

export function asignarMontoFalta(data) {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=asignarMontoFalta', 'POST', data);
}
