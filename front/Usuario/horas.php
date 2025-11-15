<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Horas Trabajadas</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilosHoras.css">
</head>

<body>
    <button class="boton-hamburguesa" id="botonHamburguesa">
        <span></span>
    </button>

    <div class="overlay" id="overlay"></div>

    <div class="contenedor-dashboard">
        <aside class="sidebar" id="sidebar">
            <div class="logo-dashboard">
                <img src="../../Fotos/LogoNegro.webp" alt="Logo Cooperativa">
                <span>Senda Firme</span>
                <p class="sidebar-slogan">Construyendo oportunidades juntos</p>
            </div>

            <nav id="NavegacionDashboard">
                <ul class="menu-dashboard">
                    <li class="item-menu">
                        <a href="index.php">
                            <i class="material-icons">home</i>
                            <span class="sidebar-menu-inicio">Inicio</span>
                        </a>
                    </li>
                    <li class="item-menu activo">
                        <a href="horas.php">
                            <i class="material-icons">punch_clock</i>
                            <span class="sidebar-menu-horas">Horas Trabajadas</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="unidades.php">
                            <i class="material-icons">apartment</i>
                            <span class="sidebar-menu-unidad">Unidad Habitacional</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="pagos.php">
                            <i class="material-icons">payments</i>
                            <span class="sidebar-menu-pagos">Pagos</span>
                        </a>
                    </li>
                    <li class="item-menu">
                        <a href="configuracion.php">
                            <i class="material-icons">settings</i>
                            <span class="sidebar-menu-configuracion">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="perfil-usuario">
                <a href="configuracion.php">
                    <div class="info-usuario">
                        <img src="" alt="Foto perfil" class="fotoPerfil">
                        <div>
                            <p class="nombre-usuario nombreUsuario">Nombre User</p>
                            <p class="rol-usuario perfil-rol">Usuario</p>
                        </div>
                    </div>
                </a>
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i>
                        <span class="btn-cerrar-sesion">Cerrar sesión</span>
                    </button>
                </form>
                <button id="boton-cambiar-sesion" class="boton-cambiar-sesion">
                    <i class="material-icons">switch_account</i>
                    <span class="btn-cambiar-sesion">Cambiar a Admin</span>
                </button>
            </div>
        </aside>

        <main class="contenido-principal">
            <header class="header-principal">
                <h1 class="horas-header-titulo">Horas Trabajadas</h1>
                <p class="horas-header-subtitulo">Registra y consulta tus horas de trabajo semanales en la cooperativa</p>
            </header>

            <section class="fila-contador">
                <div class="tarjeta-contador-horas">
                    <div class="contador-principal">
                        <div class="icono-contador">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="info-contador">
                            <h2 class="contador-titulo">Resumen de Horas</h2>
                            <div class="estadisticas-contador">
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasTrabajadas"></span>
                                    <span class="etiqueta-contador contador-label-trabajadas">Horas trabajadas esta semana</span>
                                </div>
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasObjetivo"></span>
                                    <span class="etiqueta-contador contador-label-objetivo">Meta semanal</span>
                                </div>
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasRestantes"></span>
                                    <span class="etiqueta-contador contador-label-restantes">Horas restantes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progreso-contador">
                        <div class="info-progreso">
                            <span class="porcentaje-progreso" id="porcentajeProgreso">0%</span>
                            <span class="texto-progreso contador-texto-progreso">Completado</span>
                        </div>
                        <div class="barra-progreso-contador">
                            <div class="progreso" id="progresoHoras" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="fila-formularios">
                <div class="contenedor-pestanas">
                    <div class="cabecera-pestanas">
                        <button class="pestana activo" data-pestana="horas">
                            <i class="material-icons">punch_clock</i>
                            <span class="pestana-horas-texto">Registrar Horas</span>
                        </button>
                        <button class="pestana" data-pestana="faltas">
                            <i class="material-icons">event_busy</i>
                            <span class="pestana-faltas-texto">Registrar Faltas</span>
                        </button>
                    </div>

                    <div class="contenido-pestana activo" id="contenido-horas">
                        <div class="formulario-contenido">
                            <h3 class="horas-form-titulo">Registrar horas trabajadas</h3>
                            <form method="POST" class="formulario-horas">
                                <div class="mensaje-exito horas-form-msg-exito" style="display: none;">Horas registradas correctamente.</div>
                                <div class="mensaje-error horas-form-msg-error" style="display: none;">Error al registrar las horas.</div>

                                <div class="grupo-formulario">
                                    <label for="horas" class="horas-form-label-horas">Horas trabajadas:</label>
                                    <input type="number" id="horas" name="horas" min="1" max="12" required placeholder="Ej: 3">
                                </div>
                                <div class="grupo-formulario">
                                    <p class="texto-ayuda horas-form-texto-ayuda-1">Ingresa la cantidad de horas que trabajaste</p>
                                    <p class="texto-ayuda horas-form-texto-ayuda-2">Recorda que siempre redondeamos para <strong>abajo</strong></p>
                                </div>

                                <button type="submit" name="registrar_horas" class="boton-primario">
                                    <i class="material-icons">save</i>
                                    <span class="horas-form-btn-texto">Registrar horas</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="contenido-pestana" id="contenido-faltas">
                        <div class="formulario-contenido">
                            <h3 class="faltas-form-titulo">Registrar Faltas</h3>
                            <div class="explicacion-faltas">
                                <p class="faltas-form-texto-opciones"><strong>Opciones de compensación:</strong></p>
                                <ul>
                                    <li class="faltas-form-item-exoneracion"><strong>Exoneración:</strong> Se descuentan las horas faltadas de tu objetivo semanal</li>
                                    <li class="faltas-form-item-pago"><strong>Compensación monetaria:</strong> Pagas una tarifa establecida por la cooperativa por las horas que no trabajaste</li>
                                </ul>
                            </div>

                            <form method="POST" class="formulario-horas">
                                <div class="mensaje-exito faltas-form-msg-exito" style="display: none;">Falta registrada correctamente.</div>
                                <div class="mensaje-error faltas-form-msg-error" style="display: none;">Error al registrar la falta.</div>

                                <div class="grupo-formulario">
                                    <label for="horas_faltadas" class="faltas-form-label-horas">Horas faltadas:</label>
                                    <input type="number" id="horas_faltadas" name="horas_faltadas" min="1" max="12" required placeholder="Ej: 4">
                                </div>

                                <div class="grupo-formulario">
                                    <label for="tipo_compensacion" class="faltas-form-label-tipo">Tipo de compensación:</label>
                                    <select id="tipo_compensacion" name="tipo_compensacion" required>
                                        <option value="" class="faltas-form-option-placeholder">Selecciona una opción</option>
                                        <option value="exoneracion" class="faltas-form-option-exoneracion">Exoneración de horas</option>
                                        <option value="pagoCompensatorio" class="faltas-form-option-pago">Compensación monetaria</option>
                                    </select>
                                </div>

                                <div class="grupo-formulario">
                                    <label for="motivo_falta" class="faltas-form-label-motivo">Motivo de la falta:</label>
                                    <textarea id="motivo_falta" name="motivo_falta" required placeholder="Describe brevemente el motivo de tu falta"></textarea>
                                </div>

                                <button type="submit" name="registrar_falta" class="boton-primario">
                                    <i class="material-icons">save</i>
                                    <span class="faltas-form-btn-texto">Registrar falta</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="seccion-historial">
                <h2 class="historial-titulo">Historial de horas trabajadas</h2>

                <div class="filtros-historial">
                    <div class="grupo-filtro">
                        <label for="filtro-semana" class="historial-filtro-semana-label">Semana:</label>
                        <select id="filtro-semana" name="filtro-semana">
                            <option value="" class="historial-filtro-semana-option-todas">Todas</option>
                            <option value="46" selected>11/08/2025</option>
                            <option value="45">18/08/2025</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-mes" class="historial-filtro-dia-label">Dia:</label>
                        <select id="filtro-mes" name="filtro-mes">
                            <option value="" selected class="historial-filtro-dia-option-todos">Todos</option>
                            <option value="1" class="historial-filtro-dia-option-1">Lunes</option>
                            <option value="2" class="historial-filtro-dia-option-2">Martes</option>
                            <option value="3" class="historial-filtro-dia-option-3">Miercoles</option>
                            <option value="4" class="historial-filtro-dia-option-4">Jueves</option>
                            <option value="5" class="historial-filtro-dia-option-5">Viernes</option>
                            <option value="6" class="historial-filtro-dia-option-6">Sabado</option>
                            <option value="7" class="historial-filtro-dia-option-7">Domingo</option>
                        </select>
                    </div>
                </div>

                <div class="tabla-contenedor">
                    <table class="tabla-horas">
                        <thead>
                            <tr>
                                <th class="tabla-horas-th-fecha">Fecha</th>
                                <th class="tabla-horas-th-dia">Día</th>
                                <th class="tabla-horas-th-horas">Horas</th>
                                <th class="tabla-horas-th-editar">Editar</th>
                                <th class="tabla-horas-th-borrar">Borrar</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div id="modalEditarHoras" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2 class="modal-editar-titulo">Editar horas trabajadas</h2>
                <button class="modal-cerrar" id="cerrarModal">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="modal-body">
                <form id="formEditarHoras" class="formulario-modal">
                    <div class="mensaje-exito modal-editar-msg-exito" style="display: none;">Horas actualizadas correctamente.</div>
                    <div class="mensaje-error modal-editar-msg-error" style="display: none;">Error al actualizar las horas.</div>

                    <div class="grupo-formulario">
                        <label for="fechaEditar" class="modal-editar-label-fecha">Fecha:</label>
                        <input type="date" id="fechaEditar" name="fecha" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="horasEditar" class="modal-editar-label-horas">Horas trabajadas:</label>
                        <input type="number" id="horasEditar" name="horas" min="1" max="12" required placeholder="Ej: 3">
                    </div>

                    <div class="modal-acciones">
                        <button type="button" class="boton-secundario modal-editar-boton-cancelar" id="cancelarEdicion">Cancelar</button>
                        <button type="submit" class="boton-primario modal-editar-boton-guardar" id="confirmarModal">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const botonHamburguesa = document.getElementById('botonHamburguesa');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            botonHamburguesa.classList.toggle('activo');
            sidebar.classList.toggle('activo');
            overlay.classList.toggle('activo');
            document.body.style.overflow = sidebar.classList.contains('activo') ? 'hidden' : 'auto';
        }

        botonHamburguesa.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        document.querySelectorAll('.item-menu a').forEach(enlace => {
            enlace.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                botonHamburguesa.classList.remove('activo');
                sidebar.classList.remove('activo');
                overlay.classList.remove('activo');
                document.body.style.overflow = 'auto';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const pestanas = document.querySelectorAll('.pestana');
            const contenidos = document.querySelectorAll('.contenido-pestana');

            pestanas.forEach(pestana => {
                pestana.addEventListener('click', function () {
                    const pestanaId = this.getAttribute('data-pestana');
                    pestanas.forEach(p => p.classList.remove('activo'));
                    contenidos.forEach(c => c.classList.remove('activo'));
                    this.classList.add('activo');
                    document.getElementById(`contenido-${pestanaId}`).classList.add('activo');
                });
            });

            function actualizarProgreso() {
                const horasTrabajadas = 25;
                const horasObjetivo = 40;
                const horasRestantes = Math.max(0, horasObjetivo - horasTrabajadas);
                const porcentaje = (horasTrabajadas / horasObjetivo) * 100;

                document.getElementById('horasTrabajadas').textContent = horasTrabajadas;
                document.getElementById('horasRestantes').textContent = horasRestantes;
                document.getElementById('progresoHoras').style.width = `${porcentaje}%`;
                document.getElementById('porcentajeProgreso').textContent = `${Math.round(porcentaje)}%`;
            }

            actualizarProgreso();
        });
    </script>
    <script src="../Javascript/FrontUsuario/horas.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/traduccionesHoras.js" type="module"></script>
</body>
</html>
