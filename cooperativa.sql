-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2025 a las 14:40:05
-- Versión del servidor: 8.0.43
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
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `ID_Persona` int NOT NULL,
  `Nivel_permisos` enum('Operador','Admin') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `ID_Comprobante_pago` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Motivo_pago` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Estado_pago` enum('En espera','Pendiente','Aprobado','Rechazado') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Mes` date DEFAULT NULL,
  `Foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Monto` int DEFAULT NULL
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
  `ID_Falta` int NOT NULL,
  `ID_Persona` int NOT NULL,
  `ID_Semana_trabajo` int NOT NULL,
  `Motivo_falta` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Horas_solicitadas` int NOT NULL,
  `Estado` enum('En espera','Aprobada','Rechazada') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas_trabajadas`
--

CREATE TABLE `horas_trabajadas` (
  `ID_Horas_trabajadas` int NOT NULL,
  `Horas` int DEFAULT NULL,
  `Fecha_registro_horas` date DEFAULT NULL,
  `ID_Persona` int DEFAULT NULL,
  `ID_Semana_trabajo` int NOT NULL
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
  `ID_Integrante` int NOT NULL,
  `ID_Persona` int NOT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `CI` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `FechaNacimiento` date DEFAULT NULL,
  `Email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Genero` enum('Masculino','Femenino') COLLATE utf8mb4_general_ci NOT NULL,
  `Parentesco` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interesado`
--

CREATE TABLE `interesado` (
  `ID_Persona` int NOT NULL,
  `Antecedentes` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Estado_entrevista` enum('En espera','Pendiente','Aprobado','Rechazado') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Estado_antecedentes` enum('En espera','Pendiente','Aprobado','Rechazado') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Fecha_entrevista` date DEFAULT NULL,
  `Hora_entrevista` time DEFAULT NULL,
  `Pago_inicial` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Estado_pago_inicial` enum('En espera','Pendiente','Aprobado','Rechazado') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Monto_pago_inicial` int DEFAULT NULL,
  `Unidad_Habitacional_Asignada` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `interesado`
--

INSERT INTO `interesado` (`ID_Persona`, `Antecedentes`, `Estado_entrevista`, `Estado_antecedentes`, `Fecha_entrevista`, `Hora_entrevista`, `Pago_inicial`, `Estado_pago_inicial`, `Monto_pago_inicial`, `Unidad_Habitacional_Asignada`) VALUES
(1, NULL, 'En espera', 'En espera', NULL, NULL, NULL, 'En espera', NULL, 0),
(2, NULL, 'Aprobado', 'En espera', '2025-11-12', '18:30:00', NULL, 'En espera', 1, 0),
(3, 'ANTECEDENTE3.pdf', 'En espera', 'Pendiente', NULL, NULL, NULL, 'En espera', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numero_de_telefono`
--

CREATE TABLE `numero_de_telefono` (
  `ID_Telefono` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Telefono` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `numero_de_telefono`
--

INSERT INTO `numero_de_telefono` (`ID_Telefono`, `ID_Persona`, `Telefono`) VALUES
(1, 1, 99888777),
(2, 2, 99777888),
(3, 3, 92204459);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `ID_Persona` int NOT NULL,
  `CI` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Contraseña` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Rol` enum('Usuario','Interesado','Admin') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`ID_Persona`, `CI`, `Email`, `Contraseña`, `Rol`, `Nombre`, `Apellido`) VALUES
(1, '11111111', 'jrodriguezhuerta@gmail.com', '$2y$10$62uBrqvCS0e6k7IFFrj/nuXrRPMAVOlBm3O.k9WRIa9zyHwaMQw4C', 'Admin', 'Jose', 'Rodriguez Huerta'),
(2, '12312312', 'joaquinkoez@gmail.com', '$2y$10$IHzaWR.j/Cb5OLUO4UO8pe5kvPJh7azNgT9aUfHSxsiw3VccOLmve', 'Usuario', 'Joaquin', 'Koez Diaz'),
(3, '57226409', 'nigger@gmail.com', '$2y$10$SBcASYREAR4hDMsvngdwleaO4chThaT0r/xMNKvJSq5knC8JkF5eC', 'Interesado', 'Nigger', 'Nigger');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semana_trabajo`
--

CREATE TABLE `semana_trabajo` (
  `ID_Semana_trabajo` int NOT NULL,
  `Horas_semanales` int DEFAULT '21',
  `Fecha_semana` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `semana_trabajo`
--

INSERT INTO `semana_trabajo` (`ID_Semana_trabajo`, `Horas_semanales`, `Fecha_semana`) VALUES
(1, 21, '2025-09-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `Token` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ID_Persona` int NOT NULL,
  `Fecha_creacion` date NOT NULL,
  `Fecha_expiracion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`Token`, `ID_Persona`, `Fecha_creacion`, `Fecha_expiracion`) VALUES
('1f1b90710952dfdad727ac2ffc648ffb2ba96e27fe88651f2e875059f11f365f', 1, '2025-09-19', '2025-09-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traducciones`
--

CREATE TABLE `traducciones` (
  `pagina` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `clave` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `idioma` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `texto` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `traducciones`
--

INSERT INTO `traducciones` (`pagina`, `clave`, `idioma`, `texto`) VALUES
('landing', 'login-text', 'es', 'Ingresar'),
('landing', 'login-text', 'en', 'Log in'),
('landing', 'hero-titulo', 'es', 'Impulsando el crecimiento colectivo'),
('landing', 'hero-titulo', 'en', 'Driving collective growth'),
('landing', 'hero-texto', 'es', 'Senda Firme es una cooperativa comprometida con el desarrollo económico y social de sus miembros a través de servicios financieros responsables y asesoramiento personalizado.'),
('landing', 'hero-texto', 'en', 'Senda Firme is a cooperative committed to the economic and social development of its members through responsible financial services and personalized advice.'),
('landing', 'servicios-btn', 'es', 'Nuestros servicios'),
('landing', 'servicios-btn', 'en', 'Our services'),
('landing', 'whatsapp-text', 'es', 'Unirse por WhatsApp'),
('landing', 'whatsapp-text', 'en', 'Join via WhatsApp'),
('landing', 'miembros-text', 'es', 'Miembros'),
('landing', 'miembros-text', 'en', 'Members'),
('landing', 'ahorros-text', 'es', 'En ahorros'),
('landing', 'ahorros-text', 'en', 'In savings'),
('landing', 'satisfaccion-text', 'es', 'Satisfacción'),
('landing', 'satisfaccion-text', 'en', 'Satisfaction'),
('landing', 'valores-titulo', 'es', 'Nuestros Valores Cooperativos'),
('landing', 'valores-titulo', 'en', 'Our Cooperative Values'),
('landing', 'valores-subtitulo', 'es', 'Principios que nos guían y fortalecen como comunidad'),
('landing', 'valores-subtitulo', 'en', 'Principles that guide and strengthen us as a community'),
('landing', 'ayuda-titulo', 'es', 'Ayuda Mutua'),
('landing', 'ayuda-titulo', 'en', 'Mutual Help'),
('landing', 'ayuda-texto', 'es', 'Trabajamos juntos para alcanzar objetivos comunes y mejorar la calidad de vida de nuestros miembros.'),
('landing', 'ayuda-texto', 'en', 'We work together to achieve common goals and improve the quality of life of our members.'),
('landing', 'seguridad-titulo', 'es', 'Seguridad'),
('landing', 'seguridad-titulo', 'en', 'Security'),
('landing', 'seguridad-texto', 'es', 'Protegemos los recursos de nuestros asociados con los más altos estándares de seguridad y transparencia.'),
('landing', 'seguridad-texto', 'en', 'We protect our partners resources with the highest standards of security and transparency.'),
('landing', 'desarrollo-titulo', 'es', 'Desarrollo Comunitario'),
('landing', 'desarrollo-titulo', 'en', 'Community Development'),
('landing', 'desarrollo-texto', 'es', 'Impulsamos el crecimiento económico de nuestra comunidad mediante inversiones responsables.'),
('landing', 'desarrollo-texto', 'en', 'We drive the economic growth of our community through responsible investments.'),
('landing', 'servicios-titulo', 'es', 'Nuestros Servicios Financieros'),
('landing', 'servicios-titulo', 'en', 'Our Financial Services'),
('landing', 'servicios-subtitulo', 'es', 'Soluciones diseñadas para las necesidades de nuestra comunidad'),
('landing', 'servicios-subtitulo', 'en', 'Solutions designed for the needs of our community'),
('landing', 'ahorro-titulo', 'es', 'Ahorro Programado'),
('landing', 'ahorro-titulo', 'en', 'Scheduled Savings'),
('landing', 'ahorro-texto', 'es', 'Planifica tu futuro con nuestros planes de ahorro flexible y alcanza tus metas financieras.'),
('landing', 'ahorro-texto', 'en', 'Plan your future with our flexible savings plans and achieve your financial goals.'),
('landing', 'conocer-mas-text', 'es', 'Conocer más'),
('landing', 'conocer-mas-text', 'en', 'Learn more'),
('landing', 'creditos-titulo', 'es', 'Créditos Solidarios'),
('landing', 'creditos-titulo', 'en', 'Solidarity Credits'),
('landing', 'creditos-texto', 'es', 'Accede a créditos con tasas preferenciales para proyectos productivos y personales.'),
('landing', 'creditos-texto', 'en', 'Access credits with preferential rates for productive and personal projects.'),
('landing', 'asesoria-titulo', 'es', 'Asesoría Financiera'),
('landing', 'asesoria-titulo', 'en', 'Financial Advice'),
('landing', 'asesoria-texto', 'es', 'Recibe orientación personalizada para optimizar tus recursos y tomar mejores decisiones.'),
('landing', 'asesoria-texto', 'en', 'Receive personalized guidance to optimize your resources and make better decisions.'),
('landing', 'preguntas-titulo', 'es', 'Preguntas Frecuentes'),
('landing', 'preguntas-titulo', 'en', 'Frequently Asked Questions'),
('landing', 'preguntas-subtitulo', 'es', 'Resolvemos tus dudas sobre nuestra cooperativa'),
('landing', 'preguntas-subtitulo', 'en', 'We solve your doubts about our cooperative'),
('landing', 'faq1-titulo', 'es', '¿Qué pasa al momento de registrarme?'),
('landing', 'faq1-titulo', 'en', 'What happens when I register?'),
('landing', 'faq1-texto', 'es', 'Una vez completado tu registro, nuestro equipo se pondrá en contacto para programar una entrevista y explicarte los próximos pasos.'),
('landing', 'faq1-texto', 'en', 'Once your registration is complete, our team will contact you to schedule an interview and explain the next steps.'),
('landing', 'faq2-titulo', 'es', '¿Qué necesito para ser Apto?'),
('landing', 'faq2-titulo', 'en', 'What do I need to be eligible?'),
('landing', 'faq2-texto', 'es', 'Requerimos compromiso con los valores cooperativos, participación activa en las asambleas y contribución al proyecto común.'),
('landing', 'faq2-texto', 'en', 'We require commitment to cooperative values, active participation in assemblies and contribution to the common project.'),
('landing', 'faq3-titulo', 'es', '¿Qué tanto hay que trabajar en la cooperativa?'),
('landing', 'faq3-titulo', 'en', 'How much do I have to work in the cooperative?'),
('landing', 'faq3-texto', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 21 horas semanales a las actividades comunitarias.'),
('landing', 'faq3-texto', 'en', 'Work varies by project, but all members contribute at least 21 hours per week to community activities.'),
('landing', 'faq4-titulo', 'es', '¿Tenes más preguntas?'),
('landing', 'faq4-titulo', 'en', 'Do you have more questions?'),
('landing', 'faq4-texto', 'es', 'Contactanos a través de WhatsApp y te responderemos a la brevedad.'),
('landing', 'faq4-texto', 'en', 'Contact us through WhatsApp and we will respond as soon as possible.'),
('landing', 'cta-titulo', 'es', '¿Listo para unirte a nuestra comunidad?'),
('landing', 'cta-titulo', 'en', 'Ready to join our community?'),
('landing', 'cta-texto', 'es', 'Forma parte de una cooperativa que crece junta. Disfruta de beneficios exclusivos y contribuye al desarrollo económico de tu localidad.'),
('landing', 'cta-texto', 'en', 'Be part of a cooperative that grows together. Enjoy exclusive benefits and contribute to the economic development of your locality.'),
('landing', 'contactar-whatsapp-text', 'es', 'Contactar por WhatsApp'),
('landing', 'contactar-whatsapp-text', 'en', 'Contact via WhatsApp'),
('landing', 'footer-descripcion', 'es', 'Una cooperativa financiera comprometida con el desarrollo económico y social de nuestra comunidad.'),
('landing', 'footer-descripcion', 'en', 'A financial cooperative committed to the economic and social development of our community.'),
('landing', 'enlaces-titulo', 'es', 'Enlaces rápidos'),
('landing', 'enlaces-titulo', 'en', 'Quick links'),
('landing', 'enlace-inicio', 'es', 'Inicio'),
('landing', 'enlace-inicio', 'en', 'Home'),
('landing', 'enlace-valores', 'es', 'Valores'),
('landing', 'enlace-valores', 'en', 'Values'),
('landing', 'enlace-servicios', 'es', 'Servicios'),
('landing', 'enlace-servicios', 'en', 'Services'),
('landing', 'enlace-preguntas', 'es', 'Preguntas'),
('landing', 'enlace-preguntas', 'en', 'Questions'),
('landing', 'enlace-contacto', 'es', 'Contacto'),
('landing', 'enlace-contacto', 'en', 'Contact'),
('landing', 'servicios-footer-titulo', 'es', 'Servicios'),
('landing', 'servicios-footer-titulo', 'en', 'Services'),
('landing', 'servicio-ahorro', 'es', 'Ahorro programado'),
('landing', 'servicio-ahorro', 'en', 'Scheduled savings'),
('landing', 'servicio-creditos', 'es', 'Créditos solidarios'),
('landing', 'servicio-creditos', 'en', 'Solidarity credits'),
('landing', 'servicio-asesoria', 'es', 'Asesoría financiera'),
('landing', 'servicio-asesoria', 'en', 'Financial advice'),
('landing', 'servicio-inversiones', 'es', 'Inversiones'),
('landing', 'servicio-inversiones', 'en', 'Investments'),
('landing', 'servicio-seguros', 'es', 'Seguros'),
('landing', 'servicio-seguros', 'en', 'Insurance'),
('landing', 'contacto-footer-titulo', 'es', 'Contacto'),
('landing', 'contacto-footer-titulo', 'en', 'Contact'),
('landing', 'whatsapp-contacto-text', 'es', 'Envíanos un WhatsApp'),
('landing', 'whatsapp-contacto-text', 'en', 'Send us a WhatsApp'),
('landing', 'derechos-text', 'es', '© 2025 Senda Firme Cooperativa. Todos los derechos reservados.'),
('landing', 'derechos-text', 'en', '© 2025 Senda Firme Cooperative. All rights reserved.');
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
  `ID_Unidad_habitacional` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Numero_puerta` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Pasillo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Estado_unidad` enum('En espera','En pausa','En construcción','Finalizada') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Cantidad_habitaciones` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_habitacional_semana_trabajo`
--

CREATE TABLE `unidad_habitacional_semana_trabajo` (
  `ID_Semana_trabajo` int NOT NULL,
  `ID_Unidad_habitacional` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Persona` int NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Fecha_ingreso` date DEFAULT NULL,
  `Foto` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
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
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`ID_Persona`);

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
  MODIFY `ID_Comprobante_pago` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `falta`
--
ALTER TABLE `falta`
  MODIFY `ID_Falta` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_trabajadas`
--
ALTER TABLE `horas_trabajadas`
  MODIFY `ID_Horas_trabajadas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `integrante_familiar`
--
ALTER TABLE `integrante_familiar`
  MODIFY `ID_Integrante` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `numero_de_telefono`
--
ALTER TABLE `numero_de_telefono`
  MODIFY `ID_Telefono` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `ID_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `semana_trabajo`
--
ALTER TABLE `semana_trabajo`
  MODIFY `ID_Semana_trabajo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `unidad_habitacional`
--
ALTER TABLE `unidad_habitacional`
  MODIFY `ID_Unidad_habitacional` int NOT NULL AUTO_INCREMENT;

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
