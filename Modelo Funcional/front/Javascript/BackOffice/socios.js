// socios.js - Versión funcional sin conexión a base de datos

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalUsuario');
    const btnCerrar = document.getElementById('btnCerrarModal');
    const btnEditar = document.getElementById('btnEditarUsuario');
    const btnGuardar = document.getElementById('btnGuardarCambios');
    const btnCancelar = document.getElementById('btnCancelarEdicion');

    // Función para abrir modal
    function abrirModal() {
        modal.style.display = 'flex';
        desactivarModoEdicion();
    }

    // Función para cerrar modal
    function cerrarModal() {
        modal.style.display = 'none';
    }

    // Modo edición: mostrar/ocultar inputs
    function activarModoEdicion() {
        document.querySelectorAll('.campo-editable').forEach(input => input.style.display = 'block');
        document.querySelectorAll('.info-completa span').forEach(span => span.style.display = 'none');
        btnEditar.style.display = 'none';
        btnGuardar.style.display = 'block';
        btnCancelar.style.display = 'block';
    }

    function desactivarModoEdicion() {
        document.querySelectorAll('.campo-editable').forEach(input => input.style.display = 'none');
        document.querySelectorAll('.info-completa span').forEach(span => span.style.display = 'block');
        btnEditar.style.display = 'block';
        btnGuardar.style.display = 'none';
        btnCancelar.style.display = 'none';
    }

    // Vincular botones
    document.querySelectorAll('.actions button').forEach(boton => {
        boton.addEventListener('click', abrirModal);
    });

    btnCerrar.addEventListener('click', cerrarModal);
    document.querySelectorAll('.cerrar-modal').forEach(btn => btn.addEventListener('click', cerrarModal));
    btnEditar.addEventListener('click', activarModoEdicion);
    btnCancelar.addEventListener('click', desactivarModoEdicion);

    // Cerrar al hacer clic fuera del modal
    window.addEventListener('click', function (event) {
        if (event.target === modal) cerrarModal();
    });

    // Cerrar con Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.style.display === 'flex') cerrarModal();
    });
});
