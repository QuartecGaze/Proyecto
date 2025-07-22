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
        </div>
    </header>

    <main>
        <h1>Formulario de ingreso a la cooperativa <br> <p id="nombre">- Nombre</p></h1>

        <h2>1. Entrevista Agendada</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="entrevista">Estado-Entrevista</span>
            </div>
            <div id="fechaEntrevista">Fecha y hora de la entrevista</div>
            <div id="Observaciones">Observaciones: Entrevista presencial en sede cooperativa. Dirección Av.Gral Rivera 3729 bis, 11600 Montevideo</div>
        </div>

        <div class="divider"></div>

        <h2>2. Subir Antecedentes</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="antecedentes">Estado-Antecedentes</span>
            </div>
            <div class="upload-section">
                <div>Haz clic para subir PDF de antecedentes</div>
                <div class="file-info">Ningún archivo seleccionado</div>
            </div>
        </div>

        <div class="divider"></div>

        <h2>3. Comprobante de Pago Inicial</h2>
        <div class="tarjeta">
            <div class="estado-linea">
                <span class="estado-label">Estado:</span>
                <span class="estado-badge" id="pago-inicial">Estado-PagoInicial</span>
            </div>
            <div class="cantidad" id="montoPago">Monto-PagoInicial</div>
            <div class="upload-section">
                <div>Haz clic para subir tu comprobante</div>
                <div class="file-info">Ningún archivo seleccionado</div>
            </div>
        </div>
    </main>

    <footer class="footer-registro">
        <p>&copy; 2025 Senda Firme. Todos los derechos reservados.</p>
    </footer>
</body>
<script src="../Javascript/interesado.js" type="module"></script>
</html>