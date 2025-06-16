<?php
    Class Interesado extends Persona {
        private $antecedentes;
        private $estadoEntrevista;
        private $fechaEntrevista;
        private $pagoInical;
        private $estadoPagoInicial;
        private $montoPagoInicial;

        public function __construct($CI, $email, $idPersona, $contraseña, $rol, $antecedentes, $estadoEntrevista, $fechaEntrevista, $pagoInical, $estadoPagoInicial, $montoPagoInicial) {
            parent::__construct($CI, $email, $idPersona, $contraseña, $rol);
            $this->antecedentes = $antecedentes;
            $this->estadoEntrevista = $estadoEntrevista;
            $this->fechaEntrevista = $fechaEntrevista;
            $this->pagoInicial = $pagoInicial;
            $this->estadoPagoInicial = $estadoPagoInicial;
            $this->montoPagoInicial = $montoPagoInicial;
            
    }
        // Getters
public function getAntecedentes() {
    return $this->antecedentes;
}

public function getEstadoEntrevista() {
    return $this->estadoEntrevista;
}

public function getFechaEntrevista() {
    return $this->fechaEntrevista;
}

public function getPagoInicial() {
    return $this->pagoInicial;
}

public function getEstadoPagoInicial() {
    return $this->estadoPagoInicial;
}

public function getMontoPagoInicial() {
    return $this->montoPagoInicial;
}

// Setters
public function setAntecedentes($antecedentes) {
    $this->antecedentes = $antecedentes;
}

public function setEstadoEntrevista($estadoEntrevista) {
    $this->estadoEntrevista = $estadoEntrevista;
}

public function setFechaEntrevista($fechaEntrevista) {
    $this->fechaEntrevista = $fechaEntrevista;
}

public function setPagoInicial($pagoInicial) {
    $this->pagoInicial = $pagoInicial;
}

public function setEstadoPagoInicial($estadoPagoInicial) {
    $this->estadoPagoInicial = $estadoPagoInicial;
}

public function setMontoPagoInicial($montoPagoInicial) {
    $this->montoPagoInicial = $montoPagoInicial;
}
}