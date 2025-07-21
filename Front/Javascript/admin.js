import { getInteresados } from '../../BackEnd/APIFetchs/APIUsuario.js';
import { aprobarEstado } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarEstado } from '../../BackEnd/APIFetchs/APIBackOffice.js';
const contenedor = document.getElementById("contenedor-solicitudes");
try {
    const data = await getInteresados();
    let interesados = Object.values(data.message);
       if (data.status === "exito") {
            actualizarSolicitudes(interesados);
        }
     
} catch (error){
    throw new Error("error en la api: " + error.message);
}


function snakeCamel(snakeStr) {
  return snakeStr.toLowerCase().replace(/_([a-z])/g, (_, letra) => letra.toUpperCase());
}

function actualizarEstadoArray(array, idPersona, campo, nuevoValor) {
            const interesado = array.find(i => i.idPersona === idPersona);
            if (interesado) {
                interesado[campo] = nuevoValor;
            }
            return array;
        }

function actualizarSolicitudes(interesados){
     contenedor.innerHTML= "";
     interesados.forEach(interesado => {
            const div = document.createElement("div");
            div.innerHTML = `
            <div class="contenedor-solicitud">
                <div class="contenido">
                    <div class="solicitud-header">
                        <h2>Solicitud Nr#${interesado.idPersona}  </h2>
                        <button class="btn-solicitud btn-rechazar-solicitud">
                            <i class="material-icons">block</i> Rechazar Solicitud
                        </button>
                        <button class="btn-solicitud btn-aprobar-solicitud">
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
                                <p><strong>Fecha: </strong> ${interesado.fechaEntrevista  ?? 'Sin asignar'}</p>
                                <input type="date">
                            </div>
                            <div class="hora">
                                <p><strong>Hora: </strong> ${interesado.horaEntrevista ?? 'Sin asignar'}</p>
                                <input type="time">
                            </div>
                            <div class="direccion">
                                <p><strong>Direccion: </strong></p>
                                Av.Gral Rivera 3729 bis, 11600 Montevideo
                            </div>
                            <button class="btn-asignar-entrevista">
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


                    <div class="solicitud documentos">
                        <h3>Documentos Adjuntos</h3>
                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Antecedentes Penales</h4>
                                <p>Documento PDF - <span class="estado-badge ${interesado.estadoAntecedentes}">${interesado.estadoAntecedentes}</span></p>
                            </div>
                            <div class="documento-acciones">
                                <a href="#">
                                    <li class="material-icons">download</li> Descargar
                                </a>
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
                                <h4>Comprobante de Pago Inicial</h4>
                                <p>Documento PDF - <span class="estado-badge ${interesado.estadoPagoInicial}">${interesado.estadoPagoInicial}</span></p>
                            </div>
                            <div class="documento-acciones">
                                <a href="#">
                                    <li class="material-icons">download</li> Descargar
                                </a>
                            </div>
                        </div>
                        <div class="acciones">
                        <button class="btn-asignar-entrevista">     <!--Corregir hacerlo boton que haga esto no copiado y pegado de arriba=================================== --!>
                                <i class="material-icons">price_check</i> Asignar Monto
                            </button>

                            <button class="btn-rechazar btn-${interesado.estadoPagoInicial}" data-id="${interesado.idPersona}" data-campo="Estado_pago_inicial">

                                <i class="material-icons">close</i> Rechazar
                            </button>
                            <button class="btn-aprobar btn-${interesado.estadoPagoInicial}" data-id="${interesado.idPersona}" data-campo="Estado_pago_inicial">
                                <i class="material-icons">check</i> Aprobar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="contador">
                    <div class="segmento" id="${interesado.estadoEntrevista}"></div>
                    <div class="segmento" id="${interesado.estadoAntecedentes}"></div>
                    <div class="segmento" id="${interesado.estadoPagoInicial}"></div>
                </div>
            </div>
            `;
            contenedor.appendChild(div);
            });
             const botonesAprobar = document.querySelectorAll(".btn-aprobar");
            const botonesRechazar = document.querySelectorAll(".btn-rechazar");
            botonesAprobar.forEach(boton =>{
            boton.addEventListener("click", async () => {
            const idPersona = boton.dataset.id;
            const campo = boton.dataset.campo;

            const datos = {
                idPersona: idPersona,
                campo: campo
            };

            try {
                const respuesta = await aprobarEstado(datos);
                 if(respuesta.status == "exito"){
                    interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Aprobado");
                    actualizarSolicitudes(interesados);
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
            }
                });
            });
            
            botonesRechazar.forEach(boton =>{
            boton.addEventListener("click", async () => {
            const idPersona = boton.dataset.id;
            const campo = boton.dataset.campo;

            const datos = {
                idPersona: idPersona,
                campo: campo
            };

            try {
                const respuesta = await rechazarEstado(datos);
                if(respuesta.status == "exito"){
                    interesados = actualizarEstadoArray(interesados, idPersona, snakeCamel(campo), "Rechazado");
                    actualizarSolicitudes(interesados);
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
            }
        });
            });
} 
