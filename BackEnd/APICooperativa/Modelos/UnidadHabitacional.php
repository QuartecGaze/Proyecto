<?php 
    class UnidadHabitacional {
    private $idUnidadHabitacional;
    private $idPersona;
    private $numeroPuerta;
    private $pasillo;
    private $estadoUnidad;
    private $cantidadHabitaciones;

    // Constructor
    public function __construct($numeroPuerta, $pasillo, $cantidadHabitaciones, $estadoUnidad = null, $idPersona = null, $idUnidadHabitacional = null) {
        $this->idUnidadHabitacional = $idUnidadHabitacional;
        $this->idPersona = $idPersona;
        $this->numeroPuerta = $numeroPuerta;
        $this->pasillo = $pasillo;
        $this->estadoUnidad = $estadoUnidad;
        $this->cantidadHabitaciones = $cantidadHabitaciones;
    }

    // Getters
    public function getIdUnidadHabitacional() {
        return $this->idUnidadHabitacional;
    }

    public function getIdPersona() {
        return $this->idPersona;
    }

    public function getNumeroPuerta() {
        return $this->numeroPuerta;
    }

    public function getPasillo() {
        return $this->pasillo;
    }

    public function getEstadoUnidad() {
        return $this->estadoUnidad;
    }

    public function getCantidadHabitaciones() {
        return $this->cantidadHabitaciones;
    }

    // Setters
    public function setIdUnidadHabitacional($idUnidadHabitacional) {
        $this->idUnidadHabitacional = $idUnidadHabitacional;
    }

    public function setIdPersona($idPersona) {
        $this->idPersona = $idPersona;
    }

    public function setNumeroPuerta($numeroPuerta) {
        $this->numeroPuerta = $numeroPuerta;
    }

    public function setPasillo($pasillo) {
        $this->pasillo = $pasillo;
    }

    public function setEstadoUnidad($estadoUnidad) {
        $this->estadoUnidad = $estadoUnidad;
    }

    public function setCantidadHabitaciones($cantidadHabitaciones) {
        $this->cantidadHabitaciones = $cantidadHabitaciones;
    }
}
