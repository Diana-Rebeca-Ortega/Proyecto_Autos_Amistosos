<?php
// Define los parámetros de conexión
define('DB_HOST', 'localhost');
define('DB_USER', 'dianita');
define('DB_PASS', 'dianita'); 
define('DB_NAME', 'bd_usuarios_autosamistosos_2025');

// Crea una nueva conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica la conexión
if ($conn->connect_error) {
    // Si falla, imprime el error y detiene la ejecución
    die("Fallo en la conexión: " . $conn->connect_error);
}
// Opcional: Establecer el conjunto de caracteres a UTF-8
$conn->set_charset("utf8");

?>