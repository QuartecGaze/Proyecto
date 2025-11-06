<?php
    require_once 'Persona.php';
    Class Usuario extends Persona {
        private $fechaNacimiento;
        private $foto;
        private $fechaIngreso;

        public function __construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol, $fechaNacimiento, $fechaIngreso, $foto) {
            parent::__construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol);
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

        public function toArray($horasTrabajadasTotal = 0, $horasTrabajadasSemana = 0, $totalHorasATrabajar = 0, $totalDebe = 0, $direccion = null, $integrantesFamiliares = null) {
            return [
                'ci' => $this->getCi(),
                'email' => $this->getEmail(),
                'telefono' => $this->getTelefono(),
                'idPersona' => $this->getIdPersona(),
                'nombre' => $this->getNombre(),
                'apellido' => $this->getApellido(),
                'contraseña' => $this->getContraseña(),
                'rol' => $this->getRol(),
                'fechaNacimiento' => $this->getFechaNacimiento(),
                'fechaIngreso' => $this->getFechaIngreso(),
                'foto' => $this->getFoto(),
                'horasTrabajadasTotal' => $horasTrabajadasTotal,
                'horasTrabajadasSemana' => $horasTrabajadasSemana,
                'totalHorasATrabajar' => $totalHorasATrabajar,
                'totalDebe' => $totalDebe,
                'direccion' => $direccion,
                'integrantesFamiliares' => $integrantesFamiliares
            ];
        }
        

    }