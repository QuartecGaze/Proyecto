// ../Javascript/FrontUsuario/configuracion_idioma.js
import { getIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';

try {
    // mismo nombre que en la tabla
    const data = await getIdioma("configuracion-usuario");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al aplicar idioma en configuracion-usuario:", error);
}
