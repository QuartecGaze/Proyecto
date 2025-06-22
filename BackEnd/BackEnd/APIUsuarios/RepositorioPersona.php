<?php
    Class RepositorioPersona {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        
        //CRUD Admin

        public function cargarAdmin($admin){
            $idPersona = $admin->getIdPersona();
            $nivelPermisos = $admin->getNivelPermisos();
            $consulta = "
                INSERT INTO Admin (ID_Persona, Nivel_permisos) 
                VALUES ('$idPersona', '$nivelPermisos')
            ";
            mysqli_query($this->conn, $consulta);
        }
        
         public function borrarAdmin($id){
            $consulta = "
                DELETE FROM Admin WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
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

        public function borrarPersona($id){
            $consulta = "
                DELETE FROM Persona WHERE ID_Persona=$id
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
            $antecedentes = $interesado->getAntecedentes();
            $estadoEntrevista = $interesado->getEstadoEntrevista();
            $fechaEntrevista = $interesado->getFechaEntrevista();
            $pagoInicial = $interesado->getPagoInicial();
            $estadoPagoInicial = $interesado->getEstadoPagoInicial();
            $montoPagoInicial = $interesado->getMontoPagoInicial();
            $consulta = "
                INSERT INTO Interesado (ID_Persona, Antecedentes, Estado_entrevista, Fecha_entrevista, Pago_inicial, Estado_pago_inicial, Monto_pago_inicial) 
                VALUES ('$idPersona', '$antecedentes', '$estadoEntrevista', '$fechaEntrevista', '$pagoInicial', '$estadoPagoInicial', '$montoPagoInicial')
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function borrarInteresado($id){
            $consulta = "
                DELETE FROM Interesado WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        //CRUD Usuario

        public function cargarUsuario($Usuario) {
            $idPersona = $Usuario->getIdPersona();
            $fechaNacimiento = $Usuario->getFechaNacimiento();
            $fechaIngreso = $Usuario->getFechaIngreso();
            $foto = $Usuario->getFoto();
            $consulta = "
                INSERT INTO Usuario (ID_Persona, Fecha_nacimiento, Fecha_ingreso, Foto)
                VALUES ('$idPersona','$fechaNacimiento','$fechaIngreso','$foto')
            ";
        }
 
        public function borrarUsuario($id){
            $consulta = "
                DELETE FROM Usuario WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }
       

        //Funciones
        
        
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


        
    }
    ?>