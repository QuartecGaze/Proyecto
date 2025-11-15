import { getIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';

try {
    const data = await getIdioma("usuario");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al aplicar idioma en usuario:", error);
}
