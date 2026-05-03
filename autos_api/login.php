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
    $password_ingresada = $_POST['password'];

    // >>>>> CORRECCIÓN 1 Y 2: Aplicar el Hash (SHA1) a la contraseña ingresada <<<<<
    $password_hash_a_comparar = sha1($password_ingresada);
    
    // 2. Construir la consulta SQL

$stmt = $conn->prepare("SELECT ID_Usuario, Usuario, Perfil FROM usuarios WHERE Usuario = ? AND Password = ?");
    
    // >>>>> CORRECCIÓN 3: Usar la variable hasheada en el bind_param <<<<<
    $stmt->bind_param("ss", $user, $password_hash_a_comparar); 
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. Verificar el resultado
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Login Exitoso
        $response['success'] = 1;
        $response['message'] = "Login exitoso";
        $response['user_id'] = $row['id'];
        $response['username'] = $row['Usuario'];
        $response['rol'] = $row['Perfil']; // Devuelve el rol para saber a qué Dashboard ir
        
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
<<<<<<< HEAD
$conn->close();
=======
$conn->close();
>>>>>>> temporal
