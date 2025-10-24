<?php
function consulta($conn, $query, $types = "", $params = []) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }

    // Si hay parámetros, los vinculamos
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }

    // Ejecutar consulta
    if (!$stmt->execute()) {
        die("Error en execute: " . $stmt->error);
    }

    // Si la consulta devuelve resultado (ej: SELECT)
    $result = $stmt->get_result();
    if ($result) {
        return $result;
    } else {
        return true; // Para INSERT, UPDATE, DELETE
    }
}