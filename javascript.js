let nav = document.querySelector('nav');
let burguerIco = document.querySelector('.burger-ico');
let shadow = document.querySelector('.shadow');
let header = document.querySelector('header');

let menuToggle = () => {
    nav.classList.toggle('show-menu');
    burguerIco.classList.toggle('burger-active');
    shadow.classList.toggle('shadow-active');
    header.classList.toggle('blur-effect');
};

burguerIco.addEventListener('click', menuToggle);
shadow.addEventListener('click', menuToggle);
