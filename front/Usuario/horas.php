<?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Usuario', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Horas Trabajadas</title>
    <link rel="stylesheet" href="../Css/estilosHoras.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="contenedor-dashboard">
        <!-- Sidebar (igual que antes) -->
        <aside class="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p>Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php"><i class="material-icons">home</i> Inicio</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Reuniones"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu activo">
                        <a href="horas.php"><i class="material-icons">punch_clock</i> Horas Trabajadas</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Proyectos"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="pagos.php"><i class="material-icons">payments</i> Pagos</a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <a href="configuracion.php">
                    <div class="info-usuario">
                        <img src="" alt="Foto perfil" class="fotoPerfil">
                        <div>
                            <p class="nombre-usuario nombreUsuario">Nombre User</p>
                            <p class="rol-usuario">Usuario</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i> Cerrar sesión
                    </button>
                    <button class="boton-cambiar-sesion">
                        <i class="material-icons">switch_account</i> Cambiar a Usuario
                    </button>
                </form>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Horas Trabajadas</h1>
                <p>Registra y consulta tus horas de trabajo Semanales en la cooperativa</p>
            </header>

            <!-- Fila con resumen y formulario -->
            <div class="contenedor-secciones">
                <!-- Sección de resumen -->
                <section class="seccion-resumen">
                    <h2>Resumen</h2>
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Horas Trabajadas</h3><!-- TRAER LAS HORAS TRABAJADAS EN ESTA SEMANA-->
                            <p class="tarjeta-valor" id="horasTrabajadas"><span>horas</span></p>
                        </div>
                    </div>
                    <div>
                        <p class="tarjeta-subtexto">Meta: <span id="horasObjetivo"></span> horas</p>
                        <!-- TRAER LA CANTIDAD DE HORAS QUE DEBE EL USUARIO NO LAS DE LA SEMANA PORQUE CAPAS QUE TIENE HORAS PREVIAS POR HACER-->
                        <div class="barra-progreso">
                            <div class="progreso" id="progresoHoras" style="width:0%"></div>
                        </div>
                    </div>
                </section>

                <!-- Sección de formulario -->
                <section class="seccion-formulario">
                    <h2>Registrar horas</h2>
                    <form method="POST" class="formulario-horas">
                        <div class="mensaje-exito" style="display: none;">Horas registradas correctamente.</div>
                        <div class="mensaje-error" style="display: none;">Error al registrar las horas.</div>

                        <div class="grupo-formulario">
                            <label for="horas">Horas trabajadas:</label>
                            <input type="number" id="horas" name="horas" min="1" max="12" required placeholder="Ej: 3">
                        </div>
                        <div class="grupo-formulario">
                            <p>Ingresa la cantidad de horas que trabajaste</p>
                            <p>Recorda que siempre redondeamos para <strong>abajo</strong></p>
                        </div>

                        <button type="submit" name="registrar_horas" class="boton-primario">
                            <i class="material-icons">save</i> Registrar horas
                        </button>
                    </form>
                </section>
            </div>

            <!-- Historial de horas trabajadas -->
            <section class="seccion-historial">
                <h2>Historial de horas trabajadas</h2>

                <div class="filtros-historial">

                    <div class="grupo-filtro">
                        <label for="filtro-semana">Semana:</label>
                        <select id="filtro-semana" name="filtro-semana">
                            <option value="">Todas</option>
                            <option value="46" selected>11/08/2025</option>
                            <option value="45">18/08/2025</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">

                        <label for="filtro-mes">Dia:</label>
                        <select id="filtro-mes" name="filtro-mes">
                            <option value="">Todos</option>
                            <option value="11" selected>Lunes</option>
                            <option value="10">Martes</option>
                            <option value="9">Miercoles</option>
                        </select>
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-horas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Día</th>
                                <th>Horas</th>
                                <th>Editar</th>
                                <th>Borrar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- TRAEMOS CON JS LAS LINEAS-->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para editar horas -->
    <div id="modalEditarHoras" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Editar horas trabajadas</h2>
                <button class="modal-cerrar" id="cerrarModal">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="modal-body">
                <form id="formEditarHoras" class="formulario-modal">
                    <div class="mensaje-exito" style="display: none;">Horas actualizadas correctamente.</div>
                    <div class="mensaje-error" style="display: none;">Error al actualizar las horas.</div>

                    <div class="grupo-formulario">
                        <label for="fechaEditar">Fecha:</label>
                        <input type="date" id="fechaEditar" name="fecha" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="horasEditar">Horas trabajadas:</label>
                        <input type="number" id="horasEditar" name="horas" min="1" max="12" required
                            placeholder="Ej: 3">
                    </div>


                    <div class="modal-acciones">
                        <button type="button" class="boton-secundario" id="cancelarEdicion">Cancelar</button>
                        <button type="submit" class="boton-primario" id="confirmarModal">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../Javascript/FrontUsuario/horas.js" type="module"></script>


</body>

</html>