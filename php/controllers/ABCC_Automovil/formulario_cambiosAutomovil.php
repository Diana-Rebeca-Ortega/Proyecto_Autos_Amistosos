<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {    
    session_unset();
    session_destroy();  
    header("Location: /PROYECTO/cerrar_sesion");
    exit;
}
// Asegúrate de que las rutas a los DAOs sean correctas
include_once('../../controllers/ABCC_Automovil/AutomovilDAO.php');
include_once('../../database/conexion_bdd_autos_amistosos.php'); 

$automoviles = [];
$mensaje = '';

try {
    $automovilDAO = new AutomovilDAO();
    
    $automoviles = $automovilDAO->obtenerTodosLosAutomoviles(); 
} catch (Exception $e) {
    $mensaje = '❌ Error al cargar los datos de automóviles: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta y Modificación de Automóviles</title>
</head>
<body>
    <h2>Consulta y Modificación de Automóviles 🚗</h2>

    <?php if (!empty($mensaje)): ?>
        <p style="color: darkred; font-weight: bold;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if (empty($automoviles)): ?>
        <div style="background-color: #ffcdd2; padding: 10px; border: 1px solid #d32f2f;">
            No se encontraron registros de automóviles.
        </div>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>VIN / ID Automóvil</th>
                    <th>Modelo</th>
                    <th>Precio Lista Actual</th>
                    <th>Estado</th>
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($automoviles as $auto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($auto['idAutomovil']); ?></td>
                        <td><?php echo htmlspecialchars($auto['Modelo']); ?></td>
                        <td>$<?php echo number_format($auto['Precio_Lista'], 2); ?></td>
                        <td><?php echo htmlspecialchars($auto['Estado'] ?? 'N/A'); ?></td>
                        
                        <td>
                            <a href="formulario_modificarAuto.php?id=<?php echo urlencode($auto['idAutomovil']); ?>">Modificar Precio</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>