// ../Javascript/FrontUsuario/unidad_idioma.js (por ejemplo)
import { getIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';

try {
    // Nombre de página = el que usaste en la tabla: "unidad-usuario"
    const data = await getIdioma("unidad-usuario");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al aplicar idioma en unidad-usuario:", error);
}
