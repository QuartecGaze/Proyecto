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
            foreach($comprobantesObj as $comprobante){
                    $comprobantesAsociativo[$comprobante->getIdComprobantePago()] = $comprobante->toArray();
                }
            return $comprobantesAsociativo;
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



    }
