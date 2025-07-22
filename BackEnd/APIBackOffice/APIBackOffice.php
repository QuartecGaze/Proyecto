<?php
    require_once __DIR__ . '/RepositorioBackOffice.php';
    require_once __DIR__ . '/ServicioBackOffice.php'; 
    require_once __DIR__ .'/BDConeccion.php';
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    header("Content-Type: application/json");

    $repositorio = new RepositorioBackOffice($conn);
    $servicio = new ServicioBackOffice($repositorio);


    $metodo = $_SERVER['REQUEST_METHOD'];
    $accion = $_GET['accion'] ?? ''; // USAMOS QUERY STRING EN VEZ DE PATH_INFO


    switch($metodo) {
        case "POST":
            if($accion === "asignarEntrevista"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->asignarEntrevista(
                        $datos['idPersona'],
                        $datos['fecha'],
                        $datos['hora']

                    );
                    respuesta("La entrevista se asigno con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "aprobarEstado") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    
                    $resultado = $servicio->aprobarEstado($datos['idPersona'], $datos['campo']);
            
                    if ($resultado) {
                        respuesta("Estado actualizado correctamente", "exito", 200);
                    } else {
                        respuesta("Campo inválido", "error", 400);
                    }
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "rechazarEstado") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    
                    $resultado = $servicio->rechazarEstado($datos['idPersona'], $datos['campo']);
            
                    if ($resultado) {
                        respuesta("Estado actualizado correctamente", "exito", 200);
                    } else {
                        respuesta("Campo inválido", "error", 400);
                    }
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
            

            if ($accion === "asignarPagoInicial"){
                $data = json_decode(file_get_contents("php://input"), true);

                if (!isset($data['idPersona']) || !isset($data['montoPagoInicial'])) {
                    respuesta("Datos insuficientes", "error", 400);
                }

                $idPersona = $data['idPersona'];
                $montoPagoInicial = $data['montoPagoInicial'];

                try {
                    $controlador->asignarPagoInicial($idPersona, $montoPagoInicial);
                    respuesta("Pago inicial asignado correctamente", "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "asignarEntrevista") {
                $data = json_decode(file_get_contents("php://input"), true);

                if (!isset($data['idPersona']) || !isset($data['fechaEntrevista']) || !isset($data['horaEntrevista'])) {
                    respuesta("Datos insuficientes", "error", 400);
                }

                $idPersona = $data['idPersona'];
                $fechaEntrevista = $data['fechaEntrevista'];
                $horaEntrevista = $data['horaEntrevista'];

                try {
                    $controlador->asignarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista);
                    respuesta("Entrevista asignada correctamente", "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "contarInteresados") {
                try {
                    $total = $servicio->contarInteresados();
                    respuesta($total, "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "rechazarInteresado") {
                $datos = json_decode(file_get_contents('php://input'), true);
                $servicio->rechazarInteresado($datos['idPersona']);
                respuesta("Interesado rechazado exitosamente", "exito", 200);
            }
            
            if($accion === "aprobarInteresado") {
                $datos = json_decode(file_get_contents('php://input'), true);
                $servicio->cargarUsuario($datos['idPersona']);
                respuesta("Interesado aprobado exitosamente", "exito", 200);
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