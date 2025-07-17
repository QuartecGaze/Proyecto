<?php

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
                        $datos['fechaEntrevista'],
                        $datos['horaEntrevista']
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
            
            if ($accion === "Interesados") {
                try {
                    $interesados = $servicio->Interesados();
                    respuesta($total, "exito", 200);
                    echo json_encode([
                        'status' => 'exito',
                        'data' => $interesados
                    ]);

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