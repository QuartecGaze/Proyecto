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
                        </tr>`
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
                        <option value="En espera">En espera</option>
                        <option value="En pausa">En pausa</option>
                        <option value="En construccion">En construccion</option>
                        <option value="Finalizada">Finalizada</option>
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
                                <option value="En espera">En espera</option>
                                <option value="En pausa">En pausa</option>
                                <option value="En construcción">En construccion</option>
                                <option value="Finalizada">Finalizada</option>
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
    <script src="../Javascript/BackOffice/unidades.js" type="module"></script>


  

</body>

</html>