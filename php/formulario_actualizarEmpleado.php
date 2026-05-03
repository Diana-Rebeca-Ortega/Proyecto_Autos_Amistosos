<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: /PROYECTO/cerrar_sesion");
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
        // 📢 CAMBIO 3: Incluir el nuevo DAO
       require_once('controllers/empleado_dao.php');
        
        $vendedorDAO = new EmpleadoDAO();
        
        // Ejecutar la consulta para obtener el PDOStatement
        $statement = $vendedorDAO->mostrarEmpleado('x'); 
        
        // 1. **CORRECCIÓN PDO:** Obtener todas las filas en un array asociativo
        $filas = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($datos->rowCount() == 0){
            echo "<p class='alert alert-warning'>No se encontraron registros.</p>";
        }else{
            echo '<table class="table">';
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
while($fila = $datos->fetch(PDO::FETCH_ASSOC)){
                echo "<tr>"; 
                echo "<td>" . $contador++ . "</td>"; 
                echo "<td>" . htmlspecialchars($fila['idVendedor']) . "</td>"; 
                echo "<td>" . htmlspecialchars($fila['Nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['Apellido1']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['Apellido2']) . "</td>";
                echo "<td>" . number_format($fila['Salario_Base'], 2) . "</td>";
                echo "<td>" . $fila['Porcentaje_Comisión'] . "</td>";
                echo "<td>"; 
                
                echo "<a href=\"/PROYECTO/empleados/editar/" . htmlspecialchars($fila['idVendedor']) . "\" 
                        class=\"btn btn-warning btn-sm\"> Editar </a>";
                
                echo "</td>";
                echo "</tr>";
            }
            
            echo '</tbody>';
            echo '</table>';
        }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>