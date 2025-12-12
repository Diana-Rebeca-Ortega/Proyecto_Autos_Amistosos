<?php
session_start();

// Control de acceso: Usa la Pretty URL para redirigir al login si falla la sesión
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
    <title>Bajas y Edición de Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1000px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">Eliminar Vendedor 💰</h2>

    <?php
        // 1. CORRECCIÓN DE RUTA: Ajustar la ruta relativa. 
        // Si este archivo está en PROYECTO/php/ y el DAO en PROYECTO/php/controllers/
        require_once('controllers/empleado_dao.php');
        
        $vendedorDAO = new EmpleadoDAO();
        
        // El método obtenerTodos ya está corregido a PDO y devuelve un PDOStatement
        $stmt = $vendedorDAO->obtenerTodos(); 
        
        // 2. CORRECCIÓN PDO: Usar rowCount() para saber cuántos resultados hay
        if($stmt->rowCount() == 0){
            echo "<p class='alert alert-warning'>No se encontraron registros de vendedores.</p>";
        } else {
            // CORRECCIÓN PDO: Cambiar la forma de imprimir la tabla y recorrer los resultados
            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th scope="col">#</th>';
                    echo '<th scope="col">ID Vendedor</th>';
                    echo '<th scope="col">Nombre</th>';
                    echo '<th scope="col">Apellido 1</th>';
                    echo '<th scope="col">Apellido 2</th>';
                    echo '<th scope="col">Salario Base</th>';
                    echo '<th scope="col">Comisión %</th>';
                    echo '<th scope="col">ACCIONES</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $contador = 1; 
            
            // 3. CORRECCIÓN PDO: Usar fetchAll o un bucle while con fetch(PDO::FETCH_ASSOC)
            while($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
                // Uso de echo para interpolar, es más legible que printf con tantos argumentos
                echo "<tr>"; 
                echo "<td>" . $contador++ . "</td>"; 
                echo "<td>" . htmlspecialchars($fila['idVendedor']) . "</td>"; 
                echo "<td>" . htmlspecialchars($fila['Nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['Apellido1']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['Apellido2']) . "</td>";
                echo "<td>" . number_format($fila['Salario_Base'], 2) . "</td>";
                echo "<td>" . $fila['Porcentaje_Comisión'] . "</td>";
                echo "<td>"; 
                
                // Botón de Eliminar
             echo "<a href=\"/PROYECTO/empleados/procesar/baja?idVendedor=" . htmlspecialchars($fila['idVendedor']) . "\" 
             class=\"btn btn-danger btn-sm\" 
             onclick=\"return confirm('¿Estás seguro de que deseas eliminar a este empleado permanentemente?');\"> Eliminar </a>";
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