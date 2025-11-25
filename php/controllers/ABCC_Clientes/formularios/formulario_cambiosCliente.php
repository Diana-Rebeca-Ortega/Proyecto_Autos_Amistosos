<?php
include_once('../clienteDAO.php'); 

$cliente_obj = new clienteDAO();
// El método obtenerTodos() de la clase Cliente devuelve un objeto mysqli_result
$datos = $cliente_obj->obtenerTodos(); 

$contador = 1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas y Cambios de Cliente</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 900px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-primary">Modificar Cliente 👤</h2>
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
                echo ' <th scope="col">Nombre</th>';
                echo '<th scope="col">Primer Ap.</th>';
                echo '<th scope="col">Teléfono</th>';
                echo '<th scope="col">Email</th>';
                echo '<th scope="col">ACCIONES</th>';
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
                        <td> 
                           
                            <a href=\"./actualizacion_formCliente.php?accion=actualizar&id=%s\" class=\"btn btn-warning btn-sm\"
                            > Modificar </a> 
                        </td>
                    </tr>", 
                    
                    // ARGUMENTOS DE PRINFTF (8 en total)
                    // 1. Datos para las celdas de la tabla (6 argumentos)
                    $contador++,
                    $fila['idCliente'], // ID Cliente
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Telefono'],
                    $fila['Email'],
                    
                    // 3. ID para el enlace Eliminar (1 argumento)
                    $fila['idCliente'],
                    
                    // 4. Nombre para la alerta de confirmación (1 argumento)
                    $fila['Nombre'] . ' ' . $fila['Apellido1']
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