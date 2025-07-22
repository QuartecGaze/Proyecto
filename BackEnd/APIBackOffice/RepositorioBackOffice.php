<?php

    Class RepositorioBackOffice {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        //Persona
        public function personaExiste($id){
            $consulta = "
            SELECT * FROM Persona WHERE ID_Persona=$id
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
        }
        
        public function borrarTelefono($id){
            $consulta = "
                DELETE FROM Numero_de_telefono WHERE ID_Persona=$id
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
            SELECT Telefono FROM numero_de_telefono WHERE ID_Persona = $id
        ";

        $resultado = mysqli_query($this->conn, $consulta);

        $telefonos = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $telefonos[] = $fila['Telefono'];
        }

            $consulta = "
                SELECT * FROM Persona WHERE ID_Persona=$id
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $persona = new Persona($fila['CI'], $fila['Email'], $telefonos ,$fila['ID_Persona'], $fila['Nombre'], $fila['Apellido'], $fila['Contraseña'], $fila['Rol']);
            return $persona;

        }
/*
            $consulta = "
            SELECT * FROM Persona 
            JOIN Usuario ON Persona.ID_Persona = Usuario.ID_Persona
            WHERE Persona.ID_Persona = '$id';
        ";
        $resultado = mysqli_query($this->conn, $consulta); 
        $fila = mysqli_fetch_assoc($resultado);
        $telefonos = $this->getTelefonosPersona($fila['CI']);
        $usuario = new Usuario(
        $fila['CI'], 
        $fila['Email'], 
        $telefonos,
        $fila['ID_Persona'], 
        $fila['Nombre'], 
        $fila['Apellido'], 
        $fila['Contraseña'], 
        $fila['Rol'],
        $fila['Fecha_nacimiento'],
        $fila['Fecha_ingreso'],
        $fila['Foto']
    
        );

        return $usuario;
        */
        


        //Interesado
        public function Interesados() {
            //trae solo el id persona para no traer a todo el interesado y poder lekear datos sin querer
            $consulta = "
                SELECT ID_Persona 
                FROM Persona 
                WHERE Rol = 'Interesado'
            ";
        
            $resultado = mysqli_query($this->conn, $consulta);
        
            if (!$resultado) {
                throw new Exception("Error al obtener interesados", 500);
            }
        
            return $resultado; // devuelve el resultado crudo (el cursor)
        }
        
        public function borrarInteresado($id){
            $consulta = "
                DELETE FROM Interesado WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }
        

        public function cargarEntrevista($id, $fechaEntrevista, $horaEntrevista){
            $estadoEntrevista = "Pendiente"; 
            $consulta = "
                UPDATE Interesado
                SET Estado_entrevista = '$estadoEntrevista',
                    Fecha_entrevista = '$fechaEntrevista',
                    Hora_entrevista = '$horaEntrevista'
                WHERE ID_Persona = $id
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function revisarEstado($id, $tipo, $estado){
            $consulta = "
                UPDATE Interesado
                SET $tipo = '$estado'
                WHERE ID_Persona = $id
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function setMontoPagoInicial($id, $montoPagoInicial){
            $consulta = "
                UPDATE Interesado
                SET Monto_pago_inicial = '$montoPagoInicial'
                WHERE ID_Persona = $id
            ";
            mysqli_query($this->conn, $consulta); 
        }
        
        

        //Usuario

        public function cargarUsuario($Usuario) {
            $idPersona = $Usuario->getIdPersona();
            $fechaIngreso = $Usuario->getFechaIngreso();
            $consulta = "
                INSERT INTO Usuario (ID_Persona, Fecha_ingreso)
                VALUES ('$idPersona','$fechaIngreso')
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        public function borrarUsuario($id){
            $consulta = "
                DELETE FROM Usuario WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        public function cambiarRol($id){
            $consulta = "
            UPDATE Persona
            SET Rol = 'Usuario'
            WHERE ID_Persona = $id
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