<?php   
function verificarAcceso(array $rolesPermitidos) {
    session_start();
    if (!isset($_SESSION['id'])) {
        // Usuario no autenticado
        header("Location: ../Landing Page/login.html");
        exit();
    } 
    
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        // Usuario autenticado pero sin permisos
        header("Location: ../Landing Page/index.html");
        exit();
    }
}