<?php
    Class ServicioPersona {
        //nos e especifica el tipo porque un servicio tiene un solo repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }
        public function crearUsuario(){
            if($this->repositorio->usuarioExiste()){
                return "error"; //
            } else{
                if(passwordMatch()){
                    $this->repositorio->crearUsuario()
                }
            }
        }

        public function iniciarSesison($ci, $contraseña){
            if(personaExiste($ci)){

                if(password_verify($contraseña, $this->repositorio->getContraseña($ci))){
                    return getIdPersona($ci);
                }else{
                      throw new Exception("Contraseña Incorrecta");
                }

            }else{
                throw new Exception("Usuario invalido");
            }
        }

        public function registro($ci, $email, $idPersona, $nombre, $apellido, $contraseña){
            if(!personaExiste($ci)){
                $password = password_hash($contraseña, PASSWORD_DEFAULT);
                $persona = new Persona($ci, $email, $idPersona, $nombre, $apellido, $contraseña);
                $this->repositorio->cargarUsuario($persona);
            }else{
                throw new Exception("Usuario ya existe");
            }
        }
    }
