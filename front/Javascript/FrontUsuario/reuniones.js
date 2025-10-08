import { getReunionesTerminadas, getReunionesPendientes } from '../../../BackEnd/APIFetchs/APICooperativa.js';

const contenedorTerminadas = document.getElementById("contenedorTerminadas");
const contenedorPendientes = document.getElementById("contenedorPendientes");
const spanReunionesPendientes = document.getElementById("reunionesPendientes");

try {
  const data = await getReunionesTerminadas();
  if (data?.status === "exito" && Array.isArray(data?.message?.reuniones)) {
    actualizarReunionesTerminadas(data.message.reuniones);
  } else {
    actualizarReunionesTerminadas([]);
  }
} catch (error) {
  console.error("Error en getReunionesTerminadas:", error);
  actualizarReunionesTerminadas([]);
}

try {
  const data = await getReunionesPendientes();
  if (data?.status === "exito" && Array.isArray(data?.message?.reuniones)) {
    actualizarReunionesPendientes(
      data.message.reuniones,
      data.message.reunionesPendientes ?? data.message.reuniones.length
    );
  } else {
    actualizarReunionesPendientes([]);
  }
} catch (error) {
  console.error("Error en getReunionesPendientes:", error);
  actualizarReunionesPendientes([]);
}

function actualizarReunionesTerminadas(lista) {
  contenedorTerminadas.innerHTML = "";
  if (!lista.length) {
    contenedorTerminadas.innerHTML = `<p class="estado-vacio">No hay reuniones finalizadas o canceladas.</p>`;
    return;
  }
  const frag = document.createDocumentFragment();
  lista.forEach((r) => {
    const div = document.createElement("div");
    div.innerHTML = `
      <div class="actividad">
        <i class="material-icons actividad-icono">event_available</i>
        <div class="actividad-detalle">
          <p>${r.titulo}</p>
          <span class="actividad-fecha">El día ${r.fecha} a las ${r.hora} en <strong>${r.lugar}</strong></span>
        </div>
      </div>
    `;
    frag.appendChild(div);
  });
  contenedorTerminadas.appendChild(frag);
}

function actualizarReunionesPendientes(lista, totalPendientes = 0) {
  if (spanReunionesPendientes) spanReunionesPendientes.textContent = totalPendientes;
  const listaTarget = contenedorPendientes.querySelector(".lista-eventos") || contenedorPendientes;
  listaTarget.innerHTML = "";

  if (!Array.isArray(lista) || !lista.length) {
    listaTarget.innerHTML = `<p class="estado-vacio">No hay reuniones pendientes.</p>`;
    return;
  }

  const frag = document.createDocumentFragment();
  lista.forEach((r) => {
    const div = document.createElement("div");
    div.innerHTML = `
      <div class="actividad">
        <i class="material-icons actividad-icono">event</i>
        <div class="actividad-detalle">
          <p>${r.titulo}</p>
          <span class="actividad-fecha">El día ${r.fecha} a las ${r.hora} en <strong>${r.lugar}</strong></span>
        </div>
      </div>
    `;
    frag.appendChild(div);
  });
  listaTarget.appendChild(frag);
}
