<?php
    require_once __DIR__ .'/Modelos/ComprobantePago.php';
    require_once __DIR__ .'/Modelos/UnidadHabitacional.php'; 


    Class ServicioCooperativa {
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }

        public function crearUnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones){
            $unidadHabitacional = new UnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones);
            if(!$this->repositorio->unidadHabitacionalExiste($numeroPuerta, $pasillo)){
                $this->repositorio->crearUnidadHabitacional($unidadHabitacional);
            } else{
                throw new Exception("Esta unidad ya esta registrada", 409);
            }
        }

        public function getComprobantesPendientes($idPersona){
            $comprobantesObj = $this->repositorio->getComprobantesMensuales($idPersona);
            $comprobantesAsociativo = [];
            $comprobantesPendientes = 0;
            $comprobantesMonto = 0;
            $montoUltimoPagoPendiente = null;
            foreach($comprobantesObj as $comprobante){
                $comprobantesPendientes++;
                $monto = $comprobante->getMonto();
                $comprobantesMonto += $monto;
                    $comprobantesAsociativo[$comprobante->getIdComprobantePago()] = $comprobante->toArray();
                $montoUltimoPagoPendiente = $monto;
                }
            return [
                'comprobantesPendientes'   => $comprobantesAsociativo,
                'cantidadPagosPendientes'  => $comprobantesPendientes,
                'montoPendiente'           => $comprobantesMonto,
                'pagoMensual'              => $montoUltimoPagoPendiente,
            ];
        }

        public function subirComprobante($nombreArchivo, $nombreTemp, $idComprobante){ //traer el idcomprobante del front
                session_start();
                $rutaComprobantes = "../../Recursos/Comprobantes/";
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nuevoNombre =  'COMPROBANTE' . $_SESSION['id'] . $idComprobante . '.' . $extension;
                $nuevaRuta = $rutaComprobantes . $nuevoNombre;
                $archivoExiste = glob($nuevaRuta);
                if (count($archivoExiste) > 0) {
                    throw new Exception("Ya existe un archivo con el mismo nombre en el sistema", 500);
                } else{
                   if(move_uploaded_file($nombreTemp, $nuevaRuta)){
                    $this->repositorio->subirComprobante($nuevoNombre, $idComprobante);
                    return(true);
                } else{
                    throw new Exception("No se pudo cargar el archivo", 500);
                }
            }
        }

        public function cargarHoras($idPersona, $horas){
            $fechaHoras = date("Y-m-d");
            $lunesSemana = $this->lunesDeEstaSemana();
            if($this->repositorio->semanaExiste($lunesSemana)){
                $idSemana = $this->repositorio->getIdSemana($lunesSemana);
            }else{
                $this->repositorio->crearSemana($lunesSemana);
                $idSemana = $this->repositorio->getIdSemana($lunesSemana);
            }
            return $this->repositorio->cargarHoras($idPersona, $horas, $fechaHoras, $idSemana);
        }

        public function horasTrabajadasEstaSemana($idPersona){
            $semanaActual = $this->lunesDeEstaSemana();
            $idSemana = $this->repositorio->getIdSemana($semanaActual);
            //tengo que mandar id semana no semana
            return $this->repositorio->horasTrabajadasXSemana($idPersona, $idSemana);
        }


        public function horasTrabajadasUsuario($idPersona){
            if (!$this->repositorio->usuarioExisteID($idPersona)) {
                throw new Exception("El usuario no existe", 404);
            } else {
                return $this->repositorio->horasTrabajadas($idPersona);
            }
        }

        public function horasAtrasadasUsuario($idPersona){
            $fecha = $this->lunesdeestasemana();
            $idSemanaActual =  $this->repositorio->getIdSemana($fecha); //traer el idsemana de esta semana
            if (!$this->repositorio->usuarioExisteID($idPersona)) {
                throw new Exception("El usuario no existe", 404);
            } else {
                $totalHorasAFavor = 0;
                $totalHorasPendientes = 0;
                //traer el idsemana de la primera semana que registro horas
                $primeraSemanaUsuario = $this->repositorio->primeraSemanaUsuario($idPersona);
                for ($semana = $primeraSemanaUsuario; $semana < $idSemanaActual; $semana++) {

                    $trabajadas  = $this->repositorio->horasTrabajadasXSemana($idPersona, $semana); //semana es el idsemana
                    $necesarias  = $this->repositorio->getHorasNecesariasSemana($semana); //semana es el idsemana
    
                    if ($trabajadas > 0 && $trabajadas < $necesarias) {
                        $totalHorasPendientes += $necesarias - $trabajadas;
    
                    } elseif ($trabajadas > 0 && $trabajadas > $necesarias) {
                        $totalHorasAFavor += $trabajadas - $necesarias;
                    }
                }
                return [
                    'horasPendientes' => $totalHorasPendientes,
                    'horasAFavor'     => $totalHorasAFavor
                ];
                
            }
        }

        
        public function semanaActualExiste(){
            $lunesSemana = $this->lunesDeEstaSemana();
            if($this->repositorio->semanaExiste($lunesSemana)){
                return true;
            }else{
                $this->repositorio->crearSemana($lunesSemana);
                return false;
            }
        }

        public function horasSemanales(){
            $semana = $this->lunesDeEstaSemana();
            return $this->repositorio->getHorasNecesariasSemana($semana);
        }
        
        public function lunesDeXSemana($fecha){
            $momento = new DateTime($fecha);
            $diaSemana = $momento->format('N'); //Dependiendo de momento (que es un objeto) me va a dar 1 si es un lunes hasta 7 si es domingo
            //entonces con un if 
            if($diaSemana > 1){
                //modifica el momento para que sea momento menos el dia de la semana menos 1 osea si es 7 hace 7-1=6 y hace 7-6
                $momento->modify('-' . ($diaSemana - 1) . 'days');
            }
            //devuelve el lunes de la semana
            return $momento->format('Y-m-d');
        }

        public function lunesDeEstaSemana(){
            return $this->lunesDeXSemana(date('Y-m-d'));
        }


    }
