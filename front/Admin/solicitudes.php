<!DOCTYPE html>
<html lang="es">
<?php
require_once '../verificarSesion.php';
verificarAcceso(['Admin']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Solicitudes</title>
    <link rel="stylesheet" href="../Css/estilosSolicitudes.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=event_available" />
</head>

<body>
    <button class="hamburger-btn">
        <span class="material-icons">menu</span>
    </button>
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
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
                        <a href="#"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Socios"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="#"><i class="material-icons">payments</i> Finanzas</a>
                    </li>
                    <li class="item-menu activo">
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

        <!-- Contenido de solicitudes pendientes -->
        <main class="contenido-principal">
            <h1>Solicitudes Pendientes</h1>
            <div id="contenedor-solicitudes">
            </div>
        </main>
    </div>



    <!-- Modal de confirmación -->
    <div class="modal-confirmacion" id="modalConfirmacion">
        <div class="modal-contenido">
            <h3>Confirmar Rechazo</h3>
            <p>¿Estás seguro que deseas rechazar esta solicitud?</p>
            <p>Esta acción no se puede deshacer.</p>
            <div class="modal-acciones">
                <button class="btn-cancelar">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-rechazo">
                    <i class="material-icons">delete_forever</i> Confirmar Rechazo
                </button>
            </div>
        </div>
    </div>


    <!-- Modal de Asignar Monto -->
    <div class="modal-monto" id="modalPagoInicial">
        <div class="modal-contenido">
            <h3>Confirmar Monto</h3>
            <p>¿Cuánto querés asignar de pago inicial?</p>
            <input type="number" id="inputPagoInicial" placeholder="Monto en $">
            <div class="modal-acciones">
                <button class="btn-cancelar-pago">
                    <i class="material-icons">arrow_back</i> Cancelar
                </button>
                <button class="btn-confirmar-pago">
                    <i class="material-icons">check_circle</i> Confirmar Monto
                </button>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/solicitudes.js" type="module"></script>
</body>

</html>