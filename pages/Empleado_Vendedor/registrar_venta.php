<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    // Si la sesión no existe o no está autenticada:
    session_unset();
    session_destroy();
    //header("Location: ../../php/controllers/cerrar_sesion.php");
     header("Location: /PROYECTO/cerrar_sesion");
    exit;
}

require_once '../../php/controllers/ABCC_Clientes/clienteDAO.php';
$clienteDAO = new ClienteDAO();
// Resultado: Array asociativo de clientes (PDO)
$clientesResult = $clienteDAO->obtenerTodos(); 

require_once '../../php/controllers/ABCC_Automovil/automovilDAO.php';
$automovilDAO = new AutomovilDAO();
// Resultado: Array asociativo de autos disponibles (PDO)
$autosResult = $automovilDAO->obtenerDisponibles(); 

require_once '../../php/controllers/ABCC_Garantia/GarantiaDAO.php';
$garantiaDAO = new GarantiaDAO();
// Resultado: Array asociativo de garantías (PDO)
$garantiasArray = $garantiaDAO->obtenerTodasGarantias();

$id_vendedor_logueado = $_SESSION['idVendedor'] ?? 0;
// echo "$id_vendedor_logueado"; // Descomenta solo para depuración
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
                    <li><a class="dropdown-item" href="#">Consultar Ventas</a></li>
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
                                // Lógica corregida: usar foreach para iterar sobre el ARRAY (PDO)
                                if (!empty($clientesResult)) {
                                    foreach ($clientesResult as $cliente) { 
                                        $nombreCompleto = $cliente["Nombre"] . " " . $cliente["Apellido1"] . (
                                            !empty($cliente["Apellido2"]) ? " " . $cliente["Apellido2"] : ""
                                        );
                                        echo '<option value="' . htmlspecialchars($cliente["idCliente"]) . '">' 
                                            . htmlspecialchars($nombreCompleto) . ' (ID: ' . htmlspecialchars($cliente["idCliente"]) . ')' 
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
                                // Lógica corregida: usar foreach para iterar sobre el ARRAY (PDO)
                                if (!empty($autosResult)) {
                                    foreach ($autosResult as $automovil) {
                                        $kilometrajeActual = $automovil["Kilometraje_Entrega"];
                                        $descripcionAutomovil = 
                                            htmlspecialchars($automovil["Modelo"]) . " - " . 
                                            htmlspecialchars($automovil["Tipo_Carroceria"]) . 
                                            " | $" . number_format($automovil["Precio_Lista"], 2) . 
                                            " | VIN: " . htmlspecialchars($automovil["idAutomovil"]);
                                        $descripcionAutomovil .= " | KM: " . number_format($kilometrajeActual);
                                        echo '<option value="' . htmlspecialchars($automovil["idAutomovil"]) . '" data-km="' . htmlspecialchars($kilometrajeActual) . '">' 
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
                    <input type="hidden" id="kilometraje_entrega_hidden" name="Kilometraje_Entrega" value="0">
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
                            <?php
                                // Lógica corregida: usar foreach para iterar sobre el ARRAY (PDO)
                                if (!empty($garantiasArray)) {
                                    foreach ($garantiasArray as $garantia) {
                                        $costoFormateado = '$' . number_format($garantia['Costo'], 2);
                                        $etiqueta = htmlspecialchars($garantia['Nombre_Garantia']) . 
                                            ' | ' . $costoFormateado . 
                                            ' (ID: ' . htmlspecialchars($garantia['idGarantia']) . ')';
                                        echo '<option value="' . htmlspecialchars($garantia['idGarantia']) . '">' 
                                            . $etiqueta 
                                            . '</option>';
                                    }
                                }
                            ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAutomovil = document.getElementById('id_automovil');
        const hiddenKilometraje = document.getElementById('kilometraje_entrega_hidden');

        selectAutomovil.addEventListener('change', function() {
            // Obtiene la opción seleccionada (el elemento <option>)
            const selectedOption = this.options[this.selectedIndex];
            
            // Obtiene el valor del atributo data-km, o 0 si no existe
            const kilometraje = selectedOption.getAttribute('data-km') || 0;
            
            // Actualiza el campo oculto que se enviará en el POST
            hiddenKilometraje.value = kilometraje;
            
            console.log('Kilometraje actualizado a:', kilometraje);
        });
        
        // Ejecutar al cargar por si hay una opción preseleccionada (aunque no parece ser el caso aquí)
        selectAutomovil.dispatchEvent(new Event('change')); 
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>