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
</div>

    <?php
        include('./controllers/empleado_dao.php');
        $vendedorDAO = new EmpleadoDAO();
        $datos = $vendedorDAO->mostrarEmpleado('x');
       

        if(mysqli_num_rows($datos)==0){
            echo "No se encontraron registros";
        }else{
            echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th scope="col">#</th>';
                    // Encabezados de tabla actualizados
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
while($fila = mysqli_fetch_assoc($datos)){
    printf(
        "<tr> 
            <td> %d </td> 
            <td>%s</td> 
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td> 
            <td> 
                <a href=\"./controllers/ABCC_Empleados/procesar_baja_Empleado.php?idVendedor=%s\" 
                class=\"btn btn-danger btn-sm\" onclick=\"return confirm('¿Estás seguro de que deseas eliminar a este empleado permanentemente?');\"> Eliminar </a>

            </td>
        </tr>", 
        // ARGUMENTOS DE PRINFTF
        
        // 1. Contador de filas
        $contador++,
        
        // 2. Datos para las celdas de la tabla (5 argumentos)
       
        $fila['idVendedor'],
        $fila['Nombre'],
        $fila['Apellido1'],
        $fila['Apellido2'],
        $fila['Salario_Base'],
        $fila['Porcentaje_Comisión'], // Nuevo campo
        $fila['idVendedor'],
        $fila['idVendedor'], 
        $fila['idVendedor'] 
    );
}
            echo '</tbody>';
            echo '</table>';
        }
    ?>
</body>
</html>