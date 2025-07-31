<?php
    require_once __DIR__ . '/RepositorioCooperativa.php';
    require_once __DIR__ . '/ServicioCooperativa.php'; 
    require_once __DIR__ .'/Modelos/UnidadHabitacional.php';
    require_once __DIR__ .'/Modelos/ComprobantePago.php';
    require_once __DIR__ .'/../BDConeccion.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        header("Content-Type: application/json");

        $repositorio = new RepositorioCooperativa($conn);
        $servicio = new ServicioCooperativa($repositorio);


        $metodo = $_SERVER['REQUEST_METHOD'];
        $accion = $_GET['accion'] ?? ''; // USAMOS QUERY STRING EN VEZ DE PATH_INFO


    switch($metodo) {
        case "POST":
            if($accion == "crearUnidad"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->crearUnidadHabitacional(
                        $datos['numeroPuerta'], 
                        $datos['pasillo'], 
                        $datos['cantidadHabitaciones'], 
                    );
                    respuesta("La unidad habitacional se ha cargado con éxito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
        break;
        case "GET":
            if($accion == "getComprobantes"){
                try {
                    $idPersona = $_GET['id'];
                    $comprobantes = $servicio->getComprobantes($idPersona);
                    respuesta($comprobantes, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
        break;
    }

     function respuesta($mensaje, $estado, $codigo) { // $rol = null hace que sea opcional ponerlo para usar el metodo
        header('Content-Type: application/json');
        http_response_code($codigo);
        $respuesta = [
            "status"  => $estado,
            "message" => $mensaje
        ];
        echo json_encode($respuesta);
        exit;
    }