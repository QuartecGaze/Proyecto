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

        

        public function subirComprobante($nombreArchivo, $nombreTemp){
                session_start();
                $rutaComprobantes = "../../Recursos/Comprobantes/";
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevoNombre =  'COMPROBANTEPAGOINICIAL' . $_SESSION['id'] . '.' . $extension;
                $nuevaRuta = $rutaComprobantes . $nuevoNombre;
                $archivoExiste = glob($nuevaRuta);
                if (count($archivoExiste) > 0) {
                    throw new Exception("Ya existe un archivo con el mismo nombre en el sistema", 500);
                } else{
                   if(move_uploaded_file($nombreTemp, $nuevaRuta)){
                    $this->repositorio->subirComprobante($nuevoNombre, $_SESSION['id']);
                    return(true);
                } else{
                    throw new Exception("No se pudo cargar el archivo", 500);
                }
            }
        }

        public function subirAntecedentes ($nombreArchivo, $nombreTemp){
                session_start();
                $rutaAntecedentes = "../../Recursos/Antecedentes/";
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevoNombre =  'ANTECEDENTE' . $_SESSION['id'] . '.' . $extension;
                $nuevaRuta = $rutaAntecedentes . $nuevoNombre;
                $archivoExiste = glob($nuevaRuta);
                if (count($archivoExiste) > 0) {
                    throw new Exception("Ya existe un archivo con el mismo nombre en el sistema", 500);
                } else{
                   if(move_uploaded_file($nombreTemp, $nuevaRuta)){
                    $this->repositorio->subirAntecedentes($nuevoNombre, $_SESSION['id']);
                    return(true);
                } else{
                    throw new Exception("No se pudo cargar el archivo", 500);
                }
            }
        }
        
        public function getInteresado($id) {
            if (!$this->repositorio->interesadoExisteID($id)) {
                throw new Exception("El interesado no existe", 404);
            } else {
                return $this->repositorio->getDatosInteresado($id);
            }
        }

        public function getUsuario($id) {
            if (!$this->repositorio->usuarioExisteID($id)) {
                throw new Exception("El usuario no existe", 404);
            } else {
                return $this->repositorio->getDatosUsuario($id);
            }
        
        }   

        public function horasTrabajadasUsuario($idPersona){
            $semana = $this->getSemanaNro(date('Y-m-d'));
            if (!$this->repositorio->usuarioExisteID($idPersona)) {
                throw new Exception("El usuario no existe", 404);
            } else {
                return $this->repositorio->horasTrabajadas($idPersona, $semana);
            }
        }

        public function horasAtrasadasUsuario($idPersona){
            $semanaActual = $this->getSemanaNro(date('Y-m-d'));
            if (!$this->repositorio->usuarioExisteID($idPersona)) {
                throw new Exception("El usuario no existe", 404);
            } else {
                $totalHorasAFavor = 0;
                $totalHorasPendientes = 0;
                for ($semana = 1; $semana < $semanaActual; $semana++) {

                    $trabajadas  = $this->repositorio->horasTrabajadas($idPersona, $semana);
                    $necesarias  = $this->repositorio->getHorasNecesariasSemana($semana);
    
                    if ($trabajadas > 0 && $trabajadas < $necesarias) {
                        $totalHorasPendientes += $necesarias - $trabajadas;
    
                    } elseif ($trabajadas > 0 && $trabajadas > $necesarias) {
                        $totalHorasAFavor += $trabajadas - $necesarias;
                    }
                }
                return [
                    'horasPendientes' => $totalHorasPendientes,
                    'horasAFavor'     => $totalHorasAFavor
                ];
                
            }
        }

        public function getSemanaNro($fechaSemana){
            $semana = date('W', strtotime($fechaSemana));
            return $semana;
        }



        














        }
?>
