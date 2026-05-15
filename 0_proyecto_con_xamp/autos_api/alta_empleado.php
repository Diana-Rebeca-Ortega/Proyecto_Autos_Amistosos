<?php
// Incluir el archivo de conexión. (Carga $conn conectado a bd_usuarios_autosamistosos_2025)
require_once 'db_connect.php'; 

// Cambiar a la base de datos 'autos_amistosos'
// La conexión $conn ahora apuntará a la DB donde está la tabla 'vendedor'.
$conn->select_db("autos_amistosos"); 

// Establecer cabeceras para que Android sepa que espera JSON
header('Content-Type: application/json');

// Arreglo de respuesta
$response = array();

// Verificar si se recibieron todos los campos necesarios por POST
if (isset($_POST['nombre']) && isset($_POST['apellido1']) && isset($_POST['apellido2']) && 
    isset($_POST['salario_base']) && isset($_POST['porcentaje_comision'])) {

    // 1. Obtener y limpiar los datos de entrada
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido1 = $conn->real_escape_string($_POST['apellido1']);
    $apellido2_raw = $_POST['apellido2'];
    $apellido2 = empty($apellido2_raw) ? NULL : $conn->real_escape_string($apellido2_raw);
    
    $salario_base = floatval($_POST['salario_base']);
    $porcentaje_comision = floatval($_POST['porcentaje_comision']);

    // 2. Preparar la consulta SQL para la inserción
    // La consulta se ejecutará ahora en la DB 'autos_amistosos'
    $stmt = $conn->prepare("INSERT INTO vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión) 
                            VALUES (?, ?, ?, ?, ?)");
    
    // Tipos de parámetros: s=string, d=double (decimal). idVendedor es auto_increment
    $stmt->bind_param("sssid", $nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision);

    // 3. Ejecutar la consulta
    if ($stmt->execute()) {
        $response["success"] = 1;
        $response["message"] = "Vendedor registrado exitosamente.";
    } else {
        $response["success"] = 0;
        $response["message"] = "Error al registrar: " . $stmt->error;
    }

    $stmt->close();

} else {
    // Si faltan parámetros
    $response["success"] = 0;
    $response["message"] = "Faltan parámetros de entrada para el registro.";
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>