<?php

    include(__DIR__.'/../empleado_dao.php');
    $empleadoDAO = new EmpleadoDAO();
    if($empleadoDAO->eliminarEmpleado($_GET['idVendedor'])){
        //echo "Registro ELIMINADO correctamente";
       header("location: ../../formulario_dar_baja_empleado.php?status=success_delete");
    exit();
    }else{
        echo "ERROR en la eliminacion";
    }

?>