<?php
// Lógica PHP de la página (la que carga el auto por ID)
include_once('../../controllers/ABCC_Automovil/AutomovilDAO.php'); 
include_once('../../database/conexion_bdd_autos_amistosos.php'); 

// ... (El código de la Lógica de obtención de $auto por $_GET['id'] sigue igual) ... 

$idAutomovil = $_GET['id'] ?? null;
$mensaje = $_GET['msg'] ?? null;
$auto = null;

if ($idAutomovil) {
    try {
        $automovilDAO = new AutomovilDAO();
        $auto = $automovilDAO->obtenerAutomovilPorID($idAutomovil); 
        
        if (!$auto) {
            $mensaje = "❌ Automóvil no encontrado con el ID: " . htmlspecialchars($idAutomovil);
        }
    } catch (Exception $e) {
        $mensaje = "❌ Error al cargar los datos: " . $e->getMessage();
    }
} else {
    $mensaje = "❌ ID de automóvil no proporcionado.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Automóvil</title>
</head>
<body>
    <h2>🛠️ Modificar Automóvil: <?php echo htmlspecialchars($auto['idAutomovil'] ?? 'N/A'); ?></h2>
    
    <?php if ($mensaje): ?>
        <p style="color: <?php echo (strpos($mensaje, '❌') !== false) ? 'red' : 'green'; ?>; font-weight: bold;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($auto): ?>
        <form action="procesar_cambiosAuto.php" method="POST">
            
            <input type="hidden" name="idAutomovil" value="<?php echo htmlspecialchars($auto['idAutomovil']); ?>">
            
            <label>Modelo:</label>
            <input type="text" name="modelo" value="<?php echo htmlspecialchars($auto['Modelo']); ?>" readonly><br><br>
            
            <label>Precio Lista:</label>
            <input type="number" step="0.01" name="precio_lista" value="<?php echo htmlspecialchars($auto['Precio_Lista']); ?>"><br><br>
            
            <label>Color:</label>
            <input type="text" name="color" value="<?php echo htmlspecialchars($auto['Color']); ?>" readonly><br><br>
            
            <label>Fecha Fabricación:</label>
            <input type="date" name="fecha_fabricacion" value="<?php echo htmlspecialchars($auto['FechaFabricacion']); ?>" readonly><br><br>

            <label>Estado Actual:</label>
            <input type="text" name="estado" value="<?php echo htmlspecialchars($auto['Estado']); ?>" readonly><br><br>
            
            <button type="submit">Guardar Cambios y Activar Trigger</button>
        </form>
    <?php endif; ?>
    
    <br><a href="formulario_consultasAutomovil.php">Volver al Listado</a>
</body>
</html>