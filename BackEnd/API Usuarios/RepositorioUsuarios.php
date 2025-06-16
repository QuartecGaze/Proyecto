<?php
    Class RepositorioUsuarios {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }
        public cargarAdmin($admin){
        $CI = $admin->getCI();
        $email = $admin->getEmail();
        $idPersona = $admin->getIdPersona();
        $contraseña = $admin->getContraseña();
        $rol = $admin->getRol();
        $nivelPermisos = $admin->getNivelPermisos();

        $insertQuery = "
            INSERT INTO users (name, lastname, email, password, birthday, photo_url, ci) 
            VALUES ('$CI', '$email', '$email', '$password', '$birthday', '$photoURL', '$ci')
        ";
        }


    }