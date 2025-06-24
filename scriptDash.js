document.addEventListener('DOMContentLoaded', function() {
    // Menú hamburguesa para versión móvil
    const menuHamburguesa = document.createElement('button');
    menuHamburguesa.className = 'menu-celular';
    menuHamburguesa.id = 'MenuHamburguesa';
    menuHamburguesa.innerHTML = '<span class="material-icons">menu</span>';
    
    const sidebar = document.querySelector('.sidebar');
    const contenidoPrincipal = document.querySelector('.contenido-principal');
    
    // Solo añadir el botón si estamos en móvil
    if (window.innerWidth < 768) {
        contenidoPrincipal.prepend(menuHamburguesa);
        sidebar.style.display = 'none';
    }
    
    // Toggle del sidebar en móvil
    menuHamburguesa.addEventListener('click', function() {
        if (sidebar.style.display === 'none' || !sidebar.style.display) {
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
        }
    });
    
    // Manejar cambios de tamaño de pantalla
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.style.display = 'block';
            if (document.contains(menuHamburguesa)) {
                menuHamburguesa.remove();
            }
        } else {
            if (!document.contains(menuHamburguesa)) {
                contenidoPrincipal.prepend(menuHamburguesa);
                sidebar.style.display = 'none';
            }
        }
    });
    
    // Activar elementos del menú al hacer clic
    const itemsMenu = document.querySelectorAll('.item-menu');
    itemsMenu.forEach(item => {
        item.addEventListener('click', function() {
            itemsMenu.forEach(i => i.classList.remove('activo'));
            this.classList.add('activo');
            
            // En móvil, cerrar el menú después de seleccionar
            if (window.innerWidth < 768) {
                sidebar.style.display = 'none';
            }
        });
    });
    
    // Simular carga de datos
    setTimeout(() => {
        console.log('Datos cargados para el dashboard');
    }, 1000);
});