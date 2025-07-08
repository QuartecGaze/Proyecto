<?php
    Class ServicioBackOffice {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }


        public function cargarUsuario($idPersona){
            $fechaIngreso = date("Y-m-d"); //asigna la fecha del momento en el que se ejecuta el metodo
            $persona = $this->repositorio->getPersona($idPersona);
            $usuario = new Usuario($persona->getCi(), $persona->getEmail(), $idPersona, $persona->getNombre(), $persona->getApellido(), $persona->getContraseña(), "Usuario",//Asigna el rol Usuario
                    null, 
                    null,
                    $fechaIngreso
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

                $this->repositorio->borrarInteresado($idPersona);
                $this->repositorio->borrarPersona($idPersona);

            }else{
                throw new Exception("Esa persona no existe", 404);
            }
        }






    }
?>