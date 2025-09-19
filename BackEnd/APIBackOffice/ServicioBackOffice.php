<?php
    require_once __DIR__ .'../../APIUsuarios/Modelos/Usuario.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Persona.php'; 
    require_once __DIR__ .'../../APIUsuarios/Modelos/Admin.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Interesado.php';
    require_once __DIR__ .'../../APICooperativa/Modelos/ComprobantePago.php';
    require_once __DIR__ .'../../APICooperativa/Modelos/UnidadHabitacional.php';

    Class ServicioBackOffice {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }


        public function cargarUsuario($idPersona){
            $fechaIngreso = date("Y-m-d"); //asigna la fecha del momento en el que se ejecuta el metodo
            $persona = $this->repositorio->getPersona($idPersona);
            $this->repositorio->cambiarRol($idPersona);
            $usuario = new Usuario($persona->getCi(), $persona->getEmail(), $persona->getTelefono(), $idPersona, $persona->getNombre(), $persona->getApellido(), $persona->getContraseña(), $persona->getRol(),
                    null, 
                    $fechaIngreso,
                    null
                );
            $this->repositorio->cargarUsuario($usuario);

        }

        public function cargarAdmin($ci, $email, $telefono, $nombre, $apellido, $contraseña, $nivelPermisos){
            $fechaIngreso = date("Y-m-d");
            if(!$this->repositorio->personaExisteConCI($ci)){
                    $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
                    $persona = new Persona($ci, $email, $telefono, null, $nombre, $apellido, $contraseña, "Admin");
                    $this->repositorio->cargarPersona($persona);
                    $idPersona = $this->repositorio->getIDPersonaConCi($ci);
                    $this->repositorio->cargarTelefono($idPersona, $telefono);
                    //Las cosas en null se asignan posteriormente en el backoffice ademas de cambiar el estado "En espera" etc
                    $admin = new Admin($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, "Admin", //datos heredados de persona
                    $nivelPermisos, null, $fechaIngreso); //datos de Admin
                    $this->repositorio->cargarAdmin($admin);
            } else {
                $idPersona = $this->repositorio->getIDPersonaConCi($ci);
                $admin = new Admin($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, "Admin", //datos heredados de persona
                $nivelPermisos, null, $fechaIngreso); //datos de Admin
                $this->repositorio->cargarAdmin($admin);
            }
        }



        public function getAdmin($id) {
            if (!$this->repositorio->adminExisteID($id)) {
                throw new Exception("El admin no existe", 404);
            } else {
                return $this->repositorio->getDatosAdmin($id);
            }
        
        }  

        public function rechazarInteresado($idPersona){
            if($this->repositorio->personaExiste($idPersona)){
                $this->repositorio->borrarTelefono($idPersona);
                $this->repositorio->borrarInteresado($idPersona);
                $this->repositorio->borrarPersona($idPersona);
                //opcional podria quedar un antecedente de que ya fue rechazado
            }else{
                throw new Exception("Esa persona no existe", 404);
            }
        }

        public function asignarPagoInicial($idPersona, $montoPagoInicial){
            $this->repositorio->setMontoPagoInicial($idPersona, $montoPagoInicial);
        }

        public function asignarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista){
            $this->repositorio->cargarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista);
            $this->repositorio->revisarEstado($idPersona, "Estado_entrevista", "Pendiente");
        }


        public function rechazarEstado($idPersona, $campo) {
            $camposValidos = ["Estado_entrevista", "Estado_antecedentes", "Estado_pago_inicial"];
            //hacemos esto para evitar una posible inyeccion sql desde el javscript
            if (!in_array($campo, $camposValidos)) {
                return false;
            }
        
            $this->repositorio->revisarEstado($idPersona, $campo, "Rechazado");
            return true;
        }

        public function aprobarEstado($idPersona, $campo) {
            $camposValidos = ["Estado_entrevista", "Estado_antecedentes", "Estado_pago_inicial"];
            //hacemos esto para evitar una posible inyeccion sql desde el javscript
            if (!in_array($campo, $camposValidos)) {
                return false;
            }
        
            $this->repositorio->revisarEstado($idPersona, $campo, "Aprobado");
            return true;
        }

        public function contarInteresados() {
            $resultado = $this->repositorio->soloInteresados();
            $cantidadInteresados = mysqli_num_rows($resultado);
        
            return $cantidadInteresados;
        }

        public function getInteresados(){
            $interesadosObj = $this->repositorio->getInteresados();
            $interesadoArrayAsociativo = [];
            foreach($interesadosObj as $interesado){
                
                $interesadoArrayAsociativo[$interesado->getIdPersona()] = $interesado->toArray();
            }
            return $interesadoArrayAsociativo;
        }

        public function subirFoto($nombreArchivo, $nombreTemp) {
            session_start();
            $rutaCarpeta = "../../Recursos/FotosPerfil/";
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nuevoNombre = $_SESSION['id'] . '.' . $extension;
            $rutaFoto = $rutaCarpeta . $nuevoNombre;
            $nombreViejo = $this->repositorio->getFoto($_SESSION['id']);
            $rutaFotoVieja = $rutaCarpeta . $nombreViejo;
            //Opcional a futuro, podriamos agregar algo que verifique las extensiones para que no nos suban cualquier cosa y 
            //sobrecarguen el servidor ademas de verificador de tama;o o pixeles para que no sean muy pesados los archivos
            if (!empty($nombreViejo) && file_exists($rutaFotoVieja)) {
                unlink($rutaFotoVieja);
                $this->repositorio->borrarFoto($_SESSION['id']);
            }

            if (move_uploaded_file($nombreTemp, $rutaFoto)) {
                $this->repositorio->subirFoto($_SESSION['id'], $nuevoNombre);
                return true;
            } else {
                throw new Exception("No se pudo cargar el archivo", 500);
            }
        }
        //API Cooperativa
        public function crearPagoMensual($montoPagoMensual){
            $IDUsuariosArray = $this->repositorio->getIDUsuarios();
            $fecha = date("Y-m-d");
            $this->repositorio->crearPagoMensual($montoPagoMensual, $IDUsuariosArray, $fecha);
        }

        public function crearPagoPersonalizado($motivoPago, $ci , $montoPagoPersonalizado){
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            $fecha = date("Y-m-d");
            $this->repositorio->crearPagoPersonalizado($idPersona, $motivoPago, $montoPagoPersonalizado, $fecha);
        }

        public function rechazarPago($idComprobantePago){
            $pagoViejo = $this->repositorio->getComprobantePago($idComprobantePago);
            $this->repositorio->rechazarPago($idComprobantePago);
            $this->repositorio->crearPagoPersonalizado($pagoViejo['idPersona'], "Pago Rechazado", $pagoViejo['montoPago'], $pagoViejo['fecha']);
        }

        public function aprobarPago($idComprobantePago){
            $this->repositorio->aprobarPago($idComprobantePago);
        }

        public function asignarUnidadHabitacional($ci, $idUnidadHabitacional) {
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            if(!$this->repositorio->unidadHabitacionalAsignada($idUnidadHabitacional)){
                $this->repositorio->asignarUnidadHabitacional($idPersona, $idUnidadHabitacional);
            }
        }


        public function getComprobantesPendientes(){
            $comprobantesObj = $this->repositorio->getPagosPendientes();
            $comprobantesAsociativo = [];
            $comprobantesPendientes = 0;
            if (count($comprobantesObj) > 0) {
            foreach ($comprobantesObj as $comprobante) {
                $comprobantesPendientes++;
                $usuario = $this->repositorio->getUsuariosPagos($comprobante->getIdPersona());
                $comprobantesAsociativo[] = [
                    'fecha' => $comprobante->getMes(),
                    'usuario' => $usuario['Nombre'],
                    'ci' => $usuario['CI'],
                    'motivo' => $comprobante->getMotivoPago(),
                    'monto' => $comprobante->getMonto(),
                    'estado' => $comprobante->getEstadoPago(),
                    'foto' => $comprobante->getFoto(),
                    'id' => $comprobante->getIdComprobantePago()
                ];
            }
            } else {
                return ['comprobantesPendientes' => 0]; //si no hay pagos pendientes devuelvo 0
            }

            return [
                'comprobantesPendientes' => $comprobantesPendientes,
                'comprobantes' => $comprobantesAsociativo
            ];
        }

        public function crearUnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones){
            $unidadHabitacional = new UnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones);
            if(!$this->repositorio->unidadHabitacionalExiste($numeroPuerta, $pasillo)){
                $this->repositorio->crearUnidadHabitacional($unidadHabitacional);
            } else{
                throw new Exception("Esta unidad ya esta registrada", 409);
            }
        }

        public function crearUnidadHabitacionalConCI($numeroPuerta, $pasillo, $ci){
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            if (!$idPersona) {
                throw new Exception("No se encontró la persona con CI {$ci}", 404);
            }
            $integrantesFamiliares = $this->repositorio->getIntegrantesFamiliares($idPersona) ?? [];
            $integrantes = 0;
            $menoresAdolescentes = [];
            $masculino = false;
            $femenino  = false;
            $habitacionesEspeciales = 0;
        
            foreach($integrantesFamiliares as $integrante) {
                $fechaNac = $integrante['FechaNacimiento'];
                $generoRaw = $integrante['Genero'];
                $idIntegrante = $integrante['ID_Integrante'];
                $edad = null;
                try {
                    $cumple = new DateTime($fechaNac);
                    $hoy    = new DateTime('today');
                    $edad   = $cumple->diff($hoy)->y;
                } catch (Throwable $e) {
                    $edad = null;
                }
                if ($edad !== null && $edad >= 18) {
                    $integrantes++; // mayor
                } elseif ($edad !== null && $edad >= 11 && $edad <= 17) {
                    // Guardamos adolescentes y marcamos sexo exacto
                        $menoresAdolescentes[$idIntegrante] = [
                            'genero' => $generoRaw,
                            'edad'   => $edad
                        ];
                    if ($generoRaw === 'Masculino') $masculino = true;
                    if ($generoRaw === 'Femenino')  $femenino  = true;
                }
                // <11 o edad desconocida: no afectan la regla actual
            }
            // Si hay adolescentes de ambos sexos, +1 habitacion, si son 3 nenes y 1 nena igualmente es una habitacion cu
            if ($masculino && $femenino) {
                $habitacionesEspeciales++;
            }
            $integrantesTotal = $integrantes + 1; //agregamos el due;o de la cuenta

            if ($integrantesTotal <= 2) {
                $habitacionesBase = 1;
            } elseif ($integrantesTotal <= 4) {
                $habitacionesBase = 2;
            } elseif ($integrantesTotal <= 6) {
                $habitacionesBase = 3;
            } else {
                $habitacionesBase = 4;
            }
        
            //Las unidades habitacionales tienen un minimo de 1 y un maximo de 4
            $habitaciones = $habitacionesBase + $habitacionesEspeciales;
            if ($habitaciones < 1) $habitaciones = 1;
            if ($habitaciones > 4) $habitaciones = 4;

            if(!$this->repositorio->unidadHabitacionalExiste($numeroPuerta, $pasillo)){
                $cantidadHabitaciones = $habitaciones;
                $unidadHabitacional = new UnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones);
                $this->repositorio->crearUnidadHabitacional($unidadHabitacional);
                $idUnidadHabitacional = $this->repositorio->getIDUnidadHabitacional($numeroPuerta, $pasillo);
                $this->repositorio->asignarUnidadHabitacional($idPersona, $idUnidadHabitacional);
                $this->repositorio->unidadHabitacionalBoolean($idPersona);
            }else{
                throw new Exception("Esta unidad ya esta registrada", 409);
            }
        }
        
        
    }
?>