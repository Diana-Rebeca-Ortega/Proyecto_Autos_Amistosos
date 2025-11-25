<?php
include_once('../clienteDAO.php'); 

$cliente_obj = new clienteDAO();

// 1. CAPTURAR EL TÉRMINO DE BÚSQUEDA
// Captura el valor del campo 'busqueda' si se envió por GET
$termino_busqueda = $_GET['busqueda'] ?? '';

// 2. DECIDIR QUÉ MÉTODO LLAMAR
if (!empty($termino_busqueda)) {
    // Si hay un término, llama al nuevo método de búsqueda
    $datos = $cliente_obj->buscarClientes($termino_busqueda); 
} else {
    // Si no hay término, carga todos los clientes (comportamiento original)
    $datos = $cliente_obj->obtenerTodos(); 
}

$contador = 1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Clientes Registrados</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1100px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-info">🔍 Consulta de Clientes Registrados</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar por Nombre, Apellido o Email" 
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
            echo "<div class='alert alert-info' role='alert'>No se encontraron registros de clientes.</div>";
        } else {
            // ---- COMIENZA LA TABLA ----
            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
                echo '<th scope="col">ID Cliente</th>';
                echo '<th scope="col">Nombre</th>';
                echo '<th scope="col">Primer Apellido</th>';
                echo '<th scope="col">Segundo Apellido</th>';
                echo '<th scope="col">Dirección</th>'; // Añadida para más detalles
                echo '<th scope="col">Teléfono</th>';
                echo '<th scope="col">Email</th>';
                // La columna ACCIONES se elimina
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
                        <td>%s</td>
                    </tr>", 
                    
                    // ARGUMENTOS DE PRINFTF (8 en total, ahora solo para la vista)
                    $contador++,
                    $fila['idCliente'], // ID Cliente
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Apellido2'], // Se añadió el Segundo Apellido
                    $fila['Direccion'], // Se añadió la Dirección
                    $fila['Telefono'],
                    $fila['Email']
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
        <a href="../menuPrincipal.php" class="btn btn-secondary">Volver al Menú Principal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>