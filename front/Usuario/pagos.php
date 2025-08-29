<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Pagos Pendientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilosPagosUsuario.css">
</head>

<body>
    <div class="contenedor-dashboard">
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
                    <li class="item-menu">
                        <a href="horas.php"><i class="material-icons">punch_clock</i> Horas Trabajadas</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Proyectos"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu activo">
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
                </form>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Pagos Pendientes</h1>
                <p>Consulta y gestiona tus pagos pendientes con la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">
                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">pending_actions</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Pagos Pendientes</h3>
                        <p class="tarjeta-valor">3</p>
                        <p class="tarjeta-subtexto">Cantidad total</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Monto Total</h3>
                        <p class="tarjeta-valor">$28,000</p>
                        <p class="tarjeta-subtexto">Por pagar</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">event</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Próximo Vencimiento</h3>
                        <p class="tarjeta-valor">20/11/2023</p>
                        <p class="tarjeta-subtexto">Fecha más cercana</p>
                    </div>
                </div>
            </div>

            <section class="seccion-pagos">
                <h2>Detalle de Pagos Pendientes</h2>

                <div class="filtros-pagos">
                    <div class="grupo-filtro">
                        <label for="filtro-estado">Estado:</label>
                        <select name="filtro-estado" id="filtro-estado">
                            <option value="todos">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="proximo">Proximo a vencer</option>
                            <option value="vencido">Vencido</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-mes">Mes de vencimiento</label>
                        <select name="filtro-mes" id="filtro-mes">
                            <option value="Todos">Todos</option>
                            <option value="11" selected>Noviembre 2024</option>
                            <option value="12">Noviembre 2024</option>
                        </select>
                    </div>

                    <button class="boton-primario">
                        <i class="material-icons">filter_list</i> Aplicar Filtros
                    </button>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-pagos">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Fecha de Vencimiento</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Aporte mensual</td>
                                <td>$1.500</td>
                                <td>05/18/2025</td>
                                <td><span class="estado-pago estado-pendiente">Pendiente</span></td>
                                <td>
                                    <button class="boton-icono" title="Pagar">
                                        <i class="material-icons">payment</i>
                                    </button>
                                    <button class="boton-icono" title="Ver detalles">
                                        <i class="material-icons">visibility</i>
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