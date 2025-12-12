<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ./controllers/cerrar_sesion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas y Edición de Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 900px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">Eliminar Vendedor 💰</h2>
    
    <?php
        include('./controllers/empleado_dao.php');
        $vendedorDAO = new EmpleadoDAO();
        
        // 1. Ejecutar la consulta para obtener los datos
        $statement = $vendedorDAO->mostrarEmpleado('x');
        
        // 2. Obtener TODAS las filas en un array asociativo usando PDO
        // El método mostrarEmpleado debe devolver el objeto PDOStatement.
        $filas = $statement->fetchAll(PDO::FETCH_ASSOC);

        // 3. Verificar si se encontraron registros usando count() en el array
        if(count($filas) == 0){
            echo "<p class='alert alert-warning'>No se encontraron registros de vendedores.</p>";
        }else{
            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th scope="col">#</th>';
                    echo '<th scope="col">ID Vendedor</th>';
                    echo ' <th scope="col">Nombre</th>';
                    echo '<th scope="col">Apellido 1</th>';
                    echo '<th scope="col">Apellido 2</th>';
                    echo '<th scope="col">Salario Base</th>';
                    echo '<th scope="col">Comisión %</th>';
                    echo '<th scope="col">ACCIONES</th>';
                echo ' </tr>';
            echo '</thead>';
            echo '<tbody>';

            $contador = 1; // Variable para el contador de filas
            
            // 4. Recorrer el array de resultados ($filas) con un bucle foreach (PDO style)
            foreach($filas as $fila){
                printf(
                    "<tr> 
                        <td> %d </td> 
                        <td>%s</td> 
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%.2f</td> <td>%.2f</td> <td> 
                            <a href=\"./controllers/ABCC_Empleados/procesar_baja_Empleado.php?idVendedor=%s\" 
                            class=\"btn btn-danger btn-sm\" onclick=\"return confirm('¿Estás seguro de que deseas eliminar a %s? Esta acción es permanente.');\"> Eliminar </a>
                        </td>
                    </tr>", 
                    
                    // ARGUMENTOS DE PRINFTF
                    $contador++,
                    $fila['idVendedor'],
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Apellido2'],
                    $fila['Salario_Base'],
                    $fila['Porcentaje_Comisión'],
                    $fila['idVendedor'], // Argumento para el enlace (href)
                    $fila['Nombre'] // Argumento para el mensaje de confirmación
                );
            }
            echo '</tbody>';
            echo '</table>';
        }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>