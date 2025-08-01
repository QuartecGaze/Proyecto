<?php
    require_once __DIR__ . '/RepositorioBackOffice.php';
    require_once __DIR__ . '/ServicioBackOffice.php'; 
    require_once __DIR__ .'/../BDConeccion.php';
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
                    $servicio->asignarPagoInicial($idPersona, $montoPagoInicial);
                    respuesta("Pago inicial asignado correctamente", "exito", 200);
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
            
            if ($accion === "subirFoto"){
                if(!isset($_FILES['foto'])){
                    respuesta("debe cargar un archivo", "error", 400);
                }
                $nombreArchivo = $_FILES['foto']['name'];
                $nombreTemp = $_FILES['foto']['tmp_name'];
                try{
               if($servicio->subirFoto($nombreArchivo, $nombreTemp)){
                    respuesta("archivo cargado correctamente", "exito", 200);
               }else{
                    respuesta("error al cargar al archivo", "error", 400);
               }
                }catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
            }
            }
            //API Cooperativa
            if($accion === "asignarPagoMensual"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->crearPagoMensual($montoPagoMensual); //traer el montoPagoMensual del front (lo va a ingresar el admin en un modal)
                    respuesta("Pago mensual asignado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "asignarPagoPersonalizado"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->crearPagoPersonalizado($motivoPago, $ci , $montoPagoPersonalizado); //traer el montoPagoMensual del front (lo va a ingresar el admin en un modal)
                    respuesta("Pago personalizado asignado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "rechazarPago"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->rechazarPago($idComprobantePago); //traer el idcomprobante pago del boton
                    respuesta("Pago rechazado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "aprobarPago"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->aprobarPago($idComprobantePago); //traer el idcomprobante pago del boton
                    respuesta("Pago aprobado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "asignarUnidadHabitacional"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    //tiene que recibir la cedula de la persona y el idunidadhabitacional
                    $servicio->asignarUnidadHabitacional($ci, $idUnidadHabitacional);
                    respuesta("Unidad Habitacional asignada con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

        break;

        case "GET":
            if ($accion == "getInteresados"){ 
                $interesados = $servicio->getInteresados();
                respuesta($interesados, "exito", 200);
            }

            if($accion === "getAdmin"){
                $id = $_GET['id'];
                if($id != null){
                    try{
                    $admin = $servicio->getAdmin($id);
                    $respuesta = [
                    'ci' => $admin->getCi(),
                    'email' => $admin->getEmail(),
                    'telefono' => $admin->getTelefono(),
                    'idPersona' => $admin->getIdPersona(),
                    'nombre' => $admin->getNombre(),
                    'apellido' => $admin->getApellido(),
                    'nivelPermisos' => $admin->getNivelPermisos(),
                    'foto' => $admin->getFoto(),
                    'fechaIngreso' => $admin->getFechaIngreso()
                    ];
                        respuesta($respuesta, "exito", 200);
                    }
                    catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }

            } else{
                respuesta("No se encontro una id para buscar", "error", 0);
            }
        }

            if($accion == "getIdSesion"){
                session_start();
                if(isset($_SESSION['id'])){
                    respuesta($_SESSION['id'], "exito", 200);
                } else {
                    respuesta("No se ha encontrado una variable de sesion", "error", 404);
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