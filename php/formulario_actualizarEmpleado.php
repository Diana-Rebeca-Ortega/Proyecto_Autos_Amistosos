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
    <title>Edición de Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 900px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">Editar Vendedor 💰</h2>

    <?php
        // 📢 Incluir el DAO
        include('controllers/empleado_dao.php');
        
        $vendedorDAO = new EmpleadoDAO();
        
        // Ejecutar la consulta para obtener el PDOStatement
        $statement = $vendedorDAO->mostrarEmpleado('x'); 
        
        // 1. **CORRECCIÓN PDO:** Obtener todas las filas en un array asociativo
        $filas = $statement->fetchAll(PDO::FETCH_ASSOC);

        // 2. **CORRECCIÓN PDO:** Usar count() para verificar el número de filas
        if(count($filas) == 0){
            echo "<p class='alert alert-warning'>No se encontraron registros.</p>";
        } else {
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
            
            // 3. **CORRECCIÓN PDO:** Recorrer el array de resultados con foreach
            foreach($filas as $fila){
                printf(
                    "<tr> 
                        <td> %d </td> 
                        <td>%s</td> 
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%.2f</td> <td>%.2f</td> <td> 
                            <a href=\"./controllers/ABCC_Empleados/procesar_cambios_Empleado.php?idVendedor=%s\" class=\"btn btn-warning btn-sm me-2\"> Editar </a> 
                        </td>
                    </tr>", 
                    
                    // ARGUMENTOS DE PRINFTF
                    
                    // 1. Contador de filas
                    $contador++,
                    
                    // 2-7. Datos para las celdas de la tabla
                    $fila['idVendedor'],
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Apellido2'],
                    $fila['Salario_Base'],
                    $fila['Porcentaje_Comisión'],
                    
                    // 8. ID para el enlace "Editar"
                    $fila['idVendedor'] 
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