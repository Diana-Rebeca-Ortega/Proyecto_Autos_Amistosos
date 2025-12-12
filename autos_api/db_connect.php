<?php
// Define los parámetros de conexión
define('DB_HOST', 'localhost');
define('DB_USER', 'dianita');
define('DB_PASS', 'dianita'); 
define('DB_NAME', 'bd_usuarios_autosamistosos_2025');
error_reporting(0);
// Crea una nueva conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica la conexión
if ($conn->connect_error) {
    // Establecer el encabezado JSON antes de imprimir
    header('Content-Type: application/json');
    // Devolver JSON de error que Android pueda entender
    echo json_encode(array("success" => 0, "message" => "Fallo de conexión a la base de datos."));
    exit(); // Detiene la ejecución
}
// Opcional: Establecer el conjunto de caracteres a UTF-8
$conn->set_charset("utf8");