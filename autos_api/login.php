<?php
// Establece el encabezado para que el navegador/Android sepa que recibirá datos JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once 'db_connect.php';

// Inicializa el array de respuesta
$response = array();

// Verifica si se recibieron los datos user y password mediante POST
if (isset($_POST['user']) && isset($_POST['password'])) {
    
    // 1. Sanitizar y obtener los datos
    $user = $conn->real_escape_string($_POST['user']);
    $password = $_POST['password']; // NOTA: Nunca almacenar contraseñas sin hash en DB.

    // 2. Construir la consulta SQL
    // Suponiendo que tienes una tabla 'usuarios' con campos 'username' y 'password'
    $stmt = $conn->prepare("SELECT id, username, rol FROM usuarios WHERE username = ? AND password = ?");
    
    // Si usas hashing (ej: MD5, SHA1), DEBES HASHEAR $password AQUÍ antes de la consulta
    $stmt->bind_param("ss", $user, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Verificar el resultado
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Login Exitoso
        $response['success'] = 1;
        $response['message'] = "Login exitoso";
        $response['user_id'] = $row['id'];
        $response['username'] = $row['username'];
        $response['rol'] = $row['rol']; // Devuelve el rol para saber a qué Dashboard ir
        
    } else {
        // Credenciales inválidas
        $response['success'] = 0;
        $response['message'] = "Usuario o contraseña incorrectos.";
    }
    
    $stmt->close();
    
} else {
    // Parámetros faltantes en la solicitud POST
    $response['success'] = 0;
    $response['message'] = "Parámetros faltantes (user o password).";
}

// 4. Devolver la respuesta en formato JSON
echo json_encode($response);

// Cerrar la conexión
$conn->close();

?>