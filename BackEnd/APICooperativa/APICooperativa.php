    <?php
    require_once __DIR__ . '/RepositorioCooperativa.php';
    require_once __DIR__ . '/ServicioCooperativa.php'; 
    require_once __DIR__ .'/Modelos/UnidadHabitacional.php';
    require_once __DIR__ .'/Modelos/ComprobantePago.php';
    require_once __DIR__ .'/../BDConeccion.php';
    include __DIR__ .'/../Tokens.php';
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

        if(!validarToken(obtenerToken(), $conn)){
        respuesta("Token invalido", "error", 401);
        } 
    switch($metodo) {
        case "POST":

            if ($accion === "subirHoras"){
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
                $idComprobante = $_GET['id'];
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

            elseif ($accion === "editarHoras"){
                $datos = json_decode(file_get_contents('php://input'), true);
                $idHoras = $datos['idHoras'];
                $horas = $datos['horas'];
                $fecha = $datos['fecha'];
                if ($idHoras === null || $idHoras < 1) {
                    respuesta("El ID de horas es inválido.", "error", 400);
                }
                if ($horas === null || $horas < 1 || $horas > 12) {
                    respuesta("La cantidad de horas debe ser entre 1 y 12.", "error", 400);
                }

                try{
                    if($servicio->editarHoras($idHoras, $horas, $fecha)){
                        respuesta("horas editadas correctamente", "exito", 200);
                    }else{
                        respuesta("error al editar las horas", "error", 400);
                    }
                }catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            elseif ($accion === "borrarHoras"){
                $datos = json_decode(file_get_contents('php://input'), true);
                $idHoras = $datos['idHoras'];
                if ($idHoras === null || $idHoras < 1) {
                    respuesta("El ID de horas es inválido.", "error", 400);
                }
                try{
                    if($servicio->borrarHoras($idHoras)){
                        respuesta("horas borradas correctamente", "exito", 200);
                    }else{
                        respuesta("error al borrar las horas", "error", 400);
                    }
                }catch(Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }

            elseif ($accion === "ingresarIntegrantesFamiliares"){
                session_start();
                $idPersona = $_SESSION['id'];
                $datos = json_decode(file_get_contents('php://input'), true);
                $cantidadIntegrantes = $datos['cantidadIntegrantes'];
                $integrantes = $datos['integrantes'];
            
                try {
                    $ingresados = 0;
                    foreach ($integrantes as $idx => $integrante) {
                        try {
                            $nombre = $integrante['nombre']; 
                            $apelido = $integrante['apellido'];
                            $ci = $integrante['ci'];
                            $fechaNacimiento = $integrante['fechaNacimiento'];
                            $genero = $integrante['genero'];
                            $email = $integrante['email'];

                        $servicio->ingresarIntegrante($idPersona, $nombre, $apelido, $ci, $fechaNacimiento, $genero, $email);
                            $ingresados++;
                        } catch (Throwable $e) {
                            $error = $e->getMessage();
                        }
                    }
                    $total = count($integrantes);
                    if($ingresados < $total){
                        throw new Exception("Solo se ingresaron $ingresados de $total integrantes. Error: $error", 400);
                    }
                    respuesta("Integrantes ingresados con exito $ingresados/$total", "exito", 200);
                } catch (Exception $e){
                    respuesta($e->getMessage(), "error", $e->getCode());
                }
            }
            
            elseif ($accion === "subirFalta"){
                //traer las horas del front
                session_start();
                $idPersona = $_SESSION['id'];
                $datos = json_decode(file_get_contents('php://input'), true);
                $horas = $datos['horas'];
                $compensacion = $datos['compensacion'];
                $motivo = $datos['motivo'];
                if ($horas === null || $horas < 1 || $horas > 12) {
                    respuesta("Horas inválidas (1–12).", "error", 422);
                }
                $valoresValidos = ['Exoneracion', 'Pago Compensatorio'];
                if ($compensacion === null || !in_array($compensacion, $valoresValidos, true)) {
                    respuesta("Compensación inválida", "error", 422);
                }
                try{
                    if($servicio->cargarFalta($idPersona, $horas, $compensacion, $motivo)){
                        respuesta("Falta cargada correctamente", "exito", 200);
                    }else{
                        respuesta("error al cargar la falta", "error", 400);
                    }
                    }catch(Exception $e){
                        respuesta($e->getMessage(), "error", $e->getCode());
                    }
            }

            elseif ($accion === "eliminarIntegranteFamiliar") {
                $idIntegrante = json_decode(file_get_contents('php://input'), true);;
                try {
                    if ($idIntegrante === null || $idIntegrante < 1) {
                        respuesta("El ID integrante es inválido.", "error", 400);
                    }
                    if ($servicio->eliminarIntegranteFamiliar($idIntegrante)) {
                        respuesta("Integrante borrado correctamente", "exito", 200);
                    } else {
                        respuesta("Error al borrar el integrante", "error", 500);
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
                            'comprobantesPendientes' => $comprobantes['comprobantesPendientes'],

                            'porcentajeFaltas' => $servicio->getPorcentajeFaltas($id)

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
        
        if($accion === "getPagos"){ //LLamar en el pagos de usuario
            $id = $_GET['id'];
            if($id != null){
                $comprobantes = $servicio->getComprobantesPendientes($id);
                if($servicio->semanaActualExiste()){
                    
                }

                try{
                    $respuesta = [
                        //Total pagos atrasados
                        'pagosAtrasados' => $comprobantes['cantidadPagosPendientes'],
                        //total dinero de pagos atrasados?
                        'pagosAtrasadosDinero' => $comprobantes['montoPendiente'],
                        //precio pago mensual
                        'pagoMensual' => $comprobantes['pagoMensual'] ?? 0 , //para que no devuelva null al front
                        //todos los comprobantespendientes con todos sus datos para usar en la zona de hacer los pagos
                        'comprobantesPendientes' => $comprobantes['comprobantesPendientes']

                        //el ultimo va sin coma
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
                    try {
                        $horas = $servicio->getHorasHistorial($id);
                        $horasTrabajadas = $servicio->horasTrabajadasEstaSemana($id);
                        $horasObjetivo = $servicio->horasSemanales();
                        $horasRestantes = $horasObjetivo - $horasTrabajadas;
                        $porcentaje = 0.0;
                        if ($horasObjetivo > 0) {
                            $porcentaje = ($horasTrabajadas / $horasObjetivo) * 100.0;
                            if ($porcentaje < 0)   $porcentaje = 0;
                            if ($porcentaje > 100) $porcentaje = 100;
                        }
                        $porcentaje = round($porcentaje, 2); //redondeamos para no tener valores raros
                        $semanas = $servicio->getSemanas();
                        if ($horasRestantes < 0) $horasRestantes = 0;
                            $respuesta = [
                                'horasTrabajadas' => $horasTrabajadas,
                                'horasObjetivo' => $horasObjetivo,
                                'horasRestantes' => $horasRestantes,
                                'porcentaje' => $porcentaje,
                                'semanas' => $semanas,
                                'horas' => $horas
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

        


        if($accion === "getIntegrantesFamiliares"){
            $idPersona = $_GET['id'];
            if($idPersona != null){
                try{
                    $integrantesFamiliares = $servicio->getIntegrantesFamiliares($idPersona);
                    respuesta($integrantesFamiliares, "exito", 200);
                }
                catch(Exception $e) {
                respuesta($e->getMessage(), "error", $e->getCode());
            }

        } else {
            respuesta("No se encontro una id para buscar", "error", 0);
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

    if($accion == "getReunionesTerminadas"){
        try {
            $reuniones = $servicio->getReunionesTerminadas();
            respuesta($reuniones, "exito", 201);
        } catch(Exception $e) {
            respuesta($e->getMessage(), "error", $e->getCode());
        }
    }



        break;
    }

     function respuesta($mensaje, $estado, $codigo) {
        header('Content-Type: application/json');
        http_response_code($codigo);
        $respuesta = [
            "status"  => $estado,
            "message" => $mensaje
        ];
        echo json_encode($respuesta);
        exit;
    }