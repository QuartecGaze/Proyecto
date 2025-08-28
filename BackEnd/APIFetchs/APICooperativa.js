import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos - Contiene `ci` y `contraseña`
 * @returns {Promise<Object>} - Respuesta de la API
 */
/*
        export function crearUnidadHabitacional(data){
                const data = apiRequest('/APICooperativa/APICooperativa.php?accion=crearUnidad', 'POST', data);
                return data;
        }
*/
        export function getComprobantes(){
                const data = apiRequest('/APICooperativa/APICooperativa.php?accion=getComprobantes&id=' + id , 'POST');
                return data;
        }

        export function subirComprobante(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=subirComprobante`, "POST", data);
        }

        export function subirHoras(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=subirHoras`, "POST", data);
        }
