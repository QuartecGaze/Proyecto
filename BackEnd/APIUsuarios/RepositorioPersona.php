<?php
require_once __DIR__ . '/ServicioPersona.php'; 
require __DIR__ .'/../Consultas.php';

class RepositorioPersona {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // CRUD Persona
    public function cargarPersona($persona){
        $ci = $persona->getCi(); 
        $email = $persona->getEmail();
        $contraseña = $persona->getContraseña();
        $rol = $persona->getRol();
        $nombre = $persona->getNombre();
        $apellido = $persona->getApellido();

        $consulta = "INSERT INTO persona (CI, Email, Contraseña, Rol, Nombre, Apellido) VALUES (?, ?, ?, ?, ?, ?)";
        consulta($this->conn, $consulta, "ssssss", [$ci, $email, $contraseña, $rol, $nombre, $apellido]);
    }

    public function cargarTelefono($id, $telefono){
        $consulta = "INSERT INTO numero_de_telefono (ID_Persona, Telefono) VALUES (?, ?)";
        consulta($this->conn, $consulta, "is", [$id, $telefono]);
    }

    public function getPersona($id){
        $consulta = "SELECT * FROM persona WHERE ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]);
        $fila = mysqli_fetch_assoc($resultado);

        return new Persona(
            $fila['CI'], 
            $fila['Email'], 
            $fila['Telefono'],
            $fila['ID_Persona'], 
            $fila['Nombre'], 
            $fila['Apellido'], 
            $fila['Contraseña'], 
            $fila['Rol']
        );
    }

    // CRUD Interesado
    public function cargarInteresado($interesado){
        $idPersona = $interesado->getIdPersona();
        $estadoAntecedentes = $interesado->getEstadoAntecedentes();
        $estadoEntrevista = $interesado->getEstadoEntrevista();
        $estadoPagoInicial = $interesado->getEstadoPagoInicial();

        $consulta = "INSERT INTO interesado (ID_Persona, Estado_antecedentes, Estado_entrevista, Estado_pago_inicial) VALUES (?, ?, ?, ?)";
        consulta($this->conn, $consulta, "isss", [$idPersona, $estadoAntecedentes, $estadoEntrevista, $estadoPagoInicial]);
    }

    public function getTelefonosPersona($idPersona) {
        $consulta = "SELECT Telefono FROM numero_de_telefono WHERE ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);

        $telefonos = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $telefonos[] = $fila['Telefono'];
        }
        return $telefonos;
    }

    public function getDatosInteresado($id) {
        $consulta = "
            SELECT * FROM persona 
            JOIN interesado ON persona.ID_Persona = interesado.ID_Persona
            WHERE persona.ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]); 
        $fila = mysqli_fetch_assoc($resultado);

        $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);
        return new Interesado(
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
            $fila['Monto_pago_inicial'],
            $fila['Unidad_Habitacional_Asignada']
        );
    }

    public function subirComprobante($nombre, $id) {
        $consulta = "
            UPDATE interesado
            SET Pago_inicial = ?, Estado_pago_inicial = 'Pendiente'
            WHERE ID_Persona = ?";
        consulta($this->conn, $consulta, "si", [$nombre, $id]);
    }

    public function subirAntecedentes($nombre, $id) {
        $consulta = "
            UPDATE interesado
            SET Antecedentes = ?, Estado_antecedentes = 'Pendiente'
            WHERE ID_Persona = ?";
        consulta($this->conn, $consulta, "si", [$nombre, $id]);
    }

    // CRUD Usuario
    public function subirFoto($id, $nombre) {
        $consulta = "UPDATE usuario SET Foto = ? WHERE ID_Persona = ?";
        consulta($this->conn, $consulta, "si", [$nombre, $id]);
    }

    public function getFoto($id) {
        $consulta = "SELECT Foto FROM usuario WHERE ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['Foto'] === null ? null : $fila['Foto'];
        }
        return null;
    }

    public function borrarFoto($id) {
        $consulta = "UPDATE usuario SET Foto = NULL WHERE ID_Persona = ?";
        return consulta($this->conn, $consulta, "i", [$id]);
    }

    public function cargarFechaNacimientoUsuario($id, $fechaNacimiento){
        $consulta = "UPDATE usuario SET Fecha_nacimiento = ? WHERE ID_Persona = ?";
        consulta($this->conn, $consulta, "si", [$fechaNacimiento, $id]);
    }

    public function getDatosUsuario($id) {
        $consulta = "
            SELECT * FROM persona 
            JOIN usuario ON persona.ID_Persona = usuario.ID_Persona
            WHERE persona.ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]); 
        $fila = mysqli_fetch_assoc($resultado);
        $telefonos = $this->getTelefonosPersona($fila['ID_Persona']);

        return new Usuario(
            $fila['CI'], 
            $fila['Email'], 
            $telefonos,
            $fila['ID_Persona'], 
            $fila['Nombre'], 
            $fila['Apellido'], 
            $fila['Contraseña'], 
            $fila['Rol'],
            $fila['Fecha_nacimiento'],
            $fila['Fecha_ingreso'],
            $fila['Foto']
        );
    }

    public function actualizarUsuario($usuario) {
        $id = $usuario->getIdPersona();
        $email = $usuario->getEmail();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $fechaNacimiento = $usuario->getFechaNacimiento();
        $consulta = "
            UPDATE persona 
            SET Email = ?, Nombre = ?, Apellido = ?
            WHERE ID_Persona = ?";
        
        consulta($this->conn, $consulta, "sssi", [$email, $nombre, $apellido, $id]);
        $this->cargarFechaNacimientoUsuario($id, $fechaNacimiento);
    }

    // Funciones auxiliares
    public function getIdPersona($persona){
        $ci = $persona->getCi();
        $consulta = "SELECT ID_Persona FROM persona WHERE CI = ?";
        $resultado = consulta($this->conn, $consulta, "s", [$ci]);

        if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['ID_Persona']; 
        } else {
            throw new Exception("No se encontró una persona con la CI $ci");
        }
    }

    public function getIdPersonaCi($ci){
        $consulta = "SELECT ID_Persona FROM persona WHERE CI = ?";
        $resultado = consulta($this->conn, $consulta, "s", [$ci]);

        if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['ID_Persona']; 
        } else {
            throw new Exception("No se encontró una persona con la CI $ci");
        }
    }

    public function personaExiste($ci){
        $consulta = "SELECT 1 FROM persona WHERE CI = ?";
        $resultado = consulta($this->conn, $consulta, "s", [$ci]);

        return mysqli_num_rows($resultado) > 0;
    }

    public function InteresadoExisteID($id){
        $consulta = "SELECT 1 FROM interesado WHERE ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]);

        return mysqli_num_rows($resultado) > 0;
    }

    public function getContraseña($ci){
        if($this->personaExiste($ci)){
            $consulta = "SELECT Contraseña FROM persona WHERE CI = ?";
            $resultado = consulta($this->conn, $consulta, "s", [$ci]);
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['Contraseña'];
        }
    }

    public function getRol($ci){
        $consulta = "SELECT Rol FROM persona WHERE CI = ?";
        $resultado = consulta($this->conn, $consulta, "s", [$ci]);

        if(mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            return $fila['Rol']; 
        } else {
            throw new Exception("No se encontró una persona con la CI $ci");
        }
    }

    public function usuarioExisteID($id){
        $consulta = "SELECT 1 FROM usuario WHERE ID_Persona = ?";
        $resultado = consulta($this->conn, $consulta, "i", [$id]);

        return mysqli_num_rows($resultado) > 0;
    }
    
    public function getDireccion($idPersona){
        $consulta = "
            SELECT Pasillo, Numero_Puerta
            FROM unidad_habitacional
            WHERE ID_Persona = ?
        ";
        $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);
        $datos = mysqli_fetch_assoc($resultado);
        $direccion = "";
        $direccion = $datos['Numero_Puerta'] . " " . $datos['Pasillo'];
        return $direccion;
    }

    public function unidadHabitacionalExisteID($idPersona){
        $consulta = "
            SELECT * FROM unidad_habitacional 
            WHERE ID_Persona = ?
            ";
        $resultado = consulta($this->conn, $consulta, "i", [$idPersona]);
        if(mysqli_num_rows($resultado) > 0){
            return true;
        }else{
            return false;
        }
    }
}
