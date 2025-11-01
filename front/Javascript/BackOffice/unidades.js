import { getUnidades } from "../../../BackEnd/APIFetchs/APIBackOffice.js";
import { modificarUnidadHabitacional } from "../../../BackEnd/APIFetchs/APIBackOffice.js";



        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                // Evita que redireccione si tiene submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

        // Funcionalidad para los checkboxes y botones flotantes
        document.addEventListener('DOMContentLoaded', async function () {
            const data = await getUnidades();
const unidades = Object.values(data.message);
const tablaProyectos = document.getElementById('tablaProyectos');

unidades.forEach(unidad => {
    tablaProyectos.innerHTML += ` <tr>
                            <td>${unidad.ID_Unidad_habitacional}</td>
                            <td>${unidad.CI || "Sin socio asignado"}</td>
                            <td>${unidad.Cantidad_habitaciones}</td>
                            <td>${unidad.Numero_puerta}</td>
                            <td>Pasillo ${unidad.Pasillo}</td>
                            <td><span class="estado estado-en-proceso">${unidad.Estado_unidad}
                            </span></td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" 
                                data-id="${unidad.ID_Unidad_habitacional}" 
                                data-estado="${unidad.Estado_unidad}" 
                                data-habitaciones="${unidad.Cantidad_habitaciones}"
                                data-puerta="${unidad.Numero_puerta}"
                                data-pasillo="${unidad.Pasillo}"
                                data-cedula="${unidad.CI || ""}">
                            </td>
                        </tr>`;
});
            const checkboxes = document.querySelectorAll('.seleccion-unidad');
            const contador = document.getElementById('contadorSeleccionados');
            const botonAcciones = document.getElementById('botonAcciones');
            const botonFlotante = document.querySelector(".boton-flotante");
            const accionesMultiples = document.getElementById('accionesMultiples');
            const btnBorrarUnidades = document.getElementById('btnBorrarUnidades');
            const btnCambiarEstado = document.getElementById('btnCambiarEstado');
            const btnModificarUnidades = document.getElementById('btnModificarUnidades');
            const selectEstado = document.getElementById('selectEstadoUnidad');
            
            // Actualizar contador de seleccionados y visibilidad del botón modificar
            function actualizarContador() {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked').length;
                contador.textContent = seleccionados;

                // Mostrar/ocultar botón de modificar según la cantidad seleccionada
                if (seleccionados === 1) {
                    btnModificarUnidades.style.display = 'flex';
                } else {
                    btnModificarUnidades.style.display = 'none';
                }

                if (seleccionados > 0) {
                    botonAcciones.classList.add('activo');
                    accionesMultiples.classList.add('mostrar');
                } else {
                    accionesMultiples.classList.remove('mostrar');
                    botonAcciones.classList.remove('activo');
                }
            }
            // Event listeners para checkboxes
            checkboxes.forEach(checkbox => {
                
                checkbox.addEventListener('change', actualizarContador);
            });

            // Mostrar/ocultar acciones múltiples con el botón flotante
            botonAcciones.addEventListener('click', function () {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked').length;
                if (seleccionados > 0) {
                    accionesMultiples.classList.toggle('mostrar');
                    botonFlotante.classList.toggle('activo');
                } else {
                    botonFlotante.classList.remove('activo');
                }
            });

            // Acción de borrar unidades seleccionadas
            btnBorrarUnidades.addEventListener('click', function () {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');
                if (seleccionados.length > 0) {
                    if (confirm(`¿Está seguro de que desea eliminar las ${seleccionados.length} unidades seleccionadas?`)) {
                        // Aquí iría la lógica para eliminar las unidades
                        alert(`Se eliminarían ${seleccionados.length} unidades (simulación)`);
                    }
                }
            });

            // Acción de cambiar estado
            btnCambiarEstado.addEventListener('click', function () {
                if (selectEstado.value) {
                    const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');
                    if (seleccionados.length > 0) {
                        if (confirm(`¿Cambiar el estado de ${seleccionados.length} unidades a "${selectEstado.options[selectEstado.selectedIndex].text}"?`)) {
                            // Aquí iría la lógica para cambiar el estado
                            alert(`Se cambiaría el estado de ${seleccionados.length} unidades a ${selectEstado.value} (simulación)`);
                        }
                    } else {
                        alert('Por favor, seleccione al menos una unidad.');
                    }
                } else {
                    alert('Por favor, seleccione un estado.');
                }
            });

            // Acción de modificar unidad (solo cuando hay exactamente 1 seleccionada)
            
        });



        // Cerrar modal
        document.querySelectorAll('.cerrar-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('modalUnidad').style.display = 'none';
            });
        });

        // Enviar formulario
        document.getElementById('formUnidad').addEventListener('submit', function (e) {
            e.preventDefault();

            // Aquí iría la lógica para guardar los cambios
            const datosModificados = {
                id: document.getElementById('unidadId').value,
                habitaciones: document.getElementById('habitacionesUnidad').value,
                estado: document.getElementById('estadoUnidad').value,
                puerta: document.getElementById('puertaUnidad').value,
                pasillo: document.getElementById('pasilloUnidad').value,
                observaciones: document.getElementById('observacionesUnidad').value
            };

            console.log('Datos a guardar:', datosModificados);
            alert('Cambios guardados correctamente (simulación)');
            document.getElementById('modalUnidad').style.display = 'none';
        });

        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', function (e) {
            const modal = document.getElementById('modalUnidad');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Funcionalidad para el menú desplegable
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                // Evita que redireccione si tiene submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });


        // Funcionalidad para abrir el modal de modificación
        document.getElementById('btnModificarUnidades').addEventListener('click', function () {
            const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');

            if (seleccionados.length === 1) {
                // Obtener datos de la unidad seleccionada (simulación)
                let unidadId = seleccionados[0].getAttribute('data-id');
                let unidadHabitaciones = seleccionados[0].getAttribute('data-habitaciones');
                let unidadEstado = seleccionados[0].getAttribute('data-estado');
                let unidadPuerta = seleccionados[0].getAttribute('data-puerta');
                let unidadPasillo = seleccionados[0].getAttribute('data-pasillo');
                let unidadCedula = seleccionados[0].getAttribute('data-cedula');
                console.log(unidadHabitaciones);
                abrirModalModificar(unidadId, unidadHabitaciones, unidadEstado, unidadPuerta, unidadPasillo, unidadCedula);
            } else if (seleccionados.length > 1) {
                alert('Por favor, seleccione solo una unidad para modificar.');
            } else {
                alert('Por favor, seleccione una unidad para modificar.');
            }
        });

        // Función para abrir el modal con datos de la unidad
        function abrirModalModificar(unidadId, unidadHabitaciones, unidadEstado, unidadPuerta, unidadPasillo, unidadCedula) {
            const datosUnidad = {
                id: unidadId,
                numero: `U-${unidadId}`,
                cedula: `${unidadCedula || "Sin socio asignado"}`,
                habitaciones: `${unidadHabitaciones}`,
                estado: `${unidadEstado}`,
                puerta: `${unidadPuerta}`,
                pasillo: `${unidadPasillo}`,
                observaciones: 'Unidad en buen estado, lista para entrega.'
            };
            console.log(datosUnidad);
            let estadoUnidad = 0;
            if(datosUnidad.estado == "En Espera"){
                estadoUnidad = 1;
            } else if (datosUnidad.estado == "En Construccion"){
                estadoUnidad = 3;
            } else if (datosUnidad.estado == "Finalizada"){
                estadoUnidad = 4
            }   else if (datosUnidad.estado == "En pausa"){
                estadoUnidad = 2;
            }
            document.getElementById('unidadId').value = datosUnidad.id;
            document.getElementById('numeroUnidad').value = datosUnidad.numero;
            document.getElementById('cedulaUnidad').value = datosUnidad.cedula;
            document.getElementById('habitacionesUnidad').value = datosUnidad.habitaciones;
            document.getElementById('estadoUnidad').selectedIndex = estadoUnidad;
            document.getElementById('puertaUnidad').value = datosUnidad.puerta;
            document.getElementById('pasilloUnidad').value = datosUnidad.pasillo;
            document.getElementById('observacionesUnidad').value = datosUnidad.observaciones;

            // Mostrar el modal
            document.getElementById('modalUnidad').style.display = 'block';
        }

        // Cerrar modal
        document.querySelectorAll('.cerrar-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('modalUnidad').style.display = 'none';
            });
        });

        // Enviar formulario
        document.getElementById('formUnidad').addEventListener('submit', async function (e) {
            e.preventDefault();
            let estado = document.getElementById('estadoUnidad').value;
            if(estado == "En Espera"){
                estadoUnidad = 1;
            } else if (estado == "En Construccion"){
                estadoUnidad = 3;
            } else if (estado == "Finalizada"){
                estadoUnidad = 4
            }   else if (estado == "En pausa"){
                estadoUnidad = 2;
            }
            const datosModificados = {
                id: document.getElementById('unidadId').value,
                habitaciones: document.getElementById('habitacionesUnidad').value,
                estado: estadoUnidad,
                puerta: document.getElementById('puertaUnidad').value,
                pasillo: document.getElementById('pasilloUnidad').value,
                //observaciones: document.getElementById('observacionesUnidad').value
            };

            let respuesta = await modificarUnidadHabitacional(datosModificados);
            alert('Cambios guardados correctamente (simulación)');
            document.getElementById('modalUnidad').style.display = 'none';
        });

        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', function (e) {
            const modal = document.getElementById('modalUnidad');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
