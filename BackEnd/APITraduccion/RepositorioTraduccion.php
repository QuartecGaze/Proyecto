<?php
    require_once __DIR__ . '/ServicioTraduccion.php'; 
    Class RepositorioTraduccion {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        
        //CRUD Persona

        public function getIdiomaPagina($idioma, $pagina){
            $consulta = "
                SELECT * FROM traducciones WHERE idioma = '$idioma' and pagina = '$pagina'";
            $resultado = mysqli_query($this->conn, $consulta);
            while ($linea = mysqli_fetch_assoc($resultado)) {
            $traducciones[] = new Traduccion(
            $linea['pagina'],
            $linea['clave'],
            $linea['idioma'],
            $linea['texto']
        );
    }

    return $traducciones;
}
    }