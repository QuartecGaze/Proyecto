<?php
require_once '../verificarSesion.php';
verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Proyectos</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosProyectos.css">
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
                        <a href="#"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">apartment</i> Proyectos
                        </a>
                        <ul class="submenu">
                            <a href="proyectos.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                            <a href="borrarUnidad.php"><i class="material-icons">delete</i> Borrar Unidad</a>
                            <a href="modificarUnidad.php"><i class="material-icons">edit</i> Modificar Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">payments</i> Pagos
                        </a>
                        <ul class="submenu">
                            <li><a href="confirmarPagos.php">Corroborar Comprobantes</a></li>
                            <li><a href="pagos.php">Gestor de Pagos</a></li>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="solicitudes.php"><i class="material-icons">email</i> Solicitudes</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">settings</i> Configuracion
                        </a>
                        <ul class="submenu">
                            <a href="proyectos.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
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
                <h1>Gestión de <span class="nombre-usuario-destacado">Proyectos</span></h1>
                <p>Administra los proyectos de construcción de la cooperativa</p>
            </header>

            <!-- Estadísticas de proyectos -->
            <div class="contenedor-tarjetas">
                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">apartment</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Total de Proyectos</h3>
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
                <h2>Proyectos Activos</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Habitaciones</th>
                            <th>Progreso</th>
                            <th>Estado</th>
                            <th class="checkbox-seleccion">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProyectos">
                        <tr>
                            <td>Proyecto Residencial Norte</td>
                            <td>8.765.432-1</td>
                            <td>12</td>
                            <td>
                                65%
                            </td>
                            <td><span class="estado estado-en-proceso">En Contruccion</span></td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="1">
                            </td>
                        </tr>
                        <tr>
                            <td>Torre Central</td>
                            <td>5.432.198-7</td>
                            <td>24</td>
                            <td>
                                30%
                            </td>
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
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar proyecto -->
    <div class="modal" id="modalProyecto">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 id="tituloModal">Nuevo Proyecto</h2>
                <span class="cerrar-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formProyecto">
                    <input type="hidden" id="proyectoId">

                    <div class="grupo-formulario">
                        <label for="nombreProyecto">Nombre del Proyecto *</label>
                        <input type="text" id="nombreProyecto" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="descripcionProyecto">Descripción</label>
                        <textarea id="descripcionProyecto" rows="3"></textarea>
                    </div>

                    <div class="grupo-formulario-doble">
                        <div>
                            <label for="ubicacionProyecto">Ubicación *</label>
                            <input type="text" id="ubicacionProyecto" required>
                        </div>
                        <div>
                            <label for="fechaInicio">Fecha de Inicio *</label>
                            <input type="date" id="fechaInicio" required>
                        </div>
                    </div>

                    <div class="grupo-formulario-doble">
                        <div>
                            <label for="fechaEstimadaFin">Fecha Estimada de Fin</label>
                            <input type="date" id="fechaEstimadaFin">
                        </div>
                        <div>
                            <label for="estadoProyecto">Estado *</label>
                            <select id="estadoProyecto" required>
                                <option value="planificacion">Planificación</option>
                                <option value="construccion">En Construcción</option>
                                <option value="completado">Completado</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secundario cerrar-modal">Cancelar</button>
                        <button type="submit" class="btn-primario">Guardar Proyecto</button>
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
        const selectEstado = document.getElementById('selectEstadoUnidad');
        
        // Actualizar contador de seleccionados
        function actualizarContador() {
            const seleccionados = document.querySelectorAll('.seleccion-unidad:checked').length;
            contador.textContent = seleccionados;

            if (seleccionados > 0) {
                botonAcciones.classList.add('activo');
                accionesMultiples.classList.add('mostrar');
            } else {
                accionesMultiples.classList.remove('mostrar');
                botonAcciones.classList.remove('activo'); // 🔥 resetea la rotación
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
                botonFlotante.classList.toggle('activo'); // 🔥 rota el botón (45° ↔ 0°)
            } else {
                botonFlotante.classList.remove('activo'); // 🔥 asegura que vuelva a 0°
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
    });
</script>

</body>

</html>