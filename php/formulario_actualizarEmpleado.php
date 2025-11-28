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
        /* Aumentamos el ancho para acomodar las nuevas columnas */
        .container { max-width: 900px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">Editar Vendedor 💰</h2>
</div>

    <?php
        // 📢 CAMBIO 3: Incluir el nuevo DAO
        include('controllers/empleado_dao.php');
        
        // 📢 CAMBIO 4: Instanciar la nueva clase y llamar al nuevo método
        $vendedorDAO = new EmpleadoDAO();
        $datos = $vendedorDAO->mostrarEmpleado('x'); // Llama a mostrarVendedor()
        //var_dump($datos);

        if(mysqli_num_rows($datos)==0){
            echo "No se encontraron registros";
        }else{
            echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th scope="col">#</th>';
                    // 📢 CAMBIO 5: Encabezados de tabla actualizados
                    echo '<th scope="col">ID Vendedor</th>';
                    echo ' <th scope="col">Nombre</th>';
                    echo '<th scope="col">Apellido 1</th>';
                    echo '<th scope="col">Apellido 2</th>';
                    echo '<th scope="col">Salario Base</th>';
                    echo '<th scope="col">Comisión %</th>'; // Nueva columna
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
                <a href=\"./controllers/ABCC_Empleados/procesar_cambios_Empleado.php?idVendedor=%s\" class=\"btn btn-warning btn-sm me-2\"> Editar </a> 
            </td>
        </tr>", 
        // ARGUMENTOS DE PRINFTF
        
        // 1. Contador de filas (%d)
        $contador++,
        
        // 2-7. Datos para las celdas de la tabla (%s)
        $fila['idVendedor'],
        $fila['Nombre'],
        $fila['Apellido1'],
        $fila['Apellido2'],
        $fila['Salario_Base'],
        $fila['Porcentaje_Comisión'],
        
        // 8. ID para el enlace "Editar" (%s)
        $fila['idVendedor'] 
    );
}
            echo '</tbody>';
            echo '</table>';
        }
    ?>
</body>
</html>