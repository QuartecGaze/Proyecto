<?php
    require_once 'RepositorioPersona.php';
    require_once 'ServicioPersona.php'; 
    require_once 'Modelos/Usuario.php';
    require_once 'Modelos/Persona.php'; 
    require_once 'Modelos/Admin.php';
    require_once 'Modelos/Interesado.php';
    require_once 'BDConeccion.php';
    
    $repositorio = new RepositorioPersona($conn);
    $servicio = new ServicioPersona($repositorio);

    header("Content-Type: application/json");
    $metodo = $_SERVER['REQUEST_METHOD'];
    $path = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
    
    switch($metodo){
        case "POST":
            if($path[0] = "registro"){
                try{
                $datos = json_decode(file_get_contents('php://input'), true);
                $servicio->registro($datos['ci'], $data['email'], $data['nombre'],$data['apellido'], $data['contraseña'], $data['confirmarContraseña']);
                respuesta("El usuario se a cargado con exito", "Exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());// error al registrar persona
                }
            } elseif($path[0] = "login"){
                try{
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $id = $servicio->iniciarSesison($datos['ci'], $datos['contraseña']);
                    $_SESSION['id'] = $id;
                    respuesta("Sesion iniciada con exito", "exito", 200);
                } catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());// error al registrar persona
                }
            }
            
        case "GET":
    
        default:
        
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


    

