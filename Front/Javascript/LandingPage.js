 let idiomaActual = 'es';
 const botonIdioma = document.getElementById("botonIdioma");
 import { setIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
 import { getIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
 import { aplicarIdioma } from '../../BackEnd/APIFetchs/APITraduccion.js';
        // Menú móvil
        document.getElementById('menu-hamburguesa').addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('activo');
        });

        // Funcionalidad FAQ acordeón
        function toggleRespuesta(element) {
            const pregunta = element.parentElement;
            const respuesta = pregunta.querySelector('.respuesta');
            const icono = element.querySelector('.material-icons');
            
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
        }

        botonIdioma.addEventListener("click", async function toggleIdioma(){
            if(idiomaActual == "es"){
                idiomaActual = "en";
            } else {
                idiomaActual = "es";
            }
            const datos = {
                pagina: "landing",
                idioma: idiomaActual
            }
            const data = await setIdioma(datos);
            aplicarIdioma(data);
            
        });

        const data = await getIdioma("landing");
        aplicarIdioma(data);
        //FETCH PARA CONSEGUIR LOS DATOS DEL IDIOMA
       //CONSEGUIR EL IDIOMA YA ASIGNADO
