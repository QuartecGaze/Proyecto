import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function crearUnidadHabitacional(data){
     const data = apiRequest('/APICooperativa/APICooperativa.php?accion=crearUnidad', 'POST', data);
        return data;
}

export function getComprobantes(){
     const data = apiRequest('/APICooperativa/APICooperativa.php?accion=getComprobantes&id=' + id , 'POST');
        return data;
}