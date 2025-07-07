<?php
    require_once __DIR__ . '/RepositorioPersona.php';
    require_once __DIR__ . '/ServicioPersona.php'; 
    require_once __DIR__ .'/Modelos/Usuario.php';
    require_once __DIR__ .'/Modelos/Persona.php'; 
    require_once __DIR__ .'/Modelos/Admin.php';
    require_once __DIR__ .'/Modelos/Interesado.php';
    require_once __DIR__ .'/BDConeccion.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");


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
                    session_start();
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $persona = $servicio->iniciarSesion($datos['ci'], $datos['contraseña']);
                    $_SESSION['id'] = $persona['id'];
                    $_SESSION['rol'] = $persona['rol'];
                    respuesta("Sesion iniciada con exito", "exito", 200, $persona['rol']);


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

    function respuesta($mensaje, $estado, $codigo, $rol = null) { // $rol = null hace que sea opcional ponerlo para usar el metodo
        header('Content-Type: application/json');
        http_response_code($codigo);
    
        $respuesta = [
            "status"  => $estado,
            "message" => $mensaje
        ];
    
        if ($rol != null) {
            $respuesta["rol"] = $rol;
        }
    
        echo json_encode($respuesta);
        exit;
    }


?>