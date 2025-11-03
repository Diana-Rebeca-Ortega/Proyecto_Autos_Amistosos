<?php

    //var_dump($_GET['nc']);

    //echo "Eliminar alumno"  . $_GET['nc'] ;

    include(__DIR__.'/../empleado_dao.php');
    $empleadoDAO = new EmpleadoDAO();
    if($empleadoDAO->eliminarEmpleado($_GET['ID_Empleado'])){
        //echo "Registro ELIMINADO correctamente";
        header("location: ../../formulario_dar_baja_empleado.php");
    }else{
        echo "ERROR en la eliminacion";
    }

?>