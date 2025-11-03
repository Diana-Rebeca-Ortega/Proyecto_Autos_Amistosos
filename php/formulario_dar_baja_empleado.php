<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas de Empleado - Formulario Auto-Procesado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-primary">Eliminar/Modificar Empleado 🧑‍💼</h2>
</div>

    <?php
        include('controllers/empleado_dao.php');
        
        $empleadoDAO = new EmpleadoDAO();
        $datos = $empleadoDAO->mostrarEmpleado('x');
        //var_dump($datos);

        if(mysqli_num_rows($datos)==0){
            echo "No se encontraron registros";
        }else{
            echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th scope="col">#</th>';
                    echo '<th scope="col">ID Empleado</th>';
                   echo ' <th scope="col">Nombre</th>';
                   echo '<th scope="col">Primer Ap.</th>';
                    echo '<th scope="col">Segundo Ap.</th>';
                    echo '<th scope="col">Puesto</th>';
                    echo '<th scope="col">ACCIONES</th>';
            echo ' </tr>';
            echo '</thead>';
            echo '<tbody>';

while($fila = mysqli_fetch_assoc($datos)){
    printf(
        "<tr> 
            <td> 0 </td>
            <td>%s</td> 
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td> 
                <a href=\"procesar_detalle.php?ID_Empleado=%s\" class=\"btn btn-info btn-sm me-2\"> Detalle </a>  
                
                <a href=\"./controllers/ABCC_Empleados/procesar_cambios_Empleado.php?ID_Empleado=%s\" class=\"btn btn-warning btn-sm me-2\"> Editar </a> 
                
                <a href=\"./controllers/ABCC_Empleados/procesar_baja_Empleado.php?ID_Empleado=%s\" class=\"btn btn-danger btn-sm\"> Eliminar </a>   
            </td>
        </tr>", 
        // ARGUMENTOS DE PRINFTF (10 en total)
        
        // 1. Datos para las celdas de la tabla (5 argumentos)
        $fila['ID_Empleado'],
        $fila['Nombre'],
        $fila['Primer_Apellido'],
        $fila['Segundo_Apellido'],
        $fila['ID_Puesto'],
        
        // 2. ID para el enlace Detalle (1 argumento)
        $fila['ID_Empleado'],
        
        // 3. ID para el enlace Editar (1 argumento - Es el que te interesa en la Línea 52)
        $fila['ID_Empleado'], 
        
        // 4. ID para el enlace Eliminar (1 argumento)
        $fila['ID_Empleado'] 
    );
}
            echo '</tbody>';
            echo '</table>';
        }
    ?>
</body>
</html>
