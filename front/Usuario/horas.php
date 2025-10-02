<?php
require_once '../verificarSesion.php';
verificarAcceso(['Usuario', 'Admin']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Horas Trabajadas</title>
    <link rel="stylesheet" href="../Css/estilosHoras.css">
    <link rel="stylesheet" href="../Css/genFrontUsuario.css">
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
                <h1>Horas Trabajadas</h1>
                <p>Registra y consulta tus horas de trabajo Semanales en la cooperativa</p>
            </header>

            <!-- Fila 1: Contador de horas a lo largo -->
            <section class="fila-contador">
                <div class="tarjeta-contador-horas">
                    <div class="contador-principal">
                        <div class="icono-contador">
                            <i class="material-icons">punch_clock</i>
                        </div>
                        <div class="info-contador">
                            <h2>Resumen de Horas</h2>
                            <div class="estadisticas-contador">
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasTrabajadas">0</span>
                                    <span class="etiqueta-contador">Horas trabajadas esta semana</span>
                                </div>
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasObjetivo">40</span>
                                    <span class="etiqueta-contador">Meta semanal</span>
                                </div>
                                <div class="estadistica-contador">
                                    <span class="valor-contador" id="horasRestantes">40</span>
                                    <span class="etiqueta-contador">Horas restantes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progreso-contador">
                        <div class="info-progreso">
                            <span class="porcentaje-progreso" id="porcentajeProgreso">0%</span>
                            <span class="texto-progreso">Completado</span>
                        </div>
                        <div class="barra-progreso-contador">
                            <div class="progreso" id="progresoHoras" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Fila 2: Formularios con pestañas -->
            <section class="fila-formularios">
                <div class="contenedor-pestanas">
                    <div class="cabecera-pestanas">
                        <button class="pestana activo" data-pestana="horas">
                            <i class="material-icons">punch_clock</i>
                            Registrar Horas
                        </button>
                        <button class="pestana" data-pestana="faltas">
                            <i class="material-icons">event_busy</i>
                            Registrar Faltas
                        </button>
                    </div>

                    <!-- Contenido de pestaña Horas -->
                    <div class="contenido-pestana activo" id="contenido-horas">
                        <div class="formulario-contenido">
                            <h3>Registrar horas trabajadas</h3>
                            <form method="POST" class="formulario-horas">
                                <div class="mensaje-exito" style="display: none;">Horas registradas correctamente.</div>
                                <div class="mensaje-error" style="display: none;">Error al registrar las horas.</div>

                                <div class="grupo-formulario">
                                    <label for="horas">Horas trabajadas:</label>
                                    <input type="number" id="horas" name="horas" min="1" max="12" required
                                        placeholder="Ej: 3">
                                </div>
                                <div class="grupo-formulario">
                                    <p class="texto-ayuda">Ingresa la cantidad de horas que trabajaste</p>
                                    <p class="texto-ayuda">Recorda que siempre redondeamos para <strong>abajo</strong>
                                    </p>
                                </div>

                                <button type="submit" name="registrar_horas" class="boton-primario">
                                    <i class="material-icons">save</i> Registrar horas
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contenido de pestaña Faltas -->
                    <div class="contenido-pestana" id="contenido-faltas">
                        <div class="formulario-contenido">
                            <h3>Registrar Faltas</h3>
                            <div class="explicacion-faltas">
                                <p><strong>Opciones de compensación:</strong></p>
                                <ul>
                                    <li><strong>Exoneración:</strong> Se descuentan las horas faltadas de tu objetivo
                                        semanal</li>
                                    <li><strong>Compensación monetaria:</strong> Se descuenta el valor de las horas
                                        faltadas de tu pago</li>
                                </ul>
                            </div>

                            <form method="POST" class="formulario-horas">
                                <div class="mensaje-exito" style="display: none;">Falta registrada correctamente.</div>
                                <div class="mensaje-error" style="display: none;">Error al registrar la falta.</div>

                                <div class="grupo-formulario">
                                    <label for="horas_faltadas">Horas faltadas:</label>
                                    <input type="number" id="horas_faltadas" name="horas_faltadas" min="1" max="12"
                                        required placeholder="Ej: 4">
                                </div>

                                <div class="grupo-formulario">
                                    <label for="tipo_compensacion">Tipo de compensación:</label>
                                    <select id="tipo_compensacion" name="tipo_compensacion" required>
                                        <option value="">Selecciona una opción</option>
                                        <option value="exoneracion">Exoneración de horas</option>
                                        <option value="compensacion_monetaria">Compensación monetaria</option>
                                    </select>
                                </div>

                                <div class="grupo-formulario">
                                    <label for="motivo_falta">Motivo de la falta:</label>
                                    <textarea id="motivo_falta" name="motivo_falta" required
                                        placeholder="Describe brevemente el motivo de tu falta"></textarea>
                                </div>

                                <button type="submit" name="registrar_falta" class="boton-primario">
                                    <i class="material-icons">save</i> Registrar falta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Fila 3: Historial -->
            <section class="seccion-historial">
                <h2>Historial de horas trabajadas</h2>

                <div class="filtros-historial">
                    <div class="grupo-filtro">
                        <label for="filtro-semana">Semana:</label>
                        <select id="filtro-semana" name="filtro-semana">
                            <option value="">Todas</option>
                            <option value="46" selected>11/08/2025</option>
                            <option value="45">18/08/2025</option>
                        </select>
                    </div>

                    <div class="grupo-filtro">
                        <label for="filtro-mes">Dia:</label>
                        <select id="filtro-mes" name="filtro-mes">
                            <option value="">Todos</option>
                            <option value="11" selected>Lunes</option>
                            <option value="10">Martes</option>
                            <option value="9">Miercoles</option>
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
                                <th>Editar</th>
                                <th>Borrar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- TRAEMOS CON JS LAS LINEAS-->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para editar horas -->
    <div id="modalEditarHoras" class="modal">
        <div class="modal-contenido">
            <div class="modal-header">
                <h2>Editar horas trabajadas</h2>
                <button class="modal-cerrar" id="cerrarModal">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <div class="modal-body">
                <form id="formEditarHoras" class="formulario-modal">
                    <div class="mensaje-exito" style="display: none;">Horas actualizadas correctamente.</div>
                    <div class="mensaje-error" style="display: none;">Error al actualizar las horas.</div>

                    <div class="grupo-formulario">
                        <label for="fechaEditar">Fecha:</label>
                        <input type="date" id="fechaEditar" name="fecha" required>
                    </div>

                    <div class="grupo-formulario">
                        <label for="horasEditar">Horas trabajadas:</label>
                        <input type="number" id="horasEditar" name="horas" min="1" max="12" required
                            placeholder="Ej: 3">
                    </div>

                    <div class="modal-acciones">
                        <button type="button" class="boton-secundario" id="cancelarEdicion">Cancelar</button>
                        <button type="submit" class="boton-primario" id="confirmarModal">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../Javascript/FrontUsuario/horas.js" type="module"></script>
    <script src="../Javascript/FrontUsuario/generalidades.js" type="module"></script>

    <script>
        // Funcionalidad para las pestañas
        document.addEventListener('DOMContentLoaded', function () {
            const pestanas = document.querySelectorAll('.pestana');
            const contenidos = document.querySelectorAll('.contenido-pestana');

            pestanas.forEach(pestana => {
                pestana.addEventListener('click', function () {
                    const pestanaId = this.getAttribute('data-pestana');

                    // Remover clase activa de todas las pestañas y contenidos
                    pestanas.forEach(p => p.classList.remove('activo'));
                    contenidos.forEach(c => c.classList.remove('activo'));

                    // Activar pestaña y contenido clickeado
                    this.classList.add('activo');
                    document.getElementById(`contenido-${pestanaId}`).classList.add('activo');
                });
            });

            // Simular progreso de horas (esto se reemplazará con datos reales)
            function actualizarProgreso() {
                const horasTrabajadas = 25; // Esto vendrá de la base de datos
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
</body>

</html>