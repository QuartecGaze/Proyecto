<?php
class Traduccion {
    private $pagina;
    private $clave;
    private $idioma;
    private $texto;

    // Constructor
    public function __construct($pagina, $clave, $idioma, $texto) {
        $this->pagina = $pagina;
        $this->clave = $clave;
        $this->idioma = $idioma;
        $this->texto = $texto;
    }

    // Getters
    public function getPagina() {
        return $this->pagina;
    }

    public function getClave() {
        return $this->clave;
    }

    public function getIdioma() {
        return $this->idioma;
    }

    public function getTexto() {
        return $this->texto;
    }

    // Setters
    public function setPagina($pagina) {
        $this->pagina = $pagina;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function setIdioma($idioma) {
        $this->idioma = $idioma;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }
}
?>