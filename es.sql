-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2025 a las 06:28:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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
('landing', 'hero-btn', 'es', 'RESERVA TU LUGAR'),
('landing', 'header-nav', 'es', 'Inicio;Sobre nosotros;Localizacion;FAQ;Contacto'),
('landing', 'hero-texto', 'es', 'En un mundo que divide, elegimos unirnos para compartir, apoyarnos y construir oportunidades desde la cooperación.'),
('landing', 'faq-titulo2', 'es', 'Qué necesito para ser Apto?'),
('landing', 'faqs-texto1', 'es', 'Una vez completado tu registro, nuestro equipo se pondrá en contacto para programar una entrevista y explicarte los próximos pasos.'),
('landing', 'faqs-texto2', 'es', 'Requerimos compromiso con los valores cooperativos, participación activa en las asambleas y contribución al proyecto común.'),
('landing', 'faqs-texto3', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 8 horas mensuales a las actividades comunitarias.'),
('landing', 'faqs-texto4', 'es', 'Contactanos a través del formulario y te responderemos a la brevedad.'),
('landing', 'hero-titulo', 'es', 'Cooperativa<br>nombre'),
('landing', 'faqs-titulo1', 'es', '¿Qué pasa al momento de registrarme?'),
('landing', 'faqs-titulo3', 'es', '¿Qué tanto hay que trabajar en la cooperativa?'),
('landing', 'registro-btn', 'es', 'Registrarse'),
('landing', 'faqs-titulo-4', 'es', '¿Tenes más preguntas?'),
('landing', 'contactanos-btn', 'es', 'Enviar Mensaje'),
('landing', 'decision-titulo', 'es', 'Decisión Democrática'),
('landing', 'footer-derechos', 'es', '© 2025 Cooperativa Nombre. Todos los derechos reservados.'),
('landing', 'que-hacemos-btn', 'es', 'Asociate'),
('landing', 'comunidad-titulo', 'es', 'Comunidad Sólida'),
('landing', 'contactanos-titulo', 'es', 'CONTACTANOS'),
('landing', 'localizacion-texto', 'es', 'Nos encontramos en Av. Perú y Magallanes'),
('landing', 'que-hacemos-texto1', 'es', 'Somos una cooperativa de ayuda mutua organizada bajo un modelo de gestión colectiva y democrática. Cada integrante participa activamente en las decisiones y en la planificación de acciones orientadas a mejorar la calidad de vida de la comunidad.'),
('landing', 'que-hacemos-texto2', 'es', 'A través de redes solidarias, construimos soluciones compartidas que surgen desde abajo, priorizando la autogestión, la equidad y el bienestar colectivo.'),
('landing', 'que-hacemos-titulo', 'es', '¿QUÉ HACEMOS?'),
('landing', 'localizacion-titulo', 'es', 'LOCALIZACIÓN'),
('landing', 'por-que-elegirnos-btn', 'es', 'Sumate a nuestra comunidad');

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
