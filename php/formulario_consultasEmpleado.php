<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ./controllers/cerrar_sesion.php");
    exit;
}
 
include('./controllers/empleado_dao.php');
$vendedor_obj = new EmpleadoDAO();

$termino_busqueda = $_GET['busqueda'] ?? '';
$datos_array = []; // Inicializamos la variable de array
$statement = null; // Inicializamos la variable PDOStatement

// 1. DECIDIR QUÉ MÉTODO LLAMAR y obtener el PDOStatement
if (!empty($termino_busqueda)) {
    // Almacenamos el PDOStatement devuelto por el DAO
    $statement = $vendedor_obj->buscarVendedores($termino_busqueda); 
} else {
    // Almacenamos el PDOStatement devuelto por el DAO
    $statement = $vendedor_obj->obtenerTodos(); 
}

// 2. CORRECCIÓN CLAVE: Extraer todos los resultados del PDOStatement a un ARRAY
// Hacemos esta conversión solo si el DAO devolvió un objeto PDOStatement válido
if ($statement instanceof PDOStatement) {
    $datos_array = $statement->fetchAll(PDO::FETCH_ASSOC);
}
// NOTA: Si $statement no es un PDOStatement (ej. es false/null por error), $datos_array será [], y count() funcionará.

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
        // 3. ¡SOLUCIÓN! Usamos count() sobre el array extraído ($datos_array)
        // Esta línea ya NO dará error porque $datos_array es un ARRAY (o está vacío)
        if(count($datos_array) == 0){ 
            echo "<div class='alert alert-info' role='alert'>No se encontraron registros de vendedores.</div>";
        } else {
            // ---- COMIENZA LA TABLA ----
            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
                echo '<th scope="col">ID Vendedor</th>';
                echo '<th scope="col">Nombre</th>';
                echo '<th scope="col">Primer Apellido</th>';
                echo '<th scope="col">Segundo Apellido</th>';
                echo '<th scope="col">Salario Base</th>';
                echo '<th scope="col">Comisión (%)</th>';
                echo ' </tr>';
            echo '</thead>';
            echo '<tbody>';

            // 4. Iteramos sobre el array extraído ($datos_array) con foreach
            foreach($datos_array as $fila){ 
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
                    htmlspecialchars($fila['idVendedor']), 
                    htmlspecialchars($fila['Nombre']),
                    htmlspecialchars($fila['Apellido1']),
                    htmlspecialchars($fila['Apellido2']),
                    // Formatear Salario y Comisión
                    number_format($fila['Salario_Base'], 2), 
                    number_format($fila['Porcentaje_Comisión'], 2) 
                );
            }
            echo '</tbody>';
            echo '</table>';
        }
    ?>
    <div class="text-center mt-4">
        <a href="../pages/Empleado_Vendedor/menuPrincipal_EV.php" class="btn btn-secondary">Volver al Menú Principal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>