<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once '../verificarSesion.php';
    verificarAcceso(['Interesado', 'Admin']);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Registro</title>
    <link rel="stylesheet" href="../Css/estilosInteresado.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <header class="header-registro">
        <div class="contenedor-header">
            <div class="logo">
                <a href="../Landing Page/index.html">
                    <img src="../../Fotos/logo.webp" alt="Logo Cooperativa">
                </a>
                <span>Senda Firme</span>
            </div>
            <div class="cerrar-sesion">
                <form action="../cerrarSesion.php">
                    <button class="boton-cerrar-sesion">
                        <i class="material-icons">logout</i> Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main>
        <h1>Formulario de ingreso a la cooperativa <br>
            <p id="nombre">- Nombre</p>
        </h1>

        <div class="residentes-container">
            <div class="residentes-header">
                <i class="material-icons">groups</i>
                <h2>Datos de los Residentes</h2>
            </div>

            <p class="section-description">Proporcione la información de todas las personas que residirán en la unidad
                habitacional.</p>

            <div class="selector-personas">
                <label for="cantidad-personas">Número de residentes:</label>
                <select id="cantidad-personas">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active">Persona 1</button>
                    <button class="tab-btn">Persona 2</button>
                    <button class="tab-btn">Persona 3</button>
                </div>

                <div class="tabs-content">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="required-field">Nombre</label>
                            <input type="text" placeholder="Ingrese nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="required-field">Apellido</label>
                            <input type="text" placeholder="Ingrese apellido" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="required-field">Cédula de Identidad</label>
                            <input type="text" placeholder="Ej: 1.234.567-8" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="required-field">Fecha de Nacimiento</label>
                            <input type="date" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="required-field">Correo electrónico</label>
                            <input type="email" placeholder="ejemplo@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="tel" placeholder="+598 XXX XXX">
                        </div>
                    </div>
                    
                </div>

                <div class="form-actions">
                    <button class="btn-guardar">
                        <i class="material-icons">save</i> Guardar todos los datos
                    </button>
                </div>
            </div>
        </div>

        <!-- Resto del contenido existente -->
        <h2>1. Entrevista Agendada</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="entrevista">Estado-Entrevista</span>
            </div>
            <div id="fechaEntrevista">Fecha y hora de la entrevista</div>
            <div id="Observaciones">Observaciones: Entrevista presencial en sede cooperativa. Dirección Av.Gral Rivera
                3729 bis, 11600 Montevideo</div>
        </div>

        <div class="divider"></div>

        <h2>2. Subir Antecedentes</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="antecedentes">Estado-Antecedentes</span>
            </div>
            <div class="upload-section" onclick="document.getElementById('file-antecedentes').click()">
                <div>Haz clic para subir PDF de antecedentes</div>
                <div class="file-info" id="file-info-antecedentes">Ningún archivo seleccionado</div>
                <input type="file" id="file-antecedentes" accept=".pdf" style="display: none;">
            </div>
        </div>
        <div class="acciones">
            <button class="btn-aprobar" id="antecedentesBtn">
                <i class="material-icons">check</i> Confirmar Subida
            </button>
        </div>

        <div class="divider"></div>

        <h2>3. Comprobante de Pago Inicial</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="pago-inicial">Estado-PagoInicial</span>
            </div>
            <div class="cantidad" id="montoPago">Monto-PagoInicial</div>
            <div class="upload-section" onclick="document.getElementById('file-pago').click()">
                <div>Haz clic para subir tu comprobante</div>
                <div class="file-info" id="file-info-pago">Ningún archivo seleccionado</div>
                <input type="file" id="file-pago" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
            </div>

            <!-- Nueva sección de métodos de pago -->
            <div id="metodos-pago">
                <h3>Métodos de pago disponibles:</h3>
                <div class="metodos-container">
                    <div class="metodo">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal"
                            class="logo-pago">
                        <p>PayPal: pagos@senda-firme.com</p>
                    </div>
                    <div class="metodo">
                        <img src="../../Fotos/BROU.webp" alt="BROU" class="logo-pago">
                        <p>Transferencia BROU: 123456789-12345</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="acciones">
            <button class="btn-aprobar" id="comprobante">
                <i class="material-icons">check</i> Confirmar Subida
            </button>
        </div>
    </main>

    <footer class="footer-registro">
        <p>&copy; 2025 Senda Firme. Todos los derechos reservados.</p>
    </footer>
</body>
<script src="../Javascript/interesado.js" type="module"></script>
</html>