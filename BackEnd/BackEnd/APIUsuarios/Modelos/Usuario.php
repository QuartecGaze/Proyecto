<?php
    require_once 'Persona.php';
    Class Usuario extends Persona {
        private $fechaNacimiento;
        private $foto;
        private $fechaIngreso;

        public function __construct($ci, $email, $idPersona, $nombre, $apellido, $contraseña, $rol, $fechaNacimiento, $fechaIngreso, $foto) {
            parent::__construct($ci, $email, $idPersona, $nombre, $apellido, $contraseña, $rol);
            $this->fechaNacimiento = $fechaNacimiento;
            $this->fechaIngreso = $fechaIngreso;
            $this->foto = $foto;
        }
        
        public function getFechaNacimiento() {
            return $this->fechaNacimiento;
        }

        public function getFechaIngreso() {
            return $this->fechaIngreso;
        }

        public function getFoto() {
            return $this->foto;
        }
        //Setters
        public function setFechaNacimiento($fechaNacimiento) {
            $this->fechaNacimiento = $fechaNacimiento;
        }

        public function setFechaIngreso($fechaIngreso) {
            $this->fechaIngreso = $fechaIngreso;
        }        
        
        public function setFoto($foto) {
            $this->foto = $foto;
        }

    }