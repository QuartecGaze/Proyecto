import { apiRequest } from 'apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function registrarUsuario(datos) {
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=registro', 'POST', datos);
}

export function iniciarSesion(datos) {
    return apiRequest('/APIUsuarios/ApiUsuarios.php?accion=registro', 'POST', datos);
}