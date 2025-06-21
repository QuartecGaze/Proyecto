<?php
    Class ServicioPersona {
        //nos e especifica el tipo porque un servicio tiene un solo repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }

        public function iniciarSesison($ci, $contraseña){
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
                    //idpersona y rol se envian en null, porque idpersona todavia no esta asignnado y
                    //el rol se asigna Interesado, porque la persona inicialmente es un Interesado, luego se cambia si es necesario
                    $persona = new Persona($ci, $email, null, $nombre, $apellido, $contraseña, "Interesado"); 
                    $this->repositorio->cargarUsuario($persona);
                    //se llama para crear la id persona, porque hasta no traerla de la base de datos esta en null,
                    //lo cual es clave foranea de Interesado por lo tanto, no se crearia el interesado porque 
                    //la clave foranea estaria null
                    $idPersona = $this->repositorio->getIdPersona($ci);
                    $persona->setIdPersona($idPersona);
                    $this->repositorio->cargarInteresado($persona);

                }else{
                    throw new Exception("Las contraseñas no coinciden", 400);
                }
            }else{
                throw new Exception("Usuario ya existe", 409);
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
