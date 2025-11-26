<?php
session_start();
require_once '../../php/controllers/ABCC_Clientes/clienteDAO.php';
$clienteDAO = new ClienteDAO();
$clientesResult = $clienteDAO->obtenerTodos();

require_once '../../php/controllers/ABCC_Automovil/automovilDAO';
$automovilDAO = new AutomovilDAO();
$autosResult = $automovilDAO->obtenerTodos();

// 1. CORRECCIÓN DE SEGURIDAD: Añadir exit; después de la redirección
/*
if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    header("location: login.php");
    exit;
}*/
// 2. OBTENER ID DE VENDEDOR LOGUEADO (Asumiendo que lo guardaste en la sesión)
$id_vendedor_logueado =3;
echo "$id_vendedor_logueado";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Nueva Venta - Autos Amistosos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-dark { background-color: #343a40 !important; }
        /* Estilo para el contenedor del formulario */
        .venta-form-container { max-width: 800px; margin-top: 30px; }
        legend { font-size: 1.25rem; font-weight: bold; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand me-5" href="#">AUTOS AMISTOSOS</a>
        <span class="navbar-text me-auto text-white">
            Bienvenido <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario'); ?>
        </span>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="#" id="ventasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Gestión de ventas 
                </a>
                <ul class="dropdown-menu" aria-labelledby="ventasDropdown">
                    <li><a class="dropdown-item" href="registrar_venta.php">Registrar Nueva Venta</a></li>
                    <li><hr class="dropdown-divider"></li> 
                    <li><a class="dropdown-item" href="consultar_ventas.php">Consultar Ventas</a></li>
                    <li><a class="dropdown-item" href="reporte_comisiones.php">Historial de Comisiones</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Gestión de Clientes</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Gestión de Inventario</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Reportes y Análisis</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Configuración</a></li>
            <li class="nav-item"><a class="nav-link" href="../../loginEmpleados.html">Cerrar Sesion</a></li>
        </ul>
    </div>
</nav>
<div class="container venta-form-container">
    <h2 class="mb-4 text-info">✍️ Registro de Nueva Venta</h2>
    <form action="../../php/controllers/ABCC_Ventas/procesar_venta.php" method="POST"> 

        <div class="card mb-4">
            <div class="card-header bg-light">
                Detalles de la Transacción
            </div>
            <div class="card-body">
                <div class="row g-3"> 
                    <div class="col-md-6">
                        <label for="id_cliente" class="form-label">Cliente:</label>
                        <select id="id_cliente" name="Cliente_idCliente" class="form-select" required>
                            <option value="">-- Seleccionar Cliente --</option>
                            <?php
        if ($clientesResult && $clientesResult->num_rows > 0) {
            while($cliente = $clientesResult->fetch_assoc()) {
                $nombreCompleto = $cliente["Nombre"] . " " . $cliente["Apellido1"] . (
                    !empty($cliente["Apellido2"]) ? " " . $cliente["Apellido2"] : ""
                );
                echo '<option value="' . $cliente["idCliente"] . '">' 
                     . $nombreCompleto . ' (ID: ' . $cliente["idCliente"] . ')' 
                     . '</option>';
            }
        } else {
            echo '<option value="" disabled>No se encontraron clientes</option>';
        }
        ?>
         </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_automovil" class="form-label">Automóvil:</label>
                        <select id="id_automovil" name="idAutomovil" class="form-select" required>
                            <option value="">-- Seleccionar Vehículo --</option>
          <?php
    if ($autosResult && $autosResult->num_rows > 0) {
        while($automovil = $autosResult->fetch_assoc()) { 
            $descripcionAutomovil = 
                $automovil["Modelo"] . " - " . 
                $automovil["Tipo_Vehiculo"] . 
                " | $" . number_format($automovil["Precio_Lista"], 2) . 
                " | VIN: " . $automovil["idAutomovil"];
            echo '<option value="' . $automovil["idAutomovil"] . '">' 
                 . $descripcionAutomovil 
                 . '</option>';
        }
    } else {
        echo '<option value="" disabled>No se encontraron automóviles disponibles</option>';
    }
?>
                            </select>
                    </div>
                    <div class="col-md-4">
                        <label for="precio_final" class="form-label">Precio Final ($):</label>
                        <input type="number" id="precio_final" name="Precio_Final" step="0.01" min="0" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="impuesto" class="form-label">Impuesto de Venta ($):</label>
                        <input type="number" id="impuesto" name="Impuesto_Venta" step="0.01" min="0" class="form-control" value="0.00" required>
                    </div>
                    <div class="col-md-4">
                        <label for="costo_licencia" class="form-label">Costo de Licencia ($):</label>
                        <input type="number" id="costo_licencia" name="Costo_Licencia" step="0.01" min="0" class="form-control" value="0.00" required>
                    </div>
                    
                    <input type="hidden" name="Vendedor_idVendedor" value="<?php echo $id_vendedor_logueado; ?>">
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-light">
                Opciones Adicionales
            </div>
            <div class="card-body">
                <div class="row g-3">
        
                    <div class="col-md-6">
                        <label for="id_garantia" class="form-label">Garantía Aplicada:</label>
                        <select id="id_garantia" name="idGarantia" class="form-select">
                            <option value="" selected>Ninguna (NULL)</option>
                            <option value="1">Garantía Extendida 1 Año (ID: 1)</option>
                            <option value="2">Garantía Básica 3 Meses (ID: 2)</option>
                            </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="vin_intercambio" class="form-label">VIN Vehículo Intercambio (Opcional):</label>
                        <input type="text" id="vin_intercambio" name="VIN_Intercambio" maxlength="17" placeholder="Escribir VIN si aplica" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mb-5">
            <input type="hidden" name="accion" value="insertar">
            <button type="submit" class="btn btn-success me-3">✍️ Registrar Venta</button>
            <button type="reset" class="btn btn-secondary">Borrar Formulario</button>
        </div>
        
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>