<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
   header("Location: /PROYECTO/cerrar_sesion");
    exit;
}

echo
include_once('./TransaccionVentaDAO.php');
$venta_obj = new TransaccionVentaDAO();

// ... (Captura y sanitización de variables) ...

$accion = $_POST['accion'] ?? null; 
$fecha = date("Y-m-d H:i:s");
$precio = filter_var($_POST['Precio_Final'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$impuesto = filter_var($_POST['Impuesto_Venta'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$costo_licencia = filter_var($_POST['Costo_Licencia'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Claves foráneas (INT)
$idVendedor = (int)($_SESSION['idVendedor'] ?? $_POST['Vendedor_idVendedor'] ?? 0);
$idCliente = (int)($_POST['Cliente_idCliente'] ?? 0);
$idGarantia = empty($_POST['idGarantia']) ? NULL : (int)$_POST['idGarantia'];

// Claves foráneas (CHAR)
$idAutomovil = htmlspecialchars($_POST['idAutomovil'] ?? '');
$vinIntercambio = empty($_POST['VIN_Intercambio']) ? NULL : htmlspecialchars($_POST['VIN_Intercambio']);

// 4. Lógica de inserción (Alta)
if ($accion === 'insertar') {
    
    // 1. VALIDACIÓN INICIAL DE DATOS
    if ($idVendedor === 0 || $idCliente === 0 || empty($idAutomovil) || $precio <= 0) {
        
        // 🚨 SI LA VALIDACIÓN FALLA, ESTABLECEMOS EL MENSAJE Y REDIRIGIMOS
        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = '❌ Error: Datos incompletos o inválidos (Vendedor, Cliente, Vehículo o Precio).';
        header('Location: ../../../pages/Empleado_Vendedor/registrar_venta.php');
        exit;
    } 
    
    // 2. PREPARACIÓN DE DATOS Y LLAMADA AL DAO (Solo si la validación es exitosa)
    $datosVenta = [
        'fecha' => $fecha, 
        'Precio_Final' => $precio,
        'Impuesto_Venta' => $impuesto,
        'Costo_Licencia' => $costo_licencia,
        'idVendedor' => $idVendedor,
        'idCliente' => $idCliente, 
        'idAutomovil' => $idAutomovil,
        'idGarantia' => $idGarantia,
        'VIN_Intercambio' => $vinIntercambio,
        // Agregamos Kilometraje, ya que el DAO lo requiere
        'Kilometraje_Entrega' => (int)($_POST['Kilometraje_Entrega'] ?? 0) 
    ];

    $resultado_transaccion = $venta_obj->procesarVenta($datosVenta);

    // 3. MANEJO DE RESULTADOS DE LA TRANSACCIÓN (Venta exitosa o fallida)
    if ($resultado_transaccion['success']) {
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = $resultado_transaccion['message'];
        
        header('Location: ../../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
        exit;

    } else {
        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = '❌ Error: ' . $resultado_transaccion['message']; 
        
        header('Location: ../../../pages/Empleado_Vendedor/registrar_venta.php');
        exit;
    }
}
// Si la acción no es 'insertar', el script simplemente termina.
?>