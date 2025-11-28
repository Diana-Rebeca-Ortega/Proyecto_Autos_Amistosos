<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0");
  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
   header("Location: ../../cerrar_sesion.php");
    exit;
  }
include_once('../clienteDAO.php'); 
include_once('../../../database/conexion_bdd_autos_amistosos.php'); 

$clienteDAO = new clienteDAO(); 
$cliente_datos = null;
$mensaje_status = '';

// 3. CAPTURAR EL ID DE LA URL
$id = $_GET['id'] ?? null;

// 4. LÓGICA DE CARGA DE DATOS DEL CLIENTE
if ($id) {
    // 4.1. Llamar al método del DAO para obtener un cliente por ID
    $cliente_datos = $clienteDAO->obtenerPorId($id);
if ($cliente_datos instanceof mysqli_result) {
        $cliente_datos = $cliente_datos->fetch_assoc();
    }
    if (!$cliente_datos) {
        // Si no se encuentra el cliente
        header('Location: ../clientes_lista.php?status=error_cliente_no_encontrado');
        exit();
    }
} else {
    // Si no se proporcionó ID
    header('Location: ../clientes_lista.php?status=error_id');
    exit();
}

// 5. MANEJAR MENSAJES DE ESTADO (opcional, para cuando hay fallos de validación)
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'campos_vacios_mod') {
        $mensaje_status = '<div class="alert alert-danger" role="alert"><strong>Error de Validación:</strong> Por favor, complete al menos el Nombre y el Primer Apellido.</div>';
    }
    // Puedes agregar más mensajes aquí (modificacion_error, etc.)
}

// 6. ASIGNAR VARIABLES DE FORMULARIO
// Se usa $cliente_datos para llenar los campos del formulario
$nombre = $cliente_datos['Nombre'] ?? '';
$apellido1 = $cliente_datos['Apellido1'] ?? '';
$apellido2 = $cliente_datos['Apellido2'] ?? '';
$direccion = $cliente_datos['Direccion'] ?? '';
$telefono = $cliente_datos['Telefono'] ?? '';
$email = $cliente_datos['Email'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">✍️ Modificar Cliente</h1>
        
        <?php echo $mensaje_status; ?>

        <form action="../procesar_cambiosCliente.php" method="POST">
            
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="accion" value="actualizar">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($nombre); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="apellido1" class="form-label">Primer Apellido</label>
                    <input type="text" class="form-control" id="apellido1" name="apellido1" 
                           value="<?php echo htmlspecialchars($apellido1); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="apellido2" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" id="apellido2" name="apellido2" 
                           value="<?php echo htmlspecialchars($apellido2); ?>">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($email); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" 
                       value="<?php echo htmlspecialchars($direccion); ?>">
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" 
                       value="<?php echo htmlspecialchars($telefono); ?>">
            </div>

            <button type="submit" class="btn btn-success me-2">Guardar Cambios</button>
            <a href="./formulario_cambiosCliente.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>