<?php

include_once('./clienteDAO.php'); 
// 1. Inicializa el objeto de la clase Cliente
$cliente_obj = new Cliente();

// 2. Captura la acción (Alta/Baja/Cambio)
$accion = $_POST['accion'] ?? null; // Esperamos que el formulario envíe 'accion' con valor 'insertar'

// 3. Captura los datos enviados por el formulario (POST)
// Usamos los nombres de los campos de tu formulario de Cliente:
$nombre = $_POST['nombre'] ?? '';
$apellido1 = $_POST['apellido1'] ?? '';
$apellido2 = $_POST['apellido2'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$email = $_POST['email'] ?? '';

// Líneas de depuración (Opcional, eliminar en producción)
echo "<h1>PROCESAMIENTO DE ALTAS DE CLIENTES</h1>";
echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
echo "Email: " . htmlspecialchars($email) . "<br>";
// ---------------------------------------------------------------------------------


// 4. Lógica de inserción (Alta)
if ($accion === 'insertar') {
    // NOTA: Aquí deberías realizar la validación de datos (campos requeridos, formatos, etc.)

    // 5. Llama al método de inserción de la clase Cliente
    $res = $cliente_obj->insertar($nombre, $apellido1, $apellido2, $direccion, $telefono, $email);
    
    if ($res) {
        // Éxito: Redireccionar al listado de clientes
        echo "<h2 style='color: green;'>✅ ¡Cliente registrado con éxito en la Base de Datos!</h2>";
        // header('Location: clientes_lista.php?status=alta_ok'); 
    } else {
        // Fallo: Redireccionar al formulario con un mensaje de error
        echo "<h2 style='color: red;'>❌ Error: No se pudo registrar al cliente.</h2>";
        echo "<p>Verifica la conexión a la base de datos o que los datos cumplan con las restricciones (ej. longitudes).</p>";
        // header('Location: cliente_alta.php?status=alta_error');
    }
} else {
    echo "<h2>Error: Acción no especificada.</h2>";
}

// exit(); // Descomenta esto cuando la redirección esté activa
?>