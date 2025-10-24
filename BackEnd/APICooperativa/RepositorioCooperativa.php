<?php
require __DIR__ .'/../Consultas.php';
    class RepositorioCooperativa{
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

    public function getComprobantesMensuales($idPersona){
        // Validación previa para evitar inputs inválidos
        if (!is_numeric($idPersona) || !ctype_digit((string)$idPersona)) {
            error_log("getComprobantesMensuales: idPersona inválido = " . var_export($idPersona, true));
            return []; 
        }
        $idPersona = (int)$idPersona;

        $sql = "
            SELECT ID_Persona, Motivo_pago, Estado_pago, Mes, Foto, Monto, ID_Comprobante_pago
            FROM comprobante_pago
            WHERE ID_Persona = ?
            AND TRIM(Estado_pago) IN ('En espera', 'Pendiente')
        ";

        $resultado = consulta($this->conn, $sql, "i", [$idPersona]);
        if ($resultado === false) {
            throw new Exception("Error en consulta getComprobantesMensuales");
        }

        $comprobantes = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
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
        return $comprobantes;
    }



        public function subirComprobante($nombre, $idComprobantePago) {
            $consulta = "
                UPDATE Comprobante_pago
                SET Foto = ?, Estado_pago = 'Pendiente'
                WHERE ID_Comprobante_pago = ?
            ";
            return consulta($this->conn, $consulta, "si", [$nombre, $idComprobantePago]);
        }
        
        public function cargarHoras($idPersona, $horas, $fechaHoras, $idSemana){
            $consulta = "
                INSERT INTO Horas_trabajadas 
                (ID_Persona, Horas, Fecha_registro_horas, ID_Semana_trabajo)
                VALUES (?, ?, ?, ?)
            ";
            // horas puede ser decimal -> usar 'd' si corresponde; si es entero usar 'i'
            $resultado = consulta($this->conn, $consulta, "idsi", [$idPersona, $horas, $fechaHoras, $idSemana]);
            if ($resultado === false) {
                return false;
            }
            return true;
        }

        public function horasTrabajadasXSemana($idPersona, $idSemana){
            $consulta = "
                SELECT Horas
                FROM Horas_Trabajadas
                WHERE ID_Persona = ? AND ID_Semana_trabajo = ?
            ";
            $resultado = consulta($this->conn, $consulta, "ii", [$idPersona, $idSemana]);
            if ($resultado === false) {
                return 0;
            }

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
                WHERE ID_Persona = ? AND Estado_Pago = 'En Espera'
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);
            if ($resultado === false) {
                return 0;
            }

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
                WHERE ID_Persona = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);
            if ($resultado === false) {
                return 0;
            }

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
                WHERE  Fecha_semana = ?
            ";
            $resultado = consulta($this->conn, $consulta, "s", [$semana]);
            if ($resultado === false) {
                return null;
            }
            if ($fila = mysqli_fetch_assoc($resultado)) {
                return $fila['Horas_semanales']; 
            }
            return null; 
    }

        public function getIdSemana($fecha){
            $consulta = "
                SELECT ID_Semana_trabajo
                FROM   Semana_trabajo
                WHERE  Fecha_semana = ?
                LIMIT 1 
            "; //para solo traer uno
            $resultado = consulta($this->conn, $consulta, "s", [$fecha]);
            if ($resultado === false) {
                return null;
            }
            if ($fila = mysqli_fetch_assoc($resultado)) {
                return $fila['ID_Semana_trabajo']; 
            }
            return null;
        }

        public function getHorasHistorial($idPersona) {
            $consulta = "
                SELECT *
                FROM Horas_Trabajadas
                WHERE ID_Persona = ?
            ";
            
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);
            if ($resultado === false) {
                return [];
            }

            $horas = [];
        
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $horas[] = [
                    'ID_Horas_trabajadas' => $fila['ID_Horas_trabajadas'],
                    'Horas' => $fila['Horas'],
                    'Fecha_registro_horas' => $fila['Fecha_registro_horas'],
                    'idHoras' => $fila['ID_Horas_trabajadas']
                ];
            }
        
            return $horas;
        }
        
    
        //FUNCIONES
        public function usuarioExisteID($id){
            $consulta = "SELECT * FROM Usuario WHERE ID_Persona = ?";
            $resultado = consulta($this->conn, $consulta, "i", [$id]);
            if ($resultado === false) {
                return false;
            }
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
                VALUES (?)
            ";
            return consulta($this->conn, $consulta, "s", [$fecha]);
        }
        
        public function semanaExiste($fecha){
            $consulta = "
                SELECT ID_Semana_trabajo
                FROM   Semana_trabajo
                WHERE  Fecha_semana = ?
                LIMIT 1 
            "; //para solo traer uno
            $resultado = consulta($this->conn, $consulta, "s", [$fecha]);
            if ($resultado === false) {
                return null;
            }
            if (mysqli_fetch_assoc($resultado)) {
                return true; 
            }
            return null;
        }
        
        public function editarHoras($idHoras, $horas, $fecha){
            $consulta = "
                UPDATE Horas_trabajadas
                SET Horas = ?, 
                Fecha_registro_horas = ?
                WHERE ID_Horas_trabajadas = ?
            ";
            $resultado = consulta($this->conn, $consulta, "dsi", [$horas, $fecha, $idHoras]);
            if ($resultado === false) {
                return false;
            }
            return true;
        }

        public function borrarHoras($idHoras){
            $consulta = "
                DELETE FROM Horas_trabajadas
                WHERE ID_Horas_trabajadas = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idHoras]);
            if ($resultado === false) {
                return false;
            }
            return true;
        }

        public function ingresarIntegrante($idPersona, $nombre, $apellido, $ci, $fechaNacimiento, $genero, $email){
            $consulta = "
                INSERT INTO 
                integrante_familiar
                (ID_Persona, Nombre, Apellido, CI, FechaNacimiento, Genero, Email)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            //agregar [parentesco si va]
            return consulta($this->conn, $consulta, "issssss", [$idPersona, $nombre, $apellido, $ci, $fechaNacimiento, $genero, $email]);
        }
                
        public function cargarFalta($idPersona, $horas, $fechaHoras, $idSemana, $compensacion, $motivo){
            $consulta = "
                INSERT INTO Falta
                    (ID_Persona, Horas_solicitadas, Fecha, ID_Semana_trabajo, Tipo_falta, Motivo_falta, Estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            // horas puede ser decimal -> usar 'd' si corresponde; si es entero usar 'i'
            $resultado = consulta($this->conn, $consulta, "idsisss", [$idPersona, $horas, $fechaHoras, $idSemana, $compensacion, $motivo, 'En espera']);
            if ($resultado === false) {
                return false;
            }
            return true;
        }

        public function getSemanas() {
            $sql = "
                SELECT
                    ID_Semana_trabajo,
                    Fecha_semana
                FROM Semana_trabajo
                ORDER BY Fecha_semana DESC
            ";
            $res = consulta($this->conn, $sql);

            $semanas = [];
            if ($res && mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $semanas[] = [
                        'id'    => $row['ID_Semana_trabajo'],
                        'fecha' => $row['Fecha_semana'],                  // 'YYYY-MM-DD'
                        'label' => date('d/m/Y', strtotime($row['Fecha_semana']))
                    ];
                }
            }
            return $semanas;
        }

        public function getIntegrantesFamiliares($idPersona) {
            $consulta = "
                SELECT * 
                FROM integrante_familiar
                WHERE ID_Persona = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]); 
        
            $integrantesFamiliares = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $integrantesFamiliares[] = [
                    'ID_Integrante'   => $fila['ID_Integrante'],
                    'Nombre'          => $fila['Nombre'],
                    'Apellido'        => $fila['Apellido'],
                    'CI'              => $fila['CI'],
                    'FechaNacimiento' => $fila['FechaNacimiento'],
                    'Email'           => $fila['Email'],
                    'Genero'          => $fila['Genero']
                ];
            }
            return $integrantesFamiliares;
        }
        public function integranteExiste($ci) {
            $consulta = "
                SELECT 1
                FROM integrante_familiar
                WHERE CI = ?
            ";
            $resultado = consulta($this->conn, $consulta, "s", [$ci]); 
            if ($resultado === false) {
                return false;
            }
            return mysqli_num_rows($resultado) > 0;
        }
        
        public function eliminarIntegrante($idIntegrante) {
            $consulta = "
                DELETE
                FROM integrante_familiar
                WHERE ID_Integrante = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idIntegrante]); 
            if ($resultado === false) {
                return false;
            }
            return true;
        }

        public function getReunionesPendientes(){
            $consulta = "
                SELECT * 
                FROM reunion 
                WHERE Estado_Reunion = 'Pendiente';
            ";
            $resultado = consulta($this->conn, $consulta); 
            $reunionesPendientes = [];
        
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $reunionesPendientes[] = $fila;
            }
            return $reunionesPendientes;
        }

        public function getReunionesTerminadas(){
            $consulta = "
                SELECT *
                FROM reunion
                WHERE Estado_Reunion IN ('Finalizada', 'Cancelada')
                ORDER BY Fecha DESC
                LIMIT 5;
            ";

            $resultado = consulta($this->conn, $consulta); 
            $reunionesPendientes = [];
        
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $reunionesPendientes[] = $fila;
            }
            return $reunionesPendientes;
        }

        public function getAsistencias($id) {
            $consulta = "
                SELECT Asistencia FROM asistencia WHERE ID_Persona = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$id]);

            $asistio = 0;
            $falto = 0;

            if (mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    if ($fila['Asistencia'] === 1) {
                        $asistio++;
                    } else {
                        $falto++;
                    }
                }
            }

            return [
                'cantidadReuniones' => $asistio + $falto,
                'asistencias' => $asistio
            ];
        }
    }
