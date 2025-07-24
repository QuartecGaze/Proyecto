import { getUsuario } from '../../BackEnd/APIFetchs/APIUsuario.js';

const idSesion = await getIdSesion();
const data = await getUsuario(idSesion.message);
setDatos(data);

