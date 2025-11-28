<?php
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ../cerrar_sesion.php");
    exit;
  }
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