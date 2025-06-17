<?php
    Class ServicioPersona {
        //nos e especifica el tipo porque un servicio tiene un solo repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }
        public crearUsuario(){
            if($repositorio->usuarioExiste()){
                return "error"; //
            } else{
                if(passwordMatch()){
                    $repositorio->crearUsuario()
                }
            }
        }
    }