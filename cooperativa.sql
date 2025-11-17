-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 17-11-2025 a las 21:22:23
-- Versión del servidor: 5.7.24
-- Versión de PHP: 8.3.1

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`ID_Persona`, `Nivel_permisos`, `Foto`, `Fecha_ingreso`) VALUES
(2, 'Admin', NULL, '2020-12-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `ID_Asistencia` int(11) NOT NULL,
  `ID_Reunion` int(11) NOT NULL,
  `ID_Persona` int(11) NOT NULL,
  `Asistencia` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `falta`
--

CREATE TABLE `falta` (
  `ID_Comprobante_pago` int(11) DEFAULT NULL,
  `ID_Falta` int(11) NOT NULL,
  `ID_Persona` int(11) NOT NULL,
  `ID_Semana_trabajo` int(11) NOT NULL,
  `Motivo_falta` varchar(255) NOT NULL,
  `Horas_solicitadas` int(11) NOT NULL,
  `Estado` enum('En espera','Aprobada','Rechazada') DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Tipo_falta` enum('Exoneracion','Pago Compensatorio') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `integrante_familiar`
--

INSERT INTO `integrante_familiar` (`ID_Integrante`, `ID_Persona`, `Nombre`, `Apellido`, `CI`, `FechaNacimiento`, `Email`, `Genero`, `Parentesco`) VALUES
(1, 3, 'Maria', 'Boruchovas', '77883344', '2000-12-11', 'mboru@gmail.com', 'Femenino', NULL),
(2, 3, 'Jose', 'Boruchovas', '73335155', '2010-02-12', 'joseboruchovas@gmail.com', 'Masculino', NULL);

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
  `Unidad_Habitacional_Asignada` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `interesado`
--

INSERT INTO `interesado` (`ID_Persona`, `Antecedentes`, `Estado_entrevista`, `Estado_antecedentes`, `Fecha_entrevista`, `Hora_entrevista`, `Pago_inicial`, `Estado_pago_inicial`, `Monto_pago_inicial`, `Unidad_Habitacional_Asignada`) VALUES
(1, NULL, 'En espera', 'En espera', NULL, NULL, NULL, 'En espera', NULL, 0),
(2, NULL, 'En espera', 'En espera', NULL, NULL, NULL, 'En espera', NULL, 0),
(3, 'ANTECEDENTE3.pdf', 'Aprobado', 'Aprobado', '2025-11-18', '17:30:00', 'COMPROBANTEPAGOINICIAL3.pdf', 'Aprobado', 13000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numero_de_telefono`
--

CREATE TABLE `numero_de_telefono` (
  `ID_Telefono` int(11) NOT NULL,
  `ID_Persona` int(11) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `numero_de_telefono`
--

INSERT INTO `numero_de_telefono` (`ID_Telefono`, `ID_Persona`, `Telefono`) VALUES
(1, 1, 94322278),
(2, 2, 99896211),
(3, 3, 94283607);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`ID_Persona`, `CI`, `Email`, `Contraseña`, `Rol`, `Nombre`, `Apellido`) VALUES
(1, '12312312', 'agustinparra@gmail.com', '$2y$10$LXQssPQiAeFhECWiSDoeA.98FEIECBzZr0LYeiaqKRIU3cep3.ZGW', 'Interesado', 'Agustin', 'Parra Lozano'),
(2, '11111111', 'alifernandez@gmail.com', '$2y$10$IUiW1oCL86iEjBZMw4GQD.vPEaGFgoz.6w/afhMzzjE5Q/YuchQai', 'Admin', 'Allison', 'Fernandez Parra'),
(3, '59807542', 'santiboru07@gmail.com', '$2y$10$DfqjWKBiNUXmYjT0v332quxo832mItFzLeGfmOWoq2yQV2eQDNq9e', 'Usuario', 'Santiago', 'Boruchovas Martinez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reunion`
--

CREATE TABLE `reunion` (
  `ID_Reunion` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Lugar` varchar(150) DEFAULT NULL,
  `Tipo_Reunion` enum('General','Comisión','Emergencia','Planificacion') NOT NULL,
  `Estado_Reunion` enum('Pendiente','En curso','Finalizada','Cancelada') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semana_trabajo`
--

CREATE TABLE `semana_trabajo` (
  `ID_Semana_trabajo` int(11) NOT NULL,
  `Horas_semanales` int(11) DEFAULT '21',
  `Fecha_semana` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `semana_trabajo`
--

INSERT INTO `semana_trabajo` (`ID_Semana_trabajo`, `Horas_semanales`, `Fecha_semana`) VALUES
(2, 21, '2025-11-17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `Token` varchar(512) NOT NULL,
  `ID_Persona` int(11) NOT NULL,
  `Fecha_creacion` date NOT NULL,
  `Fecha_expiracion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`Token`, `ID_Persona`, `Fecha_creacion`, `Fecha_expiracion`) VALUES
('8644956da1ade7035feaf638befba614bcf1f09c87db26b47aa7a2b573c3d14c', 1, '2025-11-17', '2025-11-18'),
('eb6e2011877df8dae2953a8a39f4ebf51b2d1602b19d82f84073cc8e96e04491', 2, '2025-11-17', '2025-11-18'),
('4a6169d11fcd68e686feef6476b5cbb568accd270a9b6bf377bfaafbd26ead18', 3, '2025-11-17', '2025-11-18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traducciones`
--

CREATE TABLE `traducciones` (
  `pagina` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `idioma` varchar(10) NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `traducciones`
--

INSERT INTO `traducciones` (`pagina`, `clave`, `idioma`, `texto`) VALUES
('configuracion-usuario', 'boton-cambiar-admin', 'en', 'Switch to Admin'),
('configuracion-usuario', 'boton-cambiar-admin', 'es', 'Cambiar a Admin'),
('configuracion-usuario', 'boton-cambiar-datos-personales', 'en', 'Edit personal data'),
('configuracion-usuario', 'boton-cambiar-datos-personales', 'es', 'Cambiar datos personales'),
('configuracion-usuario', 'boton-cambiar-foto', 'en', 'Change photo'),
('configuracion-usuario', 'boton-cambiar-foto', 'es', 'Cambiar foto'),
('configuracion-usuario', 'boton-cerrar-sesion', 'en', 'Log out'),
('configuracion-usuario', 'boton-cerrar-sesion', 'es', 'Cerrar sesión'),
('configuracion-usuario', 'estadistica-antiguedad-titulo', 'en', 'Seniority'),
('configuracion-usuario', 'estadistica-antiguedad-titulo', 'es', 'Antigüedad'),
('configuracion-usuario', 'estadistica-monto-aportado-titulo', 'en', 'Amount contributed'),
('configuracion-usuario', 'estadistica-monto-aportado-titulo', 'es', 'Monto Aportado'),
('configuracion-usuario', 'estadistica-total-horas-titulo', 'en', 'Total hours worked'),
('configuracion-usuario', 'estadistica-total-horas-titulo', 'es', 'Total Horas Trabajadas'),
('configuracion-usuario', 'estadisticas-titulo', 'en', 'My statistics'),
('configuracion-usuario', 'estadisticas-titulo', 'es', 'Mis estadísticas'),
('configuracion-usuario', 'info-personal-titulo', 'en', 'Personal information'),
('configuracion-usuario', 'info-personal-titulo', 'es', 'Información personal'),
('configuracion-usuario', 'menu-configuracion', 'en', 'Settings'),
('configuracion-usuario', 'menu-configuracion', 'es', 'Configuración'),
('configuracion-usuario', 'menu-horas-trabajadas', 'en', 'Worked Hours'),
('configuracion-usuario', 'menu-horas-trabajadas', 'es', 'Horas Trabajadas'),
('configuracion-usuario', 'menu-inicio', 'en', 'Home'),
('configuracion-usuario', 'menu-inicio', 'es', 'Inicio'),
('configuracion-usuario', 'menu-pagos', 'en', 'Payments'),
('configuracion-usuario', 'menu-pagos', 'es', 'Pagos'),
('configuracion-usuario', 'menu-unidad-habitacional', 'en', 'Housing Unit'),
('configuracion-usuario', 'menu-unidad-habitacional', 'es', 'Unidad Habitacional'),
('configuracion-usuario', 'perfil-boton-cancelar', 'en', 'Cancel'),
('configuracion-usuario', 'perfil-boton-cancelar', 'es', 'Cancelar'),
('configuracion-usuario', 'perfil-boton-guardar-cambios', 'en', 'Save changes'),
('configuracion-usuario', 'perfil-boton-guardar-cambios', 'es', 'Guardar cambios'),
('configuracion-usuario', 'perfil-label-apellido', 'en', 'Last Name'),
('configuracion-usuario', 'perfil-label-apellido', 'es', 'Apellido'),
('configuracion-usuario', 'perfil-label-direccion', 'en', 'Address'),
('configuracion-usuario', 'perfil-label-direccion', 'es', 'Dirección'),
('configuracion-usuario', 'perfil-label-email', 'en', 'Email'),
('configuracion-usuario', 'perfil-label-email', 'es', 'Correo electrónico'),
('configuracion-usuario', 'perfil-label-fecha-ingreso', 'en', 'Date of joining the cooperative'),
('configuracion-usuario', 'perfil-label-fecha-ingreso', 'es', 'Fecha de Ingreso a la cooperativa'),
('configuracion-usuario', 'perfil-label-fecha-nacimiento', 'en', 'Date of Birth'),
('configuracion-usuario', 'perfil-label-fecha-nacimiento', 'es', 'Fecha de Nacimiento'),
('configuracion-usuario', 'perfil-label-nombre', 'en', 'First Name'),
('configuracion-usuario', 'perfil-label-nombre', 'es', 'Nombre'),
('configuracion-usuario', 'perfil-label-nombre-completo', 'en', 'Full name'),
('configuracion-usuario', 'perfil-label-nombre-completo', 'es', 'Nombre completo'),
('configuracion-usuario', 'perfil-label-telefono', 'en', 'Phone'),
('configuracion-usuario', 'perfil-label-telefono', 'es', 'Teléfono'),
('configuracion-usuario', 'perfil-subtitulo', 'en', 'Manage your personal information and preferences'),
('configuracion-usuario', 'perfil-subtitulo', 'es', 'Gestiona tu información personal y preferencias'),
('configuracion-usuario', 'perfil-titulo', 'en', 'My Profile'),
('configuracion-usuario', 'perfil-titulo', 'es', 'Mi Perfil'),
('configuracion-usuario', 'sidebar-rol-usuario', 'en', 'User'),
('configuracion-usuario', 'sidebar-rol-usuario', 'es', 'Usuario'),
('configuracion-usuario', 'sidebar-slogan', 'en', 'Building opportunities together'),
('configuracion-usuario', 'sidebar-slogan', 'es', 'Construyendo oportunidades juntos'),
('horas', 'btn-cambiar-sesion', 'en', 'Switch to Admin'),
('horas', 'btn-cambiar-sesion', 'es', 'Cambiar a Admin'),
('horas', 'btn-cerrar-sesion', 'en', 'Log out'),
('horas', 'btn-cerrar-sesion', 'es', 'Cerrar sesión'),
('horas', 'contador-label-objetivo', 'en', 'Weekly goal'),
('horas', 'contador-label-objetivo', 'es', 'Meta semanal'),
('horas', 'contador-label-restantes', 'en', 'Remaining hours'),
('horas', 'contador-label-restantes', 'es', 'Horas restantes'),
('horas', 'contador-label-trabajadas', 'en', 'Hours worked this week'),
('horas', 'contador-label-trabajadas', 'es', 'Horas trabajadas esta semana'),
('horas', 'contador-texto-progreso', 'en', 'Completed'),
('horas', 'contador-texto-progreso', 'es', 'Completado'),
('horas', 'contador-titulo', 'en', 'Hours summary'),
('horas', 'contador-titulo', 'es', 'Resumen de Horas'),
('horas', 'faltas-form-btn-texto', 'en', 'Record absence'),
('horas', 'faltas-form-btn-texto', 'es', 'Registrar falta'),
('horas', 'faltas-form-item-exoneracion', 'en', 'Exemption: The missed hours are deducted from your weekly goal'),
('horas', 'faltas-form-item-exoneracion', 'es', 'Exoneración: Se descuentan las horas faltadas de tu objetivo semanal'),
('horas', 'faltas-form-item-pago', 'en', 'Monetary compensation: You pay a fee set by the cooperative for the hours you did not work'),
('horas', 'faltas-form-item-pago', 'es', 'Compensación monetaria: Pagas una tarifa establecida por la cooperativa por las horas que no trabajaste'),
('horas', 'faltas-form-label-horas', 'en', 'Missed hours:'),
('horas', 'faltas-form-label-horas', 'es', 'Horas faltadas:'),
('horas', 'faltas-form-label-motivo', 'en', 'Reason for the absence:'),
('horas', 'faltas-form-label-motivo', 'es', 'Motivo de la falta:'),
('horas', 'faltas-form-label-tipo', 'en', 'Compensation type:'),
('horas', 'faltas-form-label-tipo', 'es', 'Tipo de compensación:'),
('horas', 'faltas-form-msg-error', 'en', 'Error while recording the absence.'),
('horas', 'faltas-form-msg-error', 'es', 'Error al registrar la falta.'),
('horas', 'faltas-form-msg-exito', 'en', 'Absence successfully recorded.'),
('horas', 'faltas-form-msg-exito', 'es', 'Falta registrada correctamente.'),
('horas', 'faltas-form-option-exoneracion', 'en', 'Hours exemption'),
('horas', 'faltas-form-option-exoneracion', 'es', 'Exoneración de horas'),
('horas', 'faltas-form-option-pago', 'en', 'Monetary compensation'),
('horas', 'faltas-form-option-pago', 'es', 'Compensación monetaria'),
('horas', 'faltas-form-option-placeholder', 'en', 'Select an option'),
('horas', 'faltas-form-option-placeholder', 'es', 'Selecciona una opción'),
('horas', 'faltas-form-texto-opciones', 'en', 'Compensation options:'),
('horas', 'faltas-form-texto-opciones', 'es', 'Opciones de compensación:'),
('horas', 'faltas-form-titulo', 'en', 'Register absences'),
('horas', 'faltas-form-titulo', 'es', 'Registrar Faltas'),
('horas', 'historial-filtro-dia-label', 'en', 'Day:'),
('horas', 'historial-filtro-dia-label', 'es', 'Dia:'),
('horas', 'historial-filtro-dia-option-1', 'en', 'Monday'),
('horas', 'historial-filtro-dia-option-1', 'es', 'Lunes'),
('horas', 'historial-filtro-dia-option-2', 'en', 'Tuesday'),
('horas', 'historial-filtro-dia-option-2', 'es', 'Martes'),
('horas', 'historial-filtro-dia-option-3', 'en', 'Wednesday'),
('horas', 'historial-filtro-dia-option-3', 'es', 'Miercoles'),
('horas', 'historial-filtro-dia-option-4', 'en', 'Thursday'),
('horas', 'historial-filtro-dia-option-4', 'es', 'Jueves'),
('horas', 'historial-filtro-dia-option-5', 'en', 'Friday'),
('horas', 'historial-filtro-dia-option-5', 'es', 'Viernes'),
('horas', 'historial-filtro-dia-option-6', 'en', 'Saturday'),
('horas', 'historial-filtro-dia-option-6', 'es', 'Sabado'),
('horas', 'historial-filtro-dia-option-7', 'en', 'Sunday'),
('horas', 'historial-filtro-dia-option-7', 'es', 'Domingo'),
('horas', 'historial-filtro-dia-option-todos', 'en', 'All'),
('horas', 'historial-filtro-dia-option-todos', 'es', 'Todos'),
('horas', 'historial-filtro-semana-label', 'en', 'Week:'),
('horas', 'historial-filtro-semana-label', 'es', 'Semana:'),
('horas', 'historial-filtro-semana-option-todas', 'en', 'All'),
('horas', 'historial-filtro-semana-option-todas', 'es', 'Todas'),
('horas', 'historial-titulo', 'en', 'Worked hours history'),
('horas', 'historial-titulo', 'es', 'Historial de horas trabajadas'),
('horas', 'horas-form-btn-texto', 'en', 'Record hours'),
('horas', 'horas-form-btn-texto', 'es', 'Registrar horas'),
('horas', 'horas-form-label-horas', 'en', 'Worked hours:'),
('horas', 'horas-form-label-horas', 'es', 'Horas trabajadas:'),
('horas', 'horas-form-msg-error', 'en', 'Error while recording hours.'),
('horas', 'horas-form-msg-error', 'es', 'Error al registrar las horas.'),
('horas', 'horas-form-msg-exito', 'en', 'Hours successfully recorded.'),
('horas', 'horas-form-msg-exito', 'es', 'Horas registradas correctamente.'),
('horas', 'horas-form-texto-ayuda-1', 'en', 'Enter the number of hours you worked'),
('horas', 'horas-form-texto-ayuda-1', 'es', 'Ingresa la cantidad de horas que trabajaste'),
('horas', 'horas-form-texto-ayuda-2', 'en', 'Remember that we always round down'),
('horas', 'horas-form-texto-ayuda-2', 'es', 'Recorda que siempre redondeamos para abajo'),
('horas', 'horas-form-titulo', 'en', 'Register worked hours'),
('horas', 'horas-form-titulo', 'es', 'Registrar horas trabajadas'),
('horas', 'horas-header-subtitulo', 'en', 'Record and view your weekly working hours in the cooperative'),
('horas', 'horas-header-subtitulo', 'es', 'Registra y consulta tus horas de trabajo semanales en la cooperativa'),
('horas', 'horas-header-titulo', 'en', 'Worked hours'),
('horas', 'horas-header-titulo', 'es', 'Horas Trabajadas'),
('horas', 'modal-editar-boton-cancelar', 'en', 'Cancel'),
('horas', 'modal-editar-boton-cancelar', 'es', 'Cancelar'),
('horas', 'modal-editar-boton-guardar', 'en', 'Save changes'),
('horas', 'modal-editar-boton-guardar', 'es', 'Guardar cambios'),
('horas', 'modal-editar-label-fecha', 'en', 'Date:'),
('horas', 'modal-editar-label-fecha', 'es', 'Fecha:'),
('horas', 'modal-editar-label-horas', 'en', 'Worked hours:'),
('horas', 'modal-editar-label-horas', 'es', 'Horas trabajadas:'),
('horas', 'modal-editar-msg-error', 'en', 'Error while updating hours.'),
('horas', 'modal-editar-msg-error', 'es', 'Error al actualizar las horas.'),
('horas', 'modal-editar-msg-exito', 'en', 'Hours successfully updated.'),
('horas', 'modal-editar-msg-exito', 'es', 'Horas actualizadas correctamente.'),
('horas', 'modal-editar-titulo', 'en', 'Edit worked hours'),
('horas', 'modal-editar-titulo', 'es', 'Editar horas trabajadas'),
('horas', 'perfil-rol', 'en', 'User'),
('horas', 'perfil-rol', 'es', 'Usuario'),
('horas', 'pestana-faltas-texto', 'en', 'Register absences'),
('horas', 'pestana-faltas-texto', 'es', 'Registrar Faltas'),
('horas', 'pestana-horas-texto', 'en', 'Register hours'),
('horas', 'pestana-horas-texto', 'es', 'Registrar Horas'),
('horas', 'sidebar-menu-configuracion', 'en', 'Settings'),
('horas', 'sidebar-menu-configuracion', 'es', 'Configuración'),
('horas', 'sidebar-menu-horas', 'en', 'Worked hours'),
('horas', 'sidebar-menu-horas', 'es', 'Horas Trabajadas'),
('horas', 'sidebar-menu-inicio', 'en', 'Home'),
('horas', 'sidebar-menu-inicio', 'es', 'Inicio'),
('horas', 'sidebar-menu-pagos', 'en', 'Payments'),
('horas', 'sidebar-menu-pagos', 'es', 'Pagos'),
('horas', 'sidebar-menu-unidad', 'en', 'Housing unit'),
('horas', 'sidebar-menu-unidad', 'es', 'Unidad Habitacional'),
('horas', 'sidebar-slogan', 'en', 'Building opportunities together'),
('horas', 'sidebar-slogan', 'es', 'Construyendo oportunidades juntos'),
('horas', 'tabla-horas-th-borrar', 'en', 'Delete'),
('horas', 'tabla-horas-th-borrar', 'es', 'Borrar'),
('horas', 'tabla-horas-th-dia', 'en', 'Day'),
('horas', 'tabla-horas-th-dia', 'es', 'Día'),
('horas', 'tabla-horas-th-editar', 'en', 'Edit'),
('horas', 'tabla-horas-th-editar', 'es', 'Editar'),
('horas', 'tabla-horas-th-fecha', 'en', 'Date'),
('horas', 'tabla-horas-th-fecha', 'es', 'Fecha'),
('horas', 'tabla-horas-th-horas', 'en', 'Hours'),
('horas', 'tabla-horas-th-horas', 'es', 'Horas'),
('landing', 'comunidad-texto', 'en', 'Become part of a cooperative that grows together. Enjoy exclusive benefits and contribute to the economic development of your community.'),
('landing', 'comunidad-texto', 'es', 'Formá parte de una cooperativa que crece junta. Disfrutá de beneficios exclusivos y contribuí al desarrollo económico de tu comunidad.'),
('landing', 'comunidad-titulo', 'en', 'Ready to join our community?'),
('landing', 'comunidad-titulo', 'es', '¿Listo para unirte a nuestra comunidad?'),
('landing', 'cta-btn-whatsapp', 'en', 'Contact via WhatsApp'),
('landing', 'cta-btn-whatsapp', 'es', 'Contactar por WhatsApp'),
('landing', 'faq-subtitulo', 'en', 'We answer your questions about our cooperative'),
('landing', 'faq-subtitulo', 'es', 'Respondemos tus dudas sobre nuestra cooperativa'),
('landing', 'faq-titulo', 'en', 'Frequently asked questions'),
('landing', 'faq-titulo', 'es', 'Preguntas frecuentes'),
('landing', 'faqs-texto1', 'en', 'Once your registration is complete, our team will contact you to schedule an interview and explain the next steps.'),
('landing', 'faqs-texto1', 'es', 'Una vez completado tu registro, nuestro equipo se pondrá en contacto para programar una entrevista y explicarte los próximos pasos.'),
('landing', 'faqs-texto2', 'en', 'We require commitment to cooperative values, active participation in assemblies, and contribution to the common project.'),
('landing', 'faqs-texto2', 'es', 'Requerimos compromiso con los valores cooperativos, participación activa en las asambleas y contribución al proyecto común.'),
('landing', 'faqs-texto3', 'en', 'Work varies depending on the project, but all members contribute at least 21 hours per week to community activities.'),
('landing', 'faqs-texto3', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 21 horas semanales a las actividades comunitarias.'),
('landing', 'faqs-texto4', 'en', 'Contact us via WhatsApp and we will get back to you shortly.'),
('landing', 'faqs-texto4', 'es', 'Contactanos por WhatsApp y te responderemos a la brevedad.'),
('landing', 'faqs-titulo1', 'en', 'What happens when I register?'),
('landing', 'faqs-titulo1', 'es', '¿Qué pasa al momento de registrarme?'),
('landing', 'faqs-titulo2', 'en', 'What do I need to qualify?'),
('landing', 'faqs-titulo2', 'es', '¿Qué necesito para ser apto?'),
('landing', 'faqs-titulo3', 'en', 'How much do I have to work in the cooperative?'),
('landing', 'faqs-titulo3', 'es', '¿Qué tanto hay que trabajar en la cooperativa?'),
('landing', 'faqs-titulo4', 'en', 'Do you have any more questions?'),
('landing', 'faqs-titulo4', 'es', '¿Tenés más preguntas?'),
('landing', 'footer-contacto-direccion', 'en', 'Maipú 1764, Montevideo'),
('landing', 'footer-contacto-direccion', 'es', 'Maipú 1764, Montevideo'),
('landing', 'footer-contacto-email', 'en', 'Email: quartecgaze@gmail.com'),
('landing', 'footer-contacto-email', 'es', 'Correo: quartecgaze@gmail.com'),
('landing', 'footer-contacto-telefono', 'en', 'Phone: +598 92 204 459'),
('landing', 'footer-contacto-telefono', 'es', 'Teléfono: +598 92 204 459'),
('landing', 'footer-contacto-titulo', 'en', 'Contact'),
('landing', 'footer-contacto-titulo', 'es', 'Contacto'),
('landing', 'footer-contacto-whatsapp', 'en', 'Send us a WhatsApp'),
('landing', 'footer-contacto-whatsapp', 'es', 'Envíanos un WhatsApp'),
('landing', 'footer-derechos', 'en', '© 2025 Senda Firme. All rights reserved.'),
('landing', 'footer-derechos', 'es', '© 2025 Senda Firme. Todos los derechos reservados.'),
('landing', 'footer-descripcion', 'en', 'A financial cooperative committed to the economic and social development of our community.'),
('landing', 'footer-descripcion', 'es', 'Una cooperativa financiera comprometida con el desarrollo económico y social de nuestra comunidad.'),
('landing', 'footer-enlaces', 'en', 'Home;Values;Services;FAQ;Contact'),
('landing', 'footer-enlaces', 'es', 'Inicio;Valores;Servicios;Preguntas;Contacto'),
('landing', 'footer-enlaces-titulo', 'en', 'Quick links'),
('landing', 'footer-enlaces-titulo', 'es', 'Enlaces rápidos'),
('landing', 'footer-servicios', 'en', 'Programmed savings;Solidarity loans;Financial advisory;Investments;Insurance'),
('landing', 'footer-servicios', 'es', 'Ahorro programado;Créditos solidarios;Asesoría financiera;Inversiones;Seguros'),
('landing', 'footer-servicios-titulo', 'en', 'Services'),
('landing', 'footer-servicios-titulo', 'es', 'Servicios'),
('landing', 'header-nav', 'en', 'Home;Values;Services;FAQ;Contact'),
('landing', 'header-nav', 'es', 'Inicio;Valores;Servicios;Preguntas;Contacto'),
('landing', 'hero-btn', 'en', 'Our services'),
('landing', 'hero-btn', 'es', 'Nuestros servicios'),
('landing', 'hero-stat1-label', 'en', 'Members'),
('landing', 'hero-stat1-label', 'es', 'Miembros'),
('landing', 'hero-stat2-label', 'en', 'In savings'),
('landing', 'hero-stat2-label', 'es', 'En ahorros'),
('landing', 'hero-stat3-label', 'en', 'Satisfaction'),
('landing', 'hero-stat3-label', 'es', 'Satisfacción'),
('landing', 'hero-texto', 'en', 'Senda Firme is a cooperative committed to the economic and social development of its members through responsible financial services and personalized advice.'),
('landing', 'hero-texto', 'es', 'Senda Firme es una cooperativa comprometida con el desarrollo económico y social de sus miembros a través de servicios financieros responsables y asesoramiento personalizado.'),
('landing', 'hero-titulo', 'en', 'Driving collective growth'),
('landing', 'hero-titulo', 'es', 'Impulsando el crecimiento colectivo'),
('landing', 'iniciarsesion-btn', 'en', 'Log in'),
('landing', 'iniciarsesion-btn', 'es', 'Iniciar sesión'),
('landing', 'que-hacemos-btn', 'en', 'Apply for membership'),
('landing', 'que-hacemos-btn', 'es', 'Tramitar ingreso'),
('landing', 'servicios-card1-texto', 'en', 'Plan your future with our flexible savings plans and reach your financial goals.'),
('landing', 'servicios-card1-texto', 'es', 'Planificá tu futuro con nuestros planes de ahorro flexibles y alcanzá tus metas financieras.'),
('landing', 'servicios-card1-titulo', 'en', 'Programmed savings'),
('landing', 'servicios-card1-titulo', 'es', 'Ahorro programado'),
('landing', 'servicios-card2-texto', 'en', 'Access loans with preferential rates for productive and personal projects.'),
('landing', 'servicios-card2-texto', 'es', 'Accedé a créditos con tasas preferenciales para proyectos productivos y personales.'),
('landing', 'servicios-card2-titulo', 'en', 'Solidarity loans'),
('landing', 'servicios-card2-titulo', 'es', 'Créditos solidarios'),
('landing', 'servicios-card3-texto', 'en', 'Receive personalized guidance to optimize your resources and make better decisions.'),
('landing', 'servicios-card3-texto', 'es', 'Recibí orientación personalizada para optimizar tus recursos y tomar mejores decisiones.'),
('landing', 'servicios-card3-titulo', 'en', 'Financial advisory'),
('landing', 'servicios-card3-titulo', 'es', 'Asesoría financiera'),
('landing', 'servicios-link', 'en', 'Learn more'),
('landing', 'servicios-link', 'es', 'Conocer más'),
('landing', 'servicios-subtitulo', 'en', 'Solutions designed for the needs of our community'),
('landing', 'servicios-subtitulo', 'es', 'Soluciones diseñadas para las necesidades de nuestra comunidad'),
('landing', 'servicios-titulo', 'en', 'Our financial services'),
('landing', 'servicios-titulo', 'es', 'Nuestros servicios financieros'),
('landing', 'valores-card1-texto', 'en', 'We work together to achieve common goals and improve the quality of life of our members.'),
('landing', 'valores-card1-texto', 'es', 'Trabajamos juntos para alcanzar objetivos comunes y mejorar la calidad de vida de nuestros miembros.'),
('landing', 'valores-card1-titulo', 'en', 'Mutual support'),
('landing', 'valores-card1-titulo', 'es', 'Ayuda mutua'),
('landing', 'valores-card2-texto', 'en', 'We protect the resources of our members with high standards of safety and transparency.'),
('landing', 'valores-card2-texto', 'es', 'Protegemos los recursos de nuestros asociados con altos estándares de seguridad y transparencia.'),
('landing', 'valores-card2-titulo', 'en', 'Security'),
('landing', 'valores-card2-titulo', 'es', 'Seguridad'),
('landing', 'valores-card3-texto', 'en', 'We drive the economic growth of our community through responsible investments.'),
('landing', 'valores-card3-texto', 'es', 'Impulsamos el crecimiento económico de nuestra comunidad mediante inversiones responsables.'),
('landing', 'valores-card3-titulo', 'en', 'Community development'),
('landing', 'valores-card3-titulo', 'es', 'Desarrollo comunitario'),
('landing', 'valores-subtitulo', 'en', 'Principles that guide us and strengthen us as a community'),
('landing', 'valores-subtitulo', 'es', 'Principios que nos guían y nos fortalecen como comunidad'),
('landing', 'valores-titulo', 'en', 'Our cooperative values'),
('landing', 'valores-titulo', 'es', 'Nuestros valores cooperativos'),
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
('login', 'no-cuenta-text', 'en', 'Do not have an account yet?'),
('login', 'no-cuenta-text', 'es', '¿Aun no tienes una cuenta?'),
('pagos-usuario', 'boton-cambiar-admin', 'en', 'Switch to Admin'),
('pagos-usuario', 'boton-cambiar-admin', 'es', 'Cambiar a Admin'),
('pagos-usuario', 'boton-cerrar-sesion', 'en', 'Log out'),
('pagos-usuario', 'boton-cerrar-sesion', 'es', 'Cerrar sesión'),
('pagos-usuario', 'card-monto-total-subtexto', 'en', 'To be paid'),
('pagos-usuario', 'card-monto-total-subtexto', 'es', 'Por pagar'),
('pagos-usuario', 'card-monto-total-titulo', 'en', 'Total Amount'),
('pagos-usuario', 'card-monto-total-titulo', 'es', 'Monto Total'),
('pagos-usuario', 'card-pagos-pendientes-subtexto', 'en', 'Total amount'),
('pagos-usuario', 'card-pagos-pendientes-subtexto', 'es', 'Cantidad total'),
('pagos-usuario', 'card-pagos-pendientes-titulo', 'en', 'Pending Payments'),
('pagos-usuario', 'card-pagos-pendientes-titulo', 'es', 'Pagos Pendientes'),
('pagos-usuario', 'card-ultimo-pago-subtexto', 'en', 'Amount of the last payment'),
('pagos-usuario', 'card-ultimo-pago-subtexto', 'es', 'Monto del ultimo pago a realizar'),
('pagos-usuario', 'card-ultimo-pago-titulo', 'en', 'Last Payment'),
('pagos-usuario', 'card-ultimo-pago-titulo', 'es', 'Ultimo Pago'),
('pagos-usuario', 'filtros-boton-aplicar', 'en', 'Apply Filters'),
('pagos-usuario', 'filtros-boton-aplicar', 'es', 'Aplicar Filtros'),
('pagos-usuario', 'filtros-boton-realizar-pago', 'en', 'Make Payment'),
('pagos-usuario', 'filtros-boton-realizar-pago', 'es', 'Realizar Pago'),
('pagos-usuario', 'filtros-estado-label', 'en', 'Status:'),
('pagos-usuario', 'filtros-estado-label', 'es', 'Estado:'),
('pagos-usuario', 'filtros-estado-opcion-en-espera', 'en', 'On hold'),
('pagos-usuario', 'filtros-estado-opcion-en-espera', 'es', 'En Espera'),
('pagos-usuario', 'filtros-estado-opcion-pendiente', 'en', 'Pending'),
('pagos-usuario', 'filtros-estado-opcion-pendiente', 'es', 'Pendiente'),
('pagos-usuario', 'filtros-estado-opcion-todos', 'en', 'All'),
('pagos-usuario', 'filtros-estado-opcion-todos', 'es', 'Todos'),
('pagos-usuario', 'filtros-tipo-label', 'en', 'Type of Payment'),
('pagos-usuario', 'filtros-tipo-label', 'es', 'Tipo de Pago'),
('pagos-usuario', 'filtros-tipo-opcion-mensual', 'en', 'Monthly'),
('pagos-usuario', 'filtros-tipo-opcion-mensual', 'es', 'Mensual'),
('pagos-usuario', 'filtros-tipo-opcion-otros', 'en', 'Others'),
('pagos-usuario', 'filtros-tipo-opcion-otros', 'es', 'Otros'),
('pagos-usuario', 'filtros-tipo-opcion-todos', 'en', 'All'),
('pagos-usuario', 'filtros-tipo-opcion-todos', 'es', 'Todos'),
('pagos-usuario', 'menu-configuracion', 'en', 'Settings'),
('pagos-usuario', 'menu-configuracion', 'es', 'Configuración'),
('pagos-usuario', 'menu-horas-trabajadas', 'en', 'Worked Hours'),
('pagos-usuario', 'menu-horas-trabajadas', 'es', 'Horas Trabajadas'),
('pagos-usuario', 'menu-inicio', 'en', 'Home'),
('pagos-usuario', 'menu-inicio', 'es', 'Inicio'),
('pagos-usuario', 'menu-pagos', 'en', 'Payments'),
('pagos-usuario', 'menu-pagos', 'es', 'Pagos'),
('pagos-usuario', 'menu-unidad-habitacional', 'en', 'Housing Unit'),
('pagos-usuario', 'menu-unidad-habitacional', 'es', 'Unidad Habitacional'),
('pagos-usuario', 'modal-boton-cancelar', 'en', 'Cancel'),
('pagos-usuario', 'modal-boton-cancelar', 'es', 'Cancelar'),
('pagos-usuario', 'modal-boton-confirmar', 'en', 'Confirm Payment'),
('pagos-usuario', 'modal-boton-confirmar', 'es', 'Confirmar Pago'),
('pagos-usuario', 'modal-pago-archivo-placeholder', 'en', 'No file selected'),
('pagos-usuario', 'modal-pago-archivo-placeholder', 'es', 'Ningún archivo seleccionado'),
('pagos-usuario', 'modal-pago-boton-archivo', 'en', 'Select file'),
('pagos-usuario', 'modal-pago-boton-archivo', 'es', 'Seleccionar archivo'),
('pagos-usuario', 'modal-pago-detalles-titulo', 'en', 'Selected payment details'),
('pagos-usuario', 'modal-pago-detalles-titulo', 'es', 'Detalles del pago seleccionado'),
('pagos-usuario', 'modal-pago-label-comprobante', 'en', 'Proof of payment:'),
('pagos-usuario', 'modal-pago-label-comprobante', 'es', 'Comprobante de pago:'),
('pagos-usuario', 'modal-pago-label-concepto', 'en', 'Concept:'),
('pagos-usuario', 'modal-pago-label-concepto', 'es', 'Concepto:'),
('pagos-usuario', 'modal-pago-label-monto', 'en', 'Amount:'),
('pagos-usuario', 'modal-pago-label-monto', 'es', 'Monto:'),
('pagos-usuario', 'modal-pago-label-seleccionar', 'en', 'Select a payment to make:'),
('pagos-usuario', 'modal-pago-label-seleccionar', 'es', 'Seleccionar pago a realizar:'),
('pagos-usuario', 'modal-pago-label-vencimiento', 'en', 'Due date:'),
('pagos-usuario', 'modal-pago-label-vencimiento', 'es', 'Fecha de vencimiento:'),
('pagos-usuario', 'modal-pago-opcion-placeholder', 'en', 'Select a payment'),
('pagos-usuario', 'modal-pago-opcion-placeholder', 'es', 'Seleccione un pago'),
('pagos-usuario', 'modal-pago-texto-ayuda', 'en', 'Accepted formats: PDF, JPG, PNG (Maximum size: 5MB)'),
('pagos-usuario', 'modal-pago-texto-ayuda', 'es', 'Formatos aceptados: PDF, JPG, PNG (Tamaño máximo: 5MB)'),
('pagos-usuario', 'modal-pago-titulo', 'en', 'Make Payment'),
('pagos-usuario', 'modal-pago-titulo', 'es', 'Realizar Pago'),
('pagos-usuario', 'pagos-mensaje-exito', 'en', 'General payment registered successfully, pending approval from an administrator'),
('pagos-usuario', 'pagos-mensaje-exito', 'es', 'Pago general registrado correctamente, a la espera de la aprobacion de un administrador'),
('pagos-usuario', 'pagos-subtitulo', 'en', 'Check and manage your pending payments with the cooperative'),
('pagos-usuario', 'pagos-subtitulo', 'es', 'Consulta y gestiona tus pagos pendientes con la cooperativa'),
('pagos-usuario', 'pagos-titulo', 'en', 'Pending Payments'),
('pagos-usuario', 'pagos-titulo', 'es', 'Pagos Pendientes'),
('pagos-usuario', 'seccion-detalle-pagos-titulo', 'en', 'Detail of Overdue Payments'),
('pagos-usuario', 'seccion-detalle-pagos-titulo', 'es', 'Detalle de Pagos Atrasados'),
('pagos-usuario', 'sidebar-rol-usuario', 'en', 'User'),
('pagos-usuario', 'sidebar-rol-usuario', 'es', 'Usuario'),
('pagos-usuario', 'sidebar-slogan', 'en', 'Building opportunities together'),
('pagos-usuario', 'sidebar-slogan', 'es', 'Construyendo oportunidades juntos'),
('pagos-usuario', 'tabla-pagos-columna-acciones', 'en', 'Actions'),
('pagos-usuario', 'tabla-pagos-columna-acciones', 'es', 'Acciones'),
('pagos-usuario', 'tabla-pagos-columna-concepto', 'en', 'Concept'),
('pagos-usuario', 'tabla-pagos-columna-concepto', 'es', 'Concepto'),
('pagos-usuario', 'tabla-pagos-columna-estado', 'en', 'Status'),
('pagos-usuario', 'tabla-pagos-columna-estado', 'es', 'Estado'),
('pagos-usuario', 'tabla-pagos-columna-fecha', 'en', 'Payment Date'),
('pagos-usuario', 'tabla-pagos-columna-fecha', 'es', 'Fecha del Pago'),
('pagos-usuario', 'tabla-pagos-columna-monto', 'en', 'Amount'),
('pagos-usuario', 'tabla-pagos-columna-monto', 'es', 'Monto'),
('registro', 'cuenta-link', 'en', 'Log in here'),
('registro', 'cuenta-link', 'es', 'Inicia sesión aquí'),
('registro', 'cuenta-text', 'en', 'Already have an account?\r\n\r\n'),
('registro', 'cuenta-text', 'es', '¿Ya tienes una cuenta?'),
('registro', 'registro-btn', 'en', 'Sign In'),
('registro', 'registro-btn', 'es', 'Registrarse'),
('registro', 'registro-form', 'en', 'Name;Last Name;Email Address;Phone number;National ID;Password;Confirm Password;I accept the <a href=\"#\">Terms of Service</a> and <a href=\"#\">Privacy Policy</a>'),
('registro', 'registro-form', 'es', 'Nombre;Apellido;Correo electrónico;Teléfono Móvil;Cédula de Identidad;Contraseña;Confirmar Contraseña;Acepto los <a href=\"#\">Términos de servicio</a> y <a href=\"#\">Política de\n                            privacidad'),
('registro', 'registro-titulo', 'en', 'Create your account'),
('registro', 'registro-titulo', 'es', 'Crea tu cuenta'),
('unidad-usuario', 'boton-cambiar-admin', 'en', 'Switch to Admin'),
('unidad-usuario', 'boton-cambiar-admin', 'es', 'Cambiar a Admin'),
('unidad-usuario', 'boton-cerrar-sesion', 'en', 'Log out'),
('unidad-usuario', 'boton-cerrar-sesion', 'es', 'Cerrar sesión'),
('unidad-usuario', 'integrantes-boton-agregar', 'en', 'Add Member'),
('unidad-usuario', 'integrantes-boton-agregar', 'es', 'Agregar Integrante'),
('unidad-usuario', 'integrantes-tabla-acciones', 'en', 'Actions'),
('unidad-usuario', 'integrantes-tabla-acciones', 'es', 'Acciones'),
('unidad-usuario', 'integrantes-tabla-apellido', 'en', 'Last Name'),
('unidad-usuario', 'integrantes-tabla-apellido', 'es', 'Apellido'),
('unidad-usuario', 'integrantes-tabla-ci', 'en', 'ID'),
('unidad-usuario', 'integrantes-tabla-ci', 'es', 'CI'),
('unidad-usuario', 'integrantes-tabla-fecha-nacimiento', 'en', 'Date of Birth'),
('unidad-usuario', 'integrantes-tabla-fecha-nacimiento', 'es', 'Fecha de Nacimiento'),
('unidad-usuario', 'integrantes-tabla-genero', 'en', 'Gender'),
('unidad-usuario', 'integrantes-tabla-genero', 'es', 'Genero'),
('unidad-usuario', 'integrantes-tabla-mail', 'en', 'Email'),
('unidad-usuario', 'integrantes-tabla-mail', 'es', 'Mail'),
('unidad-usuario', 'integrantes-tabla-nombre', 'en', 'First Name'),
('unidad-usuario', 'integrantes-tabla-nombre', 'es', 'Nombre'),
('unidad-usuario', 'integrantes-titulo', 'en', 'Family Members'),
('unidad-usuario', 'integrantes-titulo', 'es', 'Integrantes Familiares'),
('unidad-usuario', 'integrantes-vacio-texto', 'en', 'Add your family members to manage them here'),
('unidad-usuario', 'integrantes-vacio-texto', 'es', 'Agrega los integrantes de tu familia para gestionarlos desde aquí'),
('unidad-usuario', 'integrantes-vacio-titulo', 'en', 'There are no registered family members'),
('unidad-usuario', 'integrantes-vacio-titulo', 'es', 'No hay integrantes familiares registrados'),
('unidad-usuario', 'menu-configuracion', 'en', 'Settings'),
('unidad-usuario', 'menu-configuracion', 'es', 'Configuración'),
('unidad-usuario', 'menu-horas-trabajadas', 'en', 'Worked Hours'),
('unidad-usuario', 'menu-horas-trabajadas', 'es', 'Horas Trabajadas'),
('unidad-usuario', 'menu-inicio', 'en', 'Home'),
('unidad-usuario', 'menu-inicio', 'es', 'Inicio'),
('unidad-usuario', 'menu-pagos', 'en', 'Payments'),
('unidad-usuario', 'menu-pagos', 'es', 'Pagos'),
('unidad-usuario', 'menu-unidad-habitacional', 'en', 'Housing Unit'),
('unidad-usuario', 'menu-unidad-habitacional', 'es', 'Unidad Habitacional'),
('unidad-usuario', 'modal-boton-cancelar', 'en', 'Cancel'),
('unidad-usuario', 'modal-boton-cancelar', 'es', 'Cancelar'),
('unidad-usuario', 'modal-boton-eliminar', 'en', 'Delete'),
('unidad-usuario', 'modal-boton-eliminar', 'es', 'Eliminar'),
('unidad-usuario', 'modal-boton-guardar', 'en', 'Save'),
('unidad-usuario', 'modal-boton-guardar', 'es', 'Guardar'),
('unidad-usuario', 'modal-confirmacion-texto', 'en', 'Are you sure you want to delete this family member?'),
('unidad-usuario', 'modal-confirmacion-texto', 'es', '¿Estás seguro de que deseas eliminar este integrante familiar?'),
('unidad-usuario', 'modal-confirmacion-titulo', 'en', 'Confirm Deletion'),
('unidad-usuario', 'modal-confirmacion-titulo', 'es', 'Confirmar Eliminación'),
('unidad-usuario', 'modal-integrante-label-apellido', 'en', 'Last Name:'),
('unidad-usuario', 'modal-integrante-label-apellido', 'es', 'Apellido:'),
('unidad-usuario', 'modal-integrante-label-ci', 'en', 'ID Number:'),
('unidad-usuario', 'modal-integrante-label-ci', 'es', 'Cedula:'),
('unidad-usuario', 'modal-integrante-label-email', 'en', 'Email:'),
('unidad-usuario', 'modal-integrante-label-email', 'es', 'Email:'),
('unidad-usuario', 'modal-integrante-label-fecha-nacimiento', 'en', 'Date of Birth:'),
('unidad-usuario', 'modal-integrante-label-fecha-nacimiento', 'es', 'Fecha de Nacimiento:'),
('unidad-usuario', 'modal-integrante-label-nombre', 'en', 'First Name:'),
('unidad-usuario', 'modal-integrante-label-nombre', 'es', 'Nombre:'),
('unidad-usuario', 'modal-integrante-mensaje-error', 'en', 'Error while saving the member.'),
('unidad-usuario', 'modal-integrante-mensaje-error', 'es', 'Error al guardar el integrante.'),
('unidad-usuario', 'modal-integrante-mensaje-exito', 'en', 'Member saved successfully.'),
('unidad-usuario', 'modal-integrante-mensaje-exito', 'es', 'Integrante guardado correctamente.'),
('unidad-usuario', 'modal-integrante-select-femenino', 'en', 'Female'),
('unidad-usuario', 'modal-integrante-select-femenino', 'es', 'Femenino'),
('unidad-usuario', 'modal-integrante-select-masculino', 'en', 'Male'),
('unidad-usuario', 'modal-integrante-select-masculino', 'es', 'Masculino'),
('unidad-usuario', 'modal-integrante-select-placeholder', 'en', 'Select…'),
('unidad-usuario', 'modal-integrante-select-placeholder', 'es', 'Seleccioná…'),
('unidad-usuario', 'modal-integrante-titulo-agregar', 'en', 'Add Family Member'),
('unidad-usuario', 'modal-integrante-titulo-agregar', 'es', 'Agregar Integrante Familiar'),
('unidad-usuario', 'sidebar-rol-usuario', 'en', 'User'),
('unidad-usuario', 'sidebar-rol-usuario', 'es', 'Usuario'),
('unidad-usuario', 'sidebar-slogan', 'en', 'Building opportunities together'),
('unidad-usuario', 'sidebar-slogan', 'es', 'Construyendo oportunidades juntos'),
('unidad-usuario', 'unidad-estado-label', 'en', 'Status:'),
('unidad-usuario', 'unidad-estado-label', 'es', 'Estado:'),
('unidad-usuario', 'unidad-habitaciones-label', 'en', 'Rooms:'),
('unidad-usuario', 'unidad-habitaciones-label', 'es', 'Habitaciones:'),
('unidad-usuario', 'unidad-info-titulo', 'en', 'Unit Information'),
('unidad-usuario', 'unidad-info-titulo', 'es', 'Información de la Unidad'),
('unidad-usuario', 'unidad-numero-label', 'en', 'Unit Number:'),
('unidad-usuario', 'unidad-numero-label', 'es', 'Número de Unidad:'),
('unidad-usuario', 'unidad-pasillo-label', 'en', 'Hallway:'),
('unidad-usuario', 'unidad-pasillo-label', 'es', 'Pasillo:'),
('unidad-usuario', 'unidad-subtitulo', 'en', 'Manage your home data and your family members'),
('unidad-usuario', 'unidad-subtitulo', 'es', 'Gestiona los datos de tu vivienda y los integrantes de tu familia'),
('unidad-usuario', 'unidad-titulo', 'en', 'Housing Unit'),
('unidad-usuario', 'unidad-titulo', 'es', 'Unidad Habitacional'),
('usuario', 'btn-cambiar-sesion', 'en', 'Switch to Admin'),
('usuario', 'btn-cambiar-sesion', 'es', 'Cambiar a Admin'),
('usuario', 'btn-cerrar-sesion', 'en', 'Log out'),
('usuario', 'btn-cerrar-sesion', 'es', 'Cerrar sesión'),
('usuario', 'card-asistencias-subtexto', 'en', 'Of the meetings'),
('usuario', 'card-asistencias-subtexto', 'es', 'De las reuniones'),
('usuario', 'card-asistencias-titulo', 'en', 'Attendance'),
('usuario', 'card-asistencias-titulo', 'es', 'Asistencias'),
('usuario', 'card-horas-subtexto', 'en', 'This week'),
('usuario', 'card-horas-subtexto', 'es', 'Esta Semana'),
('usuario', 'card-horas-titulo', 'en', 'Worked hours'),
('usuario', 'card-horas-titulo', 'es', 'Horas Trabajadas'),
('usuario', 'card-pagos-subtexto', 'en', 'Total: $0'),
('usuario', 'card-pagos-subtexto', 'es', 'Total: $0'),
('usuario', 'card-pagos-titulo', 'en', 'Overdue payments'),
('usuario', 'card-pagos-titulo', 'es', 'Pagos Atrasados'),
('usuario', 'card-reuniones-subtexto', 'en', 'Upcoming'),
('usuario', 'card-reuniones-subtexto', 'es', 'Próximas'),
('usuario', 'card-reuniones-titulo', 'en', 'Meetings'),
('usuario', 'card-reuniones-titulo', 'es', 'Reuniones'),
('usuario', 'dashboard-header-subtitulo', 'en', 'Here you can manage all your activities in the cooperative'),
('usuario', 'dashboard-header-subtitulo', 'es', 'Aquí puedes gestionar todas tus actividades en la cooperativa'),
('usuario', 'dashboard-header-titulo', 'en', 'Welcome,'),
('usuario', 'dashboard-header-titulo', 'es', 'Bienvenido,'),
('usuario', 'modal-boton-cerrar', 'en', 'Close'),
('usuario', 'modal-boton-cerrar', 'es', 'Cerrar'),
('usuario', 'modal-descripcion-label', 'en', 'Description'),
('usuario', 'modal-descripcion-label', 'es', 'Descripción'),
('usuario', 'modal-estado-label', 'en', 'Status'),
('usuario', 'modal-estado-label', 'es', 'Estado'),
('usuario', 'modal-fecha-label', 'en', 'Date and time'),
('usuario', 'modal-fecha-label', 'es', 'Fecha y Hora'),
('usuario', 'modal-titulo', 'en', 'Meeting details'),
('usuario', 'modal-titulo', 'es', 'Detalles de la Reunión'),
('usuario', 'modal-ubicacion-label', 'en', 'Location'),
('usuario', 'modal-ubicacion-label', 'es', 'Ubicación'),
('usuario', 'perfil-rol', 'en', 'User'),
('usuario', 'perfil-rol', 'es', 'Usuario'),
('usuario', 'seccion-proximas-reuniones-titulo', 'en', 'Upcoming meetings'),
('usuario', 'seccion-proximas-reuniones-titulo', 'es', 'Proximas Reuniones'),
('usuario', 'seccion-reuniones-completadas-titulo', 'en', 'Completed meetings'),
('usuario', 'seccion-reuniones-completadas-titulo', 'es', 'Reuniones Completadas'),
('usuario', 'sidebar-menu-configuracion', 'en', 'Settings'),
('usuario', 'sidebar-menu-configuracion', 'es', 'Configuración'),
('usuario', 'sidebar-menu-horas', 'en', 'Worked hours'),
('usuario', 'sidebar-menu-horas', 'es', 'Horas Trabajadas'),
('usuario', 'sidebar-menu-inicio', 'en', 'Home'),
('usuario', 'sidebar-menu-inicio', 'es', 'Inicio'),
('usuario', 'sidebar-menu-pagos', 'en', 'Payments'),
('usuario', 'sidebar-menu-pagos', 'es', 'Pagos'),
('usuario', 'sidebar-menu-unidad', 'en', 'Housing unit'),
('usuario', 'sidebar-menu-unidad', 'es', 'Unidad Habitacional'),
('usuario', 'sidebar-slogan', 'en', 'Building opportunities together'),
('usuario', 'sidebar-slogan', 'es', 'Construyendo oportunidades juntos');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `unidad_habitacional`
--

INSERT INTO `unidad_habitacional` (`ID_Unidad_habitacional`, `ID_Persona`, `Numero_puerta`, `Pasillo`, `Estado_unidad`, `Cantidad_habitaciones`) VALUES
(1, 2, '1', 'Galaxia', 'En espera', 1),
(2, 3, '2', 'Galaxia', 'En espera', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Persona` int(11) NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Fecha_ingreso` date DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_Persona`, `Fecha_nacimiento`, `Fecha_ingreso`, `Foto`) VALUES
(2, '2025-11-17', '2020-11-11', NULL),
(3, '2007-07-05', '2025-11-17', '3.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`ID_Asistencia`),
  ADD KEY `ID_Reunion` (`ID_Reunion`),
  ADD KEY `ID_Persona` (`ID_Persona`);

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
-- Indices de la tabla `reunion`
--
ALTER TABLE `reunion`
  ADD PRIMARY KEY (`ID_Reunion`);

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
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `ID_Asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comprobante_pago`
--
ALTER TABLE `comprobante_pago`
  MODIFY `ID_Comprobante_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `falta`
--
ALTER TABLE `falta`
  MODIFY `ID_Falta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_trabajadas`
--
ALTER TABLE `horas_trabajadas`
  MODIFY `ID_Horas_trabajadas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `integrante_familiar`
--
ALTER TABLE `integrante_familiar`
  MODIFY `ID_Integrante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `numero_de_telefono`
--
ALTER TABLE `numero_de_telefono`
  MODIFY `ID_Telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `ID_Persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reunion`
--
ALTER TABLE `reunion`
  MODIFY `ID_Reunion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `semana_trabajo`
--
ALTER TABLE `semana_trabajo`
  MODIFY `ID_Semana_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `unidad_habitacional`
--
ALTER TABLE `unidad_habitacional`
  MODIFY `ID_Unidad_habitacional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`ID_Reunion`) REFERENCES `reunion` (`ID_Reunion`),
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);

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
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `persona` (`ID_Persona`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
