// corroborarPagos.js
import { getPagosPendientes } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { aprobarPago } from '../../../BackEnd/APIFetchs/APIBackOffice.js';
import { rechazarPago } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

// --- Referencias DOM ---
const pagosPendientes   = document.getElementById("pagosPendientes");
const filtroEstado      = document.getElementById("filtro-estado");
const filtroFecha       = document.getElementById("filtro-fecha");
const filtroCedula      = document.getElementById("buscar-cedula");
const btnAplicarFiltros = document.getElementById("aplicar-filtros");

// Lista completa de comprobantes (sin filtrar)
let listaComprobantes = [];

// --- Init ---
const dataCoop = await getPagosPendientes();
setDatosCooperativa(dataCoop.message);

// --- Helpers ---
function actualizarContadorPendientes() {
    if (!pagosPendientes) return;
    const pendientes = listaComprobantes.filter(c =>
        (c.estado || '').toLowerCase() === 'pendiente'
    ).length;
    pagosPendientes.textContent = pendientes;
}

// --- Funciones principales ---
function setDatosCooperativa(data) {
    // contador de pendientes
    if (pagosPendientes) {
        pagosPendientes.textContent = data.comprobantesPendientes ?? 0;
    }

    // guardamos la lista original en memoria
    listaComprobantes = Object.values(data.comprobantes ?? {});

    // por defecto mostrar solo pendientes
    if (filtroEstado) filtroEstado.value = "pendiente";

    aplicarFiltros();
}

function aplicarFiltros() {
    if (!listaComprobantes.length) {
        renderPagos([]);
        return;
    }

    let filtrados = [...listaComprobantes];

    const estadoSel = (filtroEstado?.value || "todos").toLowerCase();
    const fechaSel = filtroFecha?.value || "";
    const ciBuscada = (filtroCedula?.value || "").trim();

    // Filtro por estado
    if (estadoSel !== "todos") {
        filtrados = filtrados.filter(c =>
            (c.estado || "").toLowerCase() === estadoSel
        );
    }

    // Filtro por fecha (input date devuelve YYYY-MM-DD)
    if (fechaSel) {
        filtrados = filtrados.filter(c => {
            const f = (c.fecha || "").slice(0, 10); // por si viene con hora
            return f === fechaSel;
        });
    }

    // Filtro por CI (coincidencia parcial)
    if (ciBuscada) {
        filtrados = filtrados.filter(c =>
            (c.ci || "").includes(ciBuscada)
        );
    }

    renderPagos(filtrados);
}

function renderPagos(dataMessage) {
    const tbody = document.querySelector('.tabla-horas tbody');
    if (!tbody) return;

    // dataMessage puede ser array (filtrado) o el objeto original
    let lista;
    if (Array.isArray(dataMessage)) {
        lista = dataMessage;
    } else {
        lista = Object.values(dataMessage?.comprobantes ?? {});
    }

    tbody.innerHTML = '';

    if (!lista.length) {
        return;
    }

    for (const c of lista) {
        const fecha   = c.fecha;
        const usuario = c.usuario;
        const cedula  = c.ci;
        const motivo  = c.motivo;
        const monto   = c.monto;
        const estado  = c.estado;
        const idComprobantePago = c.id;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${fecha ?? ''}</td>
            <td>${usuario ?? ''}</td>
            <td>${cedula ?? ''}</td>
            <td>${motivo ?? ''}</td>
            <td>${monto ?? ''}</td>
            <td>${estado ?? ''}</td>
            <td>
                <div class="contenedor-acciones">
                    ${
                        c.foto && c.foto !== 'null'
                        ? `
                        <a href="../../Recursos/Comprobantes/${c.foto}" download>
                            <button class="boton-icono descargar-comprobante" title="Descargar comprobante" data-id="${idComprobantePago}">
                                <i class="material-icons">download</i>
                            </button>
                        </a>`
                        : `
                        <button class="boton-icono descargar-comprobante" title="Descargar comprobante">
                            <i class="material-icons">download</i>
                        </button>`
                    }
                    <button class="boton-icono aprobar-comprobante" title="Aprobar comprobante" data-id="${idComprobantePago}">
                        <i class="material-icons">check_circle</i>
                    </button>
                    <button class="boton-icono rechazar-comprobante" title="Rechazar comprobante" data-id="${idComprobantePago}">
                        <i class="material-icons">cancel</i>
                    </button>
                </div>
            </td>
        `;

        tbody.appendChild(tr);
    }

    // --- Eventos para aprobar/rechazar (se re-asignan cada vez que se re-renderiza) ---
    const botonesAprobar  = tbody.querySelectorAll(".aprobar-comprobante");
    const botonesRechazar = tbody.querySelectorAll(".rechazar-comprobante");

    botonesAprobar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idComprobante = boton.dataset.id;
            const datos = { idComprobante };

            const result = await Swal.fire({
                icon: 'question',
                title: '¿Aprobar pago?',
                text: 'Esta acción aprobará el comprobante de pago seleccionado.',
                showCancelButton: true,
                confirmButtonText: 'Sí, aprobar',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            try {
                const respuesta = await aprobarPago(datos);
                if (respuesta.status === "exito") {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Pago aprobado',
                        text: 'El pago fue aprobado con éxito.',
                        confirmButtonText: 'Aceptar'
                    });

                    // Actualizamos el estado en la lista y volvemos a aplicar filtros
                    const item = listaComprobantes.find(c => String(c.id) === String(idComprobante));
                    if (item) item.estado = "aprobado";

                    actualizarContadorPendientes();
                    aplicarFiltros();
                } else {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta?.message ?? 'No se pudo aprobar el pago.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            } catch (error) {
                console.error("Error al aprobar estado:", error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'Ocurrió un error al aprobar el pago.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    botonesRechazar.forEach(boton => {
        boton.addEventListener("click", async () => {
            const idComprobante = boton.dataset.id;
            const datos = { idComprobante };

            const result = await Swal.fire({
                icon: 'warning',
                title: '¿Rechazar pago?',
                text: 'Esta acción marcará el comprobante como rechazado.',
                showCancelButton: true,
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            try {
                const respuesta = await rechazarPago(datos);
                if (respuesta.status === "exito") {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Pago rechazado',
                        text: 'El pago fue rechazado con éxito.',
                        confirmButtonText: 'Aceptar'
                    });

                    const item = listaComprobantes.find(c => String(c.id) === String(idComprobante));
                    if (item) item.estado = "rechazado";

                    actualizarContadorPendientes();
                    aplicarFiltros();
                } else {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta?.message ?? 'No se pudo rechazar el pago.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            } catch (error) {
                console.error("Error al rechazar estado:", error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: 'Ocurrió un error al rechazar el pago.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
}

// --- Eventos de filtros ---
if (btnAplicarFiltros) {
    btnAplicarFiltros.addEventListener("click", (e) => {
        e.preventDefault();
        aplicarFiltros();
    });
}

// opcional: aplicar filtros al apretar Enter en CI
if (filtroCedula) {
    filtroCedula.addEventListener("keyup", (e) => {
        if (e.key === "Enter") {
            aplicarFiltros();
        }
    });
}
