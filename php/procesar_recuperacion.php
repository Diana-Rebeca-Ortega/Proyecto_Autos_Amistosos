<?php
  // Conectar a la base de datos
  $conn = new mysqli("localhost", "root", "1819diana", "usuarios");

  // Verificar conexión
  if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
  }

  // Obtener el correo electrónico del usuario
  $email = $_POST["email"];

  // Buscar al usuario en la base de datos
  $query = "SELECT * FROM usuarios WHERE email = '$email'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Generar un código de recuperación
    $codigo_recuperacion = uniqid();

    // Actualizar el código de recuperación en la base de datos
    $query = "UPDATE usuarios SET codigo_recuperacion = '$codigo_recuperacion' WHERE email = '$email'";
    if(!$conn->query($query)){
        echo "Error: " . $conn->error;
    }else{
        echo "Codigo de recuperacion actualizado con exito";
    }
     } 

  // Cerrar conexión
  $conn->close();
?>
