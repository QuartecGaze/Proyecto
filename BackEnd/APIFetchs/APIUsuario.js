import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function registrarUsuario(datos) {
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=registro', 'POST', datos);
}

export function iniciarSesion(datos) {
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=login', 'POST', datos);
}
export function subirFoto(foto){
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=subirFoto', 'POST', foto);
}
export function subirComprobante(comprobante){
    return apiRequest(`/APIUsuarios/ApiUsuarios.php?accion=subirComprobante`, "POST", comprobante);
}
export function subirAntecedentes(Antecedentes){
    return apiRequest(`/APIUsuarios/ApiUsuarios.php?accion=subirAntecedentes`, "POST", Antecedentes);
}
export function verificarSesion(){
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=verificarSesion', 'GET')
}
export function getInteresados() {
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=getInteresados', 'GET');
}
export function getInteresado(id){
    return apiRequest(`/APIUsuarios/ApiUsuarios.php?accion=getInteresado&id=` + id, 'GET');
}
export function getIdSesion(){
    return apiRequest(`/APIUsuarios/ApiUsuarios.php?accion=getIdSesion`, "GET");
}