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
                        <p class="tarjeta-valor" id="pagosAtrasadosCantidad"></p>
                        <p class="tarjeta-subtexto">Cantidad total</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">attach_money</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Monto Total</h3>
                        <p class="tarjeta-valor" id="pagosAtrasadosTotal"></p>
                        <p class="tarjeta-subtexto">Por pagar</p>
                    </div>
                </div>

                <div class="tarjeta-dashboard">
                    <div class="tarjeta-icono">
                        <i class="material-icons">event</i>
                    </div>
                    <div class="tarjeta-contenido">
                        <h3>Ultimo Pago</h3>
                        <p class="tarjeta-valor" id="pagoMensual"></p>
                        <p class="tarjeta-subtexto">Monto del ultimo pago</p>
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
                            <option value="enespera">En Espera</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-mes">Tipo de Pago</label>
                        <select name="filtro-mes" id="filtro-mes">
                            <option value="Todos">Todos</option>
                            <option value="mensual" selected>Mensual</option>
                            <option value="otros">Otros</option>
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
                                <th>Fecha del Pago</th>
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
                                <td>
                                    <button class="boton-icono" id="subirComprobante" title="Pagar">
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
    <script src="../Javascript/FrontUsuario/pagos.js" type="module"></script>
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