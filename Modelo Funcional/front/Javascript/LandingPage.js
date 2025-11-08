let idiomaActual = 'es';
const botonIdioma = document.getElementById("botonIdioma");
const preguntaHeader = document.querySelectorAll(".pregunta-header");
import { setIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';

// Menú móvil
document.querySelector('.menu-toggle').addEventListener('click', function() {
    const nav = document.querySelector('.nav-menu');
    nav.classList.toggle('activo');
});

// FAQ Accordion
preguntaHeader.forEach(element => {
    element.addEventListener('click', function(){
        const pregunta = this.parentElement;
        const respuesta = pregunta.querySelector('.respuesta');
        const icono = this.querySelector('.material-icons');
        
        // Cerrar todas las demás preguntas
        document.querySelectorAll('.pregunta').forEach(p => {
            if (p !== pregunta) {
                p.classList.remove('activa');
                p.querySelector('.respuesta').style.maxHeight = null;
                p.querySelector('.material-icons').textContent = 'add';
            }
        });
        
        // Alternar pregunta actual
        pregunta.classList.toggle('activa');
        if (pregunta.classList.contains('activa')) {
            respuesta.style.maxHeight = respuesta.scrollHeight + 'px';
            icono.textContent = 'remove';
        } else {
            respuesta.style.maxHeight = null;
            icono.textContent = 'add';
        }
    });
});

// Idioma toggle
botonIdioma.addEventListener("click", async function toggleIdioma(){
    if(idiomaActual == "es"){
        idiomaActual = "en";
    } else {
        idiomaActual = "es";
    }
    const datos = {
        pagina: "landing",
        idioma: idiomaActual
    };
    const data = await setIdioma(datos);
    aplicarIdioma(data);
});

// Función para inicializar el idioma
async function inicializarIdioma() {
    try {
        const data = await getIdioma("landing");
        aplicarIdioma(data);
    } catch (error) {
        console.error("Error al inicializar el idioma:", error);
    }
}

// Llamar a la función de inicialización
inicializarIdioma();
// Funcionalidades adicionales (scroll, animaciones, etc.)
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling para links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // cerrar mobile menu si abre
                const navMenu = document.querySelector('.nav-menu');
                if (navMenu.classList.contains('activo')) {
                    navMenu.classList.remove('activo');
                }
                
                // calcular posicion considerando fixed header
                const headerHeight = document.querySelector('header').offsetHeight;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // scroll reveal animation para sections
    const sections = document.querySelectorAll('section:not(.hero), footer');
    
    const elementInView = (el, dividend = 1) => {
        const elementTop = el.getBoundingClientRect().top;
        return (
            elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
        );
    };
    
    const elementOutOfView = (el) => {
        const elementTop = el.getBoundingClientRect().top;
        return (
            elementTop > (window.innerHeight || document.documentElement.clientHeight)
        );
    };
    
    const displayScrollElement = (element) => {
        element.classList.add('visible');
    };
    
    const hideScrollElement = (element) => {
        element.classList.remove('visible');
    };
    
    const handleScrollAnimation = () => {
        sections.forEach((el) => {
            if (elementInView(el, 1.2)) {
                displayScrollElement(el);
            } else if (elementOutOfView(el)) {
                hideScrollElement(el);
            }
        });
    };
    
    // iniciar scroll animation
    window.addEventListener('scroll', () => {
        handleScrollAnimation();
    });
    
    // correr cuando cargue
    handleScrollAnimation();
    
    // Header background change scroll
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            header.style.background = 'rgba(30, 35, 68, 0.98)';
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'linear-gradient(135deg, var(--primarioOscuro), var(--primario))';
            header.style.boxShadow = 'var(--sombra)';
        }
    });
    
    // Counter animation for stats
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    const startCounter = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText.replace(/\D/g, "");
            const increment = Math.ceil(target / speed);
            
            if (count < target && elementInView(counter, 1.3)) {
                counter.innerText = count + increment + '+';
                setTimeout(() => startCounter(), 1);
            } else {
                counter.innerText = target + '+';
            }
        });
    };
    
    //contador cuando se vea
    let counterStarted = false;
    
    const checkCounter = () => {
        if (!counterStarted && elementInView(document.querySelector('.hero-stats'), 1.5)) {
            startCounter();
            counterStarted = true;
        }
    };
    
    window.addEventListener('scroll', checkCounter);
    // correr cuando cargue
    checkCounter();
    
    // Carrousel 
    const carrouselItems = document.querySelectorAll('.carrousel-item');
    let currentIndex = 0;
    
    function showNextSlide() {
        carrouselItems[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % carrouselItems.length;
        carrouselItems[currentIndex].classList.add('active');
    }
    
    // Cambia foto cada 5 segundos
    setInterval(showNextSlide, 5000);
});