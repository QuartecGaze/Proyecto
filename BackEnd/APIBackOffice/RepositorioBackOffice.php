<?php
require_once __DIR__ .'../../APIUsuarios/Modelos/Usuario.php';
require_once __DIR__ .'../../APIUsuarios/Modelos/Persona.php'; 
require_once __DIR__ .'../../APIUsuarios/Modelos/Admin.php';
require_once __DIR__ .'../../APIUsuarios/Modelos/Interesado.php';
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

        public function personaExisteConCI($ci){
            $consulta = "SELECT * FROM Persona WHERE CI = '$ci'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            } else {
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
                SELECT * FROM Persona WHERE ID_Persona=$id
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $fila = mysqli_fetch_assoc($resultado);
            $telefonos = $this->getTelefonosPersona($id);
            $persona = new Persona($fila['CI'], $fila['Email'], $telefonos ,$id, $fila['Nombre'], $fila['Apellido'], $fila['Contraseña'], $fila['Rol']);
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
        public function subirFoto($id, $nombre) {
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
                VALUES ($idPersona,'$fechaIngreso')
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

        //APICooperativa
        public function getIDUsuarios(): array {
            $consulta = "
                SELECT ID_Persona 
                FROM Persona 
                WHERE Rol = 'Usuario'
            ";

            $resultado = mysqli_query($this->conn, $consulta);

            $ids = [];
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $ids[] = $fila['ID_Persona'];
                    }
                }
            return $ids;
        }


        public function crearPagoMensual($montoPagoMensual, $IDUsuariosArray, $fecha) {
            $totalUsuarios = count($IDUsuariosArray);
            $valoresConsulta = '';
            for ($i = 0; $i < $totalUsuarios; $i++) {
                $idPersona = $IDUsuariosArray[$i];

                if($i > 0){ //aca se a;ade una , al final de cada iteracion para no romper la consulta el operador .= a;ade al final es como el mas += que usabamos en java
                    $valoresConsulta .= ','; 
                }
                $valoresConsulta .= "($idPersona , 'Aportes mensuales' , 'En espera', '$fecha', $montoPagoMensual)"; 
            } //haciendo esto nos evitamos sobrecargar el servidor haciendo 100 consultas para 100 usuarios, lo hacemos todo en una un insert gigante como el de cuando creamos la tabla traducciones
            $consulta = "
                    INSERT INTO Comprobante_pago (ID_Persona, Motivo_pago, Estado_pago, Mes, Monto)
                    VALUES $valoresConsulta;
                ";
            mysqli_query($this->conn, $consulta); 
        }

        public function getIDPersonaConCi($ci){
            $consulta = "
                SELECT ID_Persona FROM Persona WHERE CI = '$ci'
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $idPersona = $fila['ID_Persona']; 
            return $idPersona;
            }else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }
        }

        public function crearPagoPersonalizado($idPersona, $motivoPago, $montoPagoPersonalizado, $fecha) {
            $consulta = "
                INSERT INTO Comprobante_pago (ID_Persona, Motivo_pago, Estado_pago, Mes, Monto)
                VALUES ($idPersona , '$motivoPago' , 'En Espera' , '$fecha' , $montoPagoPersonalizado);
            ";
            mysqli_query($this->conn, $consulta); 
        }


        public function getComprobantePago($idComprobante) {
            $consulta = "
                SELECT ID_Persona, Monto, Mes
                FROM   Comprobante_pago
                WHERE  ID_Comprobante_pago = '$idComprobante'
            ";

            $resultado = mysqli_query($this->conn, $consulta);

            if (mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                return [
                    'idPersona' => $fila['ID_Persona'],
                    'montoPago' => $fila['Monto'],
                    'fecha'     => $fila['Mes'],
                ];
            } else {
                throw new Exception("No se encontró un comprobante con el ID $idComprobante");
            }
        }
        
        public function rechazarPago($idComprobantePago){
            $consulta = "
                UPDATE Comprobante_pago
                SET    Estado_pago = 'Rechazado'
                WHERE  ID_Comprobante_pago = '$idComprobantePago'
            ";
            $resultado = mysqli_query($this->conn, $consulta);
        }

        public function aprobarPago($idComprobantePago){
            $consulta = "
                UPDATE Comprobante_pago
                SET    Estado_pago = 'Aprobado'
                WHERE  ID_Comprobante_pago = '$idComprobantePago'
            ";
            $resultado = mysqli_query($this->conn, $consulta);
        }

        public function unidadHabitacionalAsignada($idUnidadHabitacional) {
            $consulta = "
                SELECT ID_Persona
                FROM   Unidad_Habitacional
                WHERE  ID_Unidad_Habitacional = '$idUnidadHabitacional'
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $dato = mysqli_fetch_assoc($resultado);
            $dato['ID_Persona'];
            if($dato['ID_Persona'] == null){
                return false;
            }else{
                throw new Exception('La unidad habitacional ya esta asignada '. $dato['ID_Persona'], 409);
            }
        }

        public function unidadesHabitacionalesSinUsuario(){
            $consulta = "
                SELECT *
                FROM   Unidad_Habitacional
                WHERE  ID_Persona IS NULL
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $UnidadesHabitacionalesSinAsignar = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $UnidadesHabitacionalesSinAsignar[$fila['ID_Unidad_Habitacional']] = $fila;
            }
            return $UnidadesHabitacionalesSinAsignar;
        }

        public function usuariosSinUnidadHabitacional(){
            $consulta = "
                SELECT *
                FROM   Unidad_Habitacional
                WHERE  ID_Persona IS NULL
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            $UsuariosSinUnidad = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $UsuariosSinUnidad[$fila['ID_Persona']] = $fila;
            }
            return $UsuariosSinUnidad;
        }


        public function getPagosPendientes(){
            $consulta = "
                SELECT * 
                FROM comprobante_pago  
                WHERE Estado_pago = 'Pendiente';
            ";
            $resultado = mysqli_query($this->conn, $consulta); 
            $comprobantesPendientes = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $comprobantesPendientes[] = new ComprobantePago(
                $fila['ID_Persona'], 
                $fila['Motivo_pago'], 
                $fila['Estado_pago'], 
                $fila['Mes'], 
                $fila['Foto'], 
                $fila['Monto'], 
                $fila['ID_Comprobante_pago']
            );
            }
            return $comprobantesPendientes;
            }

            public function getUsuariosPagos($idPersona){
                $consulta = "
                    SELECT CI, Nombre, Apellido
                    FROM persona
                    WHERE ID_Persona = $idPersona
                ";
                $resultado = mysqli_query($this->conn, $consulta); 
                $fila = mysqli_fetch_assoc($resultado);
                return [
                    'CI' => $fila['CI'],
                    'Nombre' => $fila['Nombre'] . " " . $fila['Apellido']
                ];
            }
            
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
            
        public function crearUnidadHabitacional($unidadHabitacional){
            $numeroPuerta = $unidadHabitacional->getNumeroPuerta();
            $pasillo = $unidadHabitacional->getPasillo();
            $cantidadHabitaciones = $unidadHabitacional->getCantidadHabitaciones();
            $consulta = "
                INSERT INTO unidad_habitacional (Numero_puerta, Pasillo, Estado_unidad, Cantidad_habitaciones) 
                VALUES ($numeroPuerta, '$pasillo', 'En espera', $cantidadHabitaciones)
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        public function unidadHabitacionalExiste($numeroPuerta, $pasillo){
            $consulta = "
                SELECT * FROM unidad_habitacional 
                WHERE Numero_puerta = '$numeroPuerta' 
                AND Pasillo = '$pasillo'
                ";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else{
                return false;
            }
        }


    }
?>