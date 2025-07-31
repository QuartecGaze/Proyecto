<?php
    require_once __DIR__ . '/RepositorioTraduccion.php';
    require_once __DIR__ . '/ServicioTraduccion.php'; 
    require_once __DIR__ .'/Traduccion.php';
    require_once __DIR__ .'/../BDConeccion.php';
    header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    header("Content-Type: application/json");

    $repositorio = new RepositorioTraduccion($conn);
    $servicio = new ServicioTraduccion($repositorio);


    $metodo = $_SERVER['REQUEST_METHOD'];
    $accion = $_GET['accion'] ?? ''; // USAMOS QUERY STRING EN VEZ DE PATH_INFO


    switch($metodo) {
        case "POST":
            if ($accion === "setIdioma") {
                    session_start();
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $_SESSION['idioma'] = $datos['idioma'];
                    $traduccion = getIdioma($servicio, $datos['idioma'], $datos['pagina']);
                    respuesta($traduccion, "exito", 201);
                }
        break;

        case "GET":
             if ($accion === "getIdioma"){
                session_start();
                if(!isset($_SESSION['idioma'])){
                    $_SESSION['idioma'] = 'es';
                }
                $traduccion = getIdioma($servicio, $_SESSION['idioma'], $_GET['pagina']);
                respuesta($traduccion, "exito", 201);
            }
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
    function getIdioma($servicio, $idioma, $pagina){
        $traduccion = $servicio->getIdiomaPagina(
                        $idioma, 
                        $pagina, 
                    );
        return $traduccion;
    }


?>