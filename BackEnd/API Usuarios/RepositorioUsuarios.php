<?php
    Class RepositorioUsuarios {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }
        
        public function cargarAdmin($admin){
        $idPersona = $admin->getIdPersona();
        $nivelPermisos = $admin->getNivelPermisos();
        $consulta = "
            INSERT INTO Admin (ID_Persona, Nivel_permisos) 
            VALUES ('$idPersona', '$nivelPermisos')
        ";
        mysqli_query($this->conn, $consulta);
        }

        public function cargarPersona($persona){
            $CI = $persona->getCI();
            $email = $presona->getEmail();
            $contraseña = $persona->getContraseña();
            $rol = $persona->getRol();
            $nivelPermisos = $admin->getNivelPermisos();
            $nombre = $admin->getNombre();
            $apellido = $admin->getApellido();
        
            $consulta = "
            INSERT INTO Persona (CI, Email, Contraseña, Rol, Nombre, Apellido) 
            VALUES ('$CI', '$email', '$contraseña', '$rol', '$nombre', '$apellido')
        ";
        mysqli_query($this->conn, $consulta);
        }
        public function getIdPersona($persona){
            $CI = $persona->getCI();
            $consulta = "SELECT ID FROM Persona WHERE CI = '$CI'";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $id = $fila['ID'];
            return $id;
        }
    }