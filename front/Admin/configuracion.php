<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Perfil</title>
    <link rel="stylesheet" href="../Css/estilosConfiguracionAdmin.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="backoffice">
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>
    <div class="overlay" id="overlay"></div>
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
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
                        <a href="#" class="submenu-toggle">
                            <i class="material-icons">apartment</i> Unidades Habitacionales
                        </a>
                        <ul class="submenu">
                            <a href="unidades.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#" class="submenu-toggle">
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
                        <a href="#" class="submenu-toggle">
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

        <!-- Contenido principal del perfil -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Mi Perfil</h1>
                <p>Gestiona tu información personal y preferencias</p>
            </header>

            <div class="contenedor-perfil">
                <section class="seccion-info-personal">
                    <div class="foto-perfil-container">
                        <img src="" alt="Foto de perfil" class="foto-perfil fotoPerfil">
                        <button class="boton-cambiar-foto" onclick="document.getElementById('subir-foto').click()">
                            <input type="file" style="display: none;" id="subir-foto">
                            <i class="material-icons">image_search</i> Cambiar foto
                        </button>
                    </div>

                    <div class="info-personal">
                        <h2>Información personal</h2>
                        <div class="campo-perfil">
                            <label>Nombre completo</label>
                            <p class="valor-perfil nombreAdmin">Diego Luis Charlo Arce</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Correo electrónico</label>
                            <p class="valor-perfil" id="emailAdmin">alainarce39@gmail.com</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Teléfono</label>
                            <p class="valor-perfil" id="telefonoAdmin">+598 92 343 168</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Creacion Admin</label>
                            <p class="valor-perfil" id="creacionAdmin">15 de Enero, 2020</p>
                        </div>
                        <div class="campo-perfil">
                            <label>Rol Admin</label>
                            <p class="valor-perfil" id="nivelPermisosAdmin">Administrador</p>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>
    <script src="../Javascript/BackOffice/perfil.js" type="module"></script>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
</body>

</html>