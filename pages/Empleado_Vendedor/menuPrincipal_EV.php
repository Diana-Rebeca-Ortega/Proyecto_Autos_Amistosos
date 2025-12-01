<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    // Si la sesión no existe o no está autenticada:
    session_unset();
    session_destroy();
   header("Location: ../../php/controllers/cerrar_sesion.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor - Autos Amistosos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-dark { background-color: #343a40 !important; }
        /* Estilos generales para centrar el contenido */
        .container { max-width: 900px; }
    </style>
</head>
<body>

<?php 
// 1. BLOQUE DE CÓDIGO PARA MOSTRAR LA ALERTA DE SESIÓN
if (isset($_SESSION['alert_message'])) {
    $alert_type = $_SESSION['alert_type'];
    $alert_message = $_SESSION['alert_message'];
    
    // Limpiar las variables de sesión para que la alerta no se muestre de nuevo al recargar
    unset($_SESSION['alert_type']);
    unset($_SESSION['alert_message']);
    
    // 2. HTML de la Alerta de Bootstrap
?>
<div class="container mt-3">
    <div class="alert alert-<?php echo htmlspecialchars($alert_type); ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($alert_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php
}
// FIN DEL BLOQUE DE ALERTA
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand me-5" href="#">AUTOS AMISTOSOS</a>
        
        <span class="navbar-text me-auto text-white">
            Bienvenido <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario'); ?>
        </span>
        
        <ul class="navbar-nav">
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="ventasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Gestión de ventas
                </a>
                <ul class="dropdown-menu" aria-labelledby="ventasDropdown">
                    <li><a class="dropdown-item" href="./registrar_venta.php">Registrar Nueva Venta</a></li>
                    <li><hr class="dropdown-divider"></li> 
                    <li><a class="dropdown-item" href="./VentasPropiasListado.php">Consultar Ventas</a></li>
                    <li><a class="dropdown-item" href="#">Historial de Comisiones</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Gestión de Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Gestión de Inventario</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Reportes y Análisis</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Configuración</a></li>
           <li class="nav-item"><a class="nav-link" href="../../php/controllers/cerrar_sesion.php">Cerrar Sesion</a></li>
        </ul>
    </div>
</nav>

    
    <div class="container mt-5">
        <h1 class="text-center mb-4">Bienvenido Vendedor</h1>
        <p class="text-center mb-5">Esta es tu página principal. Aquí puedes encontrar información y herramientas útiles.</p>
        
        <div class="card">
            <div class="card-header text-center h4">Panel de Control</div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr class="table-secondary">
                            <th style="width: 15%;"></th>
                            <th style="width: 35%;"></th>
                            <th style="width: 35%;"></th>
                            <th style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">Altas</td>
                            <td><a href="../../php/controllers/ABCC_Clientes/formularios/formulario_registrarCliente.php">Registrar Cliente</a></td>
                            <td><a href="#">Agregar Vehículo al Inventario</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Bajas</td>
                            <td><a href="../../php/controllers/ABCC_Clientes/formularios/formulario_eliminarCliente.php">Eliminar Cliente</a></td>
                            <td><a href="#">Retirar Vehículo del Inventario</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Cambios</td>
                                    <td><a href="../../php/controllers/ABCC_Clientes/formularios/formulario_cambiosCliente.php">Actualizar Cliente</a></td>
                            <td><a href="#">Modificar detalles de Vehículo</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Consultas</td>
                                    <td><a href="../../php/controllers/ABCC_Clientes/formularios/formulario_consultasCliente.php">Buscar Datos sobre el Cliente</a></td>
                            <td><a href="#">Buscar información Vehículo</a></td>
                            <td><a href="#">Consultar historial de ventas y comisiones</a></td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>