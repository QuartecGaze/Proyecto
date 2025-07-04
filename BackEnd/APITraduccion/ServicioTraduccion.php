<?php
    require_once __DIR__ . '/RepositorioTraduccion.php';
    require_once __DIR__ . '/ApiTraduccion.php';
    require_once __DIR__ .'/Traduccion.php';
    

    Class ServicioTraduccion {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }
        public function getIdiomaPagina($pagina, $idioma){
            $arrayTraduccionesObj = [];
            $arrayTraduccionesObj = $this->repositorio->getIdiomaPagina($pagina, $idioma);
            $arrayTraduccionesAsociativo = [];
            foreach($arrayTraduccionesObj as $traduccion){
                $arrayTraduccionesAsociativo[$traduccion->getClave()] = $traduccion->getTexto();
            }
            return $arrayTraduccionesAsociativo;
        }
    }