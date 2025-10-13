// socios.js - Funcionalidad para editar información personal de socios

document.addEventListener('DOMContentLoaded', function () {
    // Elementos del modal
    const modal = document.getElementById('modalUsuario');
    const btnEditar = document.getElementById('btnEditarUsuario');
    const btnGuardar = document.getElementById('btnGuardarCambios');
    const btnCancelar = document.getElementById('btnCancelarEdicion');
    const btnCerrar = document.getElementById('btnCerrarModal');

    // Elementos de visualización (spans)
    const spans = {
        nombre: document.getElementById('modalNombre'),
        cedula: document.getElementById('modalCedula'),
        fechaNacimiento: document.getElementById('modalFechaNacimiento'),
        direccion: document.getElementById('modalDireccion'),
        email: document.getElementById('modalEmail'),
        telefono: document.getElementById('modalTelefono')
    };

    // Elementos de entrada (inputs)
    const inputs = {
        nombre: document.getElementById('inputNombre'),
        cedula: document.getElementById('inputCedula'),
        fechaNacimiento: document.getElementById('inputFechaNacimiento'),
        direccion: document.getElementById('inputDireccion'),
        email: document.getElementById('inputEmail'),
        telefono: document.getElementById('inputTelefono')
    };

    // Datos originales (para restaurar en caso de cancelar)
    let datosOriginales = {};

    // Función para formatear fecha de DD/MM/AAAA a AAAA-MM-DD (para input type="date")
    function formatearFechaParaInput(fecha) {
        if (!fecha) return '';

        const partes = fecha.split('/');
        if (partes.length === 3) {
            return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        }
        return fecha;
    }

    // Función para formatear fecha de AAAA-MM-DD a DD/MM/AAAA (para mostrar)
    function formatearFechaParaMostrar(fecha) {
        if (!fecha) return '';

        const partes = fecha.split('-');
        if (partes.length === 3) {
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }
        return fecha;
    }

    // Función para activar el modo edición
    function activarModoEdicion() {
        // Guardar datos originales
        guardarDatosOriginales();

        // Ocultar spans y mostrar inputs
        Object.keys(spans).forEach(key => {
            if (inputs[key]) {
                spans[key].style.display = 'none';
                inputs[key].style.display = 'block';

                // Caso especial para fecha de nacimiento
                if (key === 'fechaNacimiento') {
                    inputs[key].value = formatearFechaParaInput(spans[key].textContent);
                } else {
                    inputs[key].value = spans[key].textContent;
                }
            }
        });

        // Cambiar visibilidad de botones
        btnEditar.style.display = 'none';
        btnGuardar.style.display = 'block';
        btnCancelar.style.display = 'block';
    }

    // Función para desactivar el modo edición
    function desactivarModoEdicion() {
        // Ocultar inputs y mostrar spans
        Object.keys(spans).forEach(key => {
            if (inputs[key]) {
                spans[key].style.display = 'block';
                inputs[key].style.display = 'none';
            }
        });

        // Cambiar visibilidad de botones
        btnEditar.style.display = 'block';
        btnGuardar.style.display = 'none';
        btnCancelar.style.display = 'none';
    }

    // Función para guardar datos originales
    function guardarDatosOriginales() {
        datosOriginales = {
            nombre: spans.nombre.textContent,
            cedula: spans.cedula.textContent,
            fechaNacimiento: spans.fechaNacimiento.textContent,
            direccion: spans.direccion.textContent,
            email: spans.email.textContent,
            telefono: spans.telefono.textContent
        };
    }

    // Función para restaurar datos originales
    function restaurarDatosOriginales() {
        spans.nombre.textContent = datosOriginales.nombre;
        spans.cedula.textContent = datosOriginales.cedula;
        spans.fechaNacimiento.textContent = datosOriginales.fechaNacimiento;
        spans.direccion.textContent = datosOriginales.direccion;
        spans.email.textContent = datosOriginales.email;
        spans.telefono.textContent = datosOriginales.telefono;
    }

    // Función para validar datos antes de guardar
    function validarDatos() {
        let errores = [];

        // Validar nombre (no vacío)
        if (!inputs.nombre.value.trim()) {
            errores.push('El nombre no puede estar vacío');
        }

        // Validar cédula (no vacía)
        if (!inputs.cedula.value.trim()) {
            errores.push('La cédula no puede estar vacía');
        }

        // Validar fecha de nacimiento
        if (!inputs.fechaNacimiento.value) {
            errores.push('La fecha de nacimiento es obligatoria');
        } else {
            // Validar que la fecha no sea futura
            const fechaNacimiento = new Date(inputs.fechaNacimiento.value);
            const hoy = new Date();
            if (fechaNacimiento > hoy) {
                errores.push('La fecha de nacimiento no puede ser futura');
            }

            // Validar edad mínima (por ejemplo, 18 años)
            const edadMinima = new Date();
            edadMinima.setFullYear(edadMinima.getFullYear() - 18);
            if (fechaNacimiento > edadMinima) {
                errores.push('El socio debe ser mayor de 18 años');
            }
            
            // Validar edad máxima (por ejemplo, 100 años)
            const edadMaxima = new Date();
            edadMaxima.setFullYear(edadMaxima.getFullYear() - 100);
            if (fechaNacimiento < edadMaxima) {
                errores.push('La fecha de nacimiento no es válida');
            }
        }

        // Validar email (formato básico)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (inputs.email.value && !emailRegex.test(inputs.email.value)) {
            errores.push('El formato del email no es válido');
        }
        return errores;
    }

    // Función para calcular edad a partir de la fecha de nacimiento
    function calcularEdad(fechaNacimiento) {
        const nacimiento = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }
        return edad;
    }

    // Función para guardar cambios
    function guardarCambios() {
        // Validar datos antes de guardar
        const errores = validarDatos();
        if (errores.length > 0) {
            alert('Errores en el formulario:\n' + errores.join('\n'));
            return;
        }
        // Actualizar spans con los valores de los inputs
        spans.nombre.textContent = inputs.nombre.value;
        spans.cedula.textContent = inputs.cedula.value;
        spans.fechaNacimiento.textContent = formatearFechaParaMostrar(inputs.fechaNacimiento.value);
        spans.direccion.textContent = inputs.direccion.value;
        spans.email.textContent = inputs.email.value;
        spans.telefono.textContent = inputs.telefono.value;

        // Calcular y mostrar edad (opcional)
        const edad = calcularEdad(inputs.fechaNacimiento.value);
        console.log(`Edad calculada: ${edad} años`);

        // Aquí iría la lógica para enviar los datos al servidor
        const datosParaGuardar = {
            nombre: inputs.nombre.value,
            cedula: inputs.cedula.value,
            fechaNacimiento: inputs.fechaNacimiento.value,
            direccion: inputs.direccion.value,
            email: inputs.email.value,
            telefono: inputs.telefono.value
        };

        console.log('Datos guardados:', datosParaGuardar);
        // Desactivar modo edición
        desactivarModoEdicion();
        // Mostrar mensaje de éxito
        alert('Los cambios se han guardado correctamente');
    }

    // Event Listeners
    btnEditar.addEventListener('click', activarModoEdicion);
    btnGuardar.addEventListener('click', guardarCambios);
    btnCancelar.addEventListener('click', function () {
        restaurarDatosOriginales();
        desactivarModoEdicion();
    });

    // Cerrar modal
    btnCerrar.addEventListener('click', function () {
        // Si estamos en modo edición, cancelar antes de cerrar
        if (btnGuardar.style.display !== 'none') {
            restaurarDatosOriginales();
            desactivarModoEdicion();
        }
        modal.style.display = 'none';
    });

    // Cerrar modal al hacer clic en la X
    document.querySelector('.cerrar-modal').addEventListener('click', function () {
        // Si estamos en modo edición, cancelar antes de cerrar
        if (btnGuardar.style.display !== 'none') {
            restaurarDatosOriginales();
            desactivarModoEdicion();
        }
        modal.style.display = 'none';
    });

    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            // Si estamos en modo edición, cancelar antes de cerrar
            if (btnGuardar.style.display !== 'none') {
                restaurarDatosOriginales();
                desactivarModoEdicion();
            }
            modal.style.display = 'none';
        }
    });

    // Permitir enviar formulario con Enter y cancelar con Escape
    document.addEventListener('keydown', function (event) {
        // Solo si estamos en modo edición
        if (btnGuardar.style.display !== 'none') {
            if (event.key === 'Enter' && event.ctrlKey) {
                guardarCambios();
            } else if (event.key === 'Escape') {
                restaurarDatosOriginales();
                desactivarModoEdicion();
            }
        }
    });
});



// MODAL - Funcionalidad para abrir y cerrar el modal de usuario

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalUsuario');
    const btnCerrar = document.getElementById('btnCerrarModal');

    function abrirModal() {
        modal.style.display = 'flex';
        desactivarModoEdicion();
    }

    function cerrarModal() {
        modal.style.display = 'none';
    }

    function desactivarModoEdicion() {
        const btnEditar = document.getElementById('btnEditarUsuario');
        const btnGuardar = document.getElementById('btnGuardarCambios');
        const btnCancelar = document.getElementById('btnCancelarEdicion');
        document.querySelectorAll('.campo-editable').forEach(input => {
            input.style.display = 'none';
        });
        document.querySelectorAll('.info-completa span').forEach(span => {
            span.style.display = 'block';
        });
        if (btnEditar) btnEditar.style.display = 'block';
        if (btnGuardar) btnGuardar.style.display = 'none';
        if (btnCancelar) btnCancelar.style.display = 'none';
    }

    document.querySelectorAll('.actions button').forEach(boton => {
        boton.addEventListener('click', function () {
            abrirModal();
        });
    });

    btnCerrar.addEventListener('click', cerrarModal);
    document.querySelector('.cerrar-modal').addEventListener('click', cerrarModal);

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            cerrarModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display === 'flex') {
            cerrarModal();
        }
    });
});
