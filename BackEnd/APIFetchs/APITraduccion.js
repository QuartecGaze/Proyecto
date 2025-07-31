import { apiRequest } from './apiConfig.js';
/**
 * Llama a la API para iniciar sesión.
 * @param {Object} datos 
 * @returns {Promise<Object>} - Respuesta de la API
 */
export function setIdioma(datos) {
     const data = apiRequest('/APITraduccion/ApiTraduccion.php?accion=setIdioma', 'POST', datos);
     return data;
}

export function getIdioma(pagina) {
    const data = apiRequest('/APITraduccion/ApiTraduccion.php?accion=getIdioma&pagina=' + pagina, 'GET');
    return data;
}

export function aplicarIdioma(data){
    for (const [clave, valor] of Object.entries(data.message)) {
        const elementos = document.querySelectorAll(`.${clave}`);

        elementos.forEach(el => {
          if (el.tagName.toLowerCase() === 'nav') {
            const ul = el.querySelector('ul');
            if (ul) {
              const items = valor.split(';');
              const lis = ul.querySelectorAll('li');

              lis.forEach((li, index) => {
                const a = li.querySelector('a');
                if (a) {
                  a.innerHTML = items[index] ?? '';
                }
              });
            }
          }

          else if (el.tagName.toLowerCase() === 'form') {
            const items = valor.split(';');
            const labels = el.querySelectorAll('label[for]');
            labels.forEach((label, index) => {
              label.innerHTML = items[index] ?? '';
              });
          } else {
            el.innerHTML = valor;
          }
        });
    }
  }