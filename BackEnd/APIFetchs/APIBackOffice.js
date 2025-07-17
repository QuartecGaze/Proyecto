import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function Interesados() {
    return apiRequest('/APIBackOffice/ApiBackOffice.php?accion=Interesados', 'GET');
}