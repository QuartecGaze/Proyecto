<?php 
    require_once 'Persona.php';
    Class Admin extends Persona {
        private $nivelPermisos;

    public function __construct($ci, $email, $idPersona, $nombre, $apellido, $contraseña, $rol, $nivelPermisos) {
        parent::__construct($ci, $email, $idPersona, $nombre, $apellido, $contraseña, $rol);
        $this->nivelPermisos = $nivelPermisos;
    }
    public function getNivelPermisos(){
        return $this->nivelPermisos;
    }
    public function setNivelPermisos($nivelPermisos){
        $this->nivelPermisos = $nivelPermisos;
    }
 }