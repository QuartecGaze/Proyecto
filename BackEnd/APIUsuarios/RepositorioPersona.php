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

        public function cargarTelefono($id, $telefono){
            $consulta = "
                INSERT INTO numero_de_telefono (ID_Persona, Telefono)
                VALUES ('$id', '$telefono')
                ";
            mysqli_query($this->conn, $consulta);
        }

        public function getPersona($id){
            $consulta = "
                SELECT * FROM Persona WHERE ID_Persona=$id
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $persona = new Persona($fila['CI'], $fila['Email'], $fila['Telefono'] ,$fila['ID_Persona'], $fila['Nombre'], $fila['Apellido'], $fila['Contraseña'], $fila['Rol']);
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


        public function getTelefonosPersona($idPersona) {
            $consulta = "
                SELECT Telefono FROM numero_de_telefono WHERE ID_Persona = $idPersona
            ";
    
            $resultado = mysqli_query($this->conn, $consulta);

            $telefonos = [];

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $telefonos[] = $fila['Telefono'];
            }

            return $telefonos;
        }

        public function getDatosInteresado($id) {
            $consulta = "
                SELECT * FROM Persona 
                JOIN Interesado ON Persona.ID_Persona = Interesado.ID_Persona
                WHERE Persona.ID_Persona = '$id';
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            return mysqli_fetch_assoc($resultado); // devuelve los datos crudos
        }

        public function getInteresados(){
        $consulta = "
                SELECT * FROM Persona JOIN Interesado ON Persona.ID_Persona = Interesado.ID_Persona
                ;
            ";
        $resultado = mysqli_query($this->conn, $consulta); 
       
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);
            $interesados[] = new Interesado(
            $fila['CI'], 
            $fila['Email'], 
            $telefonos,
            $fila['ID_Persona'], 
            $fila['Nombre'], 
            $fila['Apellido'], 
            $fila['Contraseña'], 
            $fila['Rol'],
            $fila['Antecedentes'], 
            $fila['Estado_antecedentes'], 
            $fila['Estado_entrevista'], 
            $fila['Fecha_entrevista'], 
            $fila['Hora_entrevista'], 
            $fila['Pago_inicial'], 
            $fila['Estado_pago_inicial'], 
            $fila['Monto_pago_inicial']
        );
        }
        return $interesados;
        }





        
        //CRUD Usuario

        public function cargarFotoUsuario($id, $foto){
            $consulta = "
            INSERT INTO Usuario WHERE ID_Persona=$id (Foto) 
            VALUES ('$foto')

        ";
        mysqli_query($this->conn, $consulta);
        }

        public function cargarFechaNacimientoUsuario($id, $fechaNacimiento){
            $consulta = "
            INSERT INTO Usuario WHERE ID_Persona=$id (Fecha_nacimiento) 
            VALUES ('$fechaNacimiento')
        ";
        mysqli_query($this->conn, $consulta);
        }
        //ediar datos usuario
       

        //Funciones
        
        //recibe un objeto tipo persona y devuelve la id, ya que el dato id es creado por la base de datos.
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
         public function InteresadoExisteID($id){
            $consulta = "SELECT * FROM Interesado WHERE ID_Persona = '$id'";
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
            } else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }
        }
        
        
    }
    ?>