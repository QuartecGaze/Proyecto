  <?php 
  function validarToken($token, $conn){
        $consulta = "SELECT * FROM tokens
            WHERE Token = '$token'
            AND (fecha_expiracion IS NULL OR fecha_expiracion > UTC_TIMESTAMP())"
        ;
        $resultado = mysqli_query($conn, $consulta);
        if(mysqli_num_rows($resultado) == 1){
            return true;
        } else {
            return false;
        }
    }

    function validarTokenAdmin($token, $conn){
        $consulta = "SELECT * FROM tokens t
            INNER JOIN admin p ON t.ID_Persona = p.ID_Persona WHERE t.Token = '$token'
            AND (t.Fecha_expiracion IS NULL OR t.Fecha_expiracion > UTC_TIMESTAMP())"
        ;
        $resultado = mysqli_query($conn, $consulta);
        if(mysqli_num_rows($resultado) >= 1){
            return true;
        } else {
            return false;
        }
    }

    function crearToken($id, $rol, $conn){
        //CONSULTA SI YA EXISTE UN TOKEN (EL CUAL SERIA INVALIDO YA QUE SI NO NO SE ESTARIA CREANDO)
         $consulta = "SELECT * FROM Tokens
            WHERE ID_Persona = '$id'"
        ;
        $resultado = mysqli_query($conn, $consulta);
        //ACA SI EXISTE LO BORRA
        if(mysqli_num_rows($resultado) == 1){
            $consulta = "DELETE FROM tokens WHERE ID_Persona = $id"
        ;
        mysqli_query($conn, $consulta);
        }
        $token = hash("sha256", $id . $rol . microtime(true) . "PabloMendez");
        $fechaCreacion = date('Y-m-d H:i:s');
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $consulta = "INSERT INTO tokens (Token, ID_Persona, Fecha_creacion, Fecha_expiracion)
                  VALUES ('$token', '$id', '$fechaCreacion', '$fechaExpiracion')";
        mysqli_query($conn, $consulta);
        return $token;
    }

    
    function obtenerToken() {
    $headers = getallheaders();

    if (isset($headers['Authorization'])) {
        $auth = trim($headers['Authorization']);
        return $auth;
    }

    return null; // si no hay token
}
?>