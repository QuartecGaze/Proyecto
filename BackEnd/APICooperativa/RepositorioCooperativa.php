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
    // 1) Validar el id (aceptar solo dígitos)
    if (!is_numeric($idPersona) || !ctype_digit((string)$idPersona)) {
        // Intento de recuperar si viene "null" o basura
        error_log("getComprobantesMensuales: idPersona inválido = " . var_export($idPersona, true));
        return []; // o lanzar Exception si preferís
    }
    $idPersona = (int)$idPersona;

    // 2) Prepared statement
    $sql = "
        SELECT ID_Persona, Motivo_pago, Estado_pago, Mes, Foto, Monto, ID_Comprobante_pago
        FROM comprobante_pago
        WHERE ID_Persona = ?
          AND TRIM(Estado_pago) IN ('En espera', 'Pendiente')
    ";

    $stmt = mysqli_prepare($this->conn, $sql);
    if (!$stmt) {
        throw new Exception("Error en prepare: " . mysqli_error($this->conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $idPersona);

    if (!mysqli_stmt_execute($stmt)) {
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        throw new Exception("Error en execute: " . $err);
    }

    $resultado = mysqli_stmt_get_result($stmt);
    if ($resultado === false) {
        $err = mysqli_error($this->conn);
        mysqli_stmt_close($stmt);
        throw new Exception("Error al obtener resultado: " . $err);
    }

    $comprobantes = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Opcional: normalizar espacios en Estado_pago
        $fila['Estado_pago'] = trim($fila['Estado_pago']);

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

    mysqli_stmt_close($stmt);

    // 3) Si no hay filas, devolvés array vacío (no Exception)
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

        public function horasTrabajadasXSemana($idPersona, $idSemana){
            $consulta = "
                SELECT Horas
                FROM Horas_Trabajadas
                WHERE ID_Persona = $idPersona AND ID_Semana_trabajo = $idSemana 
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
                WHERE  Fecha_semana = '$semana'
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

        public function getHorasHistorial($idPersona) {
            $consulta = "
                SELECT *
                FROM Horas_Trabajadas
                WHERE ID_Persona = $idPersona
            ";
            
            $resultado = mysqli_query($this->conn, $consulta);
            $horas = [];
        
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $horas[] = [
                    'ID_Horas_trabajadas' => $fila['ID_Horas_trabajadas'],
                    'Horas'               => $fila['Horas'],
                    'Fecha_registro_horas'=> $fila['Fecha_registro_horas']
                ];
            }
        
            return $horas;
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