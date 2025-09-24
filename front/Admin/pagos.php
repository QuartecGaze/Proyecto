<?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Pagos</title>
    <link rel="stylesheet" href="../Css/estilosPagosAdmin.css">
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
                <h1>Gestión de Pagos</h1>
                <p>Realiza pagos generales o personalizados a los miembros de la cooperativa</p>
            </header>

            <!-- Sección de formularios de pago -->
            <div class="contenedor-formularios-pagos">
                <!-- Formulario de pago Mensual -->
                <section class="formulario-pago">
                    <h3>Asignar Pago Mensual</h3>
                    <form method="POST" class="formulario-horas" id="form-pago-mensual">
                        <div class="mensaje-exito" style="display: none;">Pago general registrado correctamente.</div>
                        <div class="mensaje-error" style="display: none;">Error al registrar el pago.</div>

                        <div class="grupo-formulario">
                            <label for="monto-general">Monto:</label>
                            <input type="number" id="monto-general" name="monto_general" min="1" step="0.01" required
                                placeholder="Ej: 1500.00">
                        </div>

                        <div class="info-pago-mensual">
                            <p>
                                El <strong>pago mensual</strong> corresponde a un cargo general que se aplica
                                a todos los usuarios registrados en el sistema. Este monto se utiliza para cubrir
                                servicios,
                                mantenimientos u otros conceptos definidos por la administración.
                            </p>
                            <p>
                                Una vez registrado, el pago quedará asociado a cada usuario dentro del período
                                correspondiente,
                                facilitando la gestión y el control de las obligaciones mensuales.
                            </p>
                        </div>


                        <button type="submit" name="pago_general" class="boton-primario">
                            <i class="material-icons">attach_money</i> Asignar Pago
                        </button>
                    </form>
                </section>

                <!-- Formulario de pago personalizado -->
                <section class="formulario-pago">
                    <h3>Asignar Pago Personalizado</h3>
                    <form method="POST" class="formulario-horas" id="form-pago-personalizado">
                        <div class="mensaje-exito" style="display: none;">Pago personalizado registrado correctamente.
                        </div>
                        <div class="mensaje-error" style="display: none;">Error al registrar el pago.</div>

                        <div class="grupo-formulario">
                            <label for="cedula">Cédula de Identidad:</label>
                            <input type="text" id="cedula" name="cedula" required placeholder="Ej: 12345678">
                        </div>

                        <div class="grupo-formulario">
                            <div class="grupo-formulario">
                                <label for="monto-personalizado">Monto:</label>
                                <input type="number" id="monto-personalizado" name="monto_personalizado" min="1"
                                    step="0.01" required placeholder="Ej: 2500.00">
                            </div>
                        </div>

                        <div class="grupo-formulario">
                            <label for="motivo">Motivo del pago:</label>
                            <textarea id="motivo" name="motivo" rows="3" required
                                placeholder="Describa el motivo del pago"></textarea>
                        </div>

                        <button type="submit" name="pago_personalizado" class="boton-primario">
                            <i class="material-icons">person</i> Asignar Pago Personalizado
                        </button>
                    </form>
                </section>
            </div>
        </main>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/pagos.js" type="module"></script>

    <div class="modal-confirmacion" id="modalConfirmacion">
        <div class="modal-contenido">
            <h3>Confirmar Pago Mensual</h3>
            <p>¿Estás seguro que deseas asignar este pago?</p>
            <p>Esta acción no se puede deshacer.</p>
            <div class="modal-acciones">
                <button class="btn-cancelar">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-pago">
                    <i class="material-icons">delete_forever</i> Confirmar Pago
                </button>
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