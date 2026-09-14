<?php
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: /PROYECTO/cerrar_sesion");
    exit;
  }
include_once(ROOT_PATH . 'php/controllers/empleado_dao.php');
    $empleadoDAO = new EmpleadoDAO();
    if($empleadoDAO->eliminarEmpleado($_GET['idVendedor'])){
        //echo "Registro ELIMINADO correctamente";
      header("Location: /PROYECTO/empleados/eliminar?status=success_delete");
    exit();
    }else{
       header("Location: /PROYECTO/empleados/eliminar?status=error_delete");
       exit;
    }

?>