<?php
require_once __DIR__ . '/RepositorioPersona.php';
require_once __DIR__ . '/ServicioPersona.php'; 
require_once __DIR__ .'/Modelos/Usuario.php';
require_once __DIR__ .'/Modelos/Persona.php'; 
require_once __DIR__ .'/Modelos/Admin.php';
require_once __DIR__ .'/Modelos/Interesado.php';
require_once __DIR__ .'/BDConeccion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$repositorio = new RepositorioPersona($conn);
$servicio = new ServicioPersona($repositorio);


$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_GET['accion'] ?? ''; // USAMOS QUERY STRING EN VEZ DE PATH_INFO


switch($metodo) {
    case "POST":
        if ($accion === "registro") {
            
            try {
                $datos = json_decode(file_get_contents('php://input'), true);
                $servicio->registro(
                    $datos['ci'], 
                    $datos['email'], 
                    $datos['nombre'],
                    $datos['apellido'], 
                    $datos['contraseña'], 
                    $datos['confirmarContraseña']
                );
                respuesta("La persona se ha cargado con éxito", "exito", 201);
            } catch(Exception $e) {
                respuesta($e->getMessage(), "error", $e->getCode());
            }
            
        } elseif ($accion === "login") {

            try {
                $datos = json_decode(file_get_contents('php://input'), true);
                $id = $servicio->iniciarSesion($datos['ci'], $datos['contraseña']);
                $_SESSION['id'] = $id;
                respuesta("Sesion iniciada con exito", "exito", 200);

            } catch(Exception $e) {

                respuesta($e->getMessage(), "error", $e->getCode());
            }

        }
    break;

    case "GET":

    break;

    default:
        respuesta("Método no permitido", "error", 405);
    break;

}

function respuesta($mensaje, $estado, $codigo) {
    header('Content-Type: application/json');
    http_response_code($codigo);
    echo json_encode([
        "status"  => $estado,
        "message" => $mensaje,
    ]);
    exit;
}


?>