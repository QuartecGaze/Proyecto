import { getUnidades, modificarUnidadHabitacional, cambiarEstado } from "../../../BackEnd/APIFetchs/APIBackOffice.js";

// =================== MENÚ LATERAL ===================
document.querySelectorAll(".item-menu > a").forEach(boton => {
  boton.addEventListener("click", function (e) {
    if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
      e.preventDefault();
      this.parentElement.classList.toggle("open");
      const sub = this.parentElement.querySelector(":scope > .submenu");
      if (sub) sub.style.maxHeight = this.parentElement.classList.contains("open") ? sub.scrollHeight + "px" : "0px";
    }
  });
});

// =================== ESTADOS ===================
function canonEstado(v) {
  if (typeof v === "number") {
    return ({1:"En Espera", 2:"En pausa", 3:"En Construcción", 4:"Finalizada"}[v]) || "";
  }
  const s = String(v || "")
    .normalize("NFD").replace(/\p{Diacritic}/gu, "") // saca tildes
    .toLowerCase().trim();
  if (s.includes("complet") || s.includes("final")) return "Finalizada";
  if (s.includes("constru")) return "En Construcción";
  if (s.includes("paus"))    return "En pausa";
  if (s.includes("esper"))   return "En Espera";
  return v || "";
}

function claseBadgeEstadoCanon(canon) {
  switch (canon) {
    case "Finalizada":       return "estado-badge estado-completado";
    case "En Construcción":  return "estado-badge estado-construccion";
    case "En pausa":
    case "En Espera":        return "estado-badge estado-planificacion";
    default:                 return "estado-badge";
  }
}

// =================== MAIN ===================
document.addEventListener("DOMContentLoaded", async function () {
  const data = await getUnidades();
  const unidades = Object.values(data.message || []);
  const tablaProyectos = document.getElementById("tablaProyectos");
  tablaProyectos.innerHTML = "";

  // ===== TABLA =====
  unidades.forEach(unidad => {
    const estadoCanon = canonEstado(unidad.Estado_unidad);
    const clase = claseBadgeEstadoCanon(estadoCanon);
    tablaProyectos.insertAdjacentHTML("beforeend", `
      <tr>
        <td>${unidad.ID_Unidad_habitacional}</td>
        <td>${unidad.CI || "Sin socio asignado"}</td>
        <td>${unidad.Cantidad_habitaciones}</td>
        <td><span class="${clase}">${estadoCanon || "—"}</span></td>
        <td>${unidad.Numero_puerta}</td>
        <td>Pasillo ${unidad.Pasillo}</td>
        <td class="checkbox-seleccion">
          <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad"
            data-id="${unidad.ID_Unidad_habitacional}"
            data-estado="${estadoCanon}"
            data-habitaciones="${unidad.Cantidad_habitaciones}"
            data-puerta="${unidad.Numero_puerta}"
            data-pasillo="${unidad.Pasillo}"
            data-cedula="${unidad.CI || ""}">
        </td>
      </tr>
    `);
  });

  // ===== GRÁFICAS =====
  const colores = {
    completado:   "#27ae60",
    construccion: "#3498db",
    planificacion:"#f39c12",
    suspendido:   "#e74c3c"
  };
  const estadosOrden = ["Finalizada", "En Construcción", "En Espera", "En pausa"];
  const conteoEstados = estadosOrden.reduce((a, k) => (a[k] = 0, a), {});
  const conteoHabitaciones = {};

  unidades.forEach(u => {
    const e = canonEstado(u.Estado_unidad);
    if (e in conteoEstados) conteoEstados[e]++;
    const h = Number(u.Cantidad_habitaciones);
    if (!isNaN(h)) conteoHabitaciones[h] = (conteoHabitaciones[h] || 0) + 1;
  });

  const totalUnidades = unidades.length;
  const completadas = conteoEstados["Finalizada"] || 0;

  // Pie por estado
  const ctxEstados = document.getElementById("graficoEstados")?.getContext("2d");
  if (ctxEstados) {
    const dataEstados = estadosOrden.map(k => conteoEstados[k]);
    const coloresEstados = [colores.completado, colores.construccion, colores.planificacion, colores.planificacion];
    new Chart(ctxEstados, {
      type: "doughnut",
      data: { labels: estadosOrden, datasets: [{ data: dataEstados, backgroundColor: coloresEstados, borderWidth: 2, borderColor: "#fff", hoverOffset: 15 }] },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: "60%" }
    });
    const leyendaEstados = document.getElementById("leyendaEstados");
    if (leyendaEstados) {
      leyendaEstados.innerHTML = "";
      estadosOrden.forEach((estado, i) => {
        const item = document.createElement("div");
        item.className = "item-leyenda";
        const color = document.createElement("div");
        color.className = "color-leyenda";
        color.style.backgroundColor = coloresEstados[i];
        const texto = document.createElement("span");
        texto.textContent = `${estado}: ${conteoEstados[estado]}`;
        item.append(color, texto);
        leyendaEstados.appendChild(item);
      });
    }
  }

  // Barras por habitaciones
  const ctxHabitaciones = document.getElementById("graficoHabitaciones")?.getContext("2d");
  if (ctxHabitaciones) {
    const nums = Object.keys(conteoHabitaciones).map(Number).sort((a,b)=>a-b);
    new Chart(ctxHabitaciones, {
      type: "bar",
      data: { labels: nums.map(n => `${n} hab.`), datasets: [{ data: nums.map(n => conteoHabitaciones[n]), backgroundColor: colores.construccion, borderColor: colores.construccion, borderWidth: 1 }] },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
  }

  // Progreso general
  const ctxProgreso = document.getElementById("graficoProgreso")?.getContext("2d");
  if (ctxProgreso) {
    new Chart(ctxProgreso, {
      type: "doughnut",
      data: { labels: ["Completadas", "Restantes"], datasets: [{ data: [completadas, totalUnidades - completadas], backgroundColor: [colores.completado, "#ecf0f1"], borderWidth: 0 }] },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: "75%" }
    });
    document.getElementById("totalUnidades").textContent = totalUnidades;
    document.getElementById("unidadesCompletadas").textContent = completadas;
    document.getElementById("porcentajeCompletado").textContent = (totalUnidades ? Math.round((completadas / totalUnidades) * 100) : 0) + "%";
  }

  // =================== ACCIONES MULTIPLES ===================
  const checkboxes = document.querySelectorAll(".seleccion-unidad");
  const contador = document.getElementById("contadorSeleccionados");
  const botonAcciones = document.getElementById("botonAcciones");
  const botonFlotante = document.querySelector(".boton-flotante");
  const accionesMultiples = document.getElementById("accionesMultiples");
  const btnBorrarUnidades = document.getElementById("btnBorrarUnidades");
  const btnCambiarEstado = document.getElementById("btnCambiarEstado");
  const btnModificarUnidades = document.getElementById("btnModificarUnidades");
  const selectEstado = document.getElementById("selectEstadoUnidad");

  function actualizarContador() {
    const seleccionados = document.querySelectorAll(".seleccion-unidad:checked").length;
    contador.textContent = seleccionados;
    btnModificarUnidades.style.display = seleccionados === 1 ? "flex" : "none";
    if (seleccionados > 0) {
      botonAcciones.classList.add("activo");
      accionesMultiples.classList.add("mostrar");
    } else {
      accionesMultiples.classList.remove("mostrar");
      botonAcciones.classList.remove("activo");
    }
  }
  checkboxes.forEach(cb => cb.addEventListener("change", actualizarContador));

  botonAcciones.addEventListener("click", () => {
    const seleccionados = document.querySelectorAll(".seleccion-unidad:checked").length;
    if (seleccionados > 0) {
      accionesMultiples.classList.toggle("mostrar");
      botonFlotante.classList.toggle("activo");
    } else {
      botonFlotante.classList.remove("activo");
    }
  });

  btnBorrarUnidades.addEventListener("click", () => {
    const seleccionados = document.querySelectorAll(".seleccion-unidad:checked");
    if (seleccionados.length > 0 && confirm(`¿Eliminar ${seleccionados.length} unidades?`)) {
      alert(`Se eliminarían ${seleccionados.length} unidades (simulación)`);
    }
  });

  // ==== CAMBIO DE ESTADO MASIVO ====
  function estadoMasivoANumero(valor) {
    const v = String(valor || "").toLowerCase().trim();
    if (v === "planificacion") return 1;
    if (v === "construccion") return 3;
    if (v === "completado") return 4;
    return 0;
  }
  function numeroATextoBadge(n) {
    switch (Number(n)) {
      case 1: return "En Espera";
      case 2: return "En pausa";
      case 3: return "En Construcción";
      case 4: return "Finalizada";
      default: return "—";
    }
  }
  function claseBadgeDesdeNumero(n) {
    switch (Number(n)) {
      case 4: return "estado-badge estado-completado";
      case 3: return "estado-badge estado-construccion";
      case 2:
      case 1: return "estado-badge estado-planificacion";
      default: return "estado-badge";
    }
  }

  btnCambiarEstado.addEventListener("click", async () => {
  const valorSelect = document.getElementById("selectEstadoUnidad").value;
  if (!valorSelect) return alert("Seleccione un estado.");

  const seleccionados = [...document.querySelectorAll(".seleccion-unidad:checked")];
  if (seleccionados.length === 0) return alert("Seleccione al menos una unidad.");

  const estadoNumero = Number(valorSelect);        // <<--- NUMÉRICO
  const ids = seleccionados.map(cb => Number(cb.dataset.id));

  try {
    const res = await cambiarEstado({ ids, estado: estadoNumero });
    if (res.status === "exito") {
      // refresco visual mínimo
      seleccionados.forEach(cb => {
        cb.checked = false;
        const fila = cb.closest("tr");
        const celda = fila?.children?.[3];
        if (celda) {
          const texto = ({1:"En Espera",2:"En pausa",3:"En Construcción",4:"Finalizada"})[estadoNumero] || "—";
          const clase = ({1:"estado-badge estado-planificacion",2:"estado-badge estado-planificacion",3:"estado-badge estado-construccion",4:"estado-badge estado-completado"})[estadoNumero] || "estado-badge";
          celda.innerHTML = `<span class="${clase}">${texto}</span>`;
          cb.dataset.estado = texto;
        }
      });
      document.getElementById("contadorSeleccionados").textContent = "0";
      document.getElementById("accionesMultiples").classList.remove("mostrar");
      document.getElementById("botonAcciones").classList.remove("activo");
      document.querySelector(".boton-flotante").classList.remove("activo");
      alert("Estados actualizados correctamente.");
    } else {
      alert(res.message || "Error al actualizar los estados.");
    }
  } catch (e) {
    console.error(e);
    alert("No se pudieron cambiar los estados.");
  }
});

});

// =================== MODIFICAR (modal) ===================
function normalizaTextoEstado(s) {
  return String(s || "").normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase().trim();
}
function estadoTextoANumero(texto) {
  const t = normalizaTextoEstado(texto);
  if (t.includes("esper")) return 1;
  if (t.includes("paus"))  return 2;
  if (t.includes("constru")) return 3;
  if (t.includes("final") || t.includes("complet")) return 4;
  return 0;
}
function numeroATextoSelect(n) {
  switch (Number(n)) {
    case 1: return "En espera";
    case 2: return "En pausa";
    case 3: return "En construcción";
    case 4: return "Finalizada";
    default: return "En espera";
  }
}
function abrirModalModificar({ id, cedula, habitaciones, estadoCanon, puerta, pasillo }) {
  document.getElementById("unidadId").value = id;
  document.getElementById("numeroUnidad").value = `U-${id}`;
  document.getElementById("cedulaUnidad").value = cedula || "";
  document.getElementById("habitacionesUnidad").value = Number(habitaciones) || 1;
  document.getElementById("puertaUnidad").value = puerta || "";
  document.getElementById("pasilloUnidad").value = pasillo || "";
  const select = document.getElementById("estadoUnidad");
  select.value = numeroATextoSelect(estadoTextoANumero(estadoCanon));
  document.getElementById("modalUnidad").style.display = "block";
}
document.getElementById("btnModificarUnidades").addEventListener("click", () => {
  const sel = document.querySelector(".seleccion-unidad:checked");
  if (!sel) return alert("Seleccione una unidad.");
  abrirModalModificar({
    id: sel.dataset.id,
    cedula: sel.dataset.cedula || "",
    habitaciones: sel.dataset.habitaciones,
    estadoCanon: sel.dataset.estado,
    puerta: sel.dataset.puerta,
    pasillo: sel.dataset.pasillo
  });
});
document.querySelectorAll(".cerrar-modal").forEach(b => b.addEventListener("click", () => {
  document.getElementById("modalUnidad").style.display = "none";
}));
window.addEventListener("click", e => {
  const modal = document.getElementById("modalUnidad");
  if (e.target === modal) modal.style.display = "none";
});
document.getElementById("formUnidad").addEventListener("submit", async e => {
  e.preventDefault();
  const estadoNumero = estadoTextoANumero(document.getElementById("estadoUnidad").value);
  const datosModificados = {
    id: document.getElementById("unidadId").value,
    habitaciones: document.getElementById("habitacionesUnidad").value,
    estado: estadoNumero,
    puerta: document.getElementById("puertaUnidad").value,
    pasillo: document.getElementById("pasilloUnidad").value,
    ci: document.getElementById("cedulaUnidad").value
  };
  try {
    await modificarUnidadHabitacional(datosModificados);
    document.getElementById("modalUnidad").style.display = "none";
    alert("Cambios guardados correctamente.");
  } catch (err) {
    console.error(err);
    alert("No se pudieron guardar los cambios.");
  }
});
