<?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Borrar Unidad</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Css/borrarUnidad.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="contenedor-principal">
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
                            <i class="material-icons">apartment</i> Proyectos
                        </a>
                        <ul class="submenu">
                            <a href="proyectos.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
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
                            <li><a href="configuracion.php"><i class="material-icons">person</i> Mi Perfil</a></li>
                            <li><a href="crearAdmin.php"><i class="material-icons">add</i> Crear Admin</a></li>
                            <li><a href="borrarAdmin.php"><i class="material-icons">remove</i> Borrar Admin</a></li>
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
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="contenido-derecho">
            <header class="header-principal">
                <h1>Borrar <span class="nombre-usuario-destacado">Unidad Habitacional</span></h1>
                <p>Eliminar una unidad del sistema</p>
            </header>

            <div class="contenedor-busqueda">
                <h2 class="titulo-seccion">Buscar unidad a eliminar</h2>

                <div class="grupo-busqueda">
                    <div class="buscador">
                        <i class="material-icons">search</i>
                        <input type="text" id="buscarUnidad"
                            placeholder="Buscar por número de puerta, pasillo o CI">
                    </div>

                    <!-- Select para filtrar por tipo de unidad -->
                    <div class="filtro-tipo">
                        <select id="filtroTipo">
                            <option value="todos">Todos</option>
                            <option value="pasillo">Pasillo</option>
                            <option value="num-puerta">Numero de Puerta</option>
                            <option value="ci">CI</option>
                        </select>
                    </div>

                    <button class="btn-buscar">
                        <i class="material-icons">search</i> Buscar
                    </button>
                </div>

                <div id="mensajeError" class="mensaje mensaje-error">
                    <i class="material-icons">error</i>
                    <span>No se encontraron unidades con los criterios de búsqueda.</span>
                </div>
            </div>

            <div class="contenedor-resultados">
                <h3>Resultados de búsqueda</h3>

                <table class="tabla-unidades">
                    <thead>
                        <tr>
                            <th>Número de Puerta</th>
                            <th>Pasillo</th>
                            <th>CI</th>
                            <th>Habitaciones</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultadosUnidades">
                        <tr>
                            <td>101</td>
                            <td>A</td>
                            <td>57051830</td>
                            <td>3</td>
                            <td>Disponible</td>
                            <td class="acciones-unidad">
                                <button class="btn-eliminar" data-id="101">
                                    <i class="material-icons">delete</i> Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div id="sinResultados" class="sin-resultados" style="display: none;">
                    <i class="material-icons">search_off</i>
                    <h3>No se encontraron unidades</h3>
                    <p>Intenta con otros términos de búsqueda</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal" id="modalConfirmacion">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Confirmar eliminación</h2>
                <span class="cerrar-modal">&times;</span>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la unidad <strong id="unidadEliminar">#101</strong>? Esta acción
                    no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar cerrar-modal">Cancelar</button>
                <button class="btn-confirmar" id="confirmarEliminacion">Sí, eliminar</button>
            </div>
        </div>
    </div>

    <div id="mensajeExito" class="mensaje mensaje-exito">
        <i class="material-icons">check_circle</i>
        <span>La unidad ha sido eliminada exitosamente.</span>
    </div>

    <script>
        // Manejo de menús desplegables
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function (e) {
                if (this.nextElementSibling && this.nextElementSibling.classList.contains("submenu")) {
                    e.preventDefault();
                    this.parentElement.classList.toggle("open");
                }
            });
        });

    </script>
</body>

</html>