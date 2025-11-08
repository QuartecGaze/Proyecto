import { cargarAdmin } from '../../../BackEnd/APIFetchs/APIBackOffice.js';

const formAdmin = document.getElementById("cargarAdmin");

// Función para sanitizar strings básicos (previene XSS y SQL injection)
function sanitizeString(str) {
    if (typeof str !== 'string') return '';
    // Remueve caracteres peligrosos y limita longitud
    return str
        .trim()
        .replace(/[<>\"'`]/g, '') // Remueve caracteres HTML peligrosos
        .substring(0, 255); // Limita longitud
}

// Validación de email
function validarEmail(email) {
    const regex = /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email) && email.length <= 100;
}

// Validación de teléfono (ajusta según tu formato)
function validarTelefono(telefono) {
    const regex = /^[0-9+\-\s()]{8,20}$/;
    return regex.test(telefono);
}

// Validación de CI/cédula (ajusta según tu país)
function validarCI(ci) {
    const regex = /^[0-9]{6,12}$/; // Ajusta según formato de tu país
    return regex.test(ci);
}

// Validación de contraseña fuerte
function validarContraseña(password) {
    // Al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return regex.test(password) && password.length <= 128;
}

formAdmin.addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevenir envío antes de validar
    
    // Capturar y sanitizar valores
    const nombre = sanitizeString(document.getElementById("nombre").value);
    const apellido = sanitizeString(document.getElementById("apellido").value);
    const email = document.getElementById("email").value.trim().toLowerCase();
    const telefono = document.getElementById("telefono").value.trim();
    const ci = document.getElementById("cedula").value.trim();
    const contraseña = document.getElementById("password").value; // No sanitizar password aún
    const nivelPermisosInput = document.querySelector('input[name="nivel_permisos"]:checked')?.value;
    
    // Validaciones del lado del cliente
    const errores = [];
    
    if (!nombre || nombre.length < 2) {
        errores.push("Nombre inválido (mínimo 2 caracteres)");
    }
    
    if (!apellido || apellido.length < 2) {
        errores.push("Apellido inválido (mínimo 2 caracteres)");
    }
    
    if (!validarEmail(email)) {
        errores.push("Email inválido");
    }
    
    if (!validarTelefono(telefono)) {
        errores.push("Teléfono inválido");
    }
    
    if (!validarCI(ci)) {
        errores.push("Cédula inválida");
    }
    
    if (!validarContraseña(contraseña)) {
        errores.push("Contraseña debe tener mínimo 8 caracteres, 1 mayúscula, 1 minúscula y 1 número");
    }
    
    if (!nivelPermisosInput) {
        errores.push("Debe seleccionar un nivel de permisos");
    }
    
    // Mostrar errores si existen
    if (errores.length > 0) {
        document.getElementById("mensajeError").innerText = errores.join(". ");
        document.getElementById("mensajeError").style.display = "block";
        document.getElementById("mensajeExito").style.display = "none";
        return;
    }
    
    // Mapear nivel de permisos de forma segura
    const nivelPermisos = nivelPermisosInput === "Admin" ? 2 : 1;
    
    const datos = {
        ci: ci,
        email: email,
        telefono: telefono,
        nombre: nombre,
        apellido: apellido,
        contraseña: contraseña, // El backend DEBE hashear esto
        nivelPermisos: nivelPermisos,
    };
    
    console.log({...datos, contraseña: '***'}); // No loguear password real
    
    try {
        const data = await cargarAdmin(datos);
        if (data.status === "exito") {
            document.getElementById("mensajeExito").innerText = "Admin creado con éxito";
            document.getElementById("mensajeExito").style.display = "block";
            document.getElementById("mensajeError").style.display = "none";
            formAdmin.reset(); // Limpiar formulario
        } else {
            // Sanitizar mensaje de error del servidor
            const mensajeError = sanitizeString(data.message || "Error al crear el admin");
            document.getElementById("mensajeError").innerText = mensajeError;
            document.getElementById("mensajeError").style.display = "block";
            document.getElementById("mensajeExito").style.display = "none";
        }
    } catch (error) {
        console.error("Error en la API:", error);
        document.getElementById("mensajeError").innerText = "Error de conexión. Intente nuevamente.";
        document.getElementById("mensajeError").style.display = "block";
        document.getElementById("mensajeExito").style.display = "none";
    }
});