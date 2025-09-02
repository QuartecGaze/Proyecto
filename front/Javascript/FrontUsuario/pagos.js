import { getPagos } from '../../../BackEnd/APIFetchs/APICooperativa.js';
import { getUsuario } from '../../../BackEnd/APIFetchs/APIUsuario.js';
import { getIdSesion } from '../../../BackEnd/APIFetchs/APIUsuario.js';

const nombre = document.querySelectorAll(".nombreUsuario");
const foto = document.querySelectorAll(".fotoPerfil");
const fotoruta = "../../Recursos/FotosPerfil/";
const fotoUsuario = "usuario.webp";
const pagosAtrasadosCantidad = document.getElementById("pagosAtrasadosCantidad");
const pagosAtrasadosTotal = document.getElementById("pagosAtrasadosTotal");
const pagoMensual = document.getElementById("pagoMensual");
const cardPagosAtrasados = document.getElementById("cardPagosAtrasados");

const sesion = await getIdSesion();
const id = Number(sesion.message);
const usr = await getUsuario(id);
setDatosUsuario(usr.message);

const coop = await getPagos(id);
setDatosCooperativa(coop.message);

function setDatosUsuario(data) {
  nombre.forEach(n => { n.textContent = `${data.nombre} ${data.apellido}`; });
  foto.forEach(f => {
    f.src = fotoruta + (data.foto && data.foto !== '' ? data.foto : fotoUsuario);
  });
}

function setDatosCooperativa(data) {
  if (pagosAtrasadosCantidad) {
    pagosAtrasadosCantidad.textContent = data.pagosAtrasados;
    if (data.pagosAtrasados > 0) {
      pagosAtrasadosCantidad.classList.add("atrasado");
      cardPagosAtrasados?.classList.add("atrasado");
    } else {
      pagosAtrasadosCantidad.classList.remove("atrasado");
      cardPagosAtrasados?.classList.remove("atrasado");
    }
  }
    pagosAtrasadosTotal.textContent = '$' + data.pagosAtrasadosDinero;
    pagoMensual.textContent = '$' + data.pagoMensual;
  //para mostrar los pagos pendientes y luego vamos a poder enviarlos
    renderPagos(data);
}

//creamos una fila por cada comprobante
function renderPagos(dataMessage) {
  const tbody = document.querySelector('.tabla-pagos tbody');
  if (!tbody) return;

  const lista = Object.values(dataMessage?.comprobantesPendientes ?? {});
  if (!lista.length) { tbody.innerHTML = ''; return; }

  tbody.innerHTML = '';
  for (const c of lista) {
    const monto = c.monto;
    const estado = c.estadoPago;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${c.motivoPago}</td>
      <td>${'$' + monto}</td>
      <td>${c.mes}</td>
      <td><span class="estado-pago">${estado}</span></td>
      <td>
        <button class="boton-icono" title="Pagar" data-id="${c.idComprobantePago}">
          <i class="material-icons">payment</i>
        </button>
        <button class="boton-icono" title="Ver detalles" data-id="${c.idComprobantePago}">
          <i class="material-icons">visibility</i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  }
}
