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
    id:           u.ID_Unidad_habitacional ?? u.id ?? u.idUnidad ?? u.ID ?? '',
    puerta:       u.Numero_puerta ?? u.puerta ?? '',
    pasillo:      u.Pasillo ?? u.pasillo ?? '',
    estado:       u.Estado_unidad ?? u.estado ?? '',
    habitaciones: u.Cantidad_habitaciones ?? u.cantidad_habitaciones ?? u.habitaciones ?? ''
  })).filter(u => u.id !== '');
}

const boolDe = (v) => v === true || v === 1 || v === '1';

// acepta cualquiera de los dos flags que te mande getInteresados
const tieneUnidad = (rec) => boolDe(rec.unidadHabitacionalAsignada) || boolDe(rec.unidadAsignada);

// solo el texto que querés ver
const labelUnidad = (rec) => (tieneUnidad(rec) ? 'Asignada' : 'No asignada');

let cacheUnidadesLibres = [];

// ---------------- DOM ----------------
const contenedor        = document.getElementById("contenedor-solicitudes");
const modalConfirm      = document.getElementById('modalConfirmacion');
const btnCancelar       = modalConfirm.querySelector('.btn-cancelar');
const modalPago         = document.getElementById('modalPagoInicial');
const btnCancelarPago   = modalPago.querySelector('.btn-cancelar-pago');
const btnConfirmarPago  = modalPago.querySelector('.btn-confirmar-pago');
const modalUnidad       = document.getElementById('modalAsignarUnidad');
const btnCancelarUnidad = modalUnidad.querySelector('.btn-cancelar-unidad');
const btnConfirmarUnidad= modalUnidad.querySelector('.btn-confirmar-unidad');
const selectUnidad      = document.getElementById('selectUnidadHabitacional');
const infoUnidad        = document.getElementById('infoUnidad');
const solicitudesIcon   = document.getElementById('solicitudesIcon');

// ---------------- Eventos generales ----------------

// cerrar modal rechazar Interesado
btnCancelar.addEventListener('click', function () {
  modalConfirm.style.display = 'none';
  const confirmar = modalConfirm.querySelector('.btn-confirmar-rechazo');
  delete confirmar.dataset.id;
});

// cerrar modal de pago inicial
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
    id:       f.id ?? f.ID_Integrante ?? f.idIntegrante ?? '',
    nombre:   f.nombre ?? f.Nombre ?? '',
    apellido: f.apellido ?? f.Apellido ?? '',
    ci:       f.ci ?? f.CI ?? f.cedula ?? f.Cédula ?? '',
    fechaNac: f.fecha_nacimiento ?? f.FechaNacimiento ?? f.fechaNac ?? f.fecha ?? '',
    genero:   f.genero ?? f.Genero ?? '',
    email:    f.email ?? f.Email ?? ''
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
                : Array.isArray(resp?.data)     ? resp.data
                : Array.isArray(resp)           ? resp
                : [];
    const integrantes = normalizarIntegrantes(crudos);

    if (!integrantes.length) {
      tbody.innerHTML = `
        <tr><td colspan="6" style="text-align:center; opacity:.7;">Sin integrantes cargados</td></tr>
      `;
      return;
    }

    tbody.innerHTML = integrantes.map(int => `
      <tr data-id="${int.id}">
        <td data-label="Nombre">${int.nombre}</td>
        <td data-label="Apellido">${int.apellido}</td>
        <td data-label="Cédula">${int.ci}</td>
        <td data-label="Fecha de Nac.">${formatDate(int.fechaNac)}</td>
        <td data-label="Email">${int.email}</td>
        <td data-label="Género">${int.genero}</td>
      </tr>
    `).join('');
  } catch (e) {
    console.error('Error obteniendo integrantes de', idPersona, e);
    tbody.innerHTML = `
      <tr><td colspan="6" style="text-align:center; color:#b00;">No se pudieron cargar los integrantes</td></tr>
    `;
  }
}

// ---------------- Carga inicial de interesados ----------------
let interesados = [];

try {
  const data = await getInteresados();
  interesados = Object.values(data.message);
  if (solicitudesIcon) {
    solicitudesIcon.style.display = "none";
  }
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
  const idNum = Number(idPersona);
  let encontrado = false;

  const nuevoArray = array.map(item => {
    if (Number(item.idPersona) === idNum) {
      encontrado = true;
      return { ...item, [campo]: nuevoValor };
    }
    return item;
  });

  if (!encontrado) {
    console.warn(`actualizarEstadoArray: no se encontró interesado con id ${idPersona}`);
  }
  return nuevoArray;
}

// ---------------- Render de solicitudes ----------------
function actualizarSolicitudes(interesadosLista) {
  contenedor.innerHTML = "";

  interesadosLista.forEach(interesado => {
    const div = document.createElement("div");
    const unidadTexto  = labelUnidad(interesado);
    const puedeAprobar = tieneUnidad(interesado);

    div.innerHTML = `
      <div class="contenedor-solicitud">
        <div class="contenido">
          <div class="solicitud-header">
            <h2>Solicitud Nr#${interesado.idPersona}</h2>

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
                  <td data-label="Nombre">Cargando...</td>
                  <td data-label="Apellido"></td>
                  <td data-label="Cédula"></td>
                  <td data-label="Fecha de Nac."></td>
                  <td data-label="Email"></td>
                  <td data-label="Género"></td>
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
                ${
                  interesado.antecedentes != null && interesado.antecedentes !== ""
                    ? `
                    <a href="../../Recursos/Antecedentes/${interesado.antecedentes}" download>
                      <li class="material-icons">download</li> Descargar
                    </a>
                  `
                    : `<p><em>No se adjuntó archivo</em></p>`
                }
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
                <p><strong>Asignado:</strong> ${
                  interesado.montoPagoInicial != null && interesado.montoPagoInicial !== ""
                    ? `$${interesado.montoPagoInicial}`
                    : '<em>No asignado</em>'
                }</p>
              </div>
              <div class="documento-acciones">
                <button class="btn-asignar-pago-inicial" data-id="${interesado.idPersona}">
                  <i class="material-icons">payment</i> Asignar / Editar Monto
                </button>
              </div>
            </div>

            <div class="documento-card">
              <div class="documento-info">
                <h4>Comprobante de Pago Inicial</h4>
                <p>Documento PDF - <span class="estado-badge ${interesado.estadoPagoInicial}">${interesado.estadoPagoInicial}</span></p>
              </div>
              <div class="documento-acciones">
                ${
                  interesado.pagoInicial != null && interesado.pagoInicial !== ""
                    ? `
                    <a href="../../Recursos/Comprobantes/${interesado.pagoInicial}" download>
                      <li class="material-icons">download</li> Descargar
                    </a>
                  `
                    : `<p><em>No se adjuntó archivo</em></p>`
                }
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
                    <span class="estado-unidad" data-id="${interesado.idPersona}">
                      ${unidadTexto}
                    </span>
                  </p>
                </div>
                <div class="documento-acciones">
                  <button class="btn-asignar-unidad"
                          data-ci="${interesado.ci}"
                          data-idpersona="${interesado.idPersona}">
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

    // cargar integrantes familiares de este interesado
    renderIntegrantesPara(interesado.idPersona, div);
  });

  // ---- listeners internos (se recrean cada vez que re-renderizás) ----

  const botonesAprobar      = document.querySelectorAll(".btn-aprobar");
  const botonesRechazar     = document.querySelectorAll(".btn-rechazar");
  const botonesEntrevista   = document.querySelectorAll(".btn-asignar-entrevista");
  const botonesRechSol      = document.querySelectorAll('.btn-rechazar-solicitud');
  const botonesAprobSol     = document.querySelectorAll(".btn-aprobar-solicitud");
  const botonesPagoInicial  = document.querySelectorAll('.btn-asignar-pago-inicial');

  // abrir modal rechazar interesado
  botonesRechSol.forEach(boton => {
    boton.addEventListener('click', () => {
      const confirmar = modalConfirm.querySelector('.btn-confirmar-rechazo');
      confirmar.dataset.id = boton.dataset.id;
      modalConfirm.style.display = 'flex';
    });
  });

  // confirmar rechazo interesado (modal)
  const botonesConfirmarRechazo = modalConfirm.querySelectorAll('.btn-confirmar-rechazo');
  botonesConfirmarRechazo.forEach(boton => {
    boton.onclick = async () => {
      modalConfirm.style.display = 'none';
      const idPersona = boton.dataset.id;
      delete boton.dataset.id;

      const datos = { idPersona };

      try {
        const respuesta = await rechazarInteresado(datos);
        if (respuesta.status === 'exito') {
          await Swal.fire({
            icon: 'success',
            title: 'Interesado rechazado',
            text: 'El interesado fue rechazado y eliminado con éxito.',
            confirmButtonText: 'Aceptar'
          });
          interesados = interesados.filter(i => String(i.idPersona) !== String(idPersona));
          actualizarSolicitudes(interesados);
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: respuesta?.message ?? 'No se pudo rechazar al interesado.',
            confirmButtonText: 'Aceptar'
          });
        }
      } catch (error) {
        console.error('Error al eliminar el interesado', error);
        await Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'Ocurrió un error en el servidor.',
          confirmButtonText: 'Aceptar'
        });
      }
    };
  });

  // asignar entrevista
  botonesEntrevista.forEach(boton => {
    boton.addEventListener("click", async () => {
      const idPersona = boton.dataset.id;
      const fecha = document.getElementById('fecha' + idPersona).value;
      const hora  = document.getElementById('hora'  + idPersona).value;

      if (!fecha || !hora) {
        await Swal.fire({
          icon: 'warning',
          title: 'Datos incompletos',
          text: 'Completá fecha y hora antes de asignar la entrevista.',
          confirmButtonText: 'Aceptar'
        });
        return;
      }

      const datos = { idPersona, fecha, hora };

      try {
        const respuesta = await asignarEntrevista(datos);
        if (respuesta.status === "exito") {
          await Swal.fire({
            icon: 'success',
            title: 'Entrevista asignada',
            text: 'La entrevista se asignó con éxito.',
            confirmButtonText: 'Aceptar'
          });
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: respuesta?.message ?? 'No se pudo asignar la entrevista.',
            confirmButtonText: 'Aceptar'
          });
        }
      } catch (error) {
        console.error("Error al asignar la entrevista", error);
        await Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'Ocurrió un error en el servidor.',
          confirmButtonText: 'Aceptar'
        });
      }
    });
  });

  // aprobar estados (entrevista, antecedentes, pago inicial)
  botonesAprobar.forEach(boton => {
    boton.addEventListener("click", async () => {
      const idPersona = boton.dataset.id;
      const campo     = boton.dataset.campo;
      const datos = { idPersona, campo };

      try {
        const respuesta = await aprobarEstado(datos);
        if (respuesta.status === "exito") {
          interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Aprobado");
          actualizarSolicitudes(interesados);
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: respuesta?.message ?? 'No se pudo aprobar el estado.',
            confirmButtonText: 'Aceptar'
          });
        }
      } catch (error) {
        console.error("Error al aprobar estado:", error);
        await Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'Ocurrió un error al aprobar el estado.',
          confirmButtonText: 'Aceptar'
        });
      }
    });
  });

  // rechazar estados
  botonesRechazar.forEach(boton => {
    boton.addEventListener("click", async () => {
      const idPersona = boton.dataset.id;
      const campo     = boton.dataset.campo;
      const datos = { idPersona, campo };

      try {
        const respuesta = await rechazarEstado(datos);
        if (respuesta.status === "exito") {
          interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Rechazado");
          actualizarSolicitudes(interesados);
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: respuesta?.message ?? 'No se pudo rechazar el estado.',
            confirmButtonText: 'Aceptar'
          });
        }
      } catch (error) {
        console.error("Error al rechazar estado:", error);
        await Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'Ocurrió un error al rechazar el estado.',
          confirmButtonText: 'Aceptar'
        });
      }
    });
  });

  // abrir modal de pago inicial
  botonesPagoInicial.forEach(boton => {
    boton.addEventListener('click', function () {
      btnConfirmarPago.dataset.id = boton.dataset.id;
      modalPago.style.display = 'flex';
    });
  });

  // aprobar interesado (solicitud completa)
  botonesAprobSol.forEach(boton => {
    boton.addEventListener("click", async () => {
      const idPersona = boton.dataset.id;
      const rec = interesados.find(p => String(p.idPersona) === String(idPersona));

      // si no tiene unidad, mostrar modal SweetAlert y no seguir
      if (!rec || !(rec.unidadHabitacionalAsignada == 1 || rec.unidadAsignada == 1)) {
        await Swal.fire({
          icon: 'warning',
          title: 'Falta unidad habitacional',
          text: 'Asigná una unidad habitacional antes de aprobar la solicitud.',
          confirmButtonText: 'Aceptar'
        });
        return;
      }

      // confirmación de aprobación
      const result = await Swal.fire({
        icon: 'question',
        title: '¿Aprobar solicitud?',
        text: 'Esta acción aprobará definitivamente al interesado.',
        showCancelButton: true,
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar'
      });

      if (!result.isConfirmed) return;

      try {
        const respuesta = await aprobarInteresado({ idPersona });
        if (respuesta.status === "exito") {
          await Swal.fire({
            icon: 'success',
            title: 'Interesado aprobado',
            text: 'La solicitud fue aprobada con éxito.',
            confirmButtonText: 'Aceptar'
          });
          interesados = interesados.filter(p => String(p.idPersona) !== String(idPersona));
          actualizarSolicitudes(interesados);
        } else {
          await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: respuesta?.message ?? 'No se pudo aprobar al interesado.',
            confirmButtonText: 'Aceptar'
          });
        }
      } catch (err) {
        console.error("Error al aprobar el interesado", err);
        await Swal.fire({
          icon: 'error',
          title: 'Error del servidor',
          text: 'Ocurrió un error al aprobar al interesado.',
          confirmButtonText: 'Aceptar'
        });
      }
    });
  });
}

// ---------------- Confirmar pago inicial (modal) ----------------
btnConfirmarPago.addEventListener('click', async function () {
  modalPago.style.display = 'none';
  const idPersona = btnConfirmarPago.dataset.id;
  delete btnConfirmarPago.dataset.id;

  const montoPagoInicial = document.getElementById('inputPagoInicial').value;
  if (!montoPagoInicial) {
    await Swal.fire({
      icon: 'warning',
      title: 'Monto inválido',
      text: 'Ingresá un monto válido para el pago inicial.',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  const datos = {
    idPersona,
    montoPagoInicial
  };

  try {
    const respuesta = await asignarPagoInicial(datos);
    if (respuesta.status === 'exito') {
      await Swal.fire({
        icon: 'success',
        title: 'Pago inicial asignado',
        text: 'El pago inicial se asignó correctamente.',
        confirmButtonText: 'Aceptar'
      });
    } else {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: respuesta?.message ?? 'No se pudo asignar el pago inicial.',
        confirmButtonText: 'Aceptar'
      });
    }
  } catch (error) {
    console.error('Error al asignar pago inicial', error);
    await Swal.fire({
      icon: 'error',
      title: 'Error del servidor',
      text: 'Ocurrió un error en el servidor.',
      confirmButtonText: 'Aceptar'
    });
  }
});

// ---------------- Modal Asignar Unidad ----------------
btnCancelarUnidad.addEventListener('click', function () {
  modalUnidad.style.display = 'none';
  delete btnConfirmarUnidad.dataset.idPersona;
  delete btnConfirmarUnidad.dataset.ci;
  selectUnidad.value = '';
  btnConfirmarUnidad.disabled = true;
  infoUnidad.textContent = '';
});

// Abrir modal y cargar unidades libres (delegado)
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.btn-asignar-unidad');
  if (!btn) return;

  const ci        = btn.dataset.ci;
  const idPersona = btn.dataset.idpersona;

  if (!ci) {
    await Swal.fire({
      icon: 'error',
      title: 'Datos faltantes',
      text: 'No se encontró la cédula del interesado.',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  // si ya tiene unidad asignada, no dejamos abrir el modal
  const rec = interesados.find(p => String(p.idPersona) === String(idPersona));
  if (rec && (rec.unidadHabitacionalAsignada == 1 || rec.unidadAsignada == 1)) {
    await Swal.fire({
      icon: 'info',
      title: 'Unidad ya asignada',
      text: 'Este interesado ya tiene una unidad asignada. No se puede asignar otra.',
      confirmButtonText: 'Aceptar'
    });
    return;
  }

  btnConfirmarUnidad.dataset.ci = ci;
  if (idPersona) btnConfirmarUnidad.dataset.idPersona = idPersona;

  modalUnidad.style.display = 'flex';

  try {
    const resp = await getUnidadesLibres();
    const crudos = Array.isArray(resp?.message) ? resp.message
                : Array.isArray(resp?.data)     ? resp.data
                : Array.isArray(resp)           ? resp
                : [];
    cacheUnidadesLibres = normalizarUnidades(crudos);

    if (!cacheUnidadesLibres.length) {
      selectUnidad.innerHTML = '<option value="">No hay unidades libres</option>';
      btnConfirmarUnidad.disabled = true;
      infoUnidad.textContent = '—';
    } else {
      const opts = ['<option value="">Seleccioná una unidad…</option>']
        .concat(cacheUnidadesLibres.map(u => {
          const label = `U#${u.id} • Puerta ${u.puerta} • Pasillo ${u.pasillo} • ${u.habitaciones} hab • ${u.estado}`;
          return `<option value="${u.id}">${label}</option>`;
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
    const ci        = btnConfirmarUnidad.dataset.ci;
    const idPersona = btnConfirmarUnidad.dataset.idPersona;
    const idUnidad  = selectUnidad.value;

    if (!ci) {
      await Swal.fire({
        icon: 'error',
        title: 'Datos faltantes',
        text: 'Falta la cédula del interesado.',
        confirmButtonText: 'Aceptar'
      });
      return;
    }
    if (!idUnidad) {
      await Swal.fire({
        icon: 'warning',
        title: 'Unidad no seleccionada',
        text: 'Seleccioná una unidad válida.',
        confirmButtonText: 'Aceptar'
      });
      return;
    }

    const resp = await asignarUnidadHabitacional({ ci: String(ci), idUnidad: Number(idUnidad) });

    if (resp?.status === 'exito') {
      await Swal.fire({
        icon: 'success',
        title: 'Unidad asignada',
        text: 'La unidad habitacional se asignó correctamente.',
        confirmButtonText: 'Aceptar'
      });

      // marcar flags en memoria
      interesados = interesados.map(p =>
        String(p.idPersona) === String(idPersona)
          ? { ...p, unidadHabitacionalAsignada: 1, unidadAsignada: 1 }
          : p
      );

      // actualizar texto visible "Unidad: Asignada"
      const estadoSpan = document.querySelector(`.estado-unidad[data-id="${idPersona}"]`);
      if (estadoSpan) estadoSpan.textContent = 'Asignada';

      // habilitar botón "Aprobar Solicitud" si estaba bloqueado
      const btnAprobarSolicitud = document.querySelector(`.btn-aprobar-solicitud[data-id="${idPersona}"]`);
      if (btnAprobarSolicitud) {
        btnAprobarSolicitud.disabled = false;
        btnAprobarSolicitud.removeAttribute('title');
        btnAprobarSolicitud.classList.remove('btn-aprobar-solicitud--bloqueada');
      }

      // cerrar y limpiar modal
      modalUnidad.style.display = 'none';
      delete btnConfirmarUnidad.dataset.idPersona;
      delete btnConfirmarUnidad.dataset.ci;
      selectUnidad.value = '';
      btnConfirmarUnidad.disabled = true;
      infoUnidad.textContent = '';
    } else {
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: resp?.message || 'No se pudo asignar la unidad.',
        confirmButtonText: 'Aceptar'
      });
    }
  } catch (e) {
    console.error('Error al asignar unidad', e);
    await Swal.fire({
      icon: 'error',
      title: 'Error del servidor',
      text: 'Ocurrió un error al asignar la unidad.',
      confirmButtonText: 'Aceptar'
    });
  }
});
