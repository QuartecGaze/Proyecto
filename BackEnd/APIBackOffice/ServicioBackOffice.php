<?php
    require_once __DIR__ .'../../APIUsuarios/Modelos/Usuario.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Persona.php'; 
    require_once __DIR__ .'../../APIUsuarios/Modelos/Admin.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Interesado.php';

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



        public function cargarAdmin($idPersona, $nivelPermisos){
            $fechaIngreso = date("Y-m-d"); //asigna la fecha del momento en el que se ejecuta el metodo
            $persona = $this->repositorio->getPersona($idPersona);
            $admin = new Admin(
                    $persona->getCi(),
                    $persona->getEmail(),
                    $persona->getTelefono(),
                    $persona->getIdPersona(),
                    $persona->getNombre(),
                    $persona->getApellido(),
                    $persona->getContraseña(),
                    "Admin",          //Asigna el rol Admin
                    $nivelPermisos,
                    null,
                    $fechaIngreso
                );
            $this->repositorio->cargarAdmin($admin);
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

        public function subirFoto($nombreArchivo, $nombreTemp){
                session_start();
                $rutaFotosPerfil = "../../Recursos/FotosPerfil/";
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevoNombre =  $_SESSION['id'] . '.' . $extension;
                $nuevaRuta = $rutaFotosPerfil . $nuevoNombre;
                $nombreviejo = $this->repositorio->getFoto($_SESSION['id']);
                $extensionviejo = pathinfo($nombreviejo, PATHINFO_EXTENSION);
                $archivoExiste = glob( $rutaFotosPerfil . $nombreviejo . $extensionviejo);//usar el que traiga de la bd
                if (count($archivoExiste) > 0) {
                    $this->repositorio->borrarFoto($_SESSION['id']);
                } else{
                   if(move_uploaded_file($nombreTemp, $nuevaRuta)){
                    $this->repositorio->subirFoto($nuevoNombre, $_SESSION['id']);
                    return(true);
                } else{
                    throw new Exception("No se pudo cargar el archivo", 500);
                }
            }
        }


    }
?>