<?php
// Incluir el archivo de conexión (se conecta a bd_usuarios_autosamistosos_2025)
require_once 'db_connect.php'; 

// Cambiar a la base de datos 'autos_amistosos'
$conn->select_db("autos_amistosos"); 

header('Content-Type: application/json');
$response = array();

if (isset($_POST['id_vendedor'])) {

    // 1. Obtener y limpiar el ID
    $idVendedor = $conn->real_escape_string($_POST['id_vendedor']);

    // 2. Preparar la consulta SQL
    $stmt = $conn->prepare("SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión 
                            FROM vendedor 
                            WHERE idVendedor = ?");
    
    // El parámetro es una string (aunque sea un número, lo tratamos como 's' si no estamos seguros, o 'i' para integer)
    $stmt->bind_param("i", $idVendedor); // Usamos 'i' ya que idVendedor es int

    // 3. Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Se encontró el vendedor
            $row = $result->fetch_assoc();
            
            $response["success"] = 1;
            $response["message"] = "Vendedor encontrado.";
            
            // Devolver los detalles del vendedor
            $response["vendedor"] = array(
                "idVendedor" => $row["idVendedor"],
                "nombre" => $row["Nombre"],
                "apellido1" => $row["Apellido1"],
                "apellido2" => $row["Apellido2"],
                "salario_base" => $row["Salario_Base"],
                "porcentaje_comision" => $row["Porcentaje_Comisión"]
            );
            
        } else {
            // No se encontró el vendedor
            $response["success"] = 0;
            $response["message"] = "Vendedor con ID $idVendedor no encontrado.";
        }
    } else {
        // Error de ejecución de la consulta
        $response["success"] = 0;
        $response["message"] = "Error de base de datos: " . $stmt->error;
    }

    $stmt->close();

} else {
    // Si falta el ID
    $response["success"] = 0;
    $response["message"] = "Parámetros de búsqueda faltantes.";
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>