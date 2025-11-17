<?php
// Credenciales de conexión a la base de datos (típicas de XAMPP)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'trayectos_db');

// Conexión a la base de datos MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($link === false) {
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}

// Función para obtener todos los trayectos
function obtenerTrayectos($link) {
    $sql = "SELECT id, titulo, descripcion, imagen FROM trayectos ORDER BY id ASC";
    $result = mysqli_query($link, $sql);
    $trayectos = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $trayectos[] = $row;
        }
    }
    return $trayectos;
}

?>