<?php 
function validarToken($token, $conn){
    if (empty($token) || !is_string($token) || !$conn) return false;

    $token = trim($token);
    if ($token === '') return false;

    if (stripos($token, 'Bearer ') === 0) $token = trim(substr($token, 7));
    $token = mysqli_real_escape_string($conn, $token);

    $consulta = "SELECT 1 FROM tokens
                 WHERE BINARY Token = '$token'
                   AND (Fecha_expiracion IS NULL OR Fecha_expiracion > NOW())
                 LIMIT 1";
    $resultado = mysqli_query($conn, $consulta);
    return ($resultado && mysqli_num_rows($resultado) == 1);
}

function validarTokenAdmin($token, $conn){
    if (empty($token) || !is_string($token) || !$conn) return false;

    $token = trim($token);
    if ($token === '') return false;

    if (stripos($token, 'Bearer ') === 0) {
        $token = trim(substr($token, 7));
    }

    $token = mysqli_real_escape_string($conn, $token);

    $consulta = "SELECT p.Nivel_permisos
                 FROM tokens t
                 INNER JOIN admin p ON t.ID_Persona = p.ID_Persona
                 WHERE BINARY t.Token = '$token'
                   AND (t.Fecha_expiracion IS NULL OR t.Fecha_expiracion > NOW())
                 LIMIT 1";

    $resultado = mysqli_query($conn, $consulta);
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        return false;
    }

    $row   = mysqli_fetch_assoc($resultado);
    $nivel = isset($row['Nivel_permisos']) ? strtoupper(trim($row['Nivel_permisos'])) : '';

    if ($nivel === 'ADMIN' || $nivel === 'OPERADOR') {
        return true;
    }

    return false;
}

function validarTokenOperador($token, $conn){
    if (empty($token) || !is_string($token) || !$conn) return false;

    $token = trim($token);
    if ($token === '') return false;

    if (stripos($token, 'Bearer ') === 0) $token = trim(substr($token, 7));
    $token = mysqli_real_escape_string($conn, $token);

    $consulta = "SELECT 1
                 FROM tokens t
                 INNER JOIN admin p ON t.ID_Persona = p.ID_Persona
                 WHERE BINARY t.Token = '$token'
                   AND (t.Fecha_expiracion IS NULL OR t.Fecha_expiracion > NOW())
                   AND p.Nivel_permisos IN ('Operador','Admin')
                 LIMIT 1";
    $resultado = mysqli_query($conn, $consulta);
    return ($resultado && mysqli_num_rows($resultado) >= 1);
}

function crearToken($id, $rol, $conn){
    $id  = (int)$id;
    $rol = mysqli_real_escape_string($conn, $rol);

    mysqli_query($conn, "DELETE FROM tokens WHERE ID_Persona = $id");

    $token = hash("sha256", $id . $rol . microtime(true) . "PabloMendez");
    $fechaCreacion   = date('Y-m-d H:i:s');
    $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $consulta = "INSERT INTO tokens (Token, ID_Persona, Fecha_creacion, Fecha_expiracion)
                 VALUES ('$token', '$id', '$fechaCreacion', '$fechaExpiracion')";
    mysqli_query($conn, $consulta);

    setcookie('Authorization', 'Bearer '.$token, time()+86400, '/', '', false, true);

    return $token;
}

function obtenerToken() {
    $headers = function_exists('getallheaders') ? getallheaders() : [];

    if (isset($headers['Authorization'])) {
        $auth = trim($headers['Authorization']);
        if (stripos($auth, 'Bearer ') === 0) $auth = trim(substr($auth, 7));
        if ($auth !== '') return $auth;
    }
    if (!empty($_COOKIE['Authorization'])) {
        $auth = trim($_COOKIE['Authorization']);
        if (stripos($auth, 'Bearer ') === 0) $auth = trim(substr($auth, 7));
        if ($auth !== '') return $auth;
    }
    if (isset($_GET['token']) && $_GET['token'] !== '') return trim($_GET['token']);

    return null;
}

function validarSoloAdmin($token, $conn){
    if (empty($token) || !is_string($token) || !$conn) return false;

    $token = trim($token);
    if ($token === '') return false;

    // Soportar "Bearer xxxxxx"
    if (stripos($token, 'Bearer ') === 0) {
        $token = trim(substr($token, 7));
    }

    $token = mysqli_real_escape_string($conn, $token);

    $consulta = "SELECT p.Nivel_permisos
                 FROM tokens t
                 INNER JOIN admin p ON t.ID_Persona = p.ID_Persona
                 WHERE BINARY t.Token = '$token'
                   AND (t.Fecha_expiracion IS NULL OR t.Fecha_expiracion > NOW())
                 LIMIT 1";

    $resultado = mysqli_query($conn, $consulta);
    if (!$resultado || mysqli_num_rows($resultado) === 0) {
        return false;
    }

    $row   = mysqli_fetch_assoc($resultado);
    $nivel = isset($row['Nivel_permisos']) ? strtoupper(trim($row['Nivel_permisos'])) : '';

    // SOLO ADMIN, operador NO
    return ($nivel === 'ADMIN');
}
