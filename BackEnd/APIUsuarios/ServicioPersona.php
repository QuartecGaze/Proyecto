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

        public function registro($ci, $email, $telefono, $nombre, $apellido, $contraseña, $contraseñaConfirmar){
            if(!$this->repositorio->personaExiste($ci)){

                if($contraseña==$contraseñaConfirmar){

                    $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);

                    // idPersona se manda como null porque lo asigna la base de datos y Interesado se establece de una porque aca se esta registrando un registro desde la landing page 
                    $persona = new Persona($ci, $email, $telefono, null, $nombre, $apellido, $contraseña, "Interesado");
                    $this->repositorio->cargarPersona($persona);
                    $idPersona = $this->repositorio->getIdPersona($persona);
                    $this->repositorio->cargarTelefono($idPersona, $telefono);
                    //Las cosas en null se asignan posteriormente en el backoffice ademas de cambiar el estado "En espera" etc
                    $interesado = new Interesado($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, "Interesado", //datos heredados de persona
                    null, "En espera", "En espera", null, null, null, "En espera", null); //datos de Interesado
                    $this->repositorio->cargarInteresado($interesado);
                }else{
                    throw new Exception("Las contraseñas no coinciden", 400);
                }
            }else{
                throw new Exception("Esta Persona ya existe", 409);
            }
        }


        public function subirFoto($nombreArchivo, $nombreTemp){
                session_start();
                $rutaFotosPerfil = "http://localhost/Proyecto/Fotos/FotosPerfil/";
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevaFoto =  $_SESSION['id'] . '.' . $extension;
                $nuevaRuta = $rutaFotosPerfil . $nuevaFoto;
                $crearFoto = false;
                $archivoExiste = glob( $rutaFotosPerfil . $_SESSION['id'] . '.*');

                if (count($archivoExiste) > 0) {
                    foreach($archivoExiste as $file){
                        if(unlink($file)){
                            $crearFoto=true;
                         }else {
                            $crearFoto=false;
                        }
                    }
                } else{
                    $crearFoto = true;
                }

        if($crearFoto){
            if(move_uploaded_file($nombreTemp, $nuevaRuta)){
            return(true);
        } else{
             throw new Exception("No se pudo cargar el archivo", 500);
        }
        }

        
        

        
    }

    public function getInteresado($id) {
        if (!$this->repositorio->interesadoExisteID($id)) {
            throw new Exception("El interesado no existe", 404);
        }

        $datos = $this->repositorio->getDatosInteresado($id);
        $telefonos = $this->repositorio->getTelefonosDePersona($id);
        $telefono = $telefonos[0] ?? null;

        return new Interesado(
            $datos['CI'], 
            $datos['Email'],
            $telefonos, 
            $datos['ID_Persona'], 
            $datos['Nombre'], 
            $datos['Apellido'], 
            $datos['Contraseña'], 
            $datos['Rol'],
            $datos['Antecedentes'], 
            $datos['Estado_antecedentes'], 
            $datos['Estado_entrevista'], 
            $datos['Fecha_entrevista'], 
            $datos['Hora_entrevista'], 
            $datos['Pago_inicial'], 
            $datos['Estado_pago_inicial'], 
            $datos['Monto_pago_inicial']
        );
    }

    public function getInteresados(){
            $interesadosObj = $this->repositorio->getInteresados();
            $interesadoArrayAsociativo = [];
            foreach($interesadosObj as $interesado){
                
                $interesadoArrayAsociativo[$interesado->getIdPersona()] = $interesado->toArray();
            }
            return $interesadoArrayAsociativo;
        }

    }
    



















    
?>
