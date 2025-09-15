-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2025 a las 22:20:23
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
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `ID_Persona` int(11) NOT NULL,
  `Nivel_permisos` enum('Operador','Admin') DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL,
  `Fecha_ingreso` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`ID_Persona`, `Nivel_permisos`, `Foto`, `Fecha_ingreso`) VALUES
(1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_pago`
--

CREATE TABLE `comprobante_pago` (
  `ID_Comprobante_pago` int(11) NOT NULL,
  `ID_Persona` int(11) DEFAULT NULL,
  `Motivo_pago` varchar(255) DEFAULT NULL,
  `Estado_pago` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Mes` date DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Monto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprobante_pago`
--

INSERT INTO `comprobante_pago` (`ID_Comprobante_pago`, `ID_Persona`, `Motivo_pago`, `Estado_pago`, `Mes`, `Foto`, `Monto`) VALUES
(5, 2, 'Aportes mensuales', 'En espera', '2025-09-15', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `falta`
--

CREATE TABLE `falta` (
  `ID_Falta` int(11) NOT NULL,
  `ID_Persona` int(11) NOT NULL,
  `ID_Semana_trabajo` int(11) NOT NULL,
  `Motivo_falta` varchar(255) NOT NULL,
  `Horas_solicitadas` int(11) NOT NULL,
  `Estado` enum('En espera','Aprobada','Rechazada') DEFAULT NULL,
  `Fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas_trabajadas`
--

CREATE TABLE `horas_trabajadas` (
  `ID_Horas_trabajadas` int(11) NOT NULL,
  `Horas` int(11) DEFAULT NULL,
  `Fecha_registro_horas` date DEFAULT NULL,
  `ID_Persona` int(11) DEFAULT NULL,
  `ID_Semana_trabajo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horas_trabajadas`
--

INSERT INTO `horas_trabajadas` (`ID_Horas_trabajadas`, `Horas`, `Fecha_registro_horas`, `ID_Persona`, `ID_Semana_trabajo`) VALUES
(6, 1, '2025-09-15', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `integrante_familiar`
--

CREATE TABLE `integrante_familiar` (
  `ID_Integrante` int(11) NOT NULL,
  `ID_Persona` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL,
  `CI` varchar(20) NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Email` varchar(150) DEFAULT NULL,
  `Genero` enum('Masculino','Femenino') NOT NULL,
  `Parentesco` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interesado`
--

CREATE TABLE `interesado` (
  `ID_Persona` int(11) NOT NULL,
  `Antecedentes` varchar(255) DEFAULT NULL,
  `Estado_entrevista` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Estado_antecedentes` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Fecha_entrevista` date DEFAULT NULL,
  `Hora_entrevista` time DEFAULT NULL,
  `Pago_inicial` varchar(255) DEFAULT NULL,
  `Estado_pago_inicial` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Monto_pago_inicial` int(11) DEFAULT NULL,
  `Unidad_Habitacional_Asignada` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `interesado`
--

INSERT INTO `interesado` (`ID_Persona`, `Antecedentes`, `Estado_entrevista`, `Estado_antecedentes`, `Fecha_entrevista`, `Hora_entrevista`, `Pago_inicial`, `Estado_pago_inicial`, `Monto_pago_inicial`, `Unidad_Habitacional_Asignada`) VALUES
(1, NULL, 'En espera', 'En espera', NULL, NULL, NULL, 'En espera', NULL, 0),
(2, NULL, 'Aprobado', 'En espera', '2025-11-12', '18:30:00', NULL, 'En espera', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numero_de_telefono`
--

CREATE TABLE `numero_de_telefono` (
  `ID_Telefono` int(11) NOT NULL,
  `ID_Persona` int(11) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `numero_de_telefono`
--

INSERT INTO `numero_de_telefono` (`ID_Telefono`, `ID_Persona`, `Telefono`) VALUES
(1, 1, 99888777),
(2, 2, 99777888);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `ID_Persona` int(11) NOT NULL,
  `CI` varchar(8) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(100) NOT NULL,
  `Rol` enum('Usuario','Interesado','Admin') DEFAULT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`ID_Persona`, `CI`, `Email`, `Contraseña`, `Rol`, `Nombre`, `Apellido`) VALUES
(1, '11111111', 'jrodriguezhuerta@gmail.com', '$2y$10$62uBrqvCS0e6k7IFFrj/nuXrRPMAVOlBm3O.k9WRIa9zyHwaMQw4C', 'Admin', 'Jose', 'Rodriguez Huerta'),
(2, '12312312', 'joaquinkoez@gmail.com', '$2y$10$IHzaWR.j/Cb5OLUO4UO8pe5kvPJh7azNgT9aUfHSxsiw3VccOLmve', 'Usuario', 'Joaquin', 'Koez Diaz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semana_trabajo`
--

CREATE TABLE `semana_trabajo` (
  `ID_Semana_trabajo` int(11) NOT NULL,
  `Horas_semanales` int(11) DEFAULT 21,
  `Fecha_semana` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `semana_trabajo`
--

INSERT INTO `semana_trabajo` (`ID_Semana_trabajo`, `Horas_semanales`, `Fecha_semana`) VALUES
(1, 21, '2025-09-15');

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
('landing', 'comunidad-texto', 'en', 'Projects designed to endure over time and benefit future generations.'),
('landing', 'comunidad-texto', 'es', 'Proyectos diseñados para perdurar en el tiempo y beneficiar a futuras generaciones.'),
('landing', 'comunidad-titulo', 'en', 'Solid community'),
('landing', 'comunidad-titulo', 'es', 'Comunidad Sólida'),
('landing', 'contactanos-btn', 'en', 'Send Message'),
('landing', 'contactanos-btn', 'es', 'Enviar Mensaje'),
('landing', 'contactanos-form-label', 'en', 'Name;Email;Phone Number (e.g. 098123456);Message'),
('landing', 'contactanos-form-label', 'es', 'Nombre;Email;Telefono (Ej: 098123456);Mensaje'),
('landing', 'contactanos-titulo', 'en', 'CONTACT US'),
('landing', 'contactanos-titulo', 'es', 'CONTACTANOS'),
('landing', 'decision-texto', 'en', 'Each member has a voice and a vote in the important decisions that affect the community.'),
('landing', 'decision-texto', 'es', 'Cada miembro tiene voz y voto en las decisiones importantes que afectan a la comunidad.'),
('landing', 'decision-titulo', 'en', 'Democratic Decisions'),
('landing', 'decision-titulo', 'es', 'Decisión Democrática'),
('landing', 'faq-titulo2', 'es', 'Qué necesito para ser Apto?'),
('landing', 'faqs-texto1', 'en', 'Once your registration is complete, our team will get in touch to schedule an interview and explain the next steps.'),
('landing', 'faqs-texto1', 'es', 'Una vez completado tu registro, nuestro equipo se pondrá en contacto para programar una entrevista y explicarte los próximos pasos.'),
('landing', 'faqs-texto2', 'en', 'We require commitment to cooperative values, active participation in assemblies, and contribution to the common project.'),
('landing', 'faqs-texto2', 'es', 'Requerimos compromiso con los valores cooperativos, participación activa en las asambleas y contribución al proyecto común.'),
('landing', 'faqs-texto3', 'en', 'Work varies depending on the project, but all members contribute at least 21 hours per week to community activities.'),
('landing', 'faqs-texto3', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 21 horas semanales a las actividades comunitarias.'),
('landing', 'faqs-texto4', 'en', 'Contact us through the form, and we will get back to you shortly.'),
('landing', 'faqs-texto4', 'es', 'Contactanos a través del formulario y te responderemos a la brevedad.'),
('landing', 'faqs-titulo-4', 'es', '¿Tenes más preguntas?'),
('landing', 'faqs-titulo1', 'en', 'What happens when I register?'),
('landing', 'faqs-titulo1', 'es', '¿Qué pasa al momento de registrarme?'),
('landing', 'faqs-titulo2', 'en', 'What do I need to qualify?'),
('landing', 'faqs-titulo2', 'es', '¿Qué necesito para ser Apto?'),
('landing', 'faqs-titulo3', 'en', 'How much do I have to work in the cooperative?'),
('landing', 'faqs-titulo3', 'es', '¿Qué tanto hay que trabajar en la cooperativa?'),
('landing', 'faqs-titulo4', 'en', 'Do you have any more questions?'),
('landing', 'faqs-titulo4', 'es', '¿Tenes más preguntas?'),
('landing', 'footer-contacto-telefono', 'en', 'Phone Number: +598 92 124 491'),
('landing', 'footer-contacto-telefono', 'es', 'Teléfono: +598 92 124 491'),
('landing', 'footer-derechos', 'en', '© 2025 Senda Firme. All rights reserved.'),
('landing', 'footer-derechos', 'es', '© 2025 Senda Firme. Todos los derechos reservados.'),
('landing', 'header-nav', 'en', 'Home;About us;Localization;FAQ;Contact'),
('landing', 'header-nav', 'es', 'Inicio;Sobre nosotros;Localizacion;FAQ;Contacto'),
('landing', 'hero-btn', 'en', 'BOOK YOUR SPOT'),
('landing', 'hero-btn', 'es', 'RESERVA TU LUGAR'),
('landing', 'hero-texto', 'en', 'In a world that divides, we choose to unite—to share, support, and build opportunities through cooperation.'),
('landing', 'hero-texto', 'es', 'En un mundo que divide, elegimos unirnos para compartir, apoyarnos y construir oportunidades desde la cooperación.'),
('landing', 'hero-titulo', 'es', 'Senda<br>Firme'),
('landing', 'iniciarsesion-btn', 'en', 'Sign Up'),
('landing', 'iniciarsesion-btn', 'es', 'Iniciar Sesion'),
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
('landing', 'sostenibilidad-texto', 'en', 'Be part of a mutual support network where everyone contributes and benefits from the collective effort.'),
('landing', 'sostenibilidad-texto', 'es', 'Forma parte de una red de apoyo mutuo donde todos contribuimos y nos beneficiamos del esfuerzo colectivo.'),
('landing', 'sostenibilidad-titulo', 'en', 'Sustainability\r\n\r\n'),
('landing', 'sostenibilidad-titulo', 'es', 'Sostenibilidad\r\n\r\n'),
('login', 'footer', 'en', '© 2025 Senda Firme. All rights reserved.'),
('login', 'footer', 'es', '© 2025 Senda Firme. Todos los derechos reservados.'),
('login', 'form-login', 'en', 'National ID card;Password'),
('login', 'form-login', 'es', 'Cedula de Identidad;Contraseña'),
('login', 'login-beneficio1', 'en', 'Competitive rates'),
('login', 'login-beneficio1', 'es', 'Tasas competitivas'),
('login', 'login-beneficio2', 'en', '\r\nGuaranteed security'),
('login', 'login-beneficio2', 'es', 'Seguridad garanrtizada'),
('login', 'login-beneficio3', 'en', 'Personalized attention'),
('login', 'login-beneficio3', 'es', 'Atención personalizada'),
('login', 'login-btn', 'en', 'Log in'),
('login', 'login-btn', 'es', 'Ingresar'),
('login', 'login-texto-side', 'en', 'Access all the benefits of being part of our financial community'),
('login', 'login-texto-side', 'es', 'Accede a todos los beneficios de ser parte de nuestra comunidad financiera'),
('login', 'login-titulo', 'en', 'Log in to your account'),
('login', 'login-titulo', 'es', 'Ingrese a su cuenta'),
('login', 'login-titulo-side', 'en', 'Welcome to Senda Firme'),
('login', 'login-titulo-side', 'es', 'Bienvenido a Senda Firme'),
('login', 'no-cuenta-link', 'en', 'Sign in here'),
('login', 'no-cuenta-link', 'es', 'Crea tu cuenta aquí'),
('login', 'no-cuenta-text', 'en', 'Don\'t have an account yet?'),
('login', 'no-cuenta-text', 'es', '¿Aun no tienes una cuenta?'),
('registro', 'cuenta-link', 'en', 'Log in here'),
('registro', 'cuenta-link', 'es', 'Inicia sesión aquí'),
('registro', 'cuenta-text', 'en', 'Already have an account?\r\n\r\n'),
('registro', 'cuenta-text', 'es', '¿Ya tienes una cuenta?'),
('registro', 'registro-btn', 'en', 'Sign In'),
('registro', 'registro-btn', 'es', 'Registrarse'),
('registro', 'registro-form', 'en', 'Name;Last Name;Email Address;Phone number;National ID;Password;Confirm Password;I accept the <a href=\"#\">Terms of Service</a> and <a href=\"#\">Privacy Policy</a>'),
('registro', 'registro-form', 'es', 'Nombre;Apellido;Correo electrónico;Teléfono Móvil;Cédula de Identidad;Contraseña;Confirmar Contraseña;Acepto los <a href=\"#\">Términos de servicio</a> y <a href=\"#\">Política de\n                            privacidad'),
('registro', 'registro-titulo', 'en', 'Create your account'),
('registro', 'registro-titulo', 'es', 'Crea tu cuenta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_habitacional`
--

CREATE TABLE `unidad_habitacional` (
  `ID_Unidad_habitacional` int(11) NOT NULL,
  `ID_Persona` int(11) DEFAULT NULL,
  `Numero_puerta` varchar(20) DEFAULT NULL,
  `Pasillo` varchar(20) DEFAULT NULL,
  `Estado_unidad` enum('En espera','En pausa','En construcción','Finalizada') DEFAULT NULL,
  `Cantidad_habitaciones` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_habitacional_semana_trabajo`
--

CREATE TABLE `unidad_habitacional_semana_trabajo` (
  `ID_Semana_trabajo` int(11) NOT NULL,
  `ID_Unidad_habitacional` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Persona` int(11) NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Fecha_ingreso` date DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_Persona`, `Fecha_nacimiento`, `Fecha_ingreso`, `Foto`) VALUES
(2, NULL, '2025-09-15', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indices de la tabla `comprobante_pago`
--
ALTER TABLE `comprobante_pago`
  ADD PRIMARY KEY (`ID_Comprobante_pago`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indices de la tabla `falta`
--
ALTER TABLE `falta`
  ADD PRIMARY KEY (`ID_Falta`),
  ADD KEY `ID_Persona` (`ID_Persona`),
  ADD KEY `ID_Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Indices de la tabla `horas_trabajadas`
--
ALTER TABLE `horas_trabajadas`
  ADD PRIMARY KEY (`ID_Horas_trabajadas`),
  ADD KEY `ID_Persona` (`ID_Persona`),
  ADD KEY `ID_Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Indices de la tabla `integrante_familiar`
--
ALTER TABLE `integrante_familiar`
  ADD PRIMARY KEY (`ID_Integrante`),
  ADD UNIQUE KEY `CI` (`CI`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indices de la tabla `interesado`
--
ALTER TABLE `interesado`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indices de la tabla `numero_de_telefono`
--
ALTER TABLE `numero_de_telefono`
  ADD PRIMARY KEY (`ID_Telefono`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`ID_Persona`),
  ADD UNIQUE KEY `CI` (`CI`);

--
-- Indices de la tabla `semana_trabajo`
--
ALTER TABLE `semana_trabajo`
  ADD PRIMARY KEY (`ID_Semana_trabajo`),
  ADD UNIQUE KEY `Fecha_semana` (`Fecha_semana`);

--
-- Indices de la tabla `traducciones`
--
ALTER TABLE `traducciones`
  ADD PRIMARY KEY (`pagina`,`clave`,`idioma`);

--
-- Indices de la tabla `unidad_habitacional`
--
ALTER TABLE `unidad_habitacional`
  ADD PRIMARY KEY (`ID_Unidad_habitacional`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indices de la tabla `unidad_habitacional_semana_trabajo`
--
ALTER TABLE `unidad_habitacional_semana_trabajo`
  ADD PRIMARY KEY (`ID_Semana_trabajo`,`ID_Unidad_habitacional`),
  ADD KEY `ID_Unidad_habitacional` (`ID_Unidad_habitacional`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comprobante_pago`
--
ALTER TABLE `comprobante_pago`
  MODIFY `ID_Comprobante_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `falta`
--
ALTER TABLE `falta`
  MODIFY `ID_Falta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_trabajadas`
--
ALTER TABLE `horas_trabajadas`
  MODIFY `ID_Horas_trabajadas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `integrante_familiar`
--
ALTER TABLE `integrante_familiar`
  MODIFY `ID_Integrante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `numero_de_telefono`
--
ALTER TABLE `numero_de_telefono`
  MODIFY `ID_Telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `ID_Persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `semana_trabajo`
--
ALTER TABLE `semana_trabajo`
  MODIFY `ID_Semana_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `unidad_habitacional`
--
ALTER TABLE `unidad_habitacional`
  MODIFY `ID_Unidad_habitacional` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `comprobante_pago`
--
ALTER TABLE `comprobante_pago`
  ADD CONSTRAINT `comprobante_pago_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `falta`
--
ALTER TABLE `falta`
  ADD CONSTRAINT `falta_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`),
  ADD CONSTRAINT `falta_ibfk_2` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `semana_trabajo` (`ID_Semana_trabajo`);

--
-- Filtros para la tabla `horas_trabajadas`
--
ALTER TABLE `horas_trabajadas`
  ADD CONSTRAINT `horas_trabajadas_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`),
  ADD CONSTRAINT `horas_trabajadas_ibfk_2` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `semana_trabajo` (`ID_Semana_trabajo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `integrante_familiar`
--
ALTER TABLE `integrante_familiar`
  ADD CONSTRAINT `integrante_familiar_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `interesado`
--
ALTER TABLE `interesado`
  ADD CONSTRAINT `interesado_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `numero_de_telefono`
--
ALTER TABLE `numero_de_telefono`
  ADD CONSTRAINT `numero_de_telefono_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `unidad_habitacional`
--
ALTER TABLE `unidad_habitacional`
  ADD CONSTRAINT `unidad_habitacional_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `unidad_habitacional_semana_trabajo`
--
ALTER TABLE `unidad_habitacional_semana_trabajo`
  ADD CONSTRAINT `unidad_habitacional_semana_trabajo_ibfk_1` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `semana_trabajo` (`ID_Semana_trabajo`),
  ADD CONSTRAINT `unidad_habitacional_semana_trabajo_ibfk_2` FOREIGN KEY (`ID_Unidad_habitacional`) REFERENCES `unidad_habitacional` (`ID_Unidad_habitacional`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
