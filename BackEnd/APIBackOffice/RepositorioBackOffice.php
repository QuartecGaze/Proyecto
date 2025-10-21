<?php
require_once __DIR__ .'../../APIUsuarios/Modelos/Usuario.php';
require_once __DIR__ .'../../APIUsuarios/Modelos/Persona.php'; 
require_once __DIR__ .'../../APIUsuarios/Modelos/Admin.php';
require_once __DIR__ .'../../APIUsuarios/Modelos/Interesado.php';
require __DIR__ .'/../Consultas.php';
    Class RepositorioBackOffice {
        
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        //Persona
        public function personaExiste($id){
            $consulta = "
            SELECT * FROM Persona WHERE ID_Persona=?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$id]);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
        }

        public function personaExisteConCI($ci){
            $consulta = "SELECT * FROM Persona WHERE CI = ?";
            $resultado = consulta($this->conn, $consulta, "s", [$ci]);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            } else {
                return false;
            }
        }
        
        public function borrarTelefono($id){
            $consulta = "
                DELETE FROM Numero_de_telefono WHERE ID_Persona=?
            ";
            consulta($this->conn, $consulta, "i", [$id]);
        }

        public function borrarPersona($id){
            $consulta = "
                DELETE FROM Persona WHERE ID_Persona=?
            ";
            consulta($this->conn, $consulta, "i", [$id]);
        }

        public function getPersona($id){
            $consulta = "
                SELECT * FROM Persona WHERE ID_Persona=?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$id]);
            $fila = mysqli_fetch_assoc($resultado);
            $telefonos = $this->getTelefonosPersona($id);
            $persona = new Persona($fila['CI'], $fila['Email'], $telefonos ,$id, $fila['Nombre'], $fila['Apellido'], $fila['Contraseña'], $fila['Rol']);
            return $persona;
        }

        public function getTelefonosPersona($idPersona) {
            $consulta = "
                SELECT Telefono FROM numero_de_telefono WHERE ID_Persona = ?
            ";
    
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);

            $telefonos = [];

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $telefonos[] = $fila['Telefono'];
            }

            return $telefonos;
        }
        
        


        //Interesado
        public function Interesados() {
            //trae solo el id persona para no traer a todo el interesado y poder lekear datos sin querer
            $consulta = "
                SELECT ID_Persona 
                FROM Persona 
                WHERE Rol = 'Interesado'
            ";
        
            $resultado = consulta($this->conn, $consulta);
        
            if (!$resultado) {
                throw new Exception("Error al obtener interesados", 500);
            }
        
            return $resultado; // devuelve el resultado crudo (el cursor)
        }
        
        public function borrarInteresado($id){
            $consulta = "
                DELETE FROM Interesado WHERE ID_Persona=?
            ";
            consulta($this->conn, $consulta, "i", [$id]);
        }
        

        public function cargarEntrevista($id, $fechaEntrevista, $horaEntrevista){
            $consulta = "
                UPDATE Interesado
                SET Fecha_entrevista = ?, Hora_entrevista = ?
                WHERE ID_Persona = ?
            ";
            consulta($this->conn, $consulta, "ssi", [$fechaEntrevista, $horaEntrevista, $id]);
        }

        public function revisarEstado($id, $tipo, $estado){
            $consulta = "
                UPDATE Interesado
                SET $tipo = ?
                WHERE ID_Persona = ?
            ";
            consulta($this->conn, $consulta, "si", [$estado, $id]);
        }

        public function setMontoPagoInicial($id, $montoPagoInicial){
            $consulta = "
                UPDATE Interesado
                SET Monto_pago_inicial = ?
                WHERE ID_Persona = ?
            ";
            consulta($this->conn, $consulta, "di", [$montoPagoInicial, $id]); 
        }
 
        public function getInteresados(){
            $consulta = "
            SELECT * 
            FROM Persona 
            JOIN Interesado ON Persona.ID_Persona = Interesado.ID_Persona 
            WHERE Rol = 'Interesado';
            
                ";
            $resultado = consulta($this->conn, $consulta); 
           
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);
                $interesados[] = new Interesado(
                $fila['CI'], 
                $fila['Email'], 
                $telefonos,
                $fila['ID_Persona'], 
                $fila['Nombre'], 
                $fila['Apellido'], 
                $fila['Contraseña'], 
                $fila['Rol'],
                $fila['Antecedentes'], 
                $fila['Estado_antecedentes'], 
                $fila['Estado_entrevista'], 
                $fila['Fecha_entrevista'], 
                $fila['Hora_entrevista'], 
                $fila['Pago_inicial'], 
                $fila['Estado_pago_inicial'], 
                $fila['Monto_pago_inicial']
            );
            }
            return $interesados;
            }

        public function soloInteresados(){
            $consulta = "
                SELECT ID_Persona
                FROM Persona
                WHERE Rol = 'Interesado';
            ";
            $resultado = consulta($this->conn, $consulta); 
            return $resultado;
        }    
        //Usuario
        public function subirFoto($id, $nombre) {
        $consulta = "
            UPDATE Admin
            SET Foto = ?
            WHERE ID_Persona = ?
        ";
        consulta($this->conn, $consulta, "si", [$nombre, $id]);
        }

        public function getFoto($id) {
            $consulta = "
                SELECT Foto FROM Admin
                WHERE ID_Persona = ?
            ";

            $resultado = consulta($this->conn, $consulta, "i", [$id]);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                return $fila['Foto'] === null ? null : $fila['Foto']; //verifica que la foto no sea null, porque en la bd se carga como default null
            }
            return null;
        }

        public function borrarFoto($id) {
            $consulta = "
                UPDATE Admin
                SET Foto = NULL
                WHERE ID_Persona = ?
            ";
            return consulta($this->conn, $consulta, "i", [$id]);
        }

        public function cargarUsuario($Usuario) {
            $idPersona = $Usuario->getIdPersona();
            $fechaIngreso = $Usuario->getFechaIngreso();
            $consulta = "
                INSERT INTO Usuario (ID_Persona, Fecha_ingreso)
                VALUES (?,?)
            ";
            consulta($this->conn, $consulta, "is", [$idPersona, $fechaIngreso]);
        }
        
        public function borrarUsuario($id){
            $consulta = "
                DELETE FROM Usuario WHERE ID_Persona=?
            ";
            consulta($this->conn, $consulta, "i", [$id]);
        }
        
        public function cambiarRol($id){
            $consulta = "
            UPDATE Persona
            SET Rol = 'Usuario'
            WHERE ID_Persona = ?
        ";
        consulta($this->conn, $consulta, "i", [$id]);
        }

        //CRUD Admin

        public function cargarAdmin($admin){
            $idPersona = $admin->getIdPersona();
            $nivelPermisos = $admin->getNivelPermisos();
            $fechaIngreso = $admin->getFechaIngreso();
            $consulta = "
                INSERT INTO Admin (ID_Persona, Nivel_permisos, Fecha_ingreso) 
                VALUES (?,?,?)
            ";
            consulta($this->conn, $consulta, "iis", [$idPersona, $nivelPermisos, $fechaIngreso]);
    
        }
        
        
         public function borrarAdmin($id){
            $consulta = "
                DELETE FROM Admin WHERE ID_Persona=?
            ";
            consulta($this->conn, $consulta, "i", [$id]);
        }

        public function adminExisteID($id){
            $consulta = "SELECT * FROM Admin WHERE ID_Persona = ?";
            $resultado = consulta($this->conn, $consulta, "i", [$id]);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else
            {
                return false;
            }
            
        }
        public function cambiarRolAdmin($admin) {
             $idPersona = $admin->getIdPersona();

             $consulta = "
                UPDATE Persona
                SET Rol = 'Admin'
                WHERE ID_Persona = ?
            ";

            consulta($this->conn, $consulta, "i", [$idPersona]);
        }

        public function getDatosAdmin($id) {
            $consulta = "
                SELECT * FROM Persona 
                JOIN Admin ON Persona.ID_Persona = Admin.ID_Persona
                WHERE Persona.ID_Persona = ?;
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$id]); 
            $fila = mysqli_fetch_assoc($resultado);
            $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);
            $admin = new Admin(
            $fila['CI'], 
            $fila['Email'], 
            $telefonos,
            $fila['ID_Persona'], 
            $fila['Nombre'], 
            $fila['Apellido'], 
            $fila['Contraseña'], 
            $fila['Rol'],
            $fila['Nivel_permisos'],
            $fila['Foto'],
            $fila['Fecha_ingreso']
            );

        return $admin;
        }

        //APICooperativa
        public function getIDUsuarios() {
            $consulta = "
                SELECT p.ID_Persona
                FROM Persona p
                WHERE p.Rol = 'Usuario'

                UNION

                SELECT p.ID_Persona
                FROM Persona p
                INNER JOIN Usuario u ON p.ID_Persona = u.ID_Persona
                WHERE p.Rol = 'Admin'
            ";

            $resultado = consulta($this->conn, $consulta);

            $ids = [];
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $ids[] = $fila['ID_Persona'];
                }
            }

            return $ids;
        }



        public function crearPagoMensual($montoPagoMensual, $IDUsuariosArray, $fecha) {
            $totalUsuarios = count($IDUsuariosArray);
            $valoresConsulta = '';
            for ($i = 0; $i < $totalUsuarios; $i++) {
                $idPersona = $IDUsuariosArray[$i];

                if($i > 0){ //aca se a;ade una , al final de cada iteracion para no romper la consulta el operador .= a;ade al final es como el mas += que usabamos en java
                    $valoresConsulta .= ','; 
                }
                $valoresConsulta .= "($idPersona , 'Aportes mensuales' , 'En espera', '$fecha', $montoPagoMensual)"; 
            } //haciendo esto nos evitamos sobrecargar el servidor haciendo 100 consultas para 100 usuarios, lo hacemos todo en una un insert gigante como el de cuando creamos la tabla traducciones
            $consulta = "
                    INSERT INTO Comprobante_pago (ID_Persona, Motivo_pago, Estado_pago, Mes, Monto)
                    VALUES $valoresConsulta;
                ";
            consulta($this->conn, $consulta); 
        }

        public function getIDPersonaConCi($ci){
            $consulta = "
                SELECT ID_Persona FROM Persona WHERE CI = ?
            ";
            $resultado = consulta($this->conn, $consulta, "s", [$ci]);
            if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $idPersona = $fila['ID_Persona']; 
            return $idPersona;
            }else{
                throw new Exception("No se encontro una persona con la CI $ci");
            }
        }

        public function crearPagoPersonalizado($idPersona, $motivoPago, $montoPagoPersonalizado, $fecha) {
            $consulta = "
                INSERT INTO Comprobante_pago (ID_Persona, Motivo_pago, Estado_pago, Mes, Monto)
                VALUES (? , ? , 'En Espera' , ? , ?);
            ";
            consulta($this->conn, $consulta, "issd", [$idPersona, $motivoPago, $fecha, $montoPagoPersonalizado]); 
        }


        public function getComprobantePago($idComprobante) {
            $consulta = "
                SELECT ID_Persona, Monto, Mes
                FROM   Comprobante_pago
                WHERE  ID_Comprobante_pago = ?
            ";

            $resultado = consulta($this->conn, $consulta, "i", [$idComprobante]);

            if (mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                return [
                    'idPersona' => $fila['ID_Persona'],
                    'montoPago' => $fila['Monto'],
                    'fecha'     => $fila['Mes'],
                ];
            } else {
                throw new Exception("No se encontró un comprobante con el ID $idComprobante");
            }
        }
        
        public function rechazarPago($idComprobantePago){
            $consulta = "
                UPDATE Comprobante_pago
                SET    Estado_pago = 'Rechazado'
                WHERE  ID_Comprobante_pago = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idComprobantePago]);
        }

        public function aprobarPago($idComprobantePago){
            $consulta = "
                UPDATE Comprobante_pago
                SET    Estado_pago = 'Aprobado'
                WHERE  ID_Comprobante_pago = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idComprobantePago]);
        }

        public function unidadHabitacionalAsignada($idUnidadHabitacional) {
            $consulta = "
                SELECT ID_Persona
                FROM   Unidad_Habitacional
                WHERE  ID_Unidad_Habitacional = ?
            ";
            $resultado = consulta($this->conn, $consulta, "i", [$idUnidadHabitacional]);
            $dato = mysqli_fetch_assoc($resultado);
            $dato['ID_Persona'];
            if($dato['ID_Persona'] == null){
                return false;
            }else{
                throw new Exception('La unidad habitacional ya esta asignada '. $dato['ID_Persona'], 409);
            }
        }

        public function unidadesHabitacionalesSinUsuario(){
            $consulta = "
                SELECT *
                FROM   Unidad_Habitacional
                WHERE  ID_Persona IS NULL
            ";
            $resultado = consulta($this->conn, $consulta);
            $UnidadesHabitacionalesSinAsignar = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $UnidadesHabitacionalesSinAsignar[$fila['ID_Unidad_Habitacional']] = $fila;
            }
            return $UnidadesHabitacionalesSinAsignar;
        }

        public function usuariosSinUnidadHabitacional(){
            $consulta = "
                SELECT *
                FROM   Unidad_Habitacional
                WHERE  ID_Persona IS NULL
            ";
            $resultado = consulta($this->conn, $consulta);
            $UsuariosSinUnidad = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $UsuariosSinUnidad[$fila['ID_Persona']] = $fila;
            }
            return $UsuariosSinUnidad;
        }


        public function getPagosPendientes(){
            $consulta = "
                SELECT * 
                FROM comprobante_pago  
                WHERE Estado_pago = 'Pendiente';
            ";
            $resultado = consulta($this->conn, $consulta); 
            $comprobantesPendientes = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $comprobantesPendientes[] = new ComprobantePago(
                $fila['ID_Persona'], 
                $fila['Motivo_pago'], 
                $fila['Estado_pago'], 
                $fila['Mes'], 
                $fila['Foto'], 
                $fila['Monto'], 
                $fila['ID_Comprobante_pago']
            );
            }
            return $comprobantesPendientes;
            }

            public function getUsuariosPagos($idPersona){
                $consulta = "
                    SELECT CI, Nombre, Apellido
                    FROM persona
                    WHERE ID_Persona = ?
                ";
                $resultado = consulta($this->conn, $consulta, "i", [$idPersona]); 
                $fila = mysqli_fetch_assoc($resultado);
                return [
                    'CI' => $fila['CI'],
                    'Nombre' => $fila['Nombre'] . " " . $fila['Apellido']
                ];
            }
            
            public function cargarPersona($persona){
                $ci = $persona->getCi(); 
                $email = $persona->getEmail();
                $contraseña = $persona->getContraseña();
                $rol = $persona->getRol();
                $nombre = $persona->getNombre();
                $apellido = $persona->getApellido();
                $consulta = "
                    INSERT INTO Persona (CI, Email, Contraseña, Rol, Nombre, Apellido) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ";
                consulta($this->conn, $consulta, "ssssss", [$ci, $email, $contraseña, $rol, $nombre, $apellido]);
            }

            public function cargarTelefono($id, $telefono){
            $consulta = "
                INSERT INTO numero_de_telefono (ID_Persona, Telefono)
                VALUES (?, ?)
                ";
            consulta($this->conn, $consulta, "is", [$id, $telefono]);
        }
            
        public function crearUnidadHabitacional($unidadHabitacional){
            $numeroPuerta = $unidadHabitacional->getNumeroPuerta();
            $pasillo = $unidadHabitacional->getPasillo();
            $cantidadHabitaciones = $unidadHabitacional->getCantidadHabitaciones();
            $consulta = "
                INSERT INTO unidad_habitacional (Numero_puerta, Pasillo, Estado_unidad, Cantidad_habitaciones) 
                VALUES (?, ?, 'En espera', ?)
            ";
            consulta($this->conn, $consulta, "isi", [$numeroPuerta, $pasillo, $cantidadHabitaciones]);
        }
        
        public function unidadHabitacionalExiste($numeroPuerta, $pasillo){
            $consulta = "
                SELECT * FROM unidad_habitacional 
                WHERE Numero_puerta = ? 
                AND Pasillo = ?
                ";
            $resultado = consulta($this->conn, $consulta, "is", [$numeroPuerta, $pasillo]);
            if(mysqli_num_rows($resultado) > 0){
                return true;
            }else{
                return false;
            }
        }

        public function getIntegrantesFamiliares($idPersona){
            $consulta = "
                SELECT * 
                FROM integrante_familiar
                WHERE ID_Persona = ?
                ";
            $resultado = consulta($this->conn, $consulta, "i", [$idPersona]); 
            $integrantesFamiliares = [];
            while ($fila = $resultado->fetch_assoc()) {
                $integrantesFamiliares[] = [
                    'ID_Integrante'   => $fila['ID_Integrante'],
                    'ID_Persona'      => $fila['ID_Persona'],
                    'Nombre'          => $fila['Nombre'],
                    'Apellido'        => $fila['Apellido'],
                    'CI'              => $fila['CI'],
                    'FechaNacimiento' => $fila['FechaNacimiento'],
                    'Telefono'        => $fila['Telefono'],
                    'Genero'          => $fila['Genero']
                ];
            //agregar parentesco si hace falta
            }
            return $integrantesFamiliares;
            }
            
            public function getIDUnidadHabitacional($numeroPuerta, $pasillo){
                $consulta = "
                    SELECT ID_Unidad_habitacional FROM unidad_habitacional 
                    WHERE Numero_puerta = ? 
                    AND Pasillo = ?
                ";
                $resultado = consulta($this->conn, $consulta, "is", [$numeroPuerta, $pasillo]);
                $idUnidad = null;
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $idUnidad = $fila['ID_Unidad_habitacional'];
                }
    
                return $idUnidad;
            }

            public function asignarUnidadHabitacional($idPersona, $idUnidadHabitacional){
                $consulta = "
                    UPDATE unidad_habitacional
                    SET ID_Persona = ?
                    WHERE ID_Unidad_habitacional = ?
                ";
            consulta($this->conn, $consulta, "ii", [$idPersona, $idUnidadHabitacional]);
            }

            public function unidadHabitacionalBoolean($idPersona){
                $consulta = "
                    UPDATE interesado
                    SET Unidad_Habitacional_Asignada = 1
                    WHERE ID_Persona = ?
                ";
            consulta($this->conn, $consulta, "i", [$idPersona]);
            }
            
            public function crearReunion($titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion){
                $consulta = "
                    INSERT INTO reunion (Nombre, Descripcion, Fecha, Hora, Lugar, Tipo_Reunion, Estado_Reunion)
                    VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')
                ";
                consulta($this->conn, $consulta, "ssssss", [$titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion]);
            }

            public function getReunionesPendientes() {
                $consulta = "
                    SELECT * FROM reunion WHERE Estado_Reunion = 'Pendiente'
                ";
                $respuesta = consulta($this->conn, $consulta);

                $reuniones = [];
                while ($fila = mysqli_fetch_assoc($respuesta)) {
                    $reuniones[] = $fila;
                }
                return $reuniones;
            }

            public function getAsistenciasPorReunion($idReunion) {
                $consulta = "
                    SELECT Asistencia FROM asistencia WHERE ID_Reunion = ?
                ";
                $respuesta = consulta($this->conn, $consulta, "i", [$idReunion]);

                $asistencias = [];
                while ($fila = mysqli_fetch_assoc($respuesta)) {
                    $asistencias[] = $fila['Asistencia'];
                }
                return $asistencias;
            }

            
            public function getReunionesCompletadas(){
                $consulta = "
                    SELECT * 
                    FROM reunion 
                    WHERE Estado_Reunion = 'Finalizada' OR Estado_Reunion = 'Cancelada';
                ";
                $resultado = consulta($this->conn, $consulta); 
                $reunionesPendientes = [];
            
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $reunionesPendientes[] = $fila;
                }
                return $reunionesPendientes;
            }

            public function completarReunion($idReunion){
                $consulta = "
                    UPDATE reunion
                    SET Estado_Reunion = 'Finalizada'
                    WHERE ID_Reunion = ?
                ";
                consulta($this->conn, $consulta, "i", [$idReunion]);
            }

            public function eliminarReunion($idReunion){
                $consulta = "
                    UPDATE reunion
                    SET Estado_Reunion = 'Cancelada'
                    WHERE ID_Reunion = ?
                ";
                consulta($this->conn, $consulta, "i", [$idReunion]);
            }
            
            public function editarReunion($idReunion, $titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion){
                $sql = "
                    UPDATE reunion
                       SET Nombre = ?,
                           Descripcion = ?,
                           Fecha = ?,
                           Hora = ?,
                           Lugar = ?,
                           Tipo_Reunion = ?
                     WHERE ID_Reunion = ?
                     LIMIT 1
                ";
                consulta($this->conn, $sql, "ssssssi", [
                    $titulo, $descripcion, $fecha, $hora, $lugar, $tipoDeReunion, $idReunion
                ]);
            }
            
            public function getUsuariosAsistencias(){
                $sql = "
                    SELECT
                        p.ID_Persona,
                        p.Nombre,
                        p.Apellido,
                        p.CI,
                        u.Foto,
                        uh.ID_Unidad_habitacional,
                        uh.Numero_puerta,
                        uh.Pasillo
                    FROM Persona p
                    LEFT JOIN Usuario u
                    ON u.ID_Persona = p.ID_Persona
                    LEFT JOIN unidad_habitacional uh
                    ON uh.ID_Persona = p.ID_Persona
                    WHERE p.Rol = 'Usuario'
                    OR (p.Rol = 'Admin' AND u.ID_Persona IS NOT NULL)
                    ORDER BY p.Apellido, p.Nombre;

                ";
                $res = consulta($this->conn, $sql);

                $respuesta = [];
                if ($res && mysqli_num_rows($res) > 0) {
                    while ($r = mysqli_fetch_assoc($res)) {
                        $respuesta[] = [
                            'idPersona' => $r['ID_Persona'],
                            'Nombre' => $r['Nombre'],
                            'Apellido' => $r['Apellido'],
                            'ci'=> $r['CI'],
                            'foto' => $r['Foto'] ?? null,
                            'idUnidad' => $r['ID_Unidad_habitacional'] ?? null,
                            'nroPuerta' => $r['Numero_puerta'] ?? null,
                            'pasillo' => $r['Pasillo'] ?? null,
                        ];
                    }
                }

                return $respuesta;
            }


            public function cargarAsistencia($idReunion, $asistencias) {
                $total = count($asistencias);
                if ($total === 0) return;

                $valores = '';

                for ($i = 0; $i < $total; $i++) {
                    $fila = $asistencias[$i];
                    $idPersona = $fila['ID_Persona'];
                    $asistencia = !empty($fila['Asistencia']) ? 1 : 0; //para evitar que se rompa la consulta

                    if ($i > 0) {
                        $valores .= ',';
                    }

                    $valores .= "($idReunion, $idPersona, $asistencia)";
                }

                $consulta = "
                    INSERT INTO asistencia (ID_Reunion, ID_Persona, Asistencia)
                    VALUES $valores
                    ON DUPLICATE KEY UPDATE Asistencia = VALUES(Asistencia);
                ";

                consulta($this->conn, $consulta);
            }

            public function getFaltasPendientes() {
                $consulta = "
                    SELECT * FROM falta WHERE Estado = 'En Espera'
                ";
                $respuesta = consulta($this->conn, $consulta);
                $faltasPendientes = [];
                while ($fila = $respuesta->fetch_assoc()) {
                    $faltasPendientes[] = [
                        'ID_Falta' => $fila['ID_Falta'],
                        'ID_Persona' => $fila['ID_Persona'],
                        'ID_Semana_trabajo' => $fila['ID_Semana_trabajo'],
                        'Motivo_falta' => $fila['Motivo_falta'],
                        'Horas_solicitadas' => $fila['Horas_solicitadas'],
                        'Estado' => $fila['Estado'],
                        'Fecha' => $fila['Fecha'],
                        'Tipo_falta' => $fila['Tipo_falta']
                    ];
                }
                return $faltasPendientes;
            }

            public function getUsuariosConID($ids) {
                if (empty($ids)) return [];

                // Convertimos todos los IDs a enteros para evitar inyecciones
                $idsLimpios = array_map('intval', $ids);
                $listaIds = implode(',', $idsLimpios);

                $consulta = "
                    SELECT 
                        p.ID_Persona,
                        p.Nombre,
                        p.Apellido,
                        p.CI,
                        u.Foto,
                        uh.Numero_puerta,
                        uh.Pasillo
                    FROM Persona p
                    LEFT JOIN Usuario u ON u.ID_Persona = p.ID_Persona
                    LEFT JOIN unidad_habitacional uh ON uh.ID_Persona = p.ID_Persona
                    WHERE p.ID_Persona IN ($listaIds)
                ";

                $resultado = consulta($this->conn, $consulta);

                $usuarios = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $usuarios[$fila['ID_Persona']] = [
                        'ID_Persona'    => $fila['ID_Persona'],
                        'Nombre'        => $fila['Nombre'],
                        'Apellido'      => $fila['Apellido'],
                        'CI'            => $fila['CI'],
                        'Foto'          => $fila['Foto'],
                        'Numero_puerta' => $fila['Numero_puerta'],
                        'Pasillo'       => $fila['Pasillo']
                    ];
                }

                return $usuarios;
            }

        public function rechazarFalta($idFalta){
            $consulta = "
                UPDATE falta
                SET    Estado = 'Rechazada'
                WHERE  ID_Falta = ?
            ";
            consulta($this->conn, $consulta, "i", [$idFalta]);
        }

        public function aprobarFalta($idFalta){
            $consulta = "
                UPDATE falta
                SET    Estado = 'Aprobada'
                WHERE  ID_Falta = ?
            ";
            consulta($this->conn, $consulta, "i", [$idFalta]);
        }

        public function getFalta($idFalta) {
            $consulta = "
                SELECT * FROM falta WHERE ID_Falta = $idFalta
            ";

            $respuesta = consulta($this->conn, $consulta);
            $falta = [];

            while ($fila = $respuesta->fetch_assoc()) {
                $falta = [
                    'ID_Falta' => $fila['ID_Falta'],
                    'ID_Persona' => $fila['ID_Persona'],
                    'ID_Semana_trabajo' => $fila['ID_Semana_trabajo'],
                    'Motivo_falta' => $fila['Motivo_falta'],
                    'Horas_solicitadas' => $fila['Horas_solicitadas'],
                    'Estado' => $fila['Estado'],
                    'Fecha' => $fila['Fecha'],
                    'Tipo_falta' => $fila['Tipo_falta']
                ];
            }
            return $falta;
        }

        public function cargarHoras($idPersona, $horas, $fechaHoras, $idSemana){
            $consulta = "
                INSERT INTO Horas_trabajadas 
                (ID_Persona, Horas, Fecha_registro_horas, ID_Semana_trabajo)
                VALUES (?, ?, ?, ?)
            ";
            // horas puede ser decimal -> usar 'd' si corresponde; si es entero usar 'i'
            $resultado = consulta($this->conn, $consulta, "idsi", [$idPersona, $horas, $fechaHoras, $idSemana]);
            if ($resultado === false) {
                return false;
            }
            return true;
        }



            
    }
?>
