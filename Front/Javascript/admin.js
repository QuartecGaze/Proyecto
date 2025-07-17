import { getInteresados } from '../../BackEnd/APIFetchs/APIUsuario.js';
try {
    const data = await getInteresados();

    if (data.status === "exito") {
        const interesados = Object.values(data.message);
        interesados.forEach(interesado => {
            const div = document.createElement("div");
            div.innerHTML = `
            <div class="contenedor-solicitud">
                <div class="contenido">
                    <div class="solicitud-header">
                        <h2>Solicitud Nr #0001 <span class="estado-badge pendiente">PENDIENTE</span> </h2>
                        <p>Fecha de solicitud 00/00/0000</p>
                        <button class="btn-solicitud" id="btn-rechazar-solicitud">
                            <i class="material-icons">block</i> Rechazar Solicitud
                        </button>
                        <button class="btn-solicitud" id="btn-aprobar-solicitud">
                            <i class="material-icons">check_circle</i> Aprobar Solicitud
                        </button>
                    </div>

                    <div class="solicitud-info">
                        <div class="info-card" id="info-card-id">
                            <h3>Información Personal</h3>
                            <p><strong>Nombre: </strong>${interesado.nombre} ${interesado.apellido}</p>
                            <p><strong>CI: </strong>${interesado.ci}</p>
                            <p><strong>Mail: </strong>${interesado.email}</p>s
                            <p><strong>Telefono: </strong>${interesado.telefono}</p>
                        </div>
                        <div class="date info-card">
                            <h3>Asignar Fecha de Entrevista</h3>
                            <div class="calendario">
                                <p><strong>Fecha: </strong> </p>
                                <input type="date">
                            </div>
                            <div class="hora">
                                <p><strong>Hora: </strong></p>
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
                        <button id="btn-rechazar">
                            <i class="material-icons">close</i> Rechazar
                        </button>
                        <button id="btn-aprobar">
                            <i class="material-icons">check</i> Aprobar
                        </button>
                    </div>


                    <div class="solicitud documentos">
                        <h3>Documentos Adjuntos</h3>
                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Antecedentes Penales</h4>
                                <p>Documento PDF - <span class="estado-badge pendiente">PENDIENTE</span></p>
                            </div>
                            <div class="documento-acciones">
                                <a href="#">
                                    <li class="material-icons">download</li> Descargar
                                </a>
                            </div>
                        </div>
                        <div class="acciones">
                            <button id="btn-rechazar">
                                <i class="material-icons">close</i> Rechazar
                            </button>
                            <button id="btn-aprobar">
                                <i class="material-icons">check</i> Aprobar
                            </button>
                        </div>


                        <div class="documento-card">
                            <div class="documento-info">
                                <h4>Comprobante de Pago Inicial</h4>
                                <p>Documento PDF - <span class="estado-badge pendiente">PENDIENTE</span></p>
                            </div>
                            <div class="documento-acciones">
                                <a href="#">
                                    <li class="material-icons">download</li> Descargar
                                </a>
                            </div>
                        </div>
                        <div class="acciones">
                            <button id="btn-rechazar">
                                <i class="material-icons">close</i> Rechazar
                            </button>
                            <button id="btn-aprobar">
                                <i class="material-icons">check</i> Aprobar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="contador">
                    <div class="segmento" id="seg-1"></div>
                    <div class="segmento" id="seg-2"></div>
                    <div class="segmento" id="seg-3"></div>
                </div>
            </div>
            `;
            const contenedor = document.getElementById("contenedor-solicitudes");
            contenedor.appendChild(div);
          });
        } else {

        }
} catch (error){
    throw new Error("error en la api: " + error.message);
}