

CREATE TABLE `Admin` (
  `ID_Persona` int NOT NULL,
  `Nivel_permisos` enum('Operador','Admin') DEFAULT NULL
) 



CREATE TABLE `Comprobante_pago` (
  `ID_Comprobante_pago` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Motivo_pago` varchar(255) DEFAULT NULL,
  `Estado_pago` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Mes` date DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Monto` int DEFAULT NULL
)


CREATE TABLE `Falta` (
  `ID_Falta` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `ID_Semana_trabajo` int DEFAULT NULL,
  `Motivo_falta` varchar(255) DEFAULT NULL
) 



CREATE TABLE `Horas_trabajadas` (
  `ID_Horas_trabajadas` int NOT NULL,
  `Horas` int DEFAULT NULL,
  `Fecha_registro_horas` date DEFAULT NULL,
  `ID_Persona` int DEFAULT NULL,
  `ID_Semana_trabajo` int DEFAULT NULL
) 


CREATE TABLE `Interesado` (
  `ID_Persona` int NOT NULL,
  `Antecedentes` varchar(255) DEFAULT NULL,
  `Estado_entrevista` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Estado_antecedentes` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Fecha_entrevista` date DEFAULT NULL,
  `Hora_entrevista` time DEFAULT NULL,
  `Pago_inicial` varchar(255) DEFAULT NULL,
  `Estado_pago_inicial` enum('En espera','Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  `Monto_pago_inicial` int DEFAULT NULL
) 



CREATE TABLE `Numero_de_telefono` (
  `ID_Telefono` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Telefono` int DEFAULT NULL
) 



CREATE TABLE `Persona` (
  `ID_Persona` int NOT NULL,
  `CI` varchar(8) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(100) NOT NULL,
  `Rol` enum('Usuario','Interesado','Admin') DEFAULT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL
)


CREATE TABLE `Semana_trabajo` (
  `ID_Semana_trabajo` int NOT NULL,
  `Horas_semanales` int DEFAULT NULL,
  `Fecha_semana` date DEFAULT NULL
) 



CREATE TABLE `Traducciones` (
  `pagina` varchar(100) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `idioma` varchar(10) NOT NULL,
  `texto` text NOT NULL
)

--
-- Dumping data for table `Traducciones`
--

INSERT INTO `Traducciones` (`pagina`, `clave`, `idioma`, `texto`) VALUES
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
('landing', 'faqs-texto3', 'en', 'Work varies depending on the project, but all members contribute at least 8 hours per month to community activities.'),
('landing', 'faqs-texto3', 'es', 'El trabajo varía según el proyecto, pero todos los miembros contribuyen con al menos 8 horas mensuales a las actividades comunitarias.'),
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
('landing', 'footer-derechos', 'en', '© 2025 Cooperativa Nombre. All rights reserved.'),
('landing', 'footer-derechos', 'es', '© 2025 Cooperativa Nombre. Todos los derechos reservados.'),
('landing', 'header-nav', 'en', 'Home;About us;Localization;FAQ;Contact'),
('landing', 'header-nav', 'es', 'Inicio;Sobre nosotros;Localizacion;FAQ;Contacto'),
('landing', 'hero-btn', 'en', 'BOOK YOUR SPOT'),
('landing', 'hero-btn', 'es', 'RESERVA TU LUGAR'),
('landing', 'hero-texto', 'en', 'In a world that divides, we choose to unite—to share, support, and build opportunities through cooperation.'),
('landing', 'hero-texto', 'es', 'En un mundo que divide, elegimos unirnos para compartir, apoyarnos y construir oportunidades desde la cooperación.'),
('landing', 'hero-titulo', 'es', 'Cooperativa<br>nombre'),
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
('login', 'footer', 'en', '© 2025 Cooperativa nombre. All rights reserved.'),
('login', 'footer', 'es', '© 2025 Cooperativa Nombre. Todos los derechos reservados.'),
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
('login', 'login-titulo-side', 'en', 'Welcome to Cooperativa Nombre'),
('login', 'login-titulo-side', 'es', 'Bienvenido a Cooperativa Nombre'),
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
('registro', 'registro-form', 'en', 'Full Name;Email Address;Phone number;National ID;Password;Confirm Password;I accept the <a href=\"#\">Terms of Service</a> and <a href=\"#\">Privacy Policy</a>'),
('registro', 'registro-form', 'es', 'Nombre completo;Correo electrónico;Teléfono Móvil;Cédula de Identidad;Contraseña;Confirmar Contraseña;Acepto los <a href=\"#\">Términos de servicio</a> y <a href=\"#\">Política de\n                            privacidad'),
('registro', 'registro-titulo', 'en', 'Create your account'),
('registro', 'registro-titulo', 'es', 'Crea tu cuenta');

-- --------------------------------------------------------

--
-- Table structure for table `Unidad_habitacional`
--

CREATE TABLE `Unidad_habitacional` (
  `ID_Unidad_habitacional` int NOT NULL,
  `ID_Persona` int DEFAULT NULL,
  `Numero_puerta` varchar(20) DEFAULT NULL,
  `Pasillo` varchar(20) DEFAULT NULL,
  `Estado_unidad` enum('En espera','En pausa','En construcción','Finalizada') DEFAULT NULL,
  `Cantidad_habitaciones` int DEFAULT NULL
) 

-- --------------------------------------------------------

--
-- Table structure for table `Unidad_habitacional_Semana_trabajo`
--

CREATE TABLE `Unidad_habitacional_Semana_trabajo` (
  `ID_Semana_trabajo` int NOT NULL,
  `ID_Unidad_habitacional` int NOT NULL
)

-- --------------------------------------------------------

--
-- Table structure for table `Usuario`
--

CREATE TABLE `Usuario` (
  `ID_Persona` int NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Fecha_ingreso` date DEFAULT NULL
)

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indexes for table `Comprobante_pago`
--
ALTER TABLE `Comprobante_pago`
  ADD PRIMARY KEY (`ID_Comprobante_pago`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indexes for table `Falta`
--
ALTER TABLE `Falta`
  ADD PRIMARY KEY (`ID_Falta`),
  ADD KEY `ID_Persona` (`ID_Persona`),
  ADD KEY `ID_Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Indexes for table `Horas_trabajadas`
--
ALTER TABLE `Horas_trabajadas`
  ADD PRIMARY KEY (`ID_Horas_trabajadas`),
  ADD KEY `ID_Persona` (`ID_Persona`),
  ADD KEY `ID_Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Indexes for table `Interesado`
--
ALTER TABLE `Interesado`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- Indexes for table `Numero_de_telefono`
--
ALTER TABLE `Numero_de_telefono`
  ADD PRIMARY KEY (`ID_Telefono`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indexes for table `Persona`
--
ALTER TABLE `Persona`
  ADD PRIMARY KEY (`ID_Persona`),
  ADD UNIQUE KEY `CI` (`CI`);

--
-- Indexes for table `Semana_trabajo`
--
ALTER TABLE `Semana_trabajo`
  ADD PRIMARY KEY (`ID_Semana_trabajo`);

--
-- Indexes for table `Traducciones`
--
ALTER TABLE `Traducciones`
  ADD PRIMARY KEY (`pagina`,`clave`,`idioma`);

--
-- Indexes for table `Unidad_habitacional`
--
ALTER TABLE `Unidad_habitacional`
  ADD PRIMARY KEY (`ID_Unidad_habitacional`),
  ADD KEY `ID_Persona` (`ID_Persona`);

--
-- Indexes for table `Unidad_habitacional_Semana_trabajo`
--
ALTER TABLE `Unidad_habitacional_Semana_trabajo`
  ADD PRIMARY KEY (`ID_Semana_trabajo`,`ID_Unidad_habitacional`),
  ADD KEY `ID_Unidad_habitacional` (`ID_Unidad_habitacional`);

--
-- Indexes for table `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`ID_Persona`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comprobante_pago`
--
ALTER TABLE `Comprobante_pago`
  MODIFY `ID_Comprobante_pago` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Falta`
--
ALTER TABLE `Falta`
  MODIFY `ID_Falta` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Horas_trabajadas`
--
ALTER TABLE `Horas_trabajadas`
  MODIFY `ID_Horas_trabajadas` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Numero_de_telefono`
--
ALTER TABLE `Numero_de_telefono`
  MODIFY `ID_Telefono` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Persona`
--
ALTER TABLE `Persona`
  MODIFY `ID_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admin`
--
ALTER TABLE `Admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);

--
-- Constraints for table `Comprobante_pago`
--
ALTER TABLE `Comprobante_pago`
  ADD CONSTRAINT `comprobante_pago_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);

--
-- Constraints for table `Falta`
--
ALTER TABLE `Falta`
  ADD CONSTRAINT `falta_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`),
  ADD CONSTRAINT `falta_ibfk_2` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Constraints for table `Horas_trabajadas`
--
ALTER TABLE `Horas_trabajadas`
  ADD CONSTRAINT `horas_trabajadas_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`),
  ADD CONSTRAINT `horas_trabajadas_ibfk_2` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `Semana_trabajo` (`ID_Semana_trabajo`);

--
-- Constraints for table `Interesado`
--
ALTER TABLE `Interesado`
  ADD CONSTRAINT `interesado_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);

--
-- Constraints for table `Numero_de_telefono`
--
ALTER TABLE `Numero_de_telefono`
  ADD CONSTRAINT `numero_de_telefono_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);

--
-- Constraints for table `Unidad_habitacional`
--
ALTER TABLE `Unidad_habitacional`
  ADD CONSTRAINT `unidad_habitacional_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);

--
-- Constraints for table `Unidad_habitacional_Semana_trabajo`
--
ALTER TABLE `Unidad_habitacional_Semana_trabajo`
  ADD CONSTRAINT `unidad_habitacional_semana_trabajo_ibfk_1` FOREIGN KEY (`ID_Semana_trabajo`) REFERENCES `Semana_trabajo` (`ID_Semana_trabajo`),
  ADD CONSTRAINT `unidad_habitacional_semana_trabajo_ibfk_2` FOREIGN KEY (`ID_Unidad_habitacional`) REFERENCES `Unidad_habitacional` (`ID_Unidad_habitacional`);

--ssssssss
-- Constraints for table `Usuario`
--
ALTER TABLE `Usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_Persona`) REFERENCES `Persona` (`ID_Persona`);
COMMIT;

