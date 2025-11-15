// ../Javascript/LandingPage.js
import { setIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';

let idiomaActual = 'es';

// ===== Referencias DOM =====
const botonIdioma = document.getElementById("botonIdioma");
const preguntaHeader = document.querySelectorAll(".pregunta-header");
const menuToggle = document.querySelector(".menu-toggle");
const navMenu = document.querySelector(".nav-menu");

// ===== Menú móvil =====
if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', function () {
        navMenu.classList.toggle('activo');
    });
}

// ===== FAQ Accordion =====
preguntaHeader.forEach(element => {
    element.addEventListener('click', function () {
        const pregunta = this.parentElement;
        const respuesta = pregunta.querySelector('.respuesta');
        const icono = this.querySelector('.material-icons');

        // Cerrar otras
        document.querySelectorAll('.pregunta').forEach(p => {
            if (p !== pregunta) {
                p.classList.remove('activa');
                const r = p.querySelector('.respuesta');
                const i = p.querySelector('.material-icons');
                if (r) r.style.maxHeight = null;
                if (i) i.textContent = 'add';
            }
        });

        // Alternar actual
        pregunta.classList.toggle('activa');
        if (pregunta.classList.contains('activa')) {
            respuesta.style.maxHeight = respuesta.scrollHeight + 'px';
            if (icono) icono.textContent = 'remove';
        } else {
            respuesta.style.maxHeight = null;
            if (icono) icono.textContent = 'add';
        }
    });
});

// ===== Cambio de idioma (botón flotante) =====
if (botonIdioma) {
    botonIdioma.addEventListener("click", async function () {
        idiomaActual = (idiomaActual === "es") ? "en" : "es";

        const datos = {
            pagina: "landing",
            idioma: idiomaActual
        };

        try {
            const data = await setIdioma(datos);
            aplicarIdioma(data);
        } catch (error) {
            console.error("Error al cambiar idioma:", error);
        }
    });
}

// ===== Inicializar idioma como en login/registro =====
try {
    const data = await getIdioma("landing");
    aplicarIdioma(data);
} catch (error) {
    console.error("Error al inicializar idioma:", error);
}
