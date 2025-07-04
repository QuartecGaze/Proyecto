-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2025 a las 06:28:53
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
('landing', 'hero-btn', 'en', 'Book your spot'),
('landing', 'header-nav', 'en', 'Home;About us;Localization;FAQ;Contact'),
('landing', 'hero-texto', 'en', 'In a world that divides, we choose to unite—to share, support, and build opportunities through cooperation.'),
('landing', 'faqs-texto1', 'en', 'Once your registration is complete, our team will get in touch to schedule an interview and explain the next steps.'),
('landing', 'faqs-texto2', 'en', 'We require commitment to cooperative values, active participation in assemblies, and contribution to the common project.'),
('landing', 'faqs-texto3', 'en', 'Work varies depending on the project, but all members contribute at least 8 hours per month to community activities.'),
('landing', 'faqs-texto4', 'en', 'Contact us through the form, and we will get back to you shortly.'),
('landing', 'faqs-titulo1', 'en', 'What happens when I register?'),
('landing', 'faqs-titulo2', 'en', 'What do I need to qualify?'),
('landing', 'faqs-titulo3', 'en', 'How much do I have to work in the cooperative?'),
('landing', 'faqs-titulo4', 'en', 'Do you have any more questions?'),
('landing', 'registro-btn', 'en', 'Sign Up'),
('landing', 'contactanos-btn', 'en', 'Send Message'),
('landing', 'decision-titulo', 'en', 'Democratic Decisions'),
('landing', 'footer-derechos', 'en', '© 2025 Cooperativa Nombre. All rights reserved.'),
('landing', 'que-hacemos-btn', 'en', 'Join us'),
('landing', 'comunidad-titulo', 'en', 'Solid community'),
('landing', 'contactanos-titulo', 'en', 'CONTACT US'),
('landing', 'localizacion-texto', 'en', 'We are located at Av. Perú and Magallanes'),
('landing', 'que-hacemos-texto1', 'en', 'We are a mutual aid cooperative organized under a collective and democratic management model. Every member actively participates in decision-making and planning actions aimed at improving the community’s quality of life.'),
('landing', 'que-hacemos-texto2', 'en', 'Through networks of solidarity, we build shared solutions that arise from the grassroots, prioritizing self-management, equity, and collective well-being.'),
('landing', 'que-hacemos-titulo', 'en', 'WHAT WE DO'),
('landing', 'localizacion-titulo', 'en', 'LOCALIZATION'),
('landing', 'por-que-elegirnos-btn', 'en', 'Join our community'),
('landing', 'sostenibilidad-titulo', 'en', 'Sustainability\r\n\r\n');

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
