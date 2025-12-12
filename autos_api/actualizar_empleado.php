<?php
// Incluir el archivo de conexión
require_once 'db_connect.php'; 

// Cambiar a la base de datos 'autos_amistosos' donde está la tabla 'vendedor'
$conn->select_db("autos_amistosos"); 

header('Content-Type: application/json');
$response = array();

// Verificar si se recibieron todos los campos necesarios por POST
if (isset($_POST['id_vendedor']) && isset($_POST['nombre']) && isset($_POST['apellido1']) && 
    isset($_POST['apellido2']) && isset($_POST['salario_base']) && isset($_POST['porcentaje_comision'])) {

    // 1. Obtener y limpiar los datos de entrada
    $idVendedor = intval($_POST['id_vendedor']); // Es crucial tener el ID
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido1 = $conn->real_escape_string($_POST['apellido1']);
    
    // Apellido2 puede ser NULL, lo manejamos. Si está vacío, lo tratamos como NULL para la DB
    $apellido2_raw = $_POST['apellido2'];
    $apellido2 = empty($apellido2_raw) ? NULL : $conn->real_escape_string($apellido2_raw);
    
    $salario_base = floatval($_POST['salario_base']);
   $porcentaje_comision = floatval($_POST['porcentaje_comision']);

    // 2. Preparar la consulta SQL para la ACTUALIZACIÓN
    $stmt = $conn->prepare("UPDATE vendedor SET 
                            Nombre = ?, 
                            Apellido1 = ?, 
                            Apellido2 = ?, 
                            Salario_Base = ?, 
                            Porcentaje_Comisión = ?
                            WHERE idVendedor = ?");
    
    // Tipos de parámetros: s=string, d=double, i=integer (para el ID)
    // El orden de los tipos DEBE coincidir con el orden de las variables en bind_param
    $stmt->bind_param("sssddi", $nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision, $idVendedor);

    // 3. Ejecutar la consulta
    if ($stmt->execute()) {
        // Verificar si se afectó alguna fila (si realmente se hizo el cambio)
        if ($stmt->affected_rows > 0) {
            $response["success"] = 1;
            $response["message"] = "Vendedor actualizado exitosamente.";
        } else {
            // Esto ocurre si el ID es correcto, pero no se cambiaron los datos
            $response["success"] = 1;
            $response["message"] = "Vendedor encontrado, pero no se detectaron cambios.";
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();

} else {
    // Si faltan parámetros
    $response["success"] = 0;
    $response["message"] = "Faltan parámetros de entrada para la actualización.";
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>