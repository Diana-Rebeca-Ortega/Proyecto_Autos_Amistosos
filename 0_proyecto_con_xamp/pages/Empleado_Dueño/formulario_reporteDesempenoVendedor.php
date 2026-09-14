<?php
session_start();

// 1. Lógica de Seguridad
if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
      header("Location: /PROYECTO/cerrar_sesion");
    exit;
}

// 2. Inclusión del DAO (Ajusta la ruta si es necesario)
// Ruta asumida: Desde pages/Empleado_Dueno/ hacia php/controllers/Reportes/
include_once('../../php/controllers/Reportes/ReporteDAO.php'); 

// 3. Obtención de Parámetros de Fechas
// Usaremos fechas por defecto para que siempre se vea algo, pero idealmente 
// deberías crear un pequeño formulario en la vista para que el usuario las ingrese.
$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01', strtotime('-1 month')); // Primer día del mes pasado
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d'); // Fecha de hoy

// 4. Llama al DAO y ejecuta el Procedimiento Almacenado
$reporte_obj = new ReporteDAO(); 
$datos_reporte = $reporte_obj->obtenerReporteDesempenoVendedor($fecha_inicio, $fecha_fin);

$contador = 1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Desempeño de Vendedores</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 900px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">📊 Reporte de Desempeño de Vendedores</h2>
    <p class="mb-3">
        **Periodo Consultado:** Desde **<?php echo htmlspecialchars($fecha_inicio); ?>** hasta **<?php echo htmlspecialchars($fecha_fin); ?>**
    </p>

    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
            </div>
            <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generar Reporte</button>
            </div>
        </div>
    </form>
    
    <?php
        $num_filas = is_array($datos_reporte) ? count($datos_reporte) : 0;
        
        if($num_filas == 0){
            echo "<div class='alert alert-warning' role='alert'>No se encontraron ventas para este periodo.</div>";
        } else {
            // ---- COMIENZA LA TABLA ----
            echo '<table class="table table-striped table-bordered table-hover">';
            echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
                echo '<th scope="col">ID Vendedor</th>';
                echo '<th scope="col">Vendedor</th>';
                echo '<th scope="col">Total Ventas (Unidades)</th>';
                echo '<th scope="col">Monto Total Vendido</th>';
                echo '<th scope="col" class="bg-info text-white">Comisión Total Ganada</th>';
                echo ' </tr>';
            echo '</thead>';
            echo '<tbody>';

            // 5. Iteración del array (resultado del SP)
            foreach($datos_reporte as $fila){
                printf(
                    "<tr> 
                        <td> %s </td>
                        <td>%s</td> 
                        <td>%s</td>
                        <td>%s</td>
                        <td>$ %s</td>
                        <td>$ %s</td>
                    </tr>", 
                    
                    $contador++,
                    $fila['idVendedor'],
                    $fila['Nombre_Vendedor'],
                    $fila['Total_Ventas'],
                    // Usamos number_format para formatear los números a moneda
                    number_format($fila['Total_Vendido'], 2, '.', ','),
                    number_format($fila['Comision_Total'], 2, '.', ',')
                );
            }
            echo '</tbody>';
            echo '</table>';
        }
    ?>
    <div class="text-center mt-4">
        <a href="./menuPrincipal_ED.php" class="btn btn-secondary">Volver al Menú Principal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>