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
            $persona = $this->repositorio->getPersona($idPersona);
            $admin = new Admin(
                    $persona->getCi(),
                    $persona->getEmail(),
                    $persona->getIdPersona(),
                    $persona->getNombre(),
                    $persona->getApellido(),
                    $persona->getContraseña(),
                    "Admin",          //Asigna el rol Admin
                    $nivelPermisos
                );
            $this->repositorio->cargarAdmin($admin);
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
            $this->repositorio->revisarPagoInicial($idPersona, "Pendiente");
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
            $resultado = $this->repositorio->obtenerTodosLosInteresados();
            $cantidadInteresados = mysqli_num_rows($resultado);
        
 
        
            return $cantidadInteresados;
        }






    }
?>