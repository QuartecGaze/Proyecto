<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senda Firme - Gestión de Unidades</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Css/backoffice.css">
    <link rel="stylesheet" href="../Css/estilosProyectos-prueba.css">
    <!-- Incluir Chart.js para los gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="backoffice">
    <div class="contenedor-dashboard">
        <!-- Sidebar -->
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
                            <a href="unidades.php"><i class="material-icons">home_work</i>Gestionar Unidades</a>
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
                <h1>Gestión de <span class="nombre-usuario-destacado">Unidades</span></h1>
                <p>Administra las Unidades habitacionales de la cooperativa</p>
            </header>

            <!-- Gráficos de estadísticas de unidades -->
            <div class="contenedor-graficos">
                <!-- Gráfico circular de distribución por estado -->
                <div class="tarjeta-grafico">
                    <h3>Distribución por Estado</h3>
                    <div class="canvas-container">
                        <canvas id="graficoEstados"></canvas>
                    </div>
                    <div class="leyenda-grafico" id="leyendaEstados"></div>
                </div>

                <!-- Gráfico de barras por ubicación -->
                <div class="tarjeta-grafico">
                    <h3>Unidades por Ubicación</h3>
                    <div class="canvas-container">
                        <canvas id="graficoUbicaciones"></canvas>
                    </div>
                </div>

                <!-- Gráfico de progreso general -->
                <div class="tarjeta-grafico">
                    <h3>Progreso General</h3>
                    <div class="canvas-container">
                        <canvas id="graficoProgreso"></canvas>
                    </div>
                    <div class="resumen-estadisticas">
                        <div class="estadistica-item">
                            <div class="estadistica-valor" id="totalUnidades">42</div>
                            <div class="estadistica-etiqueta">Total Unidades</div>
                        </div>
                        <div class="estadistica-item">
                            <div class="estadistica-valor" id="unidadesCompletadas">15</div>
                            <div class="estadistica-etiqueta">Completadas</div>
                        </div>
                        <div class="estadistica-item">
                            <div class="estadistica-valor" id="porcentajeCompletado">36%</div>
                            <div class="estadistica-etiqueta">Progreso</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de unidades -->
            <div class="contenedor-tabla">
                <h2>Unidades Activas</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Número Unidad</th>
                            <th>CI</th>
                            <th>Habitaciones</th>
                            <th>Estado</th>
                            <th>Nº Puerta</th>
                            <th>Pasillo</th>
                            <th class="checkbox-seleccion">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tablaUnidades">
                        <tr>
                            <td>U-001</td>
                            <td>8.765.432-1</td>
                            <td>3</td>
                            <td><span class="estado-badge estado-completado">Completada</span></td>
                            <td>101</td>
                            <td>Pasillo A</td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="1">
                            </td>
                        </tr>
                        <tr>
                            <td>U-002</td>
                            <td>5.432.198-7</td>
                            <td>2</td>
                            <td><span class="estado-badge estado-construccion">En Construcción</span></td>
                            <td>102</td>
                            <td>Pasillo A</td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="2">
                            </td>
                        </tr>
                        <tr>
                            <td>U-003</td>
                            <td>3.456.789-0</td>
                            <td>4</td>
                            <td><span class="estado-badge estado-planificacion">En Espera</span></td>
                            <td>201</td>
                            <td>Pasillo B</td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="3">
                            </td>
                        </tr>
                        <tr>
                            <td>U-004</td>
                            <td>2.345.678-9</td>
                            <td>3</td>
                            <td><span class="estado-badge estado-construccion">En Construcción</span></td>
                            <td>202</td>
                            <td>Pasillo B</td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="4">
                            </td>
                        </tr>
                        <tr>
                            <td>U-005</td>
                            <td>1.234.567-8</td>
                            <td>2</td>
                            <td><span class="estado-badge estado-completado">Completada</span></td>
                            <td>301</td>
                            <td>Pasillo C</td>
                            <td class="checkbox-seleccion">
                                <input type="checkbox" class="seleccion-unidad" name="seleccionUnidad" value="5">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Botones flotantes (sin cambios) -->
    <div class="botones-flotantes">
        <div class="boton-flotante" id="botonAcciones">
            <i class="material-icons">tune</i>
            <span class="contador-seleccionados" id="contadorSeleccionados">0</span>
        </div>

        <div class="acciones-multiples" id="accionesMultiples">
            <div class="grupo-botones">
                <button class="btn-accion btn-borrar" id="btnBorrarUnidades">
                    <i class="material-icons">delete</i> Borrar Unidades Seleccionadas
                </button>

                <div>
                    <select class="select-estado" id="selectEstadoUnidad">
                        <option value="">Seleccionar estado...</option>
                        <option value="planificacion">En Espera</option>
                        <option value="construccion">En Construcción</option>
                        <option value="completado">Completada</option>
                    </select>
                    <button class="btn-accion btn-cambiar-estado" id="btnCambiarEstado">
                        <i class="material-icons">swap_horiz</i> Cambiar Estado
                    </button>
                </div>

                <button class="btn-accion btn-modificar" id="btnModificarUnidades">
                    <i class="material-icons">edit</i> Modificar Unidad Habitacional
                </button>
            </div>
        </div>
    </div>

    <script src="../Javascript/BackOffice/generalidades.js" type="module"></script>

    <script>
        // Datos de ejemplo para las unidades
        const datosUnidades = {
            estados: {
                'Completada': 15,
                'En Construcción': 20,
                'En Espera': 7
            },
            ubicaciones: {
                'Pasillo A': 12,
                'Pasillo B': 15,
                'Pasillo C': 10,
                'Pasillo D': 5
            },
            totalUnidades: 42,
            unidadesCompletadas: 15
        };

        // Colores para los gráficos
        const colores = {
            completado: '#27ae60',
            construccion: '#3498db',
            planificacion: '#f39c12',
            suspendido: '#e74c3c'
        };

        // Inicializar gráficos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico circular de distribución por estado
            const ctxEstados = document.getElementById('graficoEstados').getContext('2d');
            const graficoEstados = new Chart(ctxEstados, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(datosUnidades.estados),
                    datasets: [{
                        data: Object.values(datosUnidades.estados),
                        backgroundColor: [
                            colores.completado,
                            colores.construccion,
                            colores.planificacion
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Crear leyenda personalizada
            const leyendaEstados = document.getElementById('leyendaEstados');
            Object.keys(datosUnidades.estados).forEach((estado, index) => {
                const item = document.createElement('div');
                item.className = 'item-leyenda';
                
                const color = document.createElement('div');
                color.className = 'color-leyenda';
                color.style.backgroundColor = Object.values(colores)[index];
                
                const texto = document.createElement('span');
                texto.textContent = `${estado}: ${datosUnidades.estados[estado]}`;
                
                item.appendChild(color);
                item.appendChild(texto);
                leyendaEstados.appendChild(item);
            });

            // Gráfico de barras por ubicación
            const ctxUbicaciones = document.getElementById('graficoUbicaciones').getContext('2d');
            const graficoUbicaciones = new Chart(ctxUbicaciones, {
                type: 'bar',
                data: {
                    labels: Object.keys(datosUnidades.ubicaciones),
                    datasets: [{
                        label: 'Unidades',
                        data: Object.values(datosUnidades.ubicaciones),
                        backgroundColor: colores.construccion,
                        borderColor: colores.construccion,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Unidades: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });

            // Gráfico de progreso general
            const ctxProgreso = document.getElementById('graficoProgreso').getContext('2d');
            const graficoProgreso = new Chart(ctxProgreso, {
                type: 'doughnut',
                data: {
                    labels: ['Completadas', 'Restantes'],
                    datasets: [{
                        data: [
                            datosUnidades.unidadesCompletadas,
                            datosUnidades.totalUnidades - datosUnidades.unidadesCompletadas
                        ],
                        backgroundColor: [
                            colores.completado,
                            '#ecf0f1'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = datosUnidades.totalUnidades;
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '75%'
                }
            });

            // Actualizar estadísticas
            document.getElementById('totalUnidades').textContent = datosUnidades.totalUnidades;
            document.getElementById('unidadesCompletadas').textContent = datosUnidades.unidadesCompletadas;
            document.getElementById('porcentajeCompletado').textContent = 
                Math.round((datosUnidades.unidadesCompletadas / datosUnidades.totalUnidades) * 100) + '%';

            // Funcionalidad para los checkboxes y botones flotantes
            const checkboxes = document.querySelectorAll('.seleccion-unidad');
            const contador = document.getElementById('contadorSeleccionados');
            const botonAcciones = document.getElementById('botonAcciones');
            const botonFlotante = document.querySelector(".boton-flotante");
            const accionesMultiples = document.getElementById('accionesMultiples');
            const btnBorrarUnidades = document.getElementById('btnBorrarUnidades');
            const btnCambiarEstado = document.getElementById('btnCambiarEstado');
            const selectEstado = document.getElementById('selectEstadoUnidad');

            // Actualizar contador de seleccionados
            function actualizarContador() {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked').length;
                contador.textContent = seleccionados;

                if (seleccionados > 0) {
                    botonAcciones.classList.add('activo');
                    accionesMultiples.classList.add('mostrar');
                } else {
                    accionesMultiples.classList.remove('mostrar');
                    botonAcciones.classList.remove('activo');
                }
            }

            // Event listeners para checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarContador);
            });

            // Mostrar/ocultar acciones múltiples con el botón flotante
            botonAcciones.addEventListener('click', function() {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked').length;
                if (seleccionados > 0) {
                    accionesMultiples.classList.toggle('mostrar');
                    botonFlotante.classList.toggle('activo');
                } else {
                    botonFlotante.classList.remove('activo');
                }
            });

            // Acción de borrar unidades seleccionadas
            btnBorrarUnidades.addEventListener('click', function() {
                const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');
                if (seleccionados.length > 0) {
                    if (confirm(`¿Está seguro de que desea eliminar las ${seleccionados.length} unidades seleccionadas?`)) {
                        // Aquí iría la lógica para eliminar las unidades
                        alert(`Se eliminarían ${seleccionados.length} unidades (simulación)`);
                    }
                }
            });

            // Acción de cambiar estado
            btnCambiarEstado.addEventListener('click', function() {
                if (selectEstado.value) {
                    const seleccionados = document.querySelectorAll('.seleccion-unidad:checked');
                    if (seleccionados.length > 0) {
                        if (confirm(`¿Cambiar el estado de ${seleccionados.length} unidades a "${selectEstado.options[selectEstado.selectedIndex].text}"?`)) {
                            // Aquí iría la lógica para cambiar el estado
                            alert(`Se cambiaría el estado de ${seleccionados.length} unidades a ${selectEstado.value} (simulación)`);
                        }
                    } else {
                        alert('Por favor, seleccione al menos una unidad.');
                    }
                } else {
                    alert('Por favor, seleccione un estado.');
                }
            });
        });

        // Funcionalidad para el menú desplegable
        document.querySelectorAll(".item-menu > a").forEach(boton => {
            boton.addEventListener("click", function(e) {
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