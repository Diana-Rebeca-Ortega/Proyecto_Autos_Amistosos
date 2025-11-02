<?php

include("prueba_conexion.php");

$conexion = new ConexionBDautosAmistosos();

$con = $conexion->getConexion();

var_dump($con);

?>
