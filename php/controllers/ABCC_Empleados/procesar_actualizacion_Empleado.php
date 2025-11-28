<?php
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ../cerrar_sesion.php");
    exit;
  }

include_once('../empleado_dao.php'); 

// 1. Verificación y Captura de Datos por POST
// -----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso denegado. Este archivo debe ser accedido mediante el envío de un formulario.");
}

// 2. Validar que el ID del empleado exista, es crucial
if (!isset($_POST['idVendedor']) || empty($_POST['idVendedor'])) {
    die("Error de seguridad: ID de empleado no proporcionado para la actualización.");
}

// Captura segura de los datos del formulario
// Captura segura de los datos del formulario
$id_vendedor         = $_POST['idVendedor']; 
$nombre              = $_POST['nombre'];
$apellido1           = $_POST['apellido1']; 
$apellido2           = $_POST['apellido2']; 
$salario_base        = $_POST['salario_base'];
$porcentaje_comision = $_POST['porcentaje_comision'];

// 3. Ejecutar la Actualización
// -----------------------------------------------------------------
$empleadoDAO = new EmpleadoDAO();

// Llama al método que crearemos en el DAO (actualizarEmpleado)
$resultado = $empleadoDAO->actualizarEmpleado(
    $id_vendedor, 
    $nombre, 
    $apellido1, 
    $apellido2, 
    $salario_base, 
    $porcentaje_comision 
);
// 4. Redirección y Feedback
// -----------------------------------------------------------------
if ($resultado) {
    // Éxito: Redirige de vuelta a la lista de empleados
    header("Location: ../../formulario_actualizarEmpleado.php?status=success_update");
    exit();
} else {
    // Error: Muestra un mensaje o redirige con un error
    die("Error: No se pudieron guardar los cambios en la base de datos. Verifique la conexión o el método en el DAO.");
}
?>