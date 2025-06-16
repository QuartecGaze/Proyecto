<?php
    class Persona {
        private $CI;
        private $email;
        private $idPersona;
        private $nombre;
        private $apellido
        private $contraseña;
        private $rol; 

        public function __construct($CI, $email, $idPersona, $contraseña, $rol){
            $this->CI = $CI;
            $this->email = $email;
            $this->idPersona = $idPersona;
            $this->contraseña = $contraseña;
            $this->rol = $rol;
        }
    //Getters
    public function getCI() {
        return $this->CI;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIdPersona() {
        return $this->idPersona;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getRol() {
        return $this->rol;
    }

    // Setters
    public function setCI($CI) {
        $this->CI = $CI;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    public function setContraseña($contraseña) {
        $this->contraseña = $contraseña;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
    public function setNombre($nombre) {
         $this->nombre = $nombre;
    }
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

}
