<?php
include_once(__DIR__.'/../database/conexion_bd_usuarios.php');
session_start();

$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;
$p_cifrado = sha1($p); 
$user_encoded = urlencode($u ?? '');

if (empty($u) || empty($p) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}
// 3. CONEXIÓN A LA BASE DE DATOS
$con = new ConexionBDUsuarios();
$conexion = $con->getConexion();

if (!$conexion) {
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit();
}

// 4. VERIFICACIÓN Y AUTENTICACIÓN SEGURA 
$sql = "SELECT ID_Usuario, Nombre, Perfil, ID_Puesto FROM usuarios WHERE Usuario = ? AND Password = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit(); 
}
$stmt->bind_param("ss", $u, $p_cifrado);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close(); // Cierra el statement

// 5. MANEJO DE RESULTADOS
if ($res->num_rows == 1) {
    // ÉXITO: Autenticación completa
    $usuario_data = $res->fetch_assoc();
    
    // Almacenamos datos esenciales en la sesión
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['nombre_usuario'] = $usuario_data['Nombre'];
    $_SESSION['perfil'] = $usuario_data['Perfil'];
    $_SESSION['idVendedor'] = $usuario_data['ID_Puesto']; // <-- Usar esto en registrar_venta.php

    $perfil_usuario = $usuario_data['Perfil'];
    
    // 2. Redirección según el perfil
    if ($perfil_usuario === 'administrador') {
        header('Location: ../../pages/Empleado_Administrador/menuPrincipal_EA.html');
    } elseif ($perfil_usuario === 'dueno') {
        header('location:../../pages/Empleado_Dueño/menuPrincipal_ED.php');
    } elseif ($perfil_usuario === 'vendedor') {
        // Redireccionar al menú principal del vendedor
        header('location:../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
    } 

    exit(); 
} else {
    // FALLO: Credenciales inválidas (usuario o contraseña)
    header("location:../../pages/login/loginEmpleados.php?error=credenciales_invalidas&usuario={$user_encoded}");
    exit();
}
?>