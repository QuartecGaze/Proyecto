import { getInteresados } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarEstado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarInteresado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarEstado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarInteresado } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarEntrevista } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { asignarPagoInicial } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIntegrantesFamiliares } from '../../../BackEnd/APIFetchs/APICooperativa.js';



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
        div.innerHTML = `
            <div class="contenedor-solicitud">
                <div class="contenido">
                    <div class="solicitud-header">
                        <h2>Solicitud Nr#${interesado.idPersona}  </h2>
                        <button class="btn-solicitud btn-rechazar-solicitud" data-id="${interesado.idPersona}">
                            <i class="material-icons">block</i> Rechazar Solicitud
                        </button>
                        <button class="btn-solicitud btn-aprobar-solicitud" data-id="${interesado.idPersona}">
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
                                <h4>Unidad Habitacional Asignada</h4>
                                <p><strong>Unidad:</strong> ${interesado.unidadAsignada || '<em>No asignada</em>'}</p>
                            </div>
                            <div class="documento-acciones">
                                <button class="btn-asignar-unidad" data-id="${interesado.idPersona}">
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
            const datos = {
                idPersona: idPersona
            };

            try {
                const respuesta = await aprobarInteresado(datos);

                if (respuesta.status === "exito") {
                    alert("Interesado aprobado con exito.");

                } else {
                    alert("Error " + respuesta.message);
                }
            } catch (error) {
                console.error("Error al aprobar el interesado", error)
                alert("Error del servidor");
            }
        });
    });

// ====== LISTADO (mostrar todos) ======
(async function initListado() {
  try {
    const ses = await getIdSesion();
    idPersonaLog = ses?.message;
    await cargarListadoIntegrantes();
    wireDelete(); // activar eventos de borrar
  } catch (e) {
    console.error('initListado error:', e);
  }
})();

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


document.querySelectorAll('.btn-asignar-unidad').forEach(boton => {
    boton.addEventListener('click', function () {
        console.log('Abriendo modal de unidad');
        btnConfirmarUnidad.dataset.id = boton.dataset.id;
        modalUnidad.style.display = 'flex';
    });
});

// Cerrar modal de unidad
btnCancelarUnidad.addEventListener('click', function () {
    console.log('Cerrando modal de unidad');
    modalUnidad.style.display = 'none';
    delete btnConfirmarUnidad.dataset.id;
    selectUnidad.value = '';
    btnConfirmarUnidad.disabled = true;
});

// Habilitar botón cuando se selecciona una unidad
selectUnidad.addEventListener('change', function () {
    btnConfirmarUnidad.disabled = !this.value;
});

// Confirmar asignación de unidad
btnConfirmarUnidad.addEventListener('click', async function () {
    console.log('Confirmando asignación de unidad');
    const idPersona = btnConfirmarUnidad.dataset.id;
    const idUnidad = selectUnidad.value;

    if (!idUnidad) {
        alert('Por favor seleccione una unidad válida');
        return;
    }

    const selectedOption = selectUnidad.options[selectUnidad.selectedIndex];
    const nombreUnidad = selectedOption.textContent;

    const datos = {
        idPersona: idPersona,
        idUnidad: idUnidad,
        nombreUnidad: nombreUnidad
    };

    try {
        //tengo que enviar la ci de la persona y el idUnidad habitacional 
        //que va a salir del array de la lista
        //usar el metodo asignarUnidadHabitacional



        // Aquí llamarías a tu API para asignar la unidad
        // const respuesta = await asignarUnidad(datos);

        // Por ahora simulamos la respuesta
        const respuesta = { status: 'exito', message: 'Unidad asignada correctamente' };

        if (respuesta.status === 'exito') {
            alert('Unidad asignada correctamente.');
            modalUnidad.style.display = 'none';
            delete btnConfirmarUnidad.dataset.id;
            selectUnidad.value = '';
            btnConfirmarUnidad.disabled = true;

            // Actualizar la vista
            // interesados = actualizarEstadoArray(interesados, idPersona, 'unidadAsignada', nombreUnidad);
            // actualizarSolicitudes(interesados);
        } else {
            alert('Error: ' + respuesta.message);
        }
    } catch (error) {
        console.error('Error al asignar unidad', error);
        alert('Error del servidor');
    }
});
