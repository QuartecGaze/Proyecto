import { getInteresados } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarEstado, aprobarInteresado } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarEstado } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarInteresado } from '../../BackEnd/APIFetchs/APIBackOffice.js'; 
import { asignarEntrevista } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { getAdmin } from '../../BackEnd/APIFetchs/APIBackOffice.js';
import { getIdSesion } from '../../BackEnd/APIFetchs/APIBackOffice.js';

const nombre = document.querySelectorAll(".nombreAdmin");
const foto = document.querySelectorAll(".fotoPerfil");
const email = document.getElementById("emailAdmin");
const telefono = document.getElementById("telefonoAdmin");
const permisos = document.getElementById("permisosAdmin");
const creacion = document.getElementById("creacionAdmin");

const fotoruta = "../../Recursos/FotosDePerfil/";
const idSesion = await getIdSesion();
const data = await getAdmin(idSesion.message);
setDatos(data.message);


function setDatos(data) {
    nombre.forEach(nombreDiv => {
        nombreDiv.textContent=data.nombre+" "+data.apellido;
    });
    
    foto.forEach(fotoDiv => {
        fotoDiv.src = fotoruta+data.foto;
    });

    email.textContent = data.email;
    telefono.textContent = data.telefono;
    permisos.textContent = data.permisos; //revisar si esta bien el nombre de data.
    creacion.textContent = data.creacion; //revisar si esta bien el nombre de data.
}




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
                                <p><strong>Fecha: </strong> ${interesado.fechaEntrevista  ?? 'Sin asignar'}</p>
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
                                <h4>Comprobante de Pago Inicial</h4>
                                <p>Documento PDF - <span class="estado-badge ${interesado.estadoPagoInicial}">${interesado.estadoPagoInicial}</span></p>
                            </div>
                            <div class="documento-acciones">
                                ${interesado.pagoInicial != null && interesado.pagoInicial !== "" ? `
                        <a href="../../Recursos/Antecedentes/${interesado.pagoInicial}" download>
                            <li class="material-icons">download</li> Descargar
                            </a>
                            ` : `
                            <p><em>No se adjuntó archivo</em></p>
                            `}
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
            botonAprobarInteresado.forEach(boton =>{boton.addEventListener("click", async () => { 
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
            const botonRechazarInteresado = document.querySelectorAll(".btn-rechazar-solicitud");
            botonRechazarInteresado.forEach(boton =>{boton.addEventListener("click", async () => { 
                const idPersona = boton.dataset.id;
                
                const datos = {
                    idPersona: idPersona
                };

                try {
                    const respuesta = await rechazarInteresado(datos);
        
                    if (respuesta.status === "exito") {
                        alert("Interesado rechazado y eliminado con exito.");

                    } else {
                        alert("Error " + respuesta.message);
                    }
                } catch (error) {
                    console.error("Error al eliminar el interesado", error)
                    alert("Error del servidor");
                }
            });
        });
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
