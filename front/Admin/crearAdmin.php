<?php
require_once __DIR__ . '/../../BackEnd/BDConeccion.php';
require_once __DIR__ . '/../../BackEnd/Tokens.php';
$token = obtenerToken();
if (!$token || !validarSoloAdmin($token, $conn)) {
    //Para que los Operadores no puedan cambiar la ruta directa y entrar al panel de admin
    header("Location: ../Operador/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Perfil</title>
    <link rel="stylesheet" href="../Css/estilosRegistro.css">
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
        <!-- Sidebar (igual que en index.html) -->
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
                <h1>Crear Admin</h1>
                <p>En este login vas a poder ingresar los datos de tu proximo administrador</p>
            </header>

            <div class="contenedor-perfil">






                <main class="contenedor-registro">
                    <div class="divForm">
                        <h1 class="registro-titulo">Registra al proximo Admin</h1>

                        <div id="mensajeError" class="mensaje-error" style="display: none;">
                        </div>
                        <div id="mensajeExito" class="mensaje-exito" style="display: none;">
                        </div>

                        <form action="#" id="cargarAdmin" class="registro-form">
                            <div class="fieldInfo">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" required
                                    title="Ingresa tu nombre completo">
                            </div>

                            <div class="fieldInfo">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" required
                                    title="Ingresa tu apellido completo">
                            </div>

                            <div class="fieldInfo">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" required
                                    title="Ingresa un correo electrónico válido">
                            </div>

                            <div class="fieldInfo">
                                <label for="telefono">Teléfono Móvil</label>
                                <input type="tel" id="telefono" name="telefono" pattern="[0-9]{9,12}" required
                                    title="Ingresa tu número de teléfono (9 a 12 dígitos sin espacios)">
                            </div>

                            <div class="fieldInfo">
                                <label for="cedula">Cédula de Identidad</label>
                                <input type="text" id="cedula" name="cedula" required pattern="^\d{7,8}$"
                                    title="Debe contener 7 u 8 dígitos numéricos sin puntos ni guiones">
                            </div>

                            <div class="fieldInfo">
                                <label for="password">Contraseña</label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" required minlength="8"
                                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                                        title="Debe tener al menos 8 caracteres, incluyendo mayúscula, minúscula, número y símbolo especial">

                                </div>
                            </div>

                            <div class="fieldInfo">
                                <label for="nivel_permisos">Nivel de permisos</label>

                                <div class="opciones-permisos" id="nivel_permisos">
                                    <label class="radio">
                                        <input type="radio" name="nivel_permisos" value="Operador" required>
                                        <span>Operador</span>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="nivel_permisos" value="Admin">
                                        <span>Admin</span>
                                    </label>
                                </div>
                            </div>


                            <button type="submit" class="btnRegistro registro-btn">Registrar Admin</button>
                        </form>

                    </div>
                    <div class="infoSide">
                        <div class="contenidoInfo">
                            <h2 class="login-titulo-side">Operador <span class="material-icons">security</span></h2>
                            <p class="textoInfo login-texto-side">Cuenta con acceso total a la administración</p>
                            <h2 class="login-titulo-side">Admin <span class="material-icons">rocket</span></h2>
                            <p class="textoInfo login-texto-side">Cuenta con acceso restringido a acciones
                                importantes/criticas.</p>
                            <h2 class="login-titulo-side">Recomendacion <span
                                    class="material-icons">support_agent</span></h2>
                            <p class="textoInfo login-texto-side">Asignar <strong>Admin</strong> a los empleados de la
                                administracion.</p>

                        </div>
                    </div>

                </main>









            </div>
        </main>
    </div>
    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/cargarAdmin.js" type="module"></script>
</body>

</html>