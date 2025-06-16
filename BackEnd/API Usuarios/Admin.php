<?php   
 Class Admin extends Persona {
    private $nivelPermisos;

    public function __construct($CI, $email, $idPersona, $contraseña, $rol, $nivelPermisos) {
        parent::__construct($CI, $email, $idPersona, $contraseña, $rol);
        $this->nivelPermisos = $nivelPermisos;
    }
    public function getNivelPermisos(){
        return $this->NivelPermisos;
    }
    public function setNivelPermisos($nivelPermisos){
        $this->nivelPermisos = $nivelPermisos;
    }
 }