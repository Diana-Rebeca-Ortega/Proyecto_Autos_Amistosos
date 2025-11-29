<?php

session_start();

include_once('ClientePotencialDAO.php'); 
$cliente_potencial_dao = new ClientePotencialDAO();

$accion = $_POST['accion'] ?? null; 
$nombre = $_POST['nombre'] ?? '';
$apellido1 = $_POST['apellido1'] ?? '';
$apellido2 = $_POST['apellido2'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$email = $_POST['email'] ?? '';
$fuente = $_POST['fuente'] ?? ''; 


if ($accion === 'insertar') {
    $res = $cliente_potencial_dao->insertar($nombre, $apellido1, $apellido2, $direccion, $email, $fuente);
    
    if ($res) {
        header('Location: paginaPrincipal_ClientePotencial.html?status=alta_ok'); 
    } else {
       header('Location: signCP.php?status=alta_error');
    }
} else {
    echo "<h2>Error: Acción no especificada.</h2>";
}

// exit(); // Descomenta esto cuando la redirección esté activa
?>