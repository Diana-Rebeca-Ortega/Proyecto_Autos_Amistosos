<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "1819diana", "usuarios");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST["nombre"];
$email = $_POST["email"];
$password = $_POST["password"];

// Verificar si el correo electrónico ya está registrado
$query = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // El correo electrónico ya está registrado
    echo "<script>alert('Este correo electrónico ya fue registrado.'); window.location.href='../pages/signup.html';</script>";
    exit;
} else {
    // Registrar al usuario
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password_hash')";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Usuario registrado con éxito.'); window.location.href='../pages/inicio_sesion.html';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>

