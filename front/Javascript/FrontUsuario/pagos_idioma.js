import { getIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';

try {
    // El nombre de página es el mismo que usaste en la tabla: "pagos-usuario"
    const data = await getIdioma("pagos-usuario");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al aplicar idioma en pagos-usuario:", error);
}
