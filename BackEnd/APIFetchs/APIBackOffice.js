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