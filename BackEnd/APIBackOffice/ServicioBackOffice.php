<?php
    require_once __DIR__ .'../../APIUsuarios/Modelos/Usuario.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Persona.php'; 
    require_once __DIR__ .'../../APIUsuarios/Modelos/Admin.php';
    require_once __DIR__ .'../../APIUsuarios/Modelos/Interesado.php';
    require_once __DIR__ .'../../APICooperativa/Modelos/ComprobantePago.php';
    require_once __DIR__ .'../../APICooperativa/Modelos/UnidadHabitacional.php';

    Class ServicioBackOffice {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }


        public function cargarUsuario($idPersona){
            $fechaIngreso = date("Y-m-d"); //asigna la fecha del momento en el que se ejecuta el metodo
            $persona = $this->repositorio->getPersona($idPersona);
            $this->repositorio->cambiarRol($idPersona);
            $usuario = new Usuario($persona->getCi(), $persona->getEmail(), $persona->getTelefono(), $idPersona, $persona->getNombre(), $persona->getApellido(), $persona->getContraseña(), $persona->getRol(),
                    null, 
                    $fechaIngreso,
                    null
                );
            $this->repositorio->cargarUsuario($usuario);

        }

        public function cargarAdmin($ci, $email, $telefono, $nombre, $apellido, $contraseña, $nivelPermisos){
            $fechaIngreso = date("Y-m-d");
            if(!$this->repositorio->personaExisteConCI($ci)){
                    $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
                    $persona = new Persona($ci, $email, $telefono, null, $nombre, $apellido, $contraseña, "Admin");
                    $this->repositorio->cargarPersona($persona);
                    $idPersona = $this->repositorio->getIDPersonaConCi($ci);
                    $this->repositorio->cargarTelefono($idPersona, $telefono);
                    //Las cosas en null se asignan posteriormente en el backoffice ademas de cambiar el estado "En espera" etc
                    $admin = new Admin($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, "Admin", //datos heredados de persona
                    $nivelPermisos, null, $fechaIngreso); //datos de Admin
                    $this->repositorio->cargarAdmin($admin);
                    $this->repositorio->cargarUsuario($admin);
            } else {
                $idPersona = $this->repositorio->getIDPersonaConCi($ci);
                if($this->repositorio->adminExisteID($idPersona)){
                    throw new Exception("Este admin ya existe", 409);
                }
                $admin = new Admin($ci, $email, $telefono, $idPersona, $nombre, $apellido, $contraseña, "Admin", //datos heredados de persona
                $nivelPermisos, null, $fechaIngreso); //datos de Admin
                $this->repositorio->cargarAdmin($admin);
                $this->repositorio->cambiarRolAdmin($admin);
            }
        }


        public function getAdmin($id) {
            if (!$this->repositorio->adminExisteID($id)) {
                throw new Exception("El admin no existe", 404);
            } else {
                return $this->repositorio->getDatosAdmin($id);
            }
        
        }  

        public function rechazarInteresado($idPersona){
            if($this->repositorio->personaExiste($idPersona)){
                $this->repositorio->borrarTelefono($idPersona);
                $this->repositorio->borrarInteresado($idPersona);
                $this->repositorio->borrarPersona($idPersona);
                //opcional podria quedar un antecedente de que ya fue rechazado
            }else{
                throw new Exception("Esa persona no existe", 404);
            }
        }

        public function asignarPagoInicial($idPersona, $montoPagoInicial){
            $this->repositorio->setMontoPagoInicial($idPersona, $montoPagoInicial);
        }

        public function asignarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista){
            $this->repositorio->cargarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista);
            $this->repositorio->revisarEstado($idPersona, "Estado_entrevista", "Pendiente");
        }


        public function rechazarEstado($idPersona, $campo) {
            $camposValidos = ["Estado_entrevista", "Estado_antecedentes", "Estado_pago_inicial"];
            //hacemos esto para evitar una posible inyeccion sql desde el javscript
            if (!in_array($campo, $camposValidos)) {
                return false;
            }
        
            $this->repositorio->revisarEstado($idPersona, $campo, "Rechazado");
            return true;
        }

        public function aprobarEstado($idPersona, $campo) {
            $camposValidos = ["Estado_entrevista", "Estado_antecedentes", "Estado_pago_inicial"];
            //hacemos esto para evitar una posible inyeccion sql desde el javscript
            if (!in_array($campo, $camposValidos)) {
                return false;
            }
        
            $this->repositorio->revisarEstado($idPersona, $campo, "Aprobado");
            return true;
        }

        public function contarInteresados() {
            $resultado = $this->repositorio->soloInteresados();
            $cantidadInteresados = mysqli_num_rows($resultado);
        
            return $cantidadInteresados;
        }

        public function getInteresados(){
            $interesadosObj = $this->repositorio->getInteresados();
            $interesadoArrayAsociativo = [];
            foreach($interesadosObj as $interesado){
                
                $interesadoArrayAsociativo[$interesado->getIdPersona()] = $interesado->toArray();
            }
            return $interesadoArrayAsociativo;
        }

        public function getUsuarios(){
            $usuariosObj = $this->repositorio->getUsuarios();
            $usuariosArrayAsociativo = [];
            foreach($usuariosObj as $usuario){
                $horasTrabajadas = $this->repositorio->getHorasTrabajadasPorUsuario($usuario->getIdPersona());
                $usuariosArrayAsociativo[$usuario->getIdPersona()] = $usuario->toArray($horasTrabajadas);
            }


            return $usuariosArrayAsociativo;
        }
        public function subirFoto($nombreArchivo, $nombreTemp) {
            session_start();
            $rutaCarpeta = "../../Recursos/FotosPerfil/";
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nuevoNombre = $_SESSION['id'] . '.' . $extension;
            $rutaFoto = $rutaCarpeta . $nuevoNombre;
            $nombreViejo = $this->repositorio->getFoto($_SESSION['id']);
            $rutaFotoVieja = $rutaCarpeta . $nombreViejo;
            //Opcional a futuro, podriamos agregar algo que verifique las extensiones para que no nos suban cualquier cosa y 
            //sobrecarguen el servidor ademas de verificador de tama;o o pixeles para que no sean muy pesados los archivos
            if (!empty($nombreViejo) && file_exists($rutaFotoVieja)) {
                unlink($rutaFotoVieja);
                $this->repositorio->borrarFoto($_SESSION['id']);
            }

            if (move_uploaded_file($nombreTemp, $rutaFoto)) {
                $this->repositorio->subirFoto($_SESSION['id'], $nuevoNombre);
                return true;
            } else {
                throw new Exception("No se pudo cargar el archivo", 500);
            }
        }
        //API Cooperativa
        public function crearPagoMensual($montoPagoMensual){
            $IDUsuariosArray = $this->repositorio->getIDUsuarios();
            $fecha = date("Y-m-d");
            $this->repositorio->crearPagoMensual($montoPagoMensual, $IDUsuariosArray, $fecha);
        }

        public function crearPagoPersonalizado($motivoPago, $ci , $montoPagoPersonalizado){
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            $fecha = date("Y-m-d");
            $this->repositorio->crearPagoPersonalizado($idPersona, $motivoPago, $montoPagoPersonalizado, $fecha);
        }

        public function rechazarPago($idComprobantePago){
            $pagoViejo = $this->repositorio->getComprobantePago($idComprobantePago);
            $this->repositorio->rechazarPago($idComprobantePago);
            $this->repositorio->crearPagoPersonalizado($pagoViejo['idPersona'], "Pago Rechazado", $pagoViejo['montoPago'], $pagoViejo['fecha']);
        }

        public function aprobarPago($idComprobantePago){
            $this->repositorio->aprobarPago($idComprobantePago);
        }

        public function asignarUnidadHabitacional($ci, $idUnidadHabitacional) {
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            if(!$this->repositorio->unidadHabitacionalAsignada($idUnidadHabitacional)){
                $this->repositorio->asignarUnidadHabitacional($idPersona, $idUnidadHabitacional);
            }
        }


        public function getComprobantesPendientes(){
            $comprobantesObj = $this->repositorio->getPagosPendientes();
            $comprobantesAsociativo = [];
            $comprobantesPendientes = 0;
            if (count($comprobantesObj) > 0) {
            foreach ($comprobantesObj as $comprobante) {
                $comprobantesPendientes++;
                $usuario = $this->repositorio->getUsuariosPagos($comprobante->getIdPersona());
                $comprobantesAsociativo[] = [
                    'fecha' => $comprobante->getMes(),
                    'usuario' => $usuario['Nombre'],
                    'ci' => $usuario['CI'],
                    'motivo' => $comprobante->getMotivoPago(),
                    'monto' => $comprobante->getMonto(),
                    'estado' => $comprobante->getEstadoPago(),
                    'foto' => $comprobante->getFoto(),
                    'id' => $comprobante->getIdComprobantePago()
                ];
            }
            } else {
                return ['comprobantesPendientes' => 0]; //si no hay pagos pendientes devuelvo 0
            }

            return [
                'comprobantesPendientes' => $comprobantesPendientes,
                'comprobantes' => $comprobantesAsociativo
            ];
        }
        

        public function getReunionesCompletadas(){
            $filas = $this->repositorio->getReunionesCompletadas();
        
            if (empty($filas)) {
                return ['reunionesCompletadas' => 0, 'reuniones' => []];
            }
        
            $reuniones = [];
            foreach ($filas as $r) {
                $asistencias = $this->repositorio->getAsistenciasPorReunion($r['ID_Reunion']);

                // Cuenta en PHP
                $presentes = 0;
                $total = count($asistencias);
                foreach ($asistencias as $a) {
                    if ($a == 1) $presentes++;
                }

                $reuniones[] = [
                    'idReunion'     => $r['ID_Reunion'],
                    'titulo'        => $r['Nombre'],
                    'descripcion'   => $r['Descripcion'],
                    'fecha'         => $r['Fecha'],
                    'hora'          => $r['Hora'],
                    'lugar'         => $r['Lugar'],
                    'tipoDeReunion' => $r['Tipo_Reunion'],
                    'estado'        => $r['Estado_Reunion'],
                    'asistencias'   => "{$presentes}/{$total}"
                ];
            }
        
            return [
                'reunionesCompletadas' => count($reuniones),
                'reuniones'           => $reuniones
            ];
        }
        public function getHorasTrabajadasUsuarios(){
            $usuariosHoras = $this->repositorio->getHorasTrabajadasUsuarios();
            $resultado = [];

            foreach ($usuariosHoras as $usuario) {
                $resultado[$usuario['ID_Persona']] = [
                    'horasTrabajadas'=> $usuario['Total_Horas']
                ];
            }

            return $resultado;
        }


        public function getReunionesPendientes() {
            $filas = $this->repositorio->getReunionesPendientes();

            if (empty($filas)) {
                return ['reunionesPendientes' => 0, 'reuniones' => []];
            }

            $reuniones = [];
            foreach ($filas as $r) {
                $asistencias = $this->repositorio->getAsistenciasPorReunion($r['ID_Reunion']);

                // Cuenta en PHP
                $presentes = 0;
                $total = count($asistencias);
                foreach ($asistencias as $a) {
                    if ($a == 1) $presentes++;
                }

                // Arma el formato final
                $reuniones[] = [
                    'idReunion'     => $r['ID_Reunion'],
                    'titulo'        => $r['Nombre'],
                    'descripcion'   => $r['Descripcion'],
                    'fecha'         => $r['Fecha'],
                    'hora'          => $r['Hora'],
                    'lugar'         => $r['Lugar'],
                    'tipoDeReunion' => $r['Tipo_Reunion'],
                    'estado'        => $r['Estado_Reunion'],
                    'asistencias'   => "{$presentes}/{$total}"
                ];
            }

            return [
                'reunionesPendientes' => count($reuniones),
                'reuniones'           => $reuniones
            ];
        }

        
        


        public function crearUnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones){
            $unidadHabitacional = new UnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones);
            if(!$this->repositorio->unidadHabitacionalExiste($numeroPuerta, $pasillo)){
                $this->repositorio->crearUnidadHabitacional($unidadHabitacional);
            } else{
                throw new Exception("Esta unidad ya esta registrada", 409);
            }
        }

        public function crearUnidadHabitacionalConCI($numeroPuerta, $pasillo, $ci){
            $idPersona = $this->repositorio->getIDPersonaConCi($ci);
            if (!$idPersona) {
                throw new Exception("No se encontró la persona con CI {$ci}", 404);
            }
            $integrantesFamiliares = $this->repositorio->getIntegrantesFamiliares($idPersona) ?? [];
            $integrantes = 0;
            $menoresAdolescentes = [];
            $masculino = false;
            $femenino  = false;
            $habitacionesEspeciales = 0;
        
            foreach($integrantesFamiliares as $integrante) {
                $fechaNac = $integrante['FechaNacimiento'];
                $generoRaw = $integrante['Genero'];
                $idIntegrante = $integrante['ID_Integrante'];
                $edad = null;
                try {
                    $cumple = new DateTime($fechaNac);
                    $hoy    = new DateTime('today');
                    $edad   = $cumple->diff($hoy)->y;
                } catch (Throwable $e) {
                    $edad = null;
                }
                if ($edad !== null && $edad >= 18) {
                    $integrantes++; // mayor
                } elseif ($edad !== null && $edad >= 11 && $edad <= 17) {
                    // Guardamos adolescentes y marcamos sexo exacto
                        $menoresAdolescentes[$idIntegrante] = [
                            'genero' => $generoRaw,
                            'edad'   => $edad
                        ];
                    if ($generoRaw === 'Masculino') $masculino = true;
                    if ($generoRaw === 'Femenino')  $femenino  = true;
                }
                // <11 o edad desconocida: no afectan la regla actual
            }
            // Si hay adolescentes de ambos sexos, +1 habitacion, si son 3 nenes y 1 nena igualmente es una habitacion cu
            if ($masculino && $femenino) {
                $habitacionesEspeciales++;
            }
            $integrantesTotal = $integrantes + 1; //agregamos el due;o de la cuenta

            if ($integrantesTotal <= 2) {
                $habitacionesBase = 1;
            } elseif ($integrantesTotal <= 4) {
                $habitacionesBase = 2;
            } elseif ($integrantesTotal < 6) {
                $habitacionesBase = 3;
            } else {
                $habitacionesBase = 4;
            }
        
            //Las unidades habitacionales tienen un minimo de 1 y un maximo de 4
            $habitaciones = $habitacionesBase + $habitacionesEspeciales;
            if ($habitaciones < 1) $habitaciones = 1;
            if ($habitaciones > 4) $habitaciones = 4;

            if(!$this->repositorio->unidadHabitacionalExiste($numeroPuerta, $pasillo)){
                $cantidadHabitaciones = $habitaciones;
                $unidadHabitacional = new UnidadHabitacional($numeroPuerta, $pasillo, $cantidadHabitaciones);
                $this->repositorio->crearUnidadHabitacional($unidadHabitacional);
                $idUnidadHabitacional = $this->repositorio->getIDUnidadHabitacional($numeroPuerta, $pasillo);
                $this->repositorio->asignarUnidadHabitacional($idPersona, $idUnidadHabitacional);
                $this->repositorio->unidadHabitacionalBoolean($idPersona);
            }else{
                throw new Exception("Esta unidad ya esta registrada", 409);
            }
        }

        public function crearReunion($titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion){
            $this->repositorio->crearReunion($titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion);
        }
        
        public function completarReunion($idReunion) {
            $this->repositorio->completarReunion($idReunion);
        }
        
        public function eliminarReunion($idReunion) {
            $this->repositorio->eliminarReunion($idReunion);
        }

        public function editarReunion($idReunion, $titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion){
            $this->repositorio->editarReunion($idReunion, $titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion);
        }

        public function getUsuariosAsistencias(){
            $filas = $this->repositorio->getUsuariosAsistencias();
        
            if (empty($filas)) {
                return ['usuariosEncontrados' => 0, 'usuarios' => []];
            }
        
            $usuarios = [];
            foreach ($filas as $r) {
                    $usuarios[] = [
                    'idPersona' => $r['idPersona'],
                    'Nombre' => $r['Nombre'],
                    'Apellido' => $r['Apellido'],
                    'ci' => $r['ci'],
                    'foto' => $r['foto'] ?? null,
                    'idUnidad' => $r['idUnidad'] ?? null,
                    'nroPuerta' => $r['nroPuerta'] ?? null,
                    'pasillo' => $r['pasillo'] ?? null,
                ];
            }
        
            return [
                'usuariosEncontrados' => count($usuarios),
                'usuarios'           => $usuarios
            ];
        }

        public function pasarAsistencia($idReunion, $asistencias){
            $this->repositorio->cargarAsistencia($idReunion, $asistencias);
        }
        
        public function getFaltasPendientes() {
            $faltas = $this->repositorio->getFaltasPendientes();

            if (empty($faltas)) {
                return [
                    'faltasPendientes' => 0,
                    'faltas'           => []
                ];
            }

            $ids = [];
            foreach ($faltas as $f) {
                $id = $f['ID_Persona'];
                $existe = false;

                foreach ($ids as $guardado) {
                    if ($guardado == $id) {
                        $existe = true;
                        break;
                    }
                }

                if (!$existe) {
                    $ids[] = $id;
                }
            }

            $usuarios = $this->repositorio->getUsuariosConID($ids);

            $respuesta = [];
            foreach ($faltas as $f) {
                $idPersona = $f['ID_Persona'];
                $u = isset($usuarios[$idPersona]) ? $usuarios[$idPersona] : null;

                $respuesta[] = [
                    'idFalta' => $f['ID_Falta'],
                    'idPersona' => $f['ID_Persona'],
                    'idSemanaTrabajo' => $f['ID_Semana_trabajo'],
                    'motivo' => $f['Motivo_falta'],
                    'horasExonerar' => $f['Horas_solicitadas'],
                    'fecha'=> $f['Fecha'],
                    'tipoCompensacion' => $f['Tipo_falta'],
                    'nombre' => $u['Nombre'],
                    'apellido' => $u['Apellido'],
                    'cedula' => $u['CI'],
                    'nroPuerta' => $u['Numero_puerta'] ?? null,
                    'pasillo' => $u['Pasillo'] ?? null,
                    'foto' => $u['Foto'] ?? null,
                ];
            }

            return [
                'faltasPendientes' => count($respuesta),
                'faltas'           => $respuesta
            ];
        }

        public function rechazarFalta($idFalta){
            $this->repositorio->rechazarFalta($idFalta);
        }

        public function aprobarFalta($idFalta){
            $this->repositorio->aprobarFalta($idFalta);
        }

        public function getFalta($idFalta){
            $datos = $this->repositorio->getFalta($idFalta);
            return $datos;
        }

        public function acreditarHoras($idPersona, $horas, $fechaHoras, $idSemana){
            $this->repositorio->cargarHoras($idPersona, $horas, $fechaHoras, $idSemana);
        }

        public function crearPagoFalta($idPersona, $fecha, $monto){
            $this->repositorio->crearPagoPersonalizado($idPersona, "Pago Compensatorio por Horas Faltadas", $monto, $fecha);
        }


    }