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
            $consulta = "
                UPDATE Interesado
                SET Fecha_entrevista = '$fechaEntrevista',
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
        
        public function getInteresados(){
            $consulta = "
            SELECT * 
            FROM Persona 
            JOIN Interesado ON Persona.ID_Persona = Interesado.ID_Persona 
            WHERE Rol = 'Interesado';
            
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
        public function soloInteresados(){
            $consulta = "
                SELECT ID_Persona
                FROM Persona
                WHERE Rol = 'Interesado';
            ";
            $resultado = mysqli_query($this->conn, $consulta); 
            return $resultado;
        }    
        //Usuario
        public function subirFoto($nombre, $id) {
        $consulta = "
            UPDATE Admin
            SET Foto = '$nombre'
            WHERE ID_Persona = $id
        ";
        mysqli_query($this->conn, $consulta);
        }

        public function getFoto($id) {
            $consulta = "
                SELECT Foto FROM Admin
                WHERE ID_Persona = $id
            ";

            $resultado = mysqli_query($this->conn, $consulta);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                return $fila['Foto'] === null ? null : $fila['Foto']; //verifica que la foto no sea null, porque en la bd se carga como default null
            }
            return null;
        }

        public function borrarFoto($id) {
            $consulta = "
                UPDATE Admin
                SET Foto = NULL
                WHERE ID_Persona = $id
            ";
            return mysqli_query($this->conn, $consulta);
        }

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
            $fechaIngreso = $admin->getFechaIngreso();
            $consulta = "
                INSERT INTO Admin (ID_Persona, Nivel_permisos, Fecha_ingreso) 
                VALUES ('$idPersona', '$nivelPermisos', '$fechaIngreso')
            ";
            mysqli_query($this->conn, $consulta);
        }
        
         public function borrarAdmin($id){
            $consulta = "
                DELETE FROM Admin WHERE ID_Persona=$id
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function adminExisteID($id){
            $consulta = "SELECT * FROM Admin WHERE ID_Persona = '$id'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
        }

        public function getDatosAdmin($id) {
            $consulta = "
                SELECT * FROM Persona 
                JOIN Admin ON Persona.ID_Persona = Admin.ID_Persona
                WHERE Persona.ID_Persona = '$id';
            ";
            $resultado = mysqli_query($this->conn, $consulta); 
            $fila = mysqli_fetch_assoc($resultado);
            $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);
            $admin = new Admin(
            $fila['CI'], 
            $fila['Email'], 
            $telefonos,
            $fila['ID_Persona'], 
            $fila['Nombre'], 
            $fila['Apellido'], 
            $fila['Contraseña'], 
            $fila['Rol'],
            $fila['Nivel_permisos'],
            $fila['Foto'],
            $fila['Fecha_ingreso']
            );

        return $admin;
        }



    }
?>