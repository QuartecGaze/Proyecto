const baseUrl = 'http://localhost/Proyecto/BackEnd';

/**
 * apiRequest realiza una solicitud HTTP a la API PHP.
 *
 * @param {string} endpoint - Ruta desde baseUrl, ej: '/APIUsuarios/ApiUsuarios.php?accion=registro'
 * @param {string} method - Método HTTP, como 'GET', 'POST', etc.
 * @param {Object|null} body - El cuerpo de la solicitud (datos que se mandan al servidor)
 * @returns {Promise<Object>} - Devuelve la respuesta JSON o lanza un error
 */
export async function apiRequest(endpoint, method = 'GET', body = null) {
    // Arma la configuración de la solicitud
    const config = {
        method, // 'GET', 'POST', etc.
        headers: { 'Content-Type': 'application/json' }, // Tipo de datos enviados
    };

    // Si hay datos para enviar (como en POST), los convierte a JSON
    if (body) {
        config.body = JSON.stringify(body);
    }

    // Llama a fetch usando la URL base + el endpoint específico
    const response = await fetch(`${baseUrl}${endpoint}`, config);

    // Intenta leer la respuesta como JSON (puede fallar si no es JSON válido)
    const data = await response.json().catch(() => null);
    console.log(data);
    // Si la respuesta no fue exitosa (error HTTP), lanza un error
    if (!response.ok) {
        throw new Error(data?.message || `Error HTTP ${response.status}`);
    }

    // Si todo fue bien, devuelve los datos
    return data;
}