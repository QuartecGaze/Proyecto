<?php 
    require_once '../verificarSesion.php';
    verificarAcceso(['Usuario', 'Admin']);
?>
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
                <button id="boton-cambiar-sesion">
                        <i class="material-icons">switch_account</i> Cambiar a Usuario
                </button>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Pagos Pendientes</h1>
                <p>Consulta y gestiona tus pagos pendientes con la cooperativa</p>
            </header>

            <div class="contenedor-tarjetas">

                <div class="tarjeta-dashboard" id="cardPagosAtrasados">
                    <div class="tarjeta-icono" id="iconPagosAtrasados">
                        <i class="material-icons">warning</i>
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
                        <p class="tarjeta-subtexto">Monto del ultimo pago a realizar</p>
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

                    <button class="boton-primario" id="abrirModalPago">
                        <i class="material-icons">payment</i> Realizar Pago
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

    <!-- Modal para realizar pagos -->
<div id="modalPago" class="modal">
    <div class="modal-contenido">
        <div class="modal-header">
            <h2>Realizar Pago</h2>
            <button class="cerrar-modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <form id="formularioPago">
                <div class="grupo-formulario">
                    <label for="seleccionar-pago">Seleccionar pago a realizar:</label>
                    <select id="seleccionar-pago" name="seleccionar-pago" required>
                        <option value="">Seleccione un pago</option>
                        <option value="aporte-mensual">Aporte mensual - $1.500</option>
                        <option value="cuota-especial">Cuota especial - $2.000</option>
                    </select>
                </div>
                
                <div class="info-pago-seleccionado" id="info-pago">
                    <h3>Detalles del pago seleccionado</h3>
                    <div class="detalles-pago">
                        <p><strong>Concepto:</strong> <span id="detalle-concepto">-</span></p>
                        <p><strong>Monto:</strong> <span id="detalle-monto">-</span></p>
                        <p><strong>Fecha de vencimiento:</strong> <span id="detalle-vencimiento">-</span></p>
                    </div>
                </div>
                
                <div class="grupo-formulario">
                    <label for="comprobante-pago">Comprobante de pago:</label>
                    <div class="carga-archivo">
                        <input type="file" id="comprobante-pago" name="comprobante-pago" accept=".pdf,.jpg,.jpeg,.png">
                        <label for="comprobante-pago" class="boton-carga-archivo">
                            <i class="material-icons">cloud_upload</i>
                            <span>Seleccionar archivo</span>
                        </label>
                        <span class="nombre-archivo" id="nombre-archivo">Ningún archivo seleccionado</span>
                    </div>
                    <p class="texto-ayuda">Formatos aceptados: PDF, JPG, PNG (Tamaño máximo: 5MB)</p>
                </div>
                
                <div class="modal-acciones">
                    <button type="button" class="boton-secundario cerrar-modal">Cancelar</button>
                    <button type="submit" class="boton-primario">Confirmar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="../Javascript/FrontUsuario/pagos.js" type="module"></script>
        <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script>
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