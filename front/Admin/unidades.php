<?php
require_once '../verificarSesion.php';
verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Unidadess</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosUnidades.css">
</head>

<body class="backoffice">
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/logoBack.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php"><i class="material-icons">home</i> Inicio</a>
                    </li>
                    <li class="item-menu">
                        <a href="reuniones.php"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="socios.php"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">apartment</i> Unidades Habitacionales
                        </a>
                        <ul class="submenu">
                            <a href="unidades.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">payments</i> Pagos
                        </a>
                        <ul class="submenu">
                            <li><a href="confirmarPagos.php"><i class="material-icons">receipt_long</i> Corroborar
                                    Comprobantes</a></li>
                            <li><a href="pagos.php"><i class="material-icons">point_of_sale</i> Gestor de Pagos</a></li>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="solicitudes.php"><i class="material-icons">email</i> Solicitudes</a>
                    </li>
                    <li class="item-menu">
                        <a href="faltas.php">
                            <i class="material-icons">punch_clock</i> Faltas de Horas
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">settings</i> Configuracion
                        </a>
                        <ul class="submenu">
                            <a href="configuracion.php"><i class="material-icons">star</i> Mi Perfil</a>
                            <a href="crearAdmin.php"><i class="material-icons">key</i> Crear Admin</a>
                            <a href="borrarAdmin.php"><i class="material-icons">backspace</i> Borrar Admin</a>

                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <div class="info-usuario">
                    <img src="" alt="Foto perfil" class="fotoPerfil">
                    <div>
                        <p class="nombre-usuario nombreAdmin">Admin User</p>
                        <p class="rol-usuario" id="rolAdmin">Administrador</p>
                    </div>
                </div>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i> Cerrar sesión
                    </button>
                </form>
                <button id="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i> Cambiar a Usuario
                </button>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Gestión de <span class="nombre-usuario-destacado">Unidades</span></h1>
                <p>Administra las Unidades de construcción de la cooperativa</p>
            </header>

            <!-- Estadísticas de Unidades -->
            <div class="contenedor-tarjetas">
                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">apartment</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Total de Unidades</h3>
                        <p class="tarjeta-valor" id="totalProyectos">5</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">home</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Unidades Totales</h3>
                        <p class="tarjeta-valor" id="totalUnidades">42</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">construction</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>En Construcción</h3>
                        <p class="tarjeta-valor" id="proyectosConstruccion">3</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Completados</h3>
                        <p class="tarjeta-valor" id="proyectosCompletados">2</p>
                    </div>
                </div>
            </div>

            <!-- Lista de proyectos -->
            <div class="contenedor-tabla">
                <h2>Proyectos Activas</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Numero Unidad</th>
                            <th>CI</th>
                            <th>Habitaciones</th>
                            <th>Nº Puerta</th>
                            <th>Pasillo</th>
                            <th>Estado</th>
                            <th class="checkbox-seleccion">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProyectos">
                        <tr>
                            <td>Proyecto Residencial Norte</td>
                            <td>8.765.432-1</td>
                            <td>12</td>
                            <td>321</td>
                            <td>Pasillo A3</td>
                            <td><span class="estado estado-en-proceso">En Contruccion</span></td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="1">
                            </td>
                        </tr>
                        <tr>
                            <td>Torre Central</td>
                            <td>5.432.198-7</td>
                            <td>24</td>
                            <td>123</td>
                            <td>Pasillo A3</td>
                            <td><span class="estado estado-en-proceso">En Contruccion</span></td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="2">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div class="botones-flotantes">
        <div class="boton-flotante" id="botonAcciones">
            <i class="material-icons">tune</i>
            <span class="contador-seleccionados" id="contadorSeleccionados">0</span>
        </div>

        <div class="acciones-multiples" id="accionesMultiples">
            <div class="grupo-botones">
                <button class="btn-accion btn-borrar" id="btnBorrarUnidades">
                    <i class="material-icons">delete</i> Borrar Unidades Seleccionadas
                </button>

                <div>
                    <h3>Cambiar Estado Unidades</h3>
                    <select class="select-estado" id="selectEstadoUnidad">
                        <option value="">Seleccionar estado...</option>
                        <option value="planificacion">En Espera</option>
                        <option value="construccion">En Construcción</option>
                        <option value="completado">En Pausa</option>
                    </select>
                    <button class="btn-accion btn-cambiar-estado" id="btnCambiarEstado">
                        <i class="material-icons">swap_horiz</i> Cambiar Estado
                    </button>
                </div>

                <button class="btn-accion btn-modificar" id="btnModificarUnidades">
                    <i class="material-icons">edit</i> Modificar Unidad Habitacional
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para modificar unidad -->
    <div class="modal" id="modalUnidad">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 id="tituloModal">Modificar Unidad Habitacional</h2>
                <span class="cerrar-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formUnidad">
                    <input type="hidden" id="unidadId">

                    <div class="grupo-formulario-doble">
                        <div class="grupo-formulario">
                            <label for="numeroUnidad">Número de Unidad</label>
                            <input type="text" id="numeroUnidad" readonly class="campo-no-editable">
                        </div>
                        <div class="grupo-formulario">
                            <label for="cedulaUnidad">Cédula</label>
                            <input type="text" id="cedulaUnidad" readonly class="campo-no-editable">
                        </div>
                    </div>

                    <div class="grupo-formulario-doble">
                        <div class="grupo-formulario">
                            <label for="habitacionesUnidad">Habitaciones </label>
                            <input type="number" id="habitacionesUnidad" min="1" max="10" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="estadoUnidad">Estado </label>
                            <select id="estadoUnidad" required>
                                <option value="planificacion">En Espera</option>
                                <option value="construccion">En Construcción</option>
                                <option value="completado">Completada</option>
                                <option value="suspendido">Suspendida</option>
                            </select>
                        </div>
                    </div>

                    <div class="grupo-formulario-doble">
                        <div class="grupo-formulario">
                            <label for="puertaUnidad">Número de Puerta </label>
                            <input type="text" id="puertaUnidad" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="pasilloUnidad">Pasillo </label>
                            <select id="pasilloUnidad" required>
                                <option value="Pasillo A">Pasillo A</option>
                                <option value="Pasillo B">Pasillo B</option>
                                <option value="Pasillo C">Pasillo C</option>
                                <option value="Pasillo D">Pasillo D</option>
                                <option value="Pasillo E">Pasillo E</option>
                            </select>
                        </div>
                    </div>

                    <div class="grupo-formulario">
                        <label for="observacionesUnidad">Observaciones</label>
                        <textarea id="observacionesUnidad" rows="3"
                            placeholder="Observaciones adicionales sobre la unidad..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secundario cerrar-modal">Cerrar</button>
                        <button type="submit" class="btn-primario">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>

    <script>
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
        document.addEventListener('DOMContentLoaded', function () {
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
            btnModificarUnidades.addEventListener('click', function () {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');

                if (seleccionados.length === 1) {
                    // Obtener datos de la unidad seleccionada (simulación)
                    const unidadId = seleccionados[0].value;
                    abrirModalModificar(unidadId);
                }
            });
        });

        // Función para abrir el modal con datos de la unidad
        function abrirModalModificar(unidadId) {
            // Simulación de datos - en una implementación real, esto vendría de una API
            const datosUnidad = {
                id: unidadId,
                numero: `U-00${unidadId}`,
                cedula: '8.765.432-1',
                habitaciones: 3,
                estado: 'completado',
                puerta: '101',
                pasillo: 'Pasillo A',
                observaciones: 'Unidad en buen estado, lista para entrega.'
            };

            // Llenar el formulario con los datos
            document.getElementById('unidadId').value = datosUnidad.id;
            document.getElementById('numeroUnidad').value = datosUnidad.numero;
            document.getElementById('cedulaUnidad').value = datosUnidad.cedula;
            document.getElementById('habitacionesUnidad').value = datosUnidad.habitaciones;
            document.getElementById('estadoUnidad').value = datosUnidad.estado;
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
                const unidadId = seleccionados[0].value;
                abrirModalModificar(unidadId);
            } else if (seleccionados.length > 1) {
                alert('Por favor, seleccione solo una unidad para modificar.');
            } else {
                alert('Por favor, seleccione una unidad para modificar.');
            }
        });

        // Función para abrir el modal con datos de la unidad
        function abrirModalModificar(unidadId) {
            // Simulación de datos - en una implementación real, esto vendría de una API
            const datosUnidad = {
                id: unidadId,
                numero: `U-00${unidadId}`,
                cedula: '8.765.432-1',
                habitaciones: 3,
                estado: 'completado',
                puerta: '101',
                pasillo: 'Pasillo A',
                observaciones: 'Unidad en buen estado, lista para entrega.'
            };

            // Llenar el formulario con los datos
            document.getElementById('unidadId').value = datosUnidad.id;
            document.getElementById('numeroUnidad').value = datosUnidad.numero;
            document.getElementById('cedulaUnidad').value = datosUnidad.cedula;
            document.getElementById('habitacionesUnidad').value = datosUnidad.habitaciones;
            document.getElementById('estadoUnidad').value = datosUnidad.estado;
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

        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                // Evita que redireccione si tiene submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });
    </script>

</body>

</html>