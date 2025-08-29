<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Horas Trabajadas</title>
    <link rel="stylesheet" href="../Css/estilosHoras.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="contenedor-dashboard">
        <!-- Sidebar (igual que antes) -->
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
                    <li class="item-menu activo">
                        <a href="horasTrabajadas.php"><i class="material-icons">punch_clock</i> Horas Trabajadas</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Proyectos"><i class="material-icons">apartment</i> Proyectos</a>
                    </li>
                    <li class="item-menu">
                        <a href="#Finanzas"><i class="material-icons">payments</i> Finanzas</a>
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
                <h1>Horas Trabajadas</h1>
                <p>Registra y consulta tus horas de trabajo Semanales en la cooperativa</p>
            </header>

            <!-- Fila con resumen y formulario -->
            <div class="contenedor-secciones">
                <!-- Sección de resumen -->
                <section class="seccion-resumen">
                    <h2>Resumen</h2>
                    <div class="tarjeta-dashboard">
                        <div class="tarjeta-icono">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="tarjeta-contenido">
                            <h3>Horas Trabajadas</h3><!-- TRAER LAS HORAS TRABAJADAS EN ESTA SEMANA-->
                            <p class="tarjeta-valor">45 <span>horas</span></p>
                        </div>
                    </div>
                    <div>
                        <p class="tarjeta-subtexto">Meta: 21 horas</p> <!-- TRAER LA CANTIDAD DE HORAS QUE DEBE EL USUARIO NO LAS DE LA SEMANA PORQUE CAPAS QUE TIENE HORAS PREVIAS POR HACER-->
                        <div class="barra-progreso">
                            <div class="progreso" style="width: 53.57%"></div>
                        </div>
                    </div>
                </section>

                <!-- Sección de formulario -->
                <section class="seccion-formulario">
                    <h2>Registrar horas</h2>
                    <form method="POST" class="formulario-horas">
                        <div class="mensaje-exito" style="display: none;">Horas registradas correctamente.</div>
                        <div class="mensaje-error" style="display: none;">Error al registrar las horas.</div>

                        <div class="grupo-formulario">
                            <label for="horas">Horas trabajadas:</label>
                            <input type="number" id="horas" name="horas" min="1" max="12" required placeholder="Ej: 3">
                        </div>

                        <button type="submit" name="registrar_horas" class="boton-primario">
                            <i class="material-icons">save</i> Registrar horas
                        </button>
                    </form>
                </section>
            </div>

            <!-- Historial de horas trabajadas -->
            <section class="seccion-historial">
                <h2>Historial de horas trabajadas</h2>

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
                        <label for="filtro-semana">Semana:</label>
                        <select id="filtro-semana" name="filtro-semana">
                            <option value="">Todas</option>
                            <option value="46" selected>Semana 46 (13-19 Nov)</option>
                            <option value="45">Semana 45 (6-12 Nov)</option>
                        </select>
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-horas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Día</th>
                                <th>Horas</th>
                                <th>Proyecto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15/11/2023</td>
                                <td>Miércoles</td>
                                <td>8 horas</td>
                                <td>Huerto comunitario</td>
                                <td>
                                    <button class="boton-icono" title="Editar">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="boton-icono" title="Eliminar">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>14/11/2023</td>
                                <td>Martes</td>
                                <td>6 horas</td>
                                <td>Construcción</td>
                                <td>
                                    <button class="boton-icono" title="Editar">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="boton-icono" title="Eliminar">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/11/2023</td>
                                <td>Viernes</td>
                                <td>7 horas</td>
                                <td>Administración</td>
                                <td>
                                    <button class="boton-icono" title="Editar">
                                        <i class="material-icons">edit</i>
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

    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/horas.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/cooperativa.js" type="module"></script>
    <script>

        document.querySelector('.formulario-horas').addEventListener('submit', function (e) {
            e.preventDefault();

            // Simular envío exitoso
            document.querySelector('.mensaje-exito').style.display = 'block';
            document.querySelector('.mensaje-error').style.display = 'none';

            // Actualizar el resumen (ejemplo)
            const horasRegistradas = parseInt(document.getElementById('horas').value);
            const totalElement = document.querySelector('.tarjeta-valor');
            const totalActual = parseInt(totalElement.textContent);
            totalElement.textContent = (totalActual + horasRegistradas) + ' horas';

            // Actualizar barra de progreso (ejemplo)
            const progreso = document.querySelector('.progreso');
            const nuevoProgreso = ((totalActual + horasRegistradas) / 84) * 100;
            progreso.style.width = Math.min(nuevoProgreso, 100) + '%';

            // Limpiar formulario después de 2 segundos
            setTimeout(() => {
                document.querySelector('.mensaje-exito').style.display = 'none';
                this.reset();
            }, 2000);
        });
    </script>
</body>

</html>