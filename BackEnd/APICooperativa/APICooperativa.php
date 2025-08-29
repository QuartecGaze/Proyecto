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
            elseif ($accion === "subirComprobante"){
                if(!isset($_FILES['comprobante'])){
                    respuesta("debe cargar un archivo", "error", 400);
                }
                $nombreArchivo = $_FILES['comprobante']['name'];
                $nombreTemp = $_FILES['comprobante']['tmp_name'];
                //traer idComprobante y motivo del pago del boton front
                try{
                if($servicio->subirComprobante($nombreArchivo, $nombreTemp, $idComprobante)){
                    respuesta("archivo cargado correctamente", "exito", 200);
                }else{
                    respuesta("error al cargar al archivo", "error", 400);
                }
                }catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
            elseif ($accion === "subirHoras"){
                //traer las horas del front
                session_start();
                $idPersona = $_SESSION['id'];
                $datos = json_decode(file_get_contents('php://input'), true);
                $horas = $datos['horas'];
                if ($horas === null || $horas < 1 || $horas > 12) {
                    respuesta("Horas inválidas (1–12).", "error", 422);
                }
                try{
                    if($servicio->cargarHoras($idPersona, $horas)){
                        respuesta("horas cargadas correctamente", "exito", 200);
                    }else{
                        respuesta("error al cargar las horas", "error", 400);
                    }
                    }catch(Exception $e){
                        respuesta($e->getMessage(), "error", $e->getCode());
                    }
            }

            elseif ($accion === "subirComprobante"){ //PAGOS USUARIO
                if(!isset($_FILES['comprobante'])){
                    respuesta("debe cargar un archivo", "error", 400);
                }
                $nombreArchivo = $_FILES['comprobante']['name'];
                $nombreTemp = $_FILES['comprobante']['tmp_name'];
                try{
                if($servicio->subirComprobante($nombreArchivo, $nombreTemp, $idComprobante)){
                    respuesta("archivo cargado correctamente", "exito", 200);
                }else{
                    respuesta("error al cargar al archivo", "error", 400);
                }
                }catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
        break;
        case "GET":
            if($accion == "getComprobantesPendientes"){
                try {
                    $idPersona = $_GET['id'];
                    $comprobantes = $servicio->getComprobantesPendientes($idPersona);
                    respuesta($comprobantes['comprobantesPendientes'], "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "getCooperativa"){ //LLamar en el index de usuario
                $id = $_GET['id'];
                if($id != null){
                    $comprobantes = $servicio->getComprobantesPendientes($id);
                    if($servicio->semanaActualExiste()){
                        
                    }

                    try{
                        $respuesta = [
                            //Hacer esto para traer
                            //Horas trabajadas esta semana 
                            
                            'horasTrabajadas' => $servicio->horasTrabajadasEstaSemana($id),
                            
                            //Total horas semanales requeridas
                            'horasObjetivo' => $servicio->horasSemanales(),
                            
                            //Total pagos atrasados
                            'pagosAtrasados' => $comprobantes['cantidadPagosPendientes'],
                            //total dinero de pagos atrasados?
                            'pagosAtrasadosDinero' => $comprobantes['montoPendiente'],
                            //precio pago mensual
                            'pagoMensual' => $comprobantes['pagoMensual'],
                            //todos los comprobantespendientes con todos sus datos para usar en la zona de hacer los pagos
                            'comprobantesPendientes' => $comprobantes['comprobantesPendientes']

                            //el ultimo va sin coma
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
        
        if($accion === "getHorasTrabajadas"){ //LLamar en horas.php de Usuario
                $id = $_GET['id'];
                if($id != null){
                    try{
                        $respuesta = [
                            
                            //Hacer esto para traer
                            //Horas trabajadas esta semana
                            'horasTrabajadas' => $servicio->horasTrabajadasEstaSemana($id),
                            //
                            'horasAtrasadas' => $servicio->horasAtrasadasUsuario($id)
                            //el ultimo va sin coma
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



/*
        $resultado = $servicio->horasAtrasadasUsuario($idPersona);

        echo "Pendientes: " . $resultado['horasPendientes'];
        echo "A favor: "   . $resultado['horasAFavor'];
*/

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