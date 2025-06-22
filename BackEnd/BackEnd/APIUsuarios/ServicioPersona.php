<?php
    Class ServicioPersona {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }

        public function iniciarSesion($ci, $contraseña){
            if($this->repositorio->personaExiste($ci)){
                
                if(password_verify($contraseña, $this->repositorio->getContraseña($ci))){
                    return $this->repositorio->getIdPersona($ci);
                }else{
                      throw new Exception("Contraseña Incorrecta", 401);
                }

            }else{
                throw new Exception("Usuario invalido", 404);
            }
        }

        public function registro($ci, $email, $nombre, $apellido, $contraseña, $contraseñaConfirmar){
            if(!$this->repositorio->personaExiste($ci)){

                if($contraseña==$contraseñaConfirmar){

                    $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);

                    // idPersona se manda como null porque lo asigna la base de datos y Interesado se establece de una porque aca se esta registrando un registro desde la landing page 
                    $persona = new Persona($ci, $email, null, $nombre, $apellido, $contraseña, "Interesado");
                    $this->repositorio->cargarPersona($persona);
                    $idPersona = $this->repositorio->getIdPersona($persona);
                    //Las cosas en null se asignan posteriormente en el backoffice ademas de cambiar el estado "pendiente" etc
                    $interesado = new Interesado($ci, $email, $idPersona, $nombre, $apellido, $contraseña, "Interesado", //datos heredados de persona
                    null, "Pendiente", null, null, "Pendiente", null); //datos de Interesado
                    $this->repositorio->cargarInteresado($interesado);

                }else{
                    throw new Exception("Las contraseñas no coinciden", 400);
                }
            }else{
                throw new Exception("Esta Persona ya existe", 409);
            }
        }
        
        public function cargarUsuario($idPersona, $fechaNacimiento, $fechaIngreso, $foto){
            $persona = $this->repositorio->getPersona($idPersona);
            $usuario = new Usuario(
                    $persona->getCi(),
                    $persona->getEmail(),
                    $persona->getIdPersona(),
                    $persona->getNombre(),
                    $persona->getApellido(),
                    $persona->getContraseña(),
                    "Usuario",          //Asigna el rol Usuario
                    $fechaNacimiento, 
                    $fechaIngreso, 
                    $foto
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






        
    }


    
?>
