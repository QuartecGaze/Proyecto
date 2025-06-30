<?php

    Class RepositorioBackOffice {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        //Persona

        public function borrarPersona($id){
            $consulta = "
                DELETE FROM Persona WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }


        //Interesado
        public function borrarInteresado($id){
            $consulta = "
                DELETE FROM Interesado WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }
        

        public function asignarEntrevista($id, $fechaEntrevista, $horaEntrevista){
            $estadoEntrevista = "Pendiente"; // revisar bien si es pendiente o que 
            $consulta = "
                INSERT INTO Interesado WHERE ID_Persona=$id (Estado_entrevista, Fecha_entrevista, Hora_entrevista) 
                VALUES ('$estadoEntrevista', '$fechaEntrevista', '$horaEntrevista')
            ";
        }

        public function revisarAntecedentes($id, )

        //Usuario

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




    }
?>