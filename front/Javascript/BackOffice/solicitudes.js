import { getInteresados } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarEstado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarInteresado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarEstado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarInteresado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarEntrevista } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarPagoInicial } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIntegrantesFamiliares } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { getUnidadesLibres } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarUnidadHabitacional } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

// ---- helpers unidades ----
function normalizarUnidades(arr) {
  return (arr || []).map(u => ({
    id:            u.ID_Unidad_habitacional ?? u.id ?? u.idUnidad ?? u.ID ?? '',
    puerta:        u.Numero_puerta ?? u.puerta ?? '',
    pasillo:       u.Pasillo ?? u.pasillo ?? '',
    estado:        u.Estado_unidad ?? u.estado ?? '',
    habitaciones:  u.Cantidad_habitaciones ?? u.cantidad_habitaciones ?? u.habitaciones ?? ''
  })).filter(u => u.id !== '');
}


const esc = (s) => (s == null ? '' : String(s)
  .replace(/&/g,'&amp;').replace(/</g,'&lt;')
  .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
  .replace(/'/g,'&#039;'));

const boolDe = (v) => v === true || v === 1 || v === '1';

// acepta cualquiera de los dos flags que te mande getInteresados
const tieneUnidad = (rec) => boolDe(rec.unidadHabitacionalAsignada) || boolDe(rec.unidadAsignada);

// solo el texto que querés ver
const labelUnidad = (rec) => (tieneUnidad(rec) ? 'Asignada' : 'No asignada');


let cacheUnidadesLibres = [];






const contenedor = document.getElementById("contenedor-solicitudes");
const modalConfirm = document.getElementById('modalConfirmacion');
const btnCancelar = modalConfirm.querySelector('.btn-cancelar');
const modalPago = document.getElementById('modalPagoInicial');
const btnCancelarPago = modalPago.querySelector('.btn-cancelar-pago');
const btnConfirmarPago = modalPago.querySelector('.btn-confirmar-pago');
const modalUnidad = document.getElementById('modalAsignarUnidad');
const btnCancelarUnidad = modalUnidad.querySelector('.btn-cancelar-unidad');
const btnConfirmarUnidad = modalUnidad.querySelector('.btn-confirmar-unidad');
const selectUnidad = document.getElementById('selectUnidadHabitacional');
const infoUnidad = document.getElementById('infoUnidad');
const solicitudesIcon = document.getElementById('solicitudesIcon');

//mostrarintegrantesfamiliares
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
//const integrantesCount = $('#integrantes-count', panel);
//const integrantesEmpty = $('#integrantes-empty', panel);
//const integrantesTableWrapper = $('#integrantes-table-wrapper', panel);
//const integrantesTbody = $('#integrantes-tbody', panel);

//cerrar modal rechazar Interesado
btnCancelar.addEventListener('click', function () {
    modalConfirm.style.display = 'none';
    const confirmar = modalConfirm.querySelector('.btn-confirmar-rechazo');
    delete confirmar.dataset.id;
});

//cerrar modal de pago inicial
btnCancelarPago.addEventListener('click', function () {
    modalPago.style.display = 'none';
    delete btnConfirmarPago.dataset.id;
});


function formatDate(iso) {
  if (!iso) return '';
  // acepta tanto 'YYYY-MM-DD' como 'DD/MM/YYYY'
  if (/^\d{4}-\d{2}-\d{2}$/.test(iso)) {
    const [y, m, d] = iso.split('-');
    return `${d}/${m}/${y}`;
  }
  return iso;
}

function normalizarIntegrantes(arr) {
  return (arr || []).map(f => ({
    id:            f.id ?? f.ID_Integrante ?? f.idIntegrante ?? '',
    nombre:        f.nombre ?? f.Nombre ?? '',
    apellido:      f.apellido ?? f.Apellido ?? '',
    ci:            f.ci ?? f.CI ?? f.cedula ?? f.Cédula ?? '',
    fechaNac:      f.fecha_nacimiento ?? f.FechaNacimiento ?? f.fechaNac ?? f.fecha ?? '',
    genero:        f.genero ?? f.Genero ?? '',
    email:         f.email ?? f.Email ?? ''
  }));
}

async function renderIntegrantesPara(idPersona, panel) {
  const tbody = panel.querySelector('.tabla-pagos tbody');
  if (!tbody) return;

  // placeholder mientras carga
  tbody.innerHTML = `
    <tr><td colspan="6" style="text-align:center; opacity:.7;">Cargando integrantes...</td></tr>
  `;

  try {
    const resp = await getIntegrantesFamiliares(idPersona);
    const crudos = Array.isArray(resp?.message) ? resp.message
                : Array.isArray(resp?.data) ? resp.data
                : Array.isArray(resp) ? resp
                : [];
    const integrantes = normalizarIntegrantes(crudos);

    if (!integrantes.length) {
      tbody.innerHTML = `
        <tr><td colspan="6" style="text-align:center; opacity:.7;">Sin integrantes cargados</td></tr>
      `;
      return;
    }

    tbody.innerHTML = integrantes.map(int => `
      <tr data-id="${esc(int.id)}">
        <td data-label="Nombre">${esc(int.nombre)}</td>
        <td data-label="Apellido">${esc(int.apellido)}</td>
        <td data-label="Cédula">${esc(int.ci)}</td>
        <td data-label="Fecha de Nac.">${esc(formatDate(int.fechaNac))}</td>
        <td data-label="Email">${esc(int.email)}</td>
        <td data-label="Género">${esc(int.genero)}</td>
      </tr>
    `).join('');
  } catch (e) {
    console.error('Error obteniendo integrantes de', idPersona, e);
    tbody.innerHTML = `
      <tr><td colspan="6" style="text-align:center; color:#b00;">No se pudieron cargar los integrantes</td></tr>
    `;
  }
}




let interesados = [];
try {
    const data = await getInteresados();
    interesados = Object.values(data.message);
    solicitudesIcon.style.display = "none";
    if (data.status === "exito") {
        actualizarSolicitudes(interesados);
    }

} catch (error) {
    throw new Error("error en la api: " + error.message);
}


function snakeCamel(snakeStr) {
    return snakeStr.toLowerCase().replace(/_([a-z])/g, (_, letra) => letra.toUpperCase());
}

function actualizarEstadoArray(array, idPersona, campo, nuevoValor) {
  const idNum = Number(idPersona); // asegura comparación numérica
  let encontrado = false;

  const nuevoArray = array.map(item => {
    if (Number(item.idPersona) === idNum) {
      encontrado = true;
      // devolvemos copia con la propiedad actualizada
      return { ...item, [campo]: nuevoValor };
    }
    return item;
  });

  if (!encontrado) {
    console.warn(`actualizarEstadoArray: no se encontró interesado con id ${idPersona}`);
  }
  return nuevoArray;
}


function actualizarSolicitudes(interesados) {
    contenedor.innerHTML = "";
    interesados.forEach(interesado => {
        const div = document.createElement("div");
        const unidadLabel = labelUnidad(interesado);
        const puedeAprobar = tieneUnidad(interesado);

        div.innerHTML = `
            <div class="contenedor-solicitud">
                <div class="contenido">
                    <div class="solicitud-header">
                        <h2>Solicitud Nr#${interesado.idPersona}  </h2>
                        <button class="btn-solicitud btn-rechazar-solicitud" data-id="${interesado.idPersona}">
                            <i class="material-icons">block</i> Rechazar Solicitud
                        </button>
                        <button 
                        data-id="${interesado.idPersona}"
                        ${!puedeAprobar 
                            ? 'class="btn-solicitud btn-aprobar-solicitud btn-aprobar-solicitud--bloqueada" data-bloqueada="1" title="Asigná una unidad primero"' 
                            : 'class="btn-solicitud btn-aprobar-solicitud"'}
                        >
                        <i class="material-icons">check_circle</i> Aprobar Solicitud
                        </button>

                    </div>

                    <div class="solicitud-info">
                        <div class="info-card" id="info-card-id">
                            <h3>Información Personal</h3>
                            <p><strong>Nombre: </strong>${interesado.nombre} ${interesado.apellido}</p>
                            <p><strong>CI: </strong>${interesado.ci}</p>
                            <p><strong>Mail: </strong>${interesado.email}</p>
                            <p><strong>Telefono: </strong>${interesado.telefono}</p>
                        </div>
                        <div class="date info-card">
                            <h3>Asignar Fecha de Entrevista</h3>
                            <div class="calendario">
                                <p><strong>Fecha: </strong> ${interesado.fechaEntrevista ?? 'Sin asignar'}</p>
                                <input type="date" id="fecha${interesado.idPersona}">
                            </div>
                            <div class="hora">
                            
                                <p><strong>Hora: </strong> ${interesado.horaEntrevista ?? 'Sin asignar'}</p>
                                <input type="time" id="hora${interesado.idPersona}">
                            </div>
                            <div class="direccion">
                                <p><strong>Direccion: </strong></p>
                                Av.Gral Rivera 3729 bis, 11600 Montevideo
                            </div>
                            <button class="btn-asignar-entrevista" data-id="${interesado.idPersona}">
                                <i class="material-icons">event_available</i> Asignar Entrevista
                            </button>
                        </div>
                    </div>


                    <div class="acciones">

                        <button class="btn-rechazar btn-${interesado.estadoEntrevista}" data-id="${interesado.idPersona}" data-campo="Estado_entrevista">
                            <i class="material-icons">close</i> Rechazar
                        </button>
                        <button class="btn-aprobar btn-${interesado.estadoEntrevista}" data-id="${interesado.idPersona}" data-campo="Estado_entrevista">

                            <i class="material-icons">check</i> Aprobar
                        </button>
                    </div>


                    <div class="tabla-contenedor">
                            <h3>Integrantes Familiares</h3>
                            <table class="tabla-pagos">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Cédula</th>
                                        <th>Fecha de Nac.</th>
                                        <th>Email</th>
                                        <th>Género</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Nombre">Alain</td>
                                        <td data-label="Apellido">Arce</td>
                                        <td data-label="Cédula">57051830</td>
                                        <td data-label="Fecha de Nac.">06/02/2026</td>
                                        <td data-label="Email">alain@gmail.com</td>
                                        <td data-label="Género">Masculino</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    <div class="solicitud documentos">
                        <h3>Documentos Adjuntos</h3>
                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Antecedentes Penales</h4>
                                <p>Documento PDF - <span class="estado-badge ${interesado.estadoAntecedentes}">${interesado.estadoAntecedentes}</span></p>
                            </div>
                            <div class="documento-acciones">
                            ${interesado.antecedentes != null && interesado.antecedentes !== "" ? `
                        <a href="../../Recursos/Antecedentes/${interesado.antecedentes}" download>
                            <li class="material-icons">download</li> Descargar
                            </a>
                            ` : `
                            <p><em>No se adjuntó archivo</em></p>
                            `}
                        </div>
                        </div>
                        <div class="acciones">
                            <button class="btn-rechazar btn-${interesado.estadoAntecedentes}" data-id="${interesado.idPersona}" data-campo="Estado_antecedentes">
                                <i class="material-icons">close</i> Rechazar
                            </button>
                            <button class="btn-aprobar btn-${interesado.estadoAntecedentes}" data-id="${interesado.idPersona}" data-campo="Estado_antecedentes">
                                <i class="material-icons">check</i> Aprobar
                            </button>
                        </div>

                        

                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Monto de Pago Inicial</h4>
                                <p><strong>Asignado:</strong> ${interesado.montoPagoInicial != null && interesado.montoPagoInicial !== "" ? `$${interesado.montoPagoInicial}` : '<em>No asignado</em>'}</p>
                            </div>
                            <div class="documento-acciones">
                                <button class="btn-asignar-pago-inicial" data-id="${interesado.idPersona}">
                                    <i class="material-icons">payment</i> Asignar / Editar Monto
                                </button>
                            </div>
                        </div>

                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Comprobante de Pago Inicial </h4> 
                                <p>Documento PDF - <span class="estado-badge ${interesado.estadoPagoInicial}">${interesado.estadoPagoInicial}</span></p>
                            </div>

                            <div class="documento-acciones">
                                ${interesado.pagoInicial != null && interesado.pagoInicial !== "" ? `
                        <a href="../../Recursos/Comprobantes/${interesado.pagoInicial}" download>
                            <li class="material-icons">download</li> Descargar
                            </a>
                            ` : `
                            <p><em>No se adjuntó archivo</em></p>
                            `}
                            </div>
                        </div>
                        <div class="acciones">

                            <button class="btn-rechazar btn-${interesado.estadoPagoInicial}" data-id="${interesado.idPersona}" data-campo="Estado_pago_inicial">

                                <i class="material-icons">close</i> Rechazar
                            </button>
                            <button class="btn-aprobar btn-${interesado.estadoPagoInicial}" data-id="${interesado.idPersona}" data-campo="Estado_pago_inicial">
                                <i class="material-icons">check</i> Aprobar
                            </button>
                        </div>


                        <h3>Asignar Unidad Habitacional</h3>
                            <div class="asignacion-unidad">
                            <div class="documento-card">
                                <div class="documento-info">
                                <h4>Unidad Habitacional</h4>
                                <p><strong>Unidad:</strong>
                                    <span class="estado-unidad" data-id="${esc(interesado.idPersona)}">
                                    ${esc(unidadLabel)}
                                    </span>
                                </p>
                                </div>
                                <div class="documento-acciones">
                                <button class="btn-asignar-unidad"
                                        data-ci="${esc(interesado.ci)}"
                                        data-idpersona="${esc(interesado.idPersona)}">
                                    <i class="material-icons">apartment</i> Asignar Unidad
                                </button>
                                </div>
                            </div>
                            </div>


                    </div>
                </div>
                <div class="contador">
                    <div class="segmento s1" id="${interesado.estadoEntrevista}"></div>
                    <div class="segmento s2" id="${interesado.estadoAntecedentes}"></div>
                    <div class="segmento s3" id="${interesado.estadoPagoInicial}"></div>
                </div>
            </div>
            `;
        contenedor.appendChild(div);

        //cargar los integrantes familiares
        renderIntegrantesPara(interesado.idPersona, div);

    });

    const botonesAprobar = document.querySelectorAll(".btn-aprobar");
    const botonesRechazar = document.querySelectorAll(".btn-rechazar");
    const botonAsignarEntrevista = document.querySelectorAll(".btn-asignar-entrevista");

    botonAsignarEntrevista.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idPersona = boton.dataset.id;
            const fecha = document.getElementById('fecha' + idPersona).value;
            const hora = document.getElementById('hora' + idPersona).value;

            if (!fecha || !hora) {
                alert("Por favor completa la fecha y hora antes de asignar.");
                return;
            }

            const datos = {
                idPersona: idPersona,
                fecha: fecha,
                hora: hora,
            };

            try {
                const respuesta = await asignarEntrevista(datos);

                if (respuesta.status === "exito") {
                    alert("Entrevista asignada con exito.");

                } else {
                    alert("Error " + respuesta.message);
                }
            } catch (error) {
                console.error("Error al asignar la entrevista", error);
                alert("Error del servidor");
            }
        });
    });


const botonAprobarInteresado = document.querySelectorAll(".btn-aprobar-solicitud");
botonAprobarInteresado.forEach(boton => {
  boton.addEventListener("click", async () => {
    const idPersona = boton.dataset.id;
    const rec = interesados.find(p => String(p.idPersona) === String(idPersona));

    // si no tiene unidad, avisá y no sigas
    if (!rec || !(rec.unidadHabitacionalAsignada == 1 || rec.unidadAsignada == 1)) {
      alert('No podés aprobar: el interesado no tiene unidad asignada.');
      return;
    }

    try {
      const respuesta = await aprobarInteresado({ idPersona });
      if (respuesta.status === "exito") {
        alert("Interesado aprobado con éxito.");
        // opcional: remover card o refrescar listado
        // actualizarSolicitudes(interesados);
      } else {
        alert("Error " + respuesta.message);
      }
    } catch (err) {
      console.error("Error al aprobar el interesado", err);
      alert("Error del servidor");
    }
  });
});


// Guardas NO-OP por si no existen en este archivo (evitan que se corte el script)
const toggleSpinner = typeof window.toggleSpinner === 'function' ? window.toggleSpinner : () => {};
const wireDelete    = typeof window.wireDelete === 'function'    ? window.wireDelete    : () => {};
const getIdSesion   = typeof window.getIdSesion === 'function'   ? window.getIdSesion   : undefined;

// Si no hay contadores/elementos de la tabla de integrantes, que no rompa:
let integrantesCount        = document.getElementById('integrantes-count');
let integrantesEmpty        = document.getElementById('integrantes-empty');
let integrantesTableWrapper = document.getElementById('integrantes-table-wrapper');
let integrantesTbody        = document.getElementById('integrantes-tbody');

// Si no existen, renderTablaIntegrantes sale temprano:
function renderTablaIntegrantes() {
  if (!integrantesCount || !integrantesEmpty || !integrantesTableWrapper || !integrantesTbody) {
    return; // no hay UI de integrantes en esta pantalla, no hacemos nada
  }
  integrantesCount.textContent = String(integrantesGuardados.length);
  if (integrantesGuardados.length === 0) {
    integrantesTableWrapper.style.display = 'none';
    integrantesEmpty.style.display = 'flex';
    integrantesTbody.innerHTML = '';
    return;
  }
  integrantesEmpty.style.display = 'none';
  integrantesTableWrapper.style.display = 'block';
  integrantesTbody.innerHTML = integrantesGuardados.map((integrante) => `
    <tr data-id="${esc(integrante.id)}">
      <td data-label="Nombre">${esc(integrante.nombre)}</td>
      <td data-label="Apellido">${esc(integrante.apellido)}</td>
      <td data-label="Cédula">${esc(integrante.ci)}</td>
      <td data-label="Fecha Nac.">${esc(formatDate(integrante.fechaNacimiento))}</td>
      <td data-label="Género">${esc(integrante.genero)}</td>
      <td data-label="Email">${esc(integrante.email)}</td>
      <td class="panel-integrantes__actions" data-label="Acciones">
        <button type="button" class="btn btn--danger btn--sm btn-delete-integrante" 
                data-id="${esc(integrante.id)}" title="Eliminar integrante">
          <i class="material-icons">delete</i>
        </button>
      </td>
    </tr>
  `).join('');
}

// ====== LISTADO (mostrar todos) ======
if (typeof getIdSesion === 'function') {
  (async function initListado() {
    try {
      const ses = await getIdSesion();
      idPersonaLog = ses?.message;
      await cargarListadoIntegrantes();
      wireDelete(); // activar eventos de borrar si existe
    } catch (e) {
      console.error('initListado error:', e);
    }
  })();
}


async function cargarListadoIntegrantes() {
  try {
    toggleSpinner(true);
    const resp = await getIntegrantesFamiliares(idPersonaLog);
    const arr = Array.isArray(resp?.message) ? resp.message
      : (Array.isArray(resp?.message?.data) ? resp.message.data : []);
    integrantesGuardados = normalizarIntegrantes(arr);
    renderTablaIntegrantes();
  } catch (e) {
    console.error('getIntegrantesFamiliares falló:', e);
    integrantesGuardados = [];
    renderTablaIntegrantes();
  } finally {
    toggleSpinner(false);
  }
}

function normalizarIntegrantes(arr) {
  return (arr || []).map(f => ({
    id: f.id ?? f.ID_Integrante ?? '',
    nombre: f.nombre ?? f.Nombre ?? '',
    apellido: f.apellido ?? f.Apellido ?? '',
    ci: f.ci ?? f.CI ?? '',
    fechaNacimiento: f.fecha_nacimiento ?? f.FechaNacimiento ?? '',
    genero: f.genero ?? f.Genero ?? '',
    email: f.email ?? f.Email ?? ''
  }));
}

function renderTablaIntegrantes() {
  integrantesCount.textContent = String(integrantesGuardados.length);

  if (integrantesGuardados.length === 0) {
    integrantesTableWrapper.style.display = 'none';
    integrantesEmpty.style.display = 'flex';
    integrantesTbody.innerHTML = '';
    return;
  }

  integrantesEmpty.style.display = 'none';
  integrantesTableWrapper.style.display = 'block';

  integrantesTbody.innerHTML = integrantesGuardados.map((integrante, index) => `
        <tr data-id="${esc(integrante.id)}">
            <td data-label="Nombre">${esc(integrante.nombre)}</td>
            <td data-label="Apellido">${esc(integrante.apellido)}</td>
            <td data-label="Cédula">${esc(integrante.ci)}</td>
            <td data-label="Fecha Nac.">${esc(formatDate(integrante.fechaNacimiento))}</td>
            <td data-label="Género">${esc(integrante.genero)}</td>
            <td data-label="Email">${esc(integrante.email)}</td>
            <td class="panel-integrantes__actions" data-label="Acciones">
                <button type="button" class="btn btn--danger btn--sm btn-delete-integrante" 
                        data-id="${esc(integrante.id)}" title="Eliminar integrante">
                    <i class="material-icons">delete</i>
                </button>
            </td>
        </tr>
    `).join('');
}







    //abrir modal rechazar Interesado
    document.querySelectorAll('.btn-rechazar-solicitud').forEach(boton => {
        boton.addEventListener('click', () => {
            const confirmar = modalConfirm.querySelector('.btn-confirmar-rechazo');
            confirmar.dataset.id = boton.dataset.id;
            modalConfirm.style.display = 'flex';
        });
    });
    const botonRechazarInteresado = modalConfirm.querySelectorAll('.btn-confirmar-rechazo');
    botonRechazarInteresado.forEach(boton => {
        boton.addEventListener('click', async () => {
            modalConfirm.style.display = 'none';
            const idPersona = boton.dataset.id;//trae el id la persona al boton del modal para poder ejecutar el metodo
            delete boton.dataset.id;

            const datos = {
                idPersona: idPersona
            };

            try {
                const respuesta = await rechazarInteresado(datos);
                if (respuesta.status === 'exito') {
                    alert('Interesado rechazado y eliminado con exito.');
                } else {
                    alert('Error: ' + respuesta.message);
                }
            } catch (error) {
                console.error('Error al eliminar el interesado', error);
                alert('Error del servidor');
            }
        });
    });

    botonesAprobar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idPersona = boton.dataset.id;
            const campo = boton.dataset.campo;

            const datos = {
                idPersona: idPersona,
                campo: campo
            };

            try {
                const respuesta = await aprobarEstado(datos);
                if (respuesta.status == "exito") {
                    interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Aprobado");
                    actualizarSolicitudes(interesados);
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
            }
        });
    });

    botonesRechazar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idPersona = boton.dataset.id;
            const campo = boton.dataset.campo;

            const datos = {
                idPersona: idPersona,
                campo: campo
            };

            try {
                const respuesta = await rechazarEstado(datos);
                if (respuesta.status == "exito") {
                    interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Rechazado");
                    actualizarSolicitudes(interesados);
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
            }
        });
    });



    // Abrir modal de pago inicial
    document.querySelectorAll('.btn-asignar-pago-inicial').forEach(boton => {
        boton.addEventListener('click', function () {
            console.log('Abriendo modal de pago');
            btnConfirmarPago.dataset.id = boton.dataset.id;
            modalPago.style.display = 'flex';
        });
    });

    // Cerrar modal de pago inicial
    btnCancelarPago.addEventListener('click', function () {
        console.log('Cerrando modal de pago');
        modalPago.style.display = 'none';
        delete btnConfirmarPago.dataset.id;
    });


}
// Confirmar pago inicial
btnConfirmarPago.addEventListener('click', async function () {
    console.log('Confirmando pago');
    modalPago.style.display = 'none';
    const idPersona = btnConfirmarPago.dataset.id;
    delete btnConfirmarPago.dataset.id;
    const montoPagoInicial = document.getElementById('inputPagoInicial').value;
    if (!montoPagoInicial) {
        alert('Por favor ingrese un monto válido');
        return;
    }

    const datos = {
        idPersona: idPersona,
        montoPagoInicial: montoPagoInicial
    };

    try {
        const respuesta = await asignarPagoInicial(datos);
        if (respuesta.status === 'exito') {
            alert('Pago inicial asignado correctamente.');
            // Actualizar la vista si es necesario
        } else {
            alert('Error: ' + respuesta.message);
        }
    } catch (error) {
        console.error('Error al asignar pago inicial', error);
        alert('Error del servidor');
    }
});









btnCancelarUnidad.addEventListener('click', function () {
  modalUnidad.style.display = 'none';
  delete btnConfirmarUnidad.dataset.idPersona;
  delete btnConfirmarUnidad.dataset.ci;
  selectUnidad.value = '';
  btnConfirmarUnidad.disabled = true;
  infoUnidad.textContent = '';
});

// Abrir modal y cargar unidades libres
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.btn-asignar-unidad');
  if (!btn) return;

  const ci = btn.dataset.ci;
  const idPersona = btn.dataset.idpersona;
  if (!ci) { alert('No se encontró la cédula del interesado.'); return; }

  // atamos los datos al botón confirmar del modal
  btnConfirmarUnidad.dataset.ci = ci;
  if (idPersona) btnConfirmarUnidad.dataset.idPersona = idPersona;

  // abrir modal
  modalUnidad.style.display = 'flex';

  // cargar unidades
  try {
    const resp = await getUnidadesLibres();
    const crudos = Array.isArray(resp?.message) ? resp.message
                : Array.isArray(resp?.data)    ? resp.data
                : Array.isArray(resp)          ? resp
                : [];
    cacheUnidadesLibres = normalizarUnidades(crudos);

    if (!cacheUnidadesLibres.length) {
      selectUnidad.innerHTML = '<option value="">No hay unidades libres</option>';
      btnConfirmarUnidad.disabled = true;
      infoUnidad.textContent = '—';
    } else {
      const opts = ['<option value="">Seleccioná una unidad…</option>']
        .concat(cacheUnidadesLibres.map(u => {
          const label = `U#${esc(u.id)} • Puerta ${esc(u.puerta)} • Pasillo ${esc(u.pasillo)} • ${esc(u.habitaciones)} hab • ${esc(u.estado)}`;
          return `<option value="${esc(u.id)}">${label}</option>`;
        }));
      selectUnidad.innerHTML = opts.join('');
      selectUnidad.value = '';
      btnConfirmarUnidad.disabled = true;
      infoUnidad.textContent = '';
    }
  } catch (err) {
    console.error('[AsignarUnidad] Error getUnidadesLibres:', err);
    selectUnidad.innerHTML = '<option value="">Error cargando unidades</option>';
    btnConfirmarUnidad.disabled = true;
    infoUnidad.textContent = '—';
  }
});


selectUnidad.addEventListener('change', function () {
  const idSel = this.value;
  btnConfirmarUnidad.disabled = !idSel;

  const u = cacheUnidadesLibres.find(x => String(x.id) === String(idSel));
  if (u) {
    infoUnidad.textContent = `Seleccionada: U#${u.id} • Puerta ${u.puerta} • Pasillo ${u.pasillo} • ${u.habitaciones} hab • ${u.estado}`;
  } else {
    infoUnidad.textContent = '';
  }
});



btnConfirmarUnidad.addEventListener('click', async function () {
  try {
    const ci = btnConfirmarUnidad.dataset.ci;
    const idPersona = btnConfirmarUnidad.dataset.idPersona;
    const idUnidad = selectUnidad.value;

    if (!ci) { alert('Falta la cédula del interesado.'); return; }
    if (!idUnidad) { alert('Seleccioná una unidad válida.'); return; }

    // Llamada real a la API
    const resp = await asignarUnidadHabitacional({ ci: String(ci), idUnidad: Number(idUnidad) });

    // ...
if (resp?.status === 'exito') {
// — Ya tenés ci, idPersona, idUnidad y resp exitoso arriba —

alert('Unidad asignada correctamente.');

// 1) marcá flags en memoria para que “tieneUnidad(...)” quede true
interesados = interesados.map(p =>
  String(p.idPersona) === String(idPersona)
    ? { ...p, unidadHabitacionalAsignada: 1, unidadAsignada: 1 }
    : p
);

// 2) actualizá SOLO el texto visible "Unidad: Asignada"
const estadoSpan = document.querySelector(`.estado-unidad[data-id="${idPersona}"]`);
if (estadoSpan) estadoSpan.textContent = 'Asignada';

// 3) habilitá el botón "Aprobar Solicitud" (si estaba deshabilitado)
const btnAprobarSolicitud = document.querySelector(`.btn-aprobar-solicitud[data-id="${idPersona}"]`);
if (btnAprobarSolicitud) {
  btnAprobarSolicitud.disabled = false;
  btnAprobarSolicitud.removeAttribute('title');
}

// 4) cerrá y limpiá tu modal (dejá lo que ya tenías)
modalUnidad.style.display = 'none';
delete btnConfirmarUnidad.dataset.idPersona;
delete btnConfirmarUnidad.dataset.ci;
selectUnidad.value = '';
btnConfirmarUnidad.disabled = true;
infoUnidad.textContent = '';

} else {
  alert('Error: ' + (resp?.message || 'No se pudo asignar la unidad'));
}

  } catch (e) {
    console.error('Error al asignar unidad', e);
    alert('Error del servidor al asignar la unidad.');
  }
});



