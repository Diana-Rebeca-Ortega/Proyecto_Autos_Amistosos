<?php
include('./controllers/empleado_dao.php');
$vendedor_obj = new EmpleadoDAO();

$termino_busqueda = $_GET['busqueda'] ?? '';

// DECIDIR QUÉ MÉTODO LLAMAR
if (!empty($termino_busqueda)) {
    $datos = $vendedor_obj->buscarVendedores($termino_busqueda); 
} else {
    // Carga todos los vendedores
    $datos = $vendedor_obj->obtenerTodos(); 
}
$contador = 1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Vendedores Registrados</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1200px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-info">🔍 Consulta de Vendedores Registrados</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar por Nombre o Apellido" 
                    name="busqueda" value="<?php echo htmlspecialchars($termino_busqueda); ?>">
            
            <button class="btn btn-primary" type="submit">Buscar</button>
            
            <?php if (!empty($termino_busqueda)): ?>
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary">Limpiar</a>
            <?php endif; ?>
        </div>
    </form>
</div>
<div class="container">
    <?php
        // Usamos num_rows de mysqli_result
        if($datos->num_rows == 0){
            // ALERTA ACTUALIZADA
            echo "<div class='alert alert-info' role='alert'>No se encontraron registros de vendedores.</div>";
        } else {
            // ---- COMIENZA LA TABLA ----
            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
                echo '<th scope="col">ID Vendedor</th>'; // CAMBIADO
                echo '<th scope="col">Nombre</th>';
                echo '<th scope="col">Primer Apellido</th>';
                echo '<th scope="col">Segundo Apellido</th>';
                echo '<th scope="col">Salario Base</th>'; // CAMBIADO
                echo '<th scope="col">Comisión (%)</th>'; // CAMBIADO
                echo ' </tr>';
            echo '</thead>';
            echo '<tbody>';

            // Iteramos sobre los resultados
            while($fila = $datos->fetch_assoc()){ // Usamos fetch_assoc() de mysqli_result
                printf(
                    "<tr> 
                        <td> %s </td>
                        <td>%s</td> 
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                    </tr>", 
                    $contador++,
                    $fila['idVendedor'], 
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Apellido2'],
                    // Formatear Salario y Comisión para la vista (opcional, pero recomendado)
                    number_format($fila['Salario_Base'], 2), // Ejemplo de formato decimal
                    number_format($fila['Porcentaje_Comisión'], 2) // Ejemplo de formato decimal
                );
            }
            echo '</tbody>';
            echo '</table>';
        }
        
        // Es una buena práctica liberar el resultado
        if ($datos->num_rows > 0) {
            $datos->free(); 
        }
    ?>
    <div class="text-center mt-4">
        <a href="../pages/Empleado_Vendedor/menuPrincipal_EV.php" class="btn btn-secondary">Volver al Menú Principal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>