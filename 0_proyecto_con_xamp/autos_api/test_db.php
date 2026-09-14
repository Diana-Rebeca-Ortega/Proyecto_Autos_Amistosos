<?php
// Incluye tu archivo de conexión recién configurado
require_once 'db_connect.php';

echo "<h1>Prueba de Conexión a Base de Datos</h1>";

if ($conn) {
    echo "<p style='color:green;'>¡Conexión a la base de datos **bd_usuarios_autosamistosos_2025** exitosa!</p>";

    $sql = "SELECT * FROM usuarios LIMIT 1"; 
    if ($conn->query($sql)) {
        echo "<p style='color:green;'>La tabla 'usuarios' existe y es accesible.</p>";
    } else {
        echo "<p style='color:red;'>ADVERTENCIA: No se pudo acceder a la tabla 'usuarios'. Asegúrate de que exista: " . $conn->error . "</p>";
    }
} else {
    // Si la conexión falló, db_connect ya habría llamado a die(), pero esto es por si acaso.
    echo "<p style='color:red;'>Error crítico en la conexión.</p>";
}

$conn->close();
?>