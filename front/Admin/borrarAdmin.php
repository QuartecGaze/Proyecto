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
    <title>Senda Firme - Borrar Administradores</title>
        <link rel="stylesheet" href="../Css/estilosRegistro.css">
    <link rel="stylesheet" href="../Css/estilosBorrarAdmin.css">
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

        <!-- Contenido principal -->
        <main class="contenido-principal">
            <header class="header-principal">
                <h1>Borrar Administradores</h1>
                <p>Gestiona los administradores y operadores del sistema</p>
            </header>

            <div class="contenedor-perfil">
                <div class="contenedor-admins">
                    <h2>Lista de Administradores y Operadores</h2>
                    <p>Selecciona un usuario para eliminarlo del sistema</p>
                    <div class="mensaje-exito" style="display: none;">Administrador borrado correctamente.</div>
                    <div class="mensaje-error" style="display: none;">Error al borrar el administrador.</div>

                    <div class="lista-admins" id="listaAdmins">
                        <!-- Los administradores se cargarán aquí dinámicamente -->
                        <div class="admin-info">
                            <div class="admin-nombre"></div>
                            <div class="admin-detalles">
                                <span>alain@gmail.com</span>
                                <span class="admin-rol">Administrador</span>
                            </div>
                        </div>
                        <button class="boton-borrar" data-id="${admin.id}">
                            <i class="material-icons">delete</i> Eliminar
                        </button>
                        <div class="sin-admins">
                            <p>No hay administradores u operadores para mostrar</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal-confirmacion" id="modalConfirmacion">
        <div class="modal-contenido">
            <h3 class="modal-titulo">Confirmar Eliminación</h3>
            <p class="modal-texto" id="textoConfirmacion">
                ¿Estás seguro de que deseas eliminar a este usuario del sistema? Esta acción no se puede deshacer.
            </p>
            <div class="modal-acciones">
                <button class="boton-cancelar" id="botonCancelar">Cancelar</button>
                <button class="boton-confirmar" id="botonConfirmar">Eliminar</button>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script>
        // Datos de ejemplo - en un sistema real estos datos vendrían de una base de datos
        const administradores = [
            {
                id: 1,
                nombre: "María González",
                email: "maria.gonzalez@senda-firme.com",
                telefono: "098765432",
                cedula: "12345678",
                rol: "admin"
            },
            {
                id: 2,
                nombre: "Carlos Rodríguez",
                email: "carlos.rodriguez@senda-firme.com",
                telefono: "098765433",
                cedula: "23456789",
                rol: "operador"
            },
            {
                id: 3,
                nombre: "Ana Martínez",
                email: "ana.martinez@senda-firme.com",
                telefono: "098765434",
                cedula: "34567890",
                rol: "admin"
            },
            {
                id: 4,
                nombre: "Luis Fernández",
                email: "luis.fernandez@senda-firme.com",
                telefono: "098765435",
                cedula: "45678901",
                rol: "operador"
            }
        ];

        document.addEventListener('DOMContentLoaded', function () {
            const listaAdmins = document.getElementById('listaAdmins');
            const modalConfirmacion = document.getElementById('modalConfirmacion');
            const textoConfirmacion = document.getElementById('textoConfirmacion');
            const botonCancelar = document.getElementById('botonCancelar');
            const botonConfirmar = document.getElementById('botonConfirmar');

            let adminAEliminar = null;

            // Cargar la lista de administradores
            function cargarAdministradores() {
                if (administradores.length === 0) {
                    listaAdmins.innerHTML = '<div class="sin-admins"><p>No hay administradores u operadores para mostrar</p></div>';
                    return;
                }

                listaAdmins.innerHTML = '';

                administradores.forEach(admin => {
                    const adminItem = document.createElement('div');
                    adminItem.className = 'admin-item';

                    const rolClass = admin.rol === 'admin' ? 'admin' : 'operador';
                    const rolTexto = admin.rol === 'admin' ? 'Administrador' : 'Operador';

                    adminItem.innerHTML = `
                <div class="admin-info">
                    <div class="admin-nombre">${admin.nombre}</div>
                    <div class="admin-detalles">
                        <span>${admin.email}</span>
                        <span class="admin-rol ${rolClass}">${rolTexto}</span>
                    </div>
                </div>
                <button class="boton-borrar" data-id="${admin.id}">
                    <i class="material-icons">delete</i> Eliminar
                </button>
            `;

                    listaAdmins.appendChild(adminItem);
                });

                // Agregar eventos a los botones de eliminar
                document.querySelectorAll('.boton-borrar').forEach(boton => {
                    boton.addEventListener('click', function () {
                        const id = parseInt(this.getAttribute('data-id'));
                        mostrarModalConfirmacion(id);
                    });
                });
            }

            // Mostrar modal de confirmación
            function mostrarModalConfirmacion(id) {
                adminAEliminar = administradores.find(admin => admin.id === id);

                if (adminAEliminar) {
                    textoConfirmacion.textContent = `¿Estás seguro de que deseas eliminar a ${adminAEliminar.nombre} (${adminAEliminar.email}) del sistema? Esta acción no se puede deshacer.`;
                    modalConfirmacion.style.display = 'flex';
                }
            }

            // Ocultar modal de confirmación
            function ocultarModalConfirmacion() {
                modalConfirmacion.style.display = 'none';
                adminAEliminar = null;
            }

            // Eliminar administrador
            function eliminarAdministrador() {
                if (adminAEliminar) {
                    // En un sistema real, aquí se haría una petición al servidor
                    const index = administradores.findIndex(admin => admin.id === adminAEliminar.id);
                    if (index !== -1) {
                        administradores.splice(index, 1);
                        cargarAdministradores();
                    }
                }

                ocultarModalConfirmacion();
            }

            // Eventos
            botonCancelar.addEventListener('click', ocultarModalConfirmacion);
            botonConfirmar.addEventListener('click', eliminarAdministrador);

            // Cerrar modal al hacer clic fuera del contenido
            modalConfirmacion.addEventListener('click', function (e) {
                if (e.target === modalConfirmacion) {
                    ocultarModalConfirmacion();
                }
            });

            // Cargar administradores al iniciar
            cargarAdministradores();
        });
    </script>
</body>

</html>