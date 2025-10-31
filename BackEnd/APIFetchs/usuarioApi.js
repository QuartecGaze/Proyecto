import { apiRequest } from 'apiConfig.js';

export function registrarUsuario(datos) {
    return apiRequest('http://localhost/Proyecto/BackEnd/APIUsuarios/ApiUsuarios.php?accion=registro', 'POST', datos);
}
