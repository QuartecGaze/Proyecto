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

        public function getComprobantes($idPersona){
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
                $fila['ID_Comprobante_pago'],
            );
            }
        } else{
            throw new Exception("No hay comprobantes en espera o pendiente asocioados a la id: " . $idPersona);
        }
          return $comprobantes;  
            }
        


        //FUNCIONES
        public function unidadHabitacionalExiste($numeroPuerta, $pasillo){
            $consulta = "SELECT * FROM unidad_habitacional WHERE Numero_puerta = '$numeroPuerta' AND Pasillo = '$pasillo'";
            $resultado = mysqli_query($this->conn, $consulta);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
        }
    }