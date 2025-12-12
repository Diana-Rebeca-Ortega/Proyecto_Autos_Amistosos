<?php
// Incluir el archivo de conexión
require_once 'db_connect.php'; 

// Cambiar a la base de datos 'autos_amistosos' donde está la tabla 'vendedor'
$conn->select_db("autos_amistosos"); 

header('Content-Type: application/json');
$response = array();

// Verificar si se recibió el ID necesario por POST
if (isset($_POST['id_vendedor'])) {

    // 1. Obtener y limpiar el ID
    $idVendedor = intval($_POST['id_vendedor']); 

    // 2. Preparar la consulta SQL para la ELIMINACIÓN
    $stmt = $conn->prepare("DELETE FROM vendedor WHERE idVendedor = ?");
    
    // Tipo de parámetro: i=integer (para el ID)
    $stmt->bind_param("i", $idVendedor);

    // 3. Ejecutar la consulta
    if ($stmt->execute()) {
        // Verificar si se afectó alguna fila (si realmente se eliminó algo)
        if ($stmt->affected_rows > 0) {
            $response["success"] = 1;
            $response["message"] = "Vendedor con ID $idVendedor eliminado exitosamente.";
        } else {
            // Esto ocurre si el ID era válido pero no existía en la tabla
            $response["success"] = 0;
            $response["message"] = "Error: No se encontró ningún vendedor con ID $idVendedor para eliminar.";
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "Error al eliminar: " . $stmt->error;
    }

    $stmt->close();

} else {
    // Si falta el ID
    $response["success"] = 0;
    $response["message"] = "Falta el ID del vendedor para la eliminación.";
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>