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

document.addEventListener('DOMContentLoaded', () => {
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    const sidebar = document.querySelector('.sidebar');
    
    hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.toggle('mostrar');
    });
    
    // Cerrar sidebar al hacer clic fuera de él
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992 && 
            !sidebar.contains(e.target) && 
            e.target !== hamburgerBtn && 
            !hamburgerBtn.contains(e.target)) {
            sidebar.classList.remove('mostrar');
        }
    });
});