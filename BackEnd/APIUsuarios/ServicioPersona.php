<?php
    require_once __DIR__ . '/RepositorioPersona.php';
    require_once __DIR__ . '/ApiUsuarios.php';
    require_once __DIR__ .'/Modelos/Usuario.php';
    require_once __DIR__ .'/Modelos/Persona.php'; 
    require_once __DIR__ .'/Modelos/Admin.php';
    require_once __DIR__ .'/Modelos/Interesado.php';
    

    Class ServicioPersona {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }

        public function iniciarSesion($ci, $contraseña){
            if($this->repositorio->personaExiste($ci)){
                
                if(password_verify($contraseña, $this->repositorio->getContraseña($ci))){
                    $id = $this->repositorio->getIdPersonaCi($ci);
                    $rol = $this->repositorio->getRol($ci);
                    return ['id' => $id, 'rol' => $rol];
                }else{
                      throw new Exception("Contraseña Incorrecta", 401);
                }

            }else{
                throw new Exception("El usuario no existe", 404);
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
                    null, "Pendiente", "Pendiente", null, null, null, "Pendiente", null); //datos de Interesado
                    $this->repositorio->cargarInteresado($interesado);
                }else{
                    throw new Exception("Las contraseñas no coinciden", 400);
                }
            }else{
                throw new Exception("Esta Persona ya existe", 409);
            }
        }
        public function getInteresado($id){
            if($this->repositorio->interesadoExisteID($id)){
                return $this->repositorio->getInteresado($id);
            } else{
                throw new Exception("El interesado no existe", 404);
            }
        }
        
        


        
    }


    
?>
