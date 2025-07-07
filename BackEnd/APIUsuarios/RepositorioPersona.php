<?php
    require_once __DIR__ . '/ServicioPersona.php'; 
    Class RepositorioPersona {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        
        //CRUD Persona

        public function cargarPersona($persona){
            $ci = $persona->getCi(); 
            $email = $persona->getEmail();
            $contraseña = $persona->getContraseña();
            $rol = $persona->getRol();
            $nombre = $persona->getNombre();
            $apellido = $persona->getApellido();
            $consulta = "
                INSERT INTO Persona (CI, Email, Contraseña, Rol, Nombre, Apellido) 
                VALUES ('$ci', '$email', '$contraseña', '$rol', '$nombre', '$apellido')
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function getPersona($id){
            $consulta = "
                SELECT * FROM Persona WHERE ID_Persona=$id
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $persona = new Persona($fila['CI'], $fila['Email'], $fila['ID_Persona'], $fila['Nombre'], $fila['Apellido'], $fila['Contraseña'], $fila['Rol']);
            return $persona;
        }

        //CRUD Interesado

        public function cargarInteresado($interesado){
            $idPersona = $interesado->getIdPersona();
            $estadoAntecedentes = $interesado->getEstadoAntecedentes();
            $estadoEntrevista = $interesado->getEstadoEntrevista();
            $estadoPagoInicial = $interesado->getEstadoPagoInicial();
            $consulta = "
                INSERT INTO Interesado (ID_Persona, Estado_antecedentes, Estado_entrevista, Estado_pago_inicial) 
                VALUES ('$idPersona', '$estadoAntecedentes', '$estadoEntrevista', '$estadoPagoInicial')
            ";
            mysqli_query($this->conn, $consulta);
        }


        //CRUD Usuario

        //ediar datos usuario
       

        //Funciones
        
        //recibe un objeto tipo persona y devuelve la id        
        public function getIdPersona($persona){
            $ci = $persona->getCi();
            $consulta = "SELECT ID_Persona FROM Persona WHERE CI = '$ci'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $id = $fila['ID_Persona']; 
            return $id;
            }else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }

        }
        //recibe una cedula y devuelve la id;
        public function getIdPersonaCi($ci){
            $consulta = "SELECT ID_Persona FROM Persona WHERE CI = '$ci'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $id = $fila['ID_Persona']; 
            return $id;
            }else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }

        }

        public function personaExiste($ci){
            $consulta = "SELECT * FROM Persona WHERE CI = '$ci'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
        }
        

        public function getContraseña($ci){
            if($this->personaExiste($ci)){
             $consulta = "SELECT Contraseña FROM Persona WHERE CI = '$ci'";
             $resultado = mysqli_query($this->conn, $consulta);
             $fila = mysqli_fetch_assoc($resultado);
             return $fila['Contraseña'];
            }
            
        }

        public function getRol($ci){
            $consulta = "SELECT Rol FROM Persona WHERE CI = '$ci'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $rol = $fila['Rol']; 
            return $rol;
            }else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }
        }
        
    }
    ?>