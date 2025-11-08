import { getPagosPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarPago } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarPago } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const pagosPendientes = document.getElementById("pagosPendientes");
const dataCoop = await getPagosPendientes();
setDatosCooperativa(dataCoop.message);

function setDatosCooperativa(data) {
    pagosPendientes.textContent = data.comprobantesPendientes;
    renderPagos(data);
}

function renderPagos(dataMessage) {
  const tbody = document.querySelector('.tabla-horas tbody');
  if (!tbody) return;

  const lista = Object.values(dataMessage?.comprobantes ?? {});
  if (!lista.length) { tbody.innerHTML = ''; return; }

  tbody.innerHTML = '';
  for (const c of lista) {
    const fecha = c.fecha;
    const usuario = c.usuario;
    const cedula = c.ci;
    const motivo = c.motivo;
    const monto = c.monto;
    const estado = c.estado;
    const idComprobantePago = c.id;

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${fecha}</td>
        <td>${usuario}</td>
        <td>${cedula}</td>
        <td>${motivo}</td>
        <td>${monto}</td>
        <td>${estado}</td>
        <td>
            <div class="contenedor-acciones">
            ${c.foto && c.foto !== 'null'  ? 
            `<a href="../../Recursos/Comprobantes/${c.foto}" download>
            <button class="boton-icono descargar-comprobante" title="Descargar comprobante" data-id="${idComprobantePago}">
                <i class="material-icons">download</i>
              </button>
            </a>`
            : 
            `<button class="boton-icono descargar-comprobante" title="Descargar comprobante">
              <i class="material-icons">download</i>
            </button>`
        }
            <button class="boton-icono aprobar-comprobante" title="Aprobar comprobante" data-id="${idComprobantePago}">
                <i class="material-icons">check_circle</i>
            </button>
            <button class="boton-icono rechazar-comprobante" title="Rechazar comprobante" data-id="${idComprobantePago}">
                <i class="material-icons">cancel</i>
            </button>
            
    `;
    
    tbody.appendChild(tr);
  }
}

const botonAprobar = document.querySelectorAll(".aprobar-comprobante");
const botonRechazar = document.querySelectorAll(".rechazar-comprobante");

botonAprobar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idComprobante = boton.dataset.id;

            const datos = {
                idComprobante: idComprobante
            };

            try {
                const respuesta = await aprobarPago(datos);
                if (respuesta.status == "exito") {
                    alert("Pago Aprobado con exito");
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
            }
        });
    });

    botonRechazar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idComprobante = boton.dataset.id;

            const datos = {
                idComprobante: idComprobante
            };

            try {
                const respuesta = await rechazarPago(datos);
                if (respuesta.status == "exito") {
                    alert("Pago Rechazado con exito");
                }
            } catch (error) {
                console.error("Error al rechazar estado:", error);
            }
        });
    });