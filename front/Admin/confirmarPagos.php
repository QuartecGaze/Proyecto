<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Comprobantes de Pago</title>
    <link rel="stylesheet" href="../Css/estilosAdmin.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosConfirmar.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">payments</i> Pagos
                        </a>
                        <ul class="submenu">
                            <li class="activo"><a href="confirmarPagos.php">Confirmar Comprobantes</a></li>
                            <li><a href="pagos.php">Generar Pago</a></li>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="solicitudes.php"><i class="material-icons">email</i> Solicitudes</a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
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
                <h1>Comprobantes de Pago Enviados</h1>
                <p>Gestiona los comprobantes de pago enviados por los socios</p>
            </header>

            <div class="contenedor-filtros-pendientes">
                <div class="tarjeta-filtros">
                    <h2>Filtros</h2>
                    <div class="lista-filtros">
                        <div class="grupo-filtro">
                            <label for="filtro-estado">Estado:</label>
                            <select id="filtro-estado" name="filtro-estado">
                                <option value="todos">Todos</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="aprobado">Aprobados</option>
                                <option value="rechazado">Rechazados</option>
                            </select>
                        </div>

                        <div class="grupo-filtro">
                            <label for="filtro-fecha">Fecha:</label>
                            <input type="date" id="filtro-fecha" name="filtro-fecha">
                        </div>

                        <div class="grupo-filtro">
                            <label for="buscar-cedula">Buscar por CI:</label>
                            <input type="text" id="buscar-cedula" name="buscar-cedula" placeholder="Ej: 12345678">
                        </div>
                        
                        <div class="grupo-filtro">
                            <button class="boton-primario" id="aplicar-filtros">
                                <i class="material-icons">filter_list</i> Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="tarjeta-pendientes">
                    <div class="tarjeta-icono">
                        <i class="material-icons">pending_actions</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Pagos Pendientes</h3>
                        <p class="tarjeta-valor">3</p>
                        <p class="tarjeta-subtexto">Cantidad total</p>
                    </div>
                </div>
            </div>

            <!-- Lista de comprobantes -->
            <section class="seccion-historial">
                <div class="encabezado-seccion">
                    <h2>Comprobantes Enviados</h2>
                    <div class="acciones-seccion">
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-horas">
                        <thead>
                            <tr>
                                <th>Fecha de Envío</th>
                                <th>Usuario</th>
                                <th>Cédula</th>
                                <th>Motivo</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15/11/2023</td>
                                <td>Juan Pérez</td>
                                <td>V-12345678</td>
                                <td>Pago de cuota mensual</td>
                                <td>1,500.00 $</td>
                                <td><span class="estado estado-pendiente">Pendiente</span></td>
                                <td>
                                    <div class="contenedor-acciones">
                                        <button class="boton-icono descargar-comprobante" title="Descargar comprobante"
                                            data-id="1">
                                            <i class="material-icons">download</i>
                                        </button>
                                        <button class="boton-icono aprobar-comprobante" title="Aprobar comprobante"
                                            data-id="1">
                                            <i class="material-icons">check_circle</i>
                                        </button>
                                        <button class="boton-icono rechazar-comprobante" title="Rechazar comprobante"
                                            data-id="1">
                                            <i class="material-icons">cancel</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>01/11/2023</td>
                                <td>Ana López</td>
                                <td>V-55667788</td>
                                <td>Pago por bono productividad</td>
                                <td>1,800.00 $</td>
                                <td><span class="estado estado-pendiente">Pendiente</span></td>
                                <td>
                                    <div class="contenedor-acciones">
                                        <button class="boton-icono descargar-comprobante" title="Descargar comprobante"
                                            data-id="4">
                                            <i class="material-icons">download</i>
                                        </button>
                                        <button class="boton-icono aprobar-comprobante" title="Aprobar comprobante"
                                            data-id="4">
                                            <i class="material-icons">check_circle</i>
                                        </button>
                                        <button class="boton-icono rechazar-comprobante" title="Rechazar comprobante"
                                            data-id="4">
                                            <i class="material-icons">cancel</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="paginacion">
                    <button class="boton-icono" disabled>
                        <i class="material-icons">chevron_left</i>
                    </button>
                    <span class="pagina-actual">Página 1 de 3</span>
                    <button class="boton-icono">
                        <i class="material-icons">chevron_right</i>
                    </button>
                </div>
            </section>

            <div id="modal-comentario" class="modal" style="display: none;">
                <div class="modal-contenido">
                    <span class="cerrar-modal">&times;</span>
                    <h3>Gestionar Comprobante</h3>
                    <form id="form-gestion-comprobante">
                        <input type="hidden" id="comprobante-id" name="comprobante_id">
                        <div class="grupo-formulario">
                            <label for="comentario-administrador">Comentario (opcional):</label>
                            <textarea id="comentario-administrador" name="comentario" rows="4"
                                placeholder="Ingrese un comentario sobre la decisión"></textarea>
                        </div>
                        <div class="botones-accion">
                            <button type="button" id="confirmar-aprobar" class="boton-primario">Aprobar</button>
                            <button type="button" id="confirmar-rechazar" class="boton-secundario">Rechazar</button>
                            <button type="button" id="cancelar-accion" class="boton-neutral">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/comprobantes.js" type="module"></script>
</body>

</html>