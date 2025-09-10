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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <a href="#"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu activo">
                        <a href="proyectos.php"><i class="material-icons">apartment</i> Proyectos</a>
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
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Gestión de <span class="nombre-usuario-destacado">Proyectos</span></h1>
                <p>Administra los proyectos de construcción de la cooperativa</p>
            </header>

            <!-- Botones de acción -->
            <div class="barra-acciones">
                <button class="btn-primario" id="btnNuevoProyecto">
                    <i class="material-icons">add</i> Nuevo Proyecto
                </button>
                <div class="buscador">
                    <input type="text" placeholder="Buscar proyectos..." id="buscadorProyectos">
                    <i class="material-icons">search</i>
                </div>
            </div>

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
                            <th>Ubicación</th>
                            <th>Unidades</th>
                            <th>Progreso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProyectos">
                        <!-- Los proyectos se cargarán dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>
        </main>
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
    </script>
</body>
</html>