// ../Javascript/FrontUsuario/traduccionesHoras.js
import { getIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../../BackEnd/APIFetchs/APITraduccion.js';

try {
    // El nombre de página tiene que ser EXACTO al de la columna "pagina" en la tabla:
    // en este caso: 'horas'
    const data = await getIdioma("horas");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al aplicar idioma en horas:", error);
}
