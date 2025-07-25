<?php
    require_once 'Persona.php';
    Class Interesado extends Persona {
        private $antecedentes;
        private $estadoAntecedentes;
        private $estadoEntrevista;
        private $fechaEntrevista;
        private $horaEntrevista;
        private $pagoInicial;
        private $estadoPagoInicial;
        private $montoPagoInicial;

        public function __construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol, $antecedentes, $estadoAntecedentes, $estadoEntrevista, $fechaEntrevista, $horaEntrevista, $pagoInicial, $estadoPagoInicial, $montoPagoInicial) {
            parent::__construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol);
            $this->antecedentes = $antecedentes;
            $this->estadoAntecedentes = $estadoAntecedentes;
            $this->estadoEntrevista = $estadoEntrevista;
            $this->fechaEntrevista = $fechaEntrevista;
            $this->horaEntrevista = $horaEntrevista;
            $this->pagoInicial = $pagoInicial;
            $this->estadoPagoInicial = $estadoPagoInicial;
            $this->montoPagoInicial = $montoPagoInicial;
            
    }
        // Getters
public function getAntecedentes() {
    return $this->antecedentes;
}

public function getEstadoAntecedentes() {
    return $this->estadoAntecedentes;
}

public function getEstadoEntrevista() {
    return $this->estadoEntrevista;
}

public function getFechaEntrevista() {
    return $this->fechaEntrevista;
}

public function getHoraEntrevista() {
    return $this->horaEntrevista;
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

public function setEstadoAntecedentes($estadoAntecedentes) {
    $this->estadoAntecedentes = $estadoAntecedentes;
}

public function setEstadoEntrevista($estadoEntrevista) {
    $this->estadoEntrevista = $estadoEntrevista;
}

public function setFechaEntrevista($fechaEntrevista) {
    $this->fechaEntrevista = $fechaEntrevista;
}

public function setHoraEntrevista($horaEntrevista) {
    $this->horaEntrevista = $horaEntrevista;
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
public function toArray() {
    return [

        "idPersona"   => $this->getIdPersona(),
        "ci"          => $this->getCi(),
        "nombre"      => $this->getNombre(),
        "apellido"    => $this->getApellido(),
        "email"       => $this->getEmail(),
        "telefono"    => $this->getTelefono(),
        "rol"         => $this->getRol(),
        "antecedentes"        => $this->getAntecedentes(),
        "estadoAntecedentes"  => $this->getEstadoAntecedentes(),
        "estadoEntrevista"    => $this->getEstadoEntrevista(),
        "fechaEntrevista"     => $this->getFechaEntrevista(),
        "horaEntrevista"      => $this->getHoraEntrevista(),
        "pagoInicial"         => $this->getPagoInicial(),
        "estadoPagoInicial"   => $this->getEstadoPagoInicial(),
        "montoPagoInicial"    => $this->getMontoPagoInicial(),
    ];
}
}
