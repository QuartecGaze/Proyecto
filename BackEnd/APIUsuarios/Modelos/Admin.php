<?php 
    require_once 'Persona.php';
    Class Admin extends Persona {
        private $nivelPermisos;
        private $foto;
        private $fechaIngreso;

    public function __construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol, $nivelPermisos, $foto, $fechaIngreso) {
        parent::__construct($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, $rol);
        $this->nivelPermisos = $nivelPermisos;
        $this->foto = $foto;
        $this->fechaIngreso = $fechaIngreso;
           
    }
    public function getNivelPermisos(){
        return $this->nivelPermisos;
    }
    public function setNivelPermisos($nivelPermisos){
        $this->nivelPermisos = $nivelPermisos;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

        
 }