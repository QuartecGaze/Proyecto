-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2025 a las 06:01:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cooperativa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traducciones`
--

CREATE TABLE `traducciones` (
  `pagina` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `idioma` varchar(10) NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `traducciones`
--

INSERT INTO `traducciones` (`pagina`, `clave`, `idioma`, `texto`) VALUES
('', 'contactanos-form-label', '', 'Name;Email;Phone Number (e.g. 098123456);Message'),
('landing', 'comunidad-titulo', 'en', 'Solid community'),
('landing', 'comunidad-titulo', 'es', 'Comunidad Sólida'),
('landing', 'comunidad-texto', 'en', 'Projects designed to endure over time and benefit future generations.'),
('landing', 'comunidad-texto', 'es', 'Proyectos diseñados para perdurar en el tiempo y beneficiar a futuras generaciones.'),
('landing', 'contactanos-btn', 'en', 'Send Message'),
('landing', 'contactanos-btn', 'es', 'Enviar Mensaje'),
('landing', 'contactanos-form-label', 'en', 'Name;Email;Phone Number (e.g. 098123456);Message'),
('landing', 'contactanos-form-label', 'es', 'Nombre;Email;Telefono (Ej: 098123456);Mensaje'),
('landing', 'contactanos-titulo', 'en', 'CONTACT US'),
('landing', 'contactanos-titulo', 'es', 'CONTACTANOS'),
('landing', 'decision-titulo', 'en', 'Democratic Decisions'),
('landing', 'decision-titulo', 'es', 'Decisión Democrática'),
('landing', 'decision-texto', 'en', 'Each member has a voice and a vote in the important decisions that affect the community.'),
('landing', 'decision-texto', 'es', 'Cada miembro tiene voz y voto en las decisiones importantes que afectan a la comunidad.'),
('landing', 'faq-titulo2', 'es', 'Qué necesito para ser Apto?'),
('landing', 'faqs-texto1', 'en', 'Once your registration is complete, our team will get in touch to schedule an interview and explain the next steps.'),
('landing', 'faqs-texto1', 'es', 'Una vez completado tu registro, nuestro equipo se pondrá en contacto para programar una entrevista y explicarte los próximos pasos.'),
('landing', 'faqs-texto2', 'en', 'We require commitment to cooperative values, active participation in assemblies, and contribution to the common project.'),
('landing', 'faqs-texto2', 'es', 'Requerimos compromiso con los valores cooperativos, participación activa en las asambleas y contribución al proyecto común.'),
('landing', 'faqs-texto3', 'en', 'Work varies depending on the project, but all members contribute at least 8 hours per month to community activities.'),
('landing', 'faqs-texto3', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 8 horas mensuales a las actividades comunitarias.'),
('landing', 'faqs-texto4', 'en', 'Contact us through the form, and we will get back to you shortly.'),
('landing', 'faqs-texto4', 'es', 'Contactanos a través del formulario y te responderemos a la brevedad.'),
('landing', 'faqs-titulo-4', 'es', '¿Tenes más preguntas?'),
('landing', 'faqs-titulo1', 'en', 'What happens when I register?'),
('landing', 'faqs-titulo1', 'es', '¿Qué pasa al momento de registrarme?'),
('landing', 'faqs-titulo2', 'es', '¿Qué necesito para ser Apto?'),
('landing', 'faqs-titulo2', 'en', 'What do I need to qualify?'),
('landing', 'faqs-titulo3', 'en', 'How much do I have to work in the cooperative?'),
('landing', 'faqs-titulo3', 'es', '¿Qué tanto hay que trabajar en la cooperativa?'),
('landing', 'faqs-titulo4', 'es', '¿Tenes más preguntas?'),
('landing', 'faqs-titulo4', 'en', 'Do you have any more questions?'),
('landing', 'footer-contacto-telefono', 'en', 'Phone Number: +598 92 124 491'),
('landing', 'footer-contacto-telefono', 'es', 'Teléfono: +598 92 124 491'),
('landing', 'footer-derechos', 'en', '© 2025 Cooperativa Nombre. All rights reserved.'),
('landing', 'footer-derechos', 'es', '© 2025 Cooperativa Nombre. Todos los derechos reservados.'),
('landing', 'header-nav', 'en', 'Home;About us;Localization;FAQ;Contact'),
('landing', 'header-nav', 'es', 'Inicio;Sobre nosotros;Localizacion;FAQ;Contacto'),
('landing', 'hero-btn', 'en', 'BOOK YOUR SPOT'),
('landing', 'hero-btn', 'es', 'RESERVA TU LUGAR'),
('landing', 'hero-texto', 'en', 'In a world that divides, we choose to unite—to share, support, and build opportunities through cooperation.'),
('landing', 'hero-texto', 'es', 'En un mundo que divide, elegimos unirnos para compartir, apoyarnos y construir oportunidades desde la cooperación.'),
('landing', 'hero-titulo', 'es', 'Cooperativa<br>nombre'),
('landing', 'localizacion-texto', 'en', 'We are located at Av. Perú and Magallanes'),
('landing', 'localizacion-texto', 'es', 'Nos encontramos en Av. Perú y Magallanes'),
('landing', 'localizacion-titulo', 'en', 'LOCALIZATION'),
('landing', 'localizacion-titulo', 'es', 'LOCALIZACIÓN'),
('landing', 'por-que-elegirnos-btn', 'en', 'Join our community'),
('landing', 'por-que-elegirnos-btn', 'es', 'Sumate a nuestra comunidad'),
('landing', 'por-que-elegirnos-titulo', 'en', 'WHY CHOOSE US?'),
('landing', 'por-que-elegirnos-titulo', 'es', '¿POR QUÉ ELEGIRNOS?'),
('landing', 'que-hacemos-btn', 'en', 'Join us'),
('landing', 'que-hacemos-btn', 'es', 'Asociate'),
('landing', 'que-hacemos-texto1', 'en', 'We are a mutual aid cooperative organized under a collective and democratic management model. Every member actively participates in decision-making and planning actions aimed at improving the community’s quality of life.'),
('landing', 'que-hacemos-texto1', 'es', 'Somos una cooperativa de ayuda mutua organizada bajo un modelo de gestión colectiva y democrática. Cada integrante participa activamente en las decisiones y en la planificación de acciones orientadas a mejorar la calidad de vida de la comunidad.'),
('landing', 'que-hacemos-texto2', 'en', 'Through networks of solidarity, we build shared solutions that arise from the grassroots, prioritizing self-management, equity, and collective well-being.'),
('landing', 'que-hacemos-texto2', 'es', 'A través de redes solidarias, construimos soluciones compartidas que surgen desde abajo, priorizando la autogestión, la equidad y el bienestar colectivo.'),
('landing', 'que-hacemos-titulo', 'en', 'WHAT WE DO'),
('landing', 'que-hacemos-titulo', 'es', '¿QUÉ HACEMOS?'),
('landing', 'iniciarsesion-btn', 'en', 'Sign Up'),
('landing', 'iniciarsesion-btn', 'es', 'Iniciar Sesion'),
('landing', 'sostenibilidad-titulo', 'es', 'Sostenibilidad\r\n\r\n');
('landing', 'sostenibilidad-titulo', 'en', 'Sustainability\r\n\r\n');
('landing', 'sostenibilidad-texto', 'es', 'Forma parte de una red de apoyo mutuo donde todos contribuimos y nos beneficiamos del esfuerzo colectivo.');
('landing', 'sostenibilidad-texto', 'en', 'Be part of a mutual support network where everyone contributes and benefits from the collective effort.');
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `traducciones`
--
ALTER TABLE `traducciones`
  ADD PRIMARY KEY (`pagina`,`clave`,`idioma`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
