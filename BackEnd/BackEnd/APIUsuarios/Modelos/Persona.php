<?php
    class Persona {
        private $ci;
        private $email;
        private $idPersona;
        private $nombre;
        private $apellido;
        private $contraseña;
        private $rol; 

        public function __construct($ci, $email, $idPersona, $nombre, $apellido, $contraseña, $rol){
            $this->ci = $ci;
            $this->email = $email;
            $this->idPersona = $idPersona;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->contraseña = $contraseña;
            $this->rol = $rol;
        }
    //Getters
    public function getCi() {
        return $this->ci;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIdPersona() {
        return $this->idPersona;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getRol() {
        return $this->rol;
    }

    // Setters
     public function setIdPersona($id) {
        $this->idPersona = $id;
    }
    public function setCI($ci) {
        $this->ci = $ci;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

   public function setApellido($apellido) {
       $this->apellido = $apellido;
    }

    public function setContraseña($contraseña) {
        $this->contraseña = $contraseña;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

}
