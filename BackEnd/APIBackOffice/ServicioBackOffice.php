<?php
    Class ServicioBackOffice {
        //no se especifica el tipo porque cada servicio tiene un repositorio
        private $repositorio;
         public function __construct($repositorio) {
            $this->repositorio = $repositorio;
        }


        public function cargarUsuario($idPersona){
            $fechaIngreso = date("Y-m-d"); //asigna la fecha del momento en el que se ejecuta el metodo
            $persona = $this->repositorio->getPersona($idPersona);
            $usuario = new Usuario($persona->getCi(), $persona->getEmail(), $idPersona, $persona->getNombre(), $persona->getApellido(), $persona->getContraseña(), "Usuario",//Asigna el rol Usuario
                    null, 
                    null,
                    $fechaIngreso
                );
            $this->repositorio->cargarUsuario($usuario);
        }



        public function cargarAdmin($idPersona, $nivelPermisos){
            $persona = $this->repositorio->getPersona($idPersona);
            $admin = new Admin(
                    $persona->getCi(),
                    $persona->getEmail(),
                    $persona->getIdPersona(),
                    $persona->getNombre(),
                    $persona->getApellido(),
                    $persona->getContraseña(),
                    "Admin",          //Asigna el rol Admin
                    $nivelPermisos
                );
            $this->repositorio->cargarAdmin($admin);
        }

        public function rechazarInteresado($idPersona){
            if($this->repositorio->personaExiste($idPersona)){

                $this->repositorio->borrarInteresado($idPersona);
                $this->repositorio->borrarPersona($idPersona);

            }else{
                throw new Exception("Esa persona no existe", 404);
            }
        }

        public function asignarPagoInicial($idPersona, $montoPagoInicial){
            $this->repositorio->setMontoPagoInicial($idPersona, $montoPagoInicial);
            $this->repositorio->revisarPagoInicial($idPersona, "Pendiente");
        }

        public function asignarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista){
            $this->repositorio->cargarEntrevista($idPersona, $fechaEntrevista, $horaEntrevista);
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
            $resultado = $this->repositorio->obtenerTodosLosInteresados();
            $cantidadInteresados = mysqli_num_rows($resultado);
        
            $ids = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $ids[] = $fila['ID_Persona'];
            }
        
            return ['Interesados' => $cantidadInteresados,'ID' => $ids];
        }

        public function Interesados() {
            $resultado = $this->repositorio->getInteresados();
            $interesados = [];

            foreach ($resultado as $fila) {
                $interesados[] = [
                'id_persona' => $fila['ID_Persona'],
                'antecedentes' => $fila['Antecedentes'],
                'estado_entrevista' => $fila['Estado_entrevista'],
                'estado_antecedentes' => $fila['Estado_antecedentes'],
                'fecha_entrevista' => $fila['Fecha_entrevista'],
                'hora_entrevista' => $fila['Hora_entrevista'],
                'pago_inicial' => $fila['Pago_inicial'],
                'estado_pago_inicial' => $fila['Estado_pago_inicial'],
                'monto_pago_inicial' => $fila['Monto_pago_inicial']
                ];
            }
            return $interesados;

        }




    }
?>