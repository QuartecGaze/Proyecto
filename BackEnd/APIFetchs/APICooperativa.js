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
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=subirComprobante&id=` + id, 'POST', comprobante);
        }

        export function subirHoras(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=subirHoras`, "POST", data);
        }
        export function getCooperativa(id) {
                return apiRequest('/APICooperativa/ApiCooperativa.php?accion=getCooperativa&id=' + id, 'GET');
        }
        export function getPagos(id) {
                return apiRequest('/APICooperativa/ApiCooperativa.php?accion=getPagos&id=' + id, 'GET');
        }
        export function getHorasTrabajadas(id) {
                return apiRequest('/APICooperativa/ApiCooperativa.php?accion=getHorasTrabajadas&id=' + id, 'GET');
        }
        export function editarHoras(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=editarHoras`, "POST", data);
        }
        export function borrarHoras(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=borrarHoras`, "POST", data);
        }
        export function ingresarIntegrantesFamiliares(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=ingresarIntegrantesFamiliares`, "POST", data);
        }
        export function subirFalta(data){
                return apiRequest(`/APICooperativa/ApiCooperativa.php?accion=subirFalta`, "POST", data);
        }
        export function getIntegrantesFamiliares(id) {
                return apiRequest('/APICooperativa/ApiCooperativa.php?accion=getIntegrantesFamiliares&id=' + id, 'GET');
        }
        export function eliminarIntegranteFamiliar(idIntegrante) {
                return apiRequest('/APICooperativa/ApiCooperativa.php?accion=eliminarIntegranteFamiliar', 'POST', idIntegrante);
        }