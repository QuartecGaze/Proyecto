<?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Faltas</title>
    <link rel="stylesheet" href="../Css/estilosAdmin.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosFaltas.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="backoffice">
    <div class="contenedor-dashboard">
        <!-- Sidebar (igual que en index.php) -->
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
                        <a href="reuniones.php"><i class="material-icons">event</i> Reuniones</a>
                    </li>
                    <li class="item-menu">
                        <a href="socios.php"><i class="material-icons">people</i> Socios</a>
                    </li>
                    <li class="item-menu">
                        <a href="#">
                            <i class="material-icons">apartment</i> Unidades Habitacionales
                        </a>
                        <ul class="submenu">
                            <a href="unidades.php"><i class="material-icons">home_work</i>Gestionar Proyectos</a>
                            <a href="crearUnidad.php"><i class="material-icons">add_circle</i> Crear Unidad</a>
                        </ul>
                    </li>
                    <li class="item-menu">
                        <a href="#">
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
                <h1>Gestión de Faltas de Horas</h1>
                <p>Administra las faltas de horas de trabajo de los socios</p>
            </header>

            <!-- Filtros -->
            <div class="filtros-faltas">
                <select id="filtro-estado">
                    <option value="todas">Todas las faltas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="aprobada">Aprobadas</option>
                    <option value="compensada">Compensadas</option>
                </select>
                
                <select id="filtro-tipo">
                    <option value="todos">Todos los tipos</option>
                    <option value="horas">Compensación en horas</option>
                    <option value="monetaria">Compensación monetaria</option>
                </select>
                
                <button id="btn-aplicar-filtros">
                    Aplicar Filtros
                </button>
            </div>

            <!-- Lista de faltas -->
            <div class="contenedor-faltas">
               <!-- ACA el js trae todas las faltas -->
            </div>
        </main>
    </div>

    <!-- Modal para asignar monto -->
    <div id="modalCompensacion" class="modal">
        <div class="modal-contenido">
            <h3>Asignar Compensación Monetaria</h3>
            <div class="form-group">
                <label for="montoCompensacion">Monto a asignar ($):</label>
                <input type="number" id="montoCompensacion" min="0">
            </div>
            <div class="modal-acciones">
                <button onclick="cerrarModal()">Cancelar</button>
                <button onclick="asignarMonto()">Asignar</button>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>
    <script src="../Javascript/BackOffice/faltaHoras.js" type="module"></script>
    <script>
        let faltaActualId = null;

        // Funciones para el modal
        function mostrarModalCompensacion(faltaId) {
            faltaActualId = faltaId;
            document.getElementById('modalCompensacion').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modalCompensacion').style.display = 'none';
            faltaActualId = null;
        }

        function asignarMonto() {
            const monto = document.getElementById('montoCompensacion').value;
            const descripcion = document.getElementById('descripcionCompensacion').value;
            
            if (!monto || monto <= 0) {
                alert('Por favor ingrese un monto válido');
                return;
            }

            // Aquí iría la lógica para guardar en la base de datos
            console.log(`Asignando monto $${monto} a falta ${faltaActualId}`);
            
            // Actualizar la interfaz
            const tarjeta = document.querySelector(`[data-id="${faltaActualId}"]`);
            const montoElement = tarjeta.querySelector('.monto-asignado');
            montoElement.style.display = 'flex';
            montoElement.querySelector('.valor').textContent = `$${parseFloat(monto).toFixed(2)}`;
            
            // Cambiar a tipo monetaria
            tarjeta.setAttribute('data-tipo', 'monetaria');
            
            cerrarModal();
            alert('Monto asignado correctamente');
        }

        function aprobarFalta(faltaId, tipo) {
            if (confirm('¿Está seguro de que desea aprobar esta falta?')) {
                // Aquí iría la lógica para aprobar en la base de datos
                console.log(`Aprobando falta ${faltaId} tipo ${tipo}`);
                
                const tarjeta = document.querySelector(`[data-id="${faltaId}"]`);
                tarjeta.setAttribute('data-estado', 'aprobada');
                tarjeta.style.opacity = '0.7';
                
                alert('Falta aprobada correctamente');
            }
        }

        function rechazarFalta(faltaId) {
            if (confirm('¿Está seguro de que desea rechazar esta falta?')) {
                // Aquí iría la lógica para rechazar en la base de datos
                console.log(`Rechazando falta ${faltaId}`);
                
                const tarjeta = document.querySelector(`[data-id="${faltaId}"]`);
                tarjeta.remove();
                
                alert('Falta rechazada correctamente');
            }
        }

        // Filtros
        document.getElementById('btn-aplicar-filtros').addEventListener('click', function() {
            const estado = document.getElementById('filtro-estado').value;
            const tipo = document.getElementById('filtro-tipo').value;
            
            document.querySelectorAll('.tarjeta-falta').forEach(tarjeta => {
                const tarjetaEstado = tarjeta.getAttribute('data-estado');
                const tarjetaTipo = tarjeta.getAttribute('data-tipo');
                
                const mostrar = 
                    (estado === 'todas' || tarjetaEstado === estado) &&
                    (tipo === 'todos' || tarjetaTipo === tipo);
                
                tarjeta.style.display = mostrar ? 'flex' : 'none';
            });
        });

        // Cerrar modal al hacer click fuera
        document.getElementById('modalCompensacion').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

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