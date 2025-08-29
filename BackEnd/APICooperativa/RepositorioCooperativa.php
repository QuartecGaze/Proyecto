<?php
    class RepositorioCooperativa{
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function crearUnidadHabitacional($unidadHabitacional){
            $numeroPuerta = $unidadHabitacional->getNumeroPuerta();
            $pasillo = $unidadHabitacional->getPasillo();
            $cantidadHabitaciones = $unidadHabitacional->getCantidadHabitaciones();
            //private $estadoUnidad; podriamos hacer que cargue el progreso, pero por ahora voy a hacer que por defecto empieze en espera /DIEGO/
            $consulta = "
                INSERT INTO unidad_habitacional (Numero_puerta, Pasillo, Estado_unidad, Cantidad_habitabitaciones) 
                VALUES ('$numeroPuerta', '$pasillo', 'En espera', '$cantidadHabitaciones')
            ";
            mysqli_query($this->conn, $consulta);
        }

        public function getComprobantesMensuales($idPersona){
            $consulta = "
                SELECT * 
                FROM comprobante_pago 
                WHERE ID_Persona = '$idPersona'
                AND Estado_pago IN ('En espera', 'Pendiente');
            ";
            $resultado = mysqli_query($this->conn, $consulta); 
            if(mysqli_num_rows($resultado) > 0){
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $comprobantes[] = new ComprobantePago(
                    $fila['ID_Persona'], 
                    $fila['Motivo_pago'],
                    $fila['Estado_pago'], 
                    $fila['Mes'], 
                    $fila['Foto'], 
                    $fila['Monto'], 
                    $fila['ID_Comprobante_pago']
                    );
                }
            } else {
                throw new Exception("No hay comprobantes en espera o pendiente asocioados a la id: " . $idPersona);
            }
                return $comprobantes;  
            }

        public function subirComprobante($nombre, $idComprobantePago) {
            $consulta = "
                UPDATE Comprobante_pago
                SET Foto = '$nombre',
                Estado_pago = 'Pendiente'
                WHERE ID_Comprobante_pago = $idComprobantePago
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        public function cargarHoras($idPersona, $horas, $fechaHoras, $idSemana){
            $consulta = "
                INSERT INTO Horas_trabajadas 
                (ID_Persona, Horas, Fecha_registro_horas, ID_Semana_trabajo)
                VALUES ($idPersona, $horas, '$fechaHoras', $idSemana);
            ";
            if(mysqli_query($this->conn, $consulta)){
                return true;
            }else{
                return false;
            }
            
            
        }

        public function horasTrabajadasXSemana($idPersona, $semana){
            $consulta = "
                SELECT Horas
                FROM Horas_Trabajadas
                WHERE ID_Persona = $idPersona AND Fecha_semana = '$semana'
            ";
            $resultado = mysqli_query($this->conn, $consulta);

            $total = 0;
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $total +=  $fila['Horas'];
            }
            return $total;
        }

        public function cuantoDebo($idPersona){
            $consulta = "
                SELECT Monto 
                FROM Comprobante_Pago
                WHERE ID_Persona = $idPersona AND Estado_Pago = 'En Espera'
            ";
            $resultado = mysqli_query($this->conn, $consulta);

            $total = 0;
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $total +=  $fila['Monto'];
            }
            return $total;
        }
       
        public function horasTrabajadas($idPersona){
            $consulta = "
                SELECT Horas
                FROM Horas_Trabajadas
                WHERE ID_Persona = $idPersona
            ";
            $resultado = mysqli_query($this->conn, $consulta);

            $total = 0;
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $total +=  $fila['Horas'];
            }
            return $total;

        }

        public function getHorasNecesariasSemana($semana){
            $consulta = "
                SELECT Horas_semanales
                FROM   Semana_trabajo
                WHERE  ID_Semana_trabajo = $semana
            ";
            $resultado = mysqli_query($this->conn, $consulta);
            if ($fila = mysqli_fetch_assoc($resultado)) {
                return $fila['Horas_semanales']; 
            }
            return null; 
    }

        public function getIdSemana($fecha){
            $consulta = "
                SELECT ID_Semana_trabajo
                FROM   Semana_trabajo
                WHERE  Fecha_semana = '$fecha'
                LIMIT 1 
            "; //para solo traer uno
            $resultado = mysqli_query($this->conn, $consulta);
            if ($fila = mysqli_fetch_assoc($resultado)) {
                return $fila['ID_Semana_trabajo']; 
            }
            return null;
        }
    
        //FUNCIONES
        public function usuarioExisteID($id){
            $consulta = "SELECT * FROM Usuario WHERE ID_Persona = '$id'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
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
            }else
            {
                return false;
            }
        }
        
        public function crearSemana($fecha){
            $consulta = "
                INSERT INTO 
                Semana_trabajo
                (Fecha_semana)
                VALUES ('$fecha')
            ";
            mysqli_query($this->conn, $consulta);
        }
        
        public function semanaExiste($fecha){
            $consulta = "
                SELECT ID_Semana_trabajo
                FROM   Semana_trabajo
                WHERE  Fecha_semana = '$fecha'
                LIMIT 1 
            "; //para solo traer uno
            $resultado = mysqli_query($this->conn, $consulta);
            if (mysqli_fetch_assoc($resultado)) {
                return true; 
            }
            return null;
        }
    }