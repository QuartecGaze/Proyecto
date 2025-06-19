document.querySelectorAll('nav a').forEach(enlace => {
    enlace.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
        
        const navegacion = document.getElementById('navegacion-principal');
        navegacion.classList.remove('activo');
    });
});

const menuHamburguesa = document.getElementById('menu-hamburguesa');
const navegacion = document.getElementById('navegacion-principal');

menuHamburguesa.addEventListener('click', () => {
    navegacion.classList.toggle('activo');
    menuHamburguesa.classList.toggle('activo');
});

document.addEventListener('click', (e) => {
    if (!navegacion.contains(e.target) && !menuHamburguesa.contains(e.target)) {
        navegacion.classList.remove('activo');
        menuHamburguesa.classList.remove('activo');
    }
});