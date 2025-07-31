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
    const config = {
        method,
        headers: {},
    };

    if (body && !(body instanceof FormData)) {
        config.headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(body);
    } else if (body instanceof FormData) {
        config.body = body;
        
    }

    const response = await fetch(`${baseUrl}${endpoint}`, config);
    const data = await response.json(); 
    console.log(data);
    return data;
}

