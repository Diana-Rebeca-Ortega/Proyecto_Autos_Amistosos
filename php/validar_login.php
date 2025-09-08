<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "1819diana", "usuarios");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Buscar al usuario en la base de datos
$query = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verificar la contraseña
    if (password_verify($password, $row['password'])) {
        // Iniciar sesión
        session_start();
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre'];
        header("Location:../pages/inicio_sesion.html");
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}

$conn->close();
?>
