<?php
    require_once __DIR__ . '/RepositorioBackOffice.php';
    require_once __DIR__ . '/ServicioBackOffice.php'; 
    require_once __DIR__ .'/../BDConeccion.php';
    include __DIR__ .'/../Tokens.php';
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    header("Content-Type: application/json");

    $repositorio = new RepositorioBackOffice($conn);
    $servicio = new ServicioBackOffice($repositorio);


    $metodo = $_SERVER['REQUEST_METHOD'];
    $accion = $_GET['accion'] ?? ''; // USAMOS QUERY STRING EN VEZ DE PATH_INFO

    if(!validarTokenAdmin(obtenerToken(), $conn)){
        respuesta("Token invalido", "error", 401);
    } 
    
    switch($metodo) {
        case "POST":
            if ($accion === "cargarAdmin") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->cargarAdmin(
                        $datos['ci'], 
                        $datos['email'], 
                        $datos['telefono'], 
                        $datos['nombre'],
                        $datos['apellido'], 
                        $datos['contraseña'], 
                        $datos['nivelPermisos']
                    );
                    respuesta("Admin creado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

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
                try {
                $servicio->rechazarInteresado($datos['idPersona']);
                respuesta("Interesado rechazado exitosamente", "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
            
            if($accion === "aprobarInteresado") {
                $datos = json_decode(file_get_contents('php://input'), true);
                try{
                    $servicio->cargarUsuario($datos['idPersona']);
                    respuesta("Interesado aprobado exitosamente", "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
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
                    if (!isset($datos['montoPagoMensual'])) {
                        respuesta("Falta el monto", "error", 400);
                    }
                    $montoPagoMensual = $datos['montoPagoMensual'];
                    if ($montoPagoMensual <= 0) {
                        respuesta("El monto debe ser mayor a 0", "error", 422);
                    }
                    $servicio->crearPagoMensual($montoPagoMensual);
                    respuesta("Pago mensual asignado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "asignarPagoPersonalizado"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    if (!isset($datos['montoPagoPersonalizado']) || !isset($datos['ci']) || !isset($datos['motivoPagoPersonalizado'])) {
                        respuesta("Faltan datos", "error", 400);
                    }
                    $montoPagoPersonalizado = $datos['montoPagoPersonalizado'];
                    if ($montoPagoPersonalizado <= 0) {
                        respuesta("El monto debe ser mayor a 0", "error", 422);
                    }
                    $ci = $datos['ci'];
                    $motivoPago = $datos['motivoPagoPersonalizado'];
                    $servicio->crearPagoPersonalizado($motivoPago, $ci , $montoPagoPersonalizado); //traer el montoPagoMensual del front (lo va a ingresar el admin en un modal)
                    respuesta("Pago personalizado asignado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "rechazarPago"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->rechazarPago($datos['idComprobante']);
                    respuesta("Pago rechazado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "aprobarPago"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->aprobarPago($datos['idComprobante']);
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

            if($accion === "modificarUnidadHabitacional"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->modificarUnidadHabitacional(
                        $datos['id'],
                        $datos['habitaciones'],
                        $datos['estado'],
                        $datos['puerta'],
                        $datos['pasillo'],
                        $datos['ci']
                    );
                    respuesta("La unidad habitacional se ha modificado con éxito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

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

            if($accion == "crearUnidadConCI"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->crearUnidadHabitacionalConCI(
                        $datos['numeroPuerta'], 
                        $datos['pasillo'], 
                        $datos['ci']
                    );
                    respuesta("La unidad habitacional se ha cargado con éxito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "crearReunion"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->crearReunion(
                        $datos['titulo'], 
                        $datos['descripcion'], 
                        $datos['fecha'],
                        $datos['hora'], 
                        $datos['lugar'], 
                        $datos['tipoDeReunion']
                    );
                    respuesta("La reunion se ha cargado con éxito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "completarReunion") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    
                    $resultado = $servicio->completarReunion($datos['idReunion']);
                    respuesta("Reunion completada correctamente", "exito", 200);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "eliminarReunion") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    
                    $resultado = $servicio->eliminarReunion($datos['idReunion']);
                    respuesta("Reunion eliminada correctamente", "exito", 200);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "editarReunion") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $id = isset($datos['idReunion']) ? (int)$datos['idReunion'] : 0;
                    if ($id <= 0) {
                        respuesta("ID Reunion invalido", "error", 400);
                    }
            
                    $servicio->editarReunion(
                        $id,
                        $datos['titulo'],
                        $datos['descripcion'],
                        $datos['fecha'],
                        $datos['hora'],
                        $datos['lugar'],
                        $datos['tipoDeReunion']
                    );
            
                    respuesta("La reunión se ha editado con éxito", "exito", 200);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode() ?: 500);
                }
            }

            if ($accion === "pasarAsistencia") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $idReunion = $datos['idReunion'];
                    $asistencias = $datos['Asistencias'];
                    if(!isset($asistencias)){
                        respuesta('Sin registros para guardar','error', 400);
                    }
                    $resultado = $servicio->pasarAsistencia($idReunion, $asistencias); //asistencias es un array as
                    respuesta('Asistencias cargadas correctamente', 'exito', 200);
                    } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "aprobarFalta"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $falta = $servicio->getFalta($datos['idFalta']);
                    if($falta['Tipo_falta'] == "Exoneracion"){
                        $servicio->acreditarHoras($falta['ID_Persona'], $falta['Horas_solicitadas'], $falta['Fecha'], $falta['ID_Semana_trabajo']);
                    }elseif($falta['Tipo_falta'] == "Pago Compensatorio"){
                        // ver que hacer al aprobar, todavia nose al asignar el pago se va a crear 
                        //el comprobante pero podriamos hacer una confirmacion si fue pagado el comprobante 
                        //si traemos el id del comprobante ademas del monto (que probablemente habra que hacer)
                    }else{
                        respuesta("tipo de falta invalido", "error", 404);
                    }
                    $servicio->aprobarFalta($datos['idFalta']);
                    respuesta("Pago aprobado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "rechazarFalta"){
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $servicio->rechazarFalta($datos['idFalta']);
                    respuesta("Pago rechazado con exito", "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }


            if($accion === "asignarMontoFalta"){ //traer idFalta y monto
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $monto = $datos['monto'];
                    $falta = $servicio->getFalta($datos['idFalta']);

                    if($falta['Tipo_falta'] == "Pago Compensatorio"){
                        $servicio->crearPagoFalta($falta['ID_Persona'], $falta['Fecha'], $monto);
                        respuesta("Monto asignado con exito", "exito", 201);
                    }else{
                        respuesta("Campo incorrecto", "error", 404);
                    }
                    
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            
            if($accion === "getHorasTrabajadasUsuarios"){
                try {
                    $horasUsuarios = $servicio->getHorasTrabajadasUsuarios();
                    respuesta($horasUsuarios, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if ($accion === "cambiarEstado") {
                try {
                    $datos = json_decode(file_get_contents('php://input'), true);
                    $ids = $datos['ids'];
                    $estado = $datos['estado'];
                    $resultado = $servicio->cambiarEstado($ids, $estado);
            
                    if ($resultado) {
                        respuesta("Estado actualizado correctamente", "exito", 200);
                    } else {
                        respuesta("Campo inválido", "error", 400);
                    }
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

                } else {
                    respuesta("No se encontro una id para buscar", "error", 0);
                }
            }

            if($accion === "getUsuarios"){
                    try{
                    $usuarios = $servicio->getUsuarios();
                    respuesta($usuarios, "exito", 200);
                    }
                    catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
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

            if ($accion === "cantidadPendientes") {
                try {
                    $interesados = $servicio->contarInteresados();
                    $faltas = $servicio->contarFaltasPendientes();
                    $comprobantes = $servicio->contarComprobantesEnEspera();
                    $respuesta = [
                        'interesados' => $interesados,
                        'faltas' => $faltas,
                        'comprobantes' => $comprobantes
                    ];
                    respuesta($respuesta, "exito", 200);
                } catch (Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion === "getPagosPendientes"){ 
                try{
                    $comprobantes = $servicio->getComprobantesPendientes();
                    respuesta($comprobantes, "exito", 200);
                    }
                    catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }

            }

            if($accion == "getReunionesPendientes"){
                try {
                    $reuniones = $servicio->getReunionesPendientes();
                    respuesta($reuniones, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "getReunionesCompletadas"){
                try {
                    $reuniones = $servicio->getReunionesCompletadas();
                    respuesta($reuniones, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "getUsuariosAsistencias"){
                try {
                    $usuarios = $servicio->getUsuariosAsistencias();
                    respuesta($usuarios, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "getFaltasPendientes"){
                try {
                    $faltas = $servicio->getFaltasPendientes();
                    respuesta($faltas, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "getUnidadesLibres"){
                try {
                    $unidades = $servicio->getUnidadesLibres();
                    respuesta($unidades, "exito", 201);
                } catch(Exception $e) {
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            if($accion == "getUnidades"){
                try {
                    $unidades = $servicio->getUnidades();
                    respuesta($unidades, "exito", 201);
                } catch(Exception $e) {
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
