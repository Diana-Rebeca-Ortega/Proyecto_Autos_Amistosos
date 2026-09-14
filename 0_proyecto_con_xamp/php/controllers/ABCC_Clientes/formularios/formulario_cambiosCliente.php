<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: /PROYECTO/cerrar_sesion");
    exit;
}
include_once('../clienteDAO.php'); 

$cliente_obj = new clienteDAO();
// $datos ahora es un array asociativo de clientes, o un array vacío [] si no hay registros
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
        // 🚨 CAMBIO CRÍTICO AQUÍ: Usamos count() para contar elementos en el array
        $num_filas = is_array($datos) ? count($datos) : 0;
        
        if($num_filas == 0){
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

            // 🚨 CAMBIO CRÍTICO AQUÍ: Usamos foreach para iterar sobre el array
            foreach($datos as $fila){
                printf(
                    "<tr> 
                        <td> %s </td>
                        <td>%s</td> 
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td> 
                            <a href=\"./actualizacion_formCliente.php?accion=actualizar&id=%s\" class=\"btn btn-warning btn-sm\"> Modificar </a> 
                        </td>
                    </tr>", 
                    
                    // ARGUMENTOS DE PRINFTF
                    $contador++,
                    $fila['idCliente'], // ID Cliente
                    $fila['Nombre'],
                    $fila['Apellido1'],
                    $fila['Telefono'],
                    $fila['Email'],
                    
                    $fila['idCliente'], // ID para el enlace Modificar
                    $fila['Nombre'] . ' ' . $fila['Apellido1'] // Este argumento se ignorará, ya que solo hay 7 placeholders en el string de formato
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