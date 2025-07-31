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

    public function getComprobantes($idPersona){
        $comprobantesObj = $this->repositorio->getComprobantes($idPersona);
        $comprobantesAsociativo = [];
        foreach($comprobantesObj as $comprobante){
                $comprobantesAsociativo[$comprobante->getIdComprobantePago()] = $comprobante->toArray();
            }
        return $comprobantesAsociativo;
    }
    }
