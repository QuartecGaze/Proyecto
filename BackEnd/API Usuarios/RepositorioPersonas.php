<?php
    Class RepositorioUsuarios {
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
            $CI = $persona->getCI();
            $email = $persona->getEmail();
            $contraseña = $persona->getContraseña();
            $rol = $persona->getRol();
            $nivelPermisos = $persona->getNivelPermisos();
            $nombre = $persona->getNombre();
            $apellido = $persona->getApellido();
            $consulta = "
                INSERT INTO Persona (CI, Email, Contraseña, Rol, Nombre, Apellido) 
                VALUES ('$CI', '$email', '$contraseña', '$rol', '$nombre', '$apellido')
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function borrarPersona($id){
            $consulta = "
                DELETE FROM Persona WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }

        //CRUD Interesado

        public function cargarInteresado($interesado){
            $idPersona = $interesado->getIdPersona();
            $antecedentes = $interesado->getAntecedentes();
            $estadoEntrevista = $interesado->getEstadoEntrevista();
            $fechaEntrevista = $interesado->getFechaEntrevista();
            $pagoInicial = $interesado->getPagoIncial();
            $estadoPagoInicial = $interesado->getEstadoPagoIncial();
            $montoPagoInicial = $interesado->getMontoPagoIncial();
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
            $CI = $persona->getCI();
            $consulta = "SELECT ID FROM Persona WHERE CI = '$CI'";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $id = $fila['ID'];
            return $id;
        }
    }