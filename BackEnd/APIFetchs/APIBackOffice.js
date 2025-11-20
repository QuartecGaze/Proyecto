import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */

export function aprobarEstado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=aprobarEstado', 'POST', data);
}

export function rechazarEstado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=rechazarEstado', 'POST', data);
}

export function getInteresados() {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=getInteresados', 'GET');
}

export function rechazarInteresado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=rechazarInteresado', 'POST', data);
}

export function aprobarInteresado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=aprobarInteresado', 'POST', data);
}

export function asignarEntrevista(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarEntrevista', 'POST', data);
}

export function asignarPagoInicial(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarPagoInicial', 'POST', data);
}

export function getAdmin(id){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getAdmin&id=` + id, 'GET');
}

export function getIdSesion(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getIdSesion`, "GET");
}

export function cantidadPendientes(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=cantidadPendientes`, "GET");
}

export function subirFoto(foto){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=subirFoto', 'POST', foto);
}

export function asignarPagoMensual(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarPagoMensual', 'POST', data);
}

export function asignarPagoPersonalizado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarPagoPersonalizado', 'POST', data);
}
export function getPagosPendientes(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getPagosPendientes`, "GET");
}
export function aprobarPago(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=aprobarPago', 'POST', data);
}
export function rechazarPago(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=rechazarPago', 'POST', data);
}

export function crearUnidadHabitacional(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=crearUnidad', 'POST', data);
}
export function crearUnidadHabitacionalPersonalizada(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=crearUnidadConCI', 'POST', data);
}
export function cargarAdmin(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=cargarAdmin', 'POST', data);
}
export function crearReunion(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=crearReunion', 'POST', data);
}
export function getReunionesPendientes(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getReunionesPendientes`, "GET");
}
export function getReunionesCompletadas(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getReunionesCompletadas`, "GET");
}
export function completarReunion(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=completarReunion', 'POST', data);
}
export function eliminarReunion(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=eliminarReunion', 'POST', data);
}
export function editarReunion(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=editarReunion', 'POST', data);
}
export function pasarAsistencia(data){
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=pasarAsistencia', 'POST', data);
}
export function getUsuariosAsistencias(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getUsuariosAsistencias`, "GET");
}
export function getFaltasPendientes(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getFaltasPendientes`, "GET");
}
export function getUsuarios(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getUsuarios`, 'GET');
}

export function aprobarFalta(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=aprobarFalta', 'POST', data);
}

export function rechazarFalta(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=rechazarFalta', 'POST', data);
}

export function asignarMontoFalta(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarMontoFalta', 'POST', data);
}

export function getUnidadesLibres() {
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getUnidadesLibres`, 'GET');
}

export function getUnidades()  {
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getUnidades`, 'GET');
}
export function asignarUnidad(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarUnidad', 'POST', data);
}

export function modificarUnidadHabitacional(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=modificarUnidadHabitacional', 'POST', data);
}

export function cambiarEstado(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=cambiarEstado', 'POST', data);
}

export function asignarUnidadHabitacional(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarUnidadHabitacional', 'POST', data);
}

export function asignarHorasSemanales(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=asignarHorasSemanales', 'POST', data);
}

export function getAdmins(){
    return apiRequest(`/APIBackOffice/APIBackOffice.php?accion=getAdmins`, 'GET');
}

export function borrarAdmin(data) {
    return apiRequest('/APIBackOffice/APIBackOffice.php?accion=borrarAdmin', 'POST', data);
}