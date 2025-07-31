<?php 
class ComprobantePago {
    private $idPersona;
    private $motivoPago;
    private $estadoPago;
    private $mes;
    private $foto;
    private $monto;
    private $idComprobantePago;

    // Constructor
    public function __construct($idPersona, $motivoPago, $estadoPago, $mes, $foto, $monto, $idComprobantePago = null) {
        $this->idPersona = $idPersona;
        $this->motivoPago = $motivoPago;
        $this->estadoPago = $estadoPago;
        $this->mes = $mes;
        $this->foto = $foto;
        $this->monto = $monto;
        $this->idComprobantePago = $idComprobantePago;
    }

    // Getters
    public function getIdPersona() {
        return $this->idPersona;
    }

    public function getMotivoPago() {
        return $this->motivoPago;
    }

    public function getEstadoPago() {
        return $this->estadoPago;
    }

    public function getMes() {
        return $this->mes;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function getIdComprobantePago() {
        return $this->idComprobantePago;
    }

    // Setters
    public function setIdPersona($idPersona) {
        $this->idPersona = $idPersona;
    }

    public function setMotivoPago($motivoPago) {
        $this->motivoPago = $motivoPago;
    }

    public function setEstadoPago($estadoPago) {
        $this->estadoPago = $estadoPago;
    }

    public function setMes($mes) {
        $this->mes = $mes;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function setIdComprobantePago($idComprobantePago) {
        $this->idComprobantePago = $idComprobantePago;
    }

    public function toArray() {
    return [
        "idComprobantePago" => $this->getIdComprobantePago(),
        "idPersona"         => $this->getIdPersona(),
        "motivoPago"        => $this->getMotivoPago(),
        "estadoPago"        => $this->getEstadoPago(),
        "mes"               => $this->getMes(),
        "foto"              => $this->getFoto(),
        "monto"             => $this->getMonto(),
    ];
}
}
?>
