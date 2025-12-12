<?php
// Incluir el archivo de conexión (se conecta a bd_usuarios_autosamistosos_2025)
require_once 'db_connect.php'; 

// Cambiar a la base de datos 'autos_amistosos' donde está la tabla 'vendedor'
$conn->select_db("autos_amistosos"); 

header('Content-Type: application/json');
$response = array();

// 1. Preparar la consulta SQL para obtener TODOS los vendedores
$sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión FROM vendedor ORDER BY Nombre ASC";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $response["success"] = 1;
        $response["message"] = "Lista de vendedores obtenida exitosamente.";
        $response["vendedores"] = array(); // Array para guardar los objetos vendedor

        // 2. Iterar sobre los resultados y guardarlos en el array
        while ($row = $result->fetch_assoc()) {
            $vendedor = array(
                "idVendedor" => $row["idVendedor"],
                "nombre" => $row["Nombre"],
                "apellido1" => $row["Apellido1"],
                "apellido2" => $row["Apellido2"],
                "salario_base" => $row["Salario_Base"],
                "porcentaje_comision" => $row["Porcentaje_Comisión"]
            );
            // Agregar el vendedor actual al array de vendedores
            array_push($response["vendedores"], $vendedor);
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "No se encontraron vendedores.";
        $response["vendedores"] = array();
    }
    
    $result->close();
} else {
    $response["success"] = 0;
    $response["message"] = "Error en la consulta SQL: " . $conn->error;
    $response["vendedores"] = array();
}

echo json_encode($response);
?>