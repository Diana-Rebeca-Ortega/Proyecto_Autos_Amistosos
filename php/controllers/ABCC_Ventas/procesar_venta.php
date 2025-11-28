<?php
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ../cerrar_sesion.php");
    exit;
  }
include_once('./ventaDAO.php'); 
$venta_obj = new VentaDAO();

// 2. Captura la acción (Alta/Baja/Cambio)
$accion = $_POST['accion'] ?? null; 
$fecha = date("Y-m-d H:i:s"); // Captura el TIMESTAMP actual para la venta
$precio = filter_var($_POST['Precio_Final'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$impuesto = filter_var($_POST['Impuesto_Venta'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$costo_licencia = filter_var($_POST['Costo_Licencia'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Claves foráneas (INT)

$idVendedor = (int)($_SESSION['idVendedor'] ?? $_POST['Vendedor_idVendedor'] ?? 0);
$idCliente = (int)($_POST['Cliente_idCliente'] ?? 0); // Viene de un select
$idGarantia = empty($_POST['idGarantia']) ? NULL : (int)$_POST['idGarantia']; // Opcional (NULL)

// Claves foráneas (CHAR)
$idAutomovil = htmlspecialchars($_POST['idAutomovil'] ?? ''); // Viene de un select
$vinIntercambio = empty($_POST['VIN_Intercambio']) ? NULL : htmlspecialchars($_POST['VIN_Intercambio']); // Opcional (NULL)

// 4. Lógica de inserción (Alta)
if ($accion === 'insertar') {
    // NOTA: Se recomienda validar que $precio > 0, $idVendedor > 0, $idCliente > 0 y $idAutomovil no esté vacío.
    if ($idVendedor === 0 || $idCliente === 0 || empty($idAutomovil) || $precio <= 0) {
        $res = false;
        $error_msg = "Datos incompletos o inválidos (Vendedor, Cliente, Vehículo o Precio).";
    } else {
        // 5. Llama al método de inserción de la clase VentaDAO
        $res = $venta_obj->registrarVenta(
            $fecha, 
            $precio, 
            $impuesto, 
            $costo_licencia, 
            $idVendedor, 
            $idCliente, 
            $idAutomovil, 
            $idGarantia, 
            $vinIntercambio
        );
    }

if ($res) {
    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = '✅ ¡Venta registrada con éxito en la Base de Datos!';
    
    header('Location: ../../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
    exit;

} else {
    $_SESSION['alert_type'] = 'danger';
    $error_msg = $conn->error ?? 'Verifica la conexión a la base de datos y la integridad de las claves foráneas.';
    $_SESSION['alert_message'] = '❌ Error: No se pudo registrar la venta. ' . $error_msg;
    header('Location: ../../pages/Empleado_Vendedor/registrar_venta.php');
    exit;
}
}
?>
