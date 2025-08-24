<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Pagos</title>
    <link rel="stylesheet" href="../Css/estilosPagos.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="backoffice">
    <div class="contenedor-dashboard">
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
                    <li class="item-menu activo">
                        <a href="pagos.php"><i class="material-icons">payments</i> Pagos</a>
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
                <h1>Gestión de Pagos</h1>
                <p>Realiza pagos generales o personalizados a los miembros de la cooperativa</p>
            </header>

            <!-- Sección de formularios de pago -->
            <div class="contenedor-formularios-pagos">
                <!-- Formulario de pago general -->
                <section class="formulario-pago">
                    <h3>Pago General</h3>
                    <form method="POST" class="formulario-horas">
                        <div class="mensaje-exito" style="display: none;">Pago general registrado correctamente.</div>
                        <div class="mensaje-error" style="display: none;">Error al registrar el pago.</div>

                        <div class="grupo-formulario">
                            <label for="monto-general">Monto:</label>
                            <input type="number" id="monto-general" name="monto_general" min="1" step="0.01" required
                                placeholder="Ej: 1500.00">
                        </div>

                        <div class="grupo-formulario">
                            <label for="concepto-general">Concepto:</label>
                            <input type="text" id="concepto-general" name="concepto_general" required
                                placeholder="Ej: Pago por servicios">
                        </div>

                        <button type="submit" name="pago_general" class="boton-primario">
                            <i class="material-icons">attach_money</i> Realizar Pago General
                        </button>
                    </form>
                </section>

                <!-- Formulario de pago personalizado -->
                <section class="formulario-pago">
                    <h3>Pago Personalizado</h3>
                    <form method="POST" class="formulario-horas">
                        <div class="mensaje-exito" style="display: none;">Pago personalizado registrado correctamente.
                        </div>
                        <div class="mensaje-error" style="display: none;">Error al registrar el pago.</div>

                        <div class="grupo-formulario">
                            <label for="cedula">Cédula de Identidad:</label>
                            <input type="text" id="cedula" name="cedula" required placeholder="Ej: 12345678">
                        </div>

                        <div class="grupo-formulario-doble">
                            <div class="grupo-formulario">
                                <label for="monto-personalizado">Monto:</label>
                                <input type="number" id="monto-personalizado" name="monto_personalizado" min="1"
                                    step="0.01" required placeholder="Ej: 2500.00">
                            </div>

                            <div class="grupo-formulario">
                                <label for="fecha-pago">Fecha:</label>
                                <input type="date" id="fecha-pago" name="fecha_pago" required>
                            </div>
                        </div>

                        <div class="grupo-formulario">
                            <label for="motivo">Motivo del pago:</label>
                            <textarea id="motivo" name="motivo" rows="3" required
                                placeholder="Describa el motivo del pago"></textarea>
                        </div>

                        <button type="submit" name="pago_personalizado" class="boton-primario">
                            <i class="material-icons">person</i> Realizar Pago Personalizado
                        </button>
                    </form>
                </section>
            </div>

            <!-- Historial de pagos -->
            <section class="seccion-historial">
                <h2>Historial de Pagos Realizados</h2>

                <div class="filtros-historial">
                    <div class="grupo-filtro">
                        <label for="filtro-mes">Mes:</label>
                        <select id="filtro-mes" name="filtro-mes">
                            <option value="">Todos</option>
                            <option value="11" selected>Noviembre 2023</option>
                            <option value="10">Octubre 2023</option>
                            <option value="9">Septiembre 2023</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-tipo">Tipo de pago:</label>
                        <select id="filtro-tipo" name="filtro-tipo">
                            <option value="">Todos</option>
                            <option value="general">General</option>
                            <option value="personalizado">Personalizado</option>
                        </select>
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-horas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Monto</th>
                                <th>Tipo</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15/11/2023</td>
                                <td>General</td>
                                <td>1,500.00 $</td>
                                <td>General</td>
                                <td>Pago por servicios de octubre</td>
                                <td>
                                    <button class="boton-icono" title="Ver detalles">
                                        <i class="material-icons">visibility</i>
                                    </button>
                                    <button class="boton-icono" title="Eliminar">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/11/2023</td>
                                <td>Juan Pérez (V-12345678)</td>
                                <td>2,300.00 $</td>
                                <td>Personalizado</td>
                                <td>Pago por horas extras</td>
                                <td>
                                    <button class="boton-icono" title="Ver detalles">
                                        <i class="material-icons">visibility</i>
                                    </button>
                                    <button class="boton-icono" title="Eliminar">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>05/11/2023</td>
                                <td>María García (V-87654321)</td>
                                <td>1,800.00 $</td>
                                <td>Personalizado</td>
                                <td>Bono por productividad</td>
                                <td>
                                    <button class="boton-icono" title="Ver detalles">
                                        <i class="material-icons">visibility</i>
                                    </button>
                                    <button class="boton-icono" title="Eliminar">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>