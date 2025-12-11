<?php
// Incluimos la conexión a la base de datos de usuarios (donde asumimos que están AMBAS tablas)
include_once(__DIR__.'/../database/conexion_bd_usuarios.php'); 
session_start();

// 1. CAPTURA, SANITIZACIÓN Y CAPTURA DEL CAPTCHA
$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;
$captcha_ingresado = $_POST['captcha_input'] ?? null; // <-- NUEVA CAPTURA

// Si la tabla usa SHA1 (inseguro, pero replicando tu código original)
$p_cifrado = sha1($p); 
$user_encoded = urlencode($u ?? '');

// Verificar campos vacíos (incluyendo contraseña para que no se procese sin ella)
if (empty($u) || empty($p) || empty($captcha_ingresado) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

// ====================================================================
// 🚨 MODIFICACIÓN CRÍTICA: VERIFICACIÓN DEL CAPTCHA 🚨
// ====================================================================

$captcha_sesion = $_SESSION['captcha_code'] ?? null;

// 1. Verificar si el CAPTCHA es correcto (comparación insensible a mayúsculas/minúsculas)
if (strtolower($captcha_ingresado) !== strtolower($captcha_sesion)) {
    // 2. Limpiar el código usado (buena práctica)
    unset($_SESSION['captcha_code']); 
    
    // 3. Redirigir y salir si es inválido
    header("location:../../pages/login/loginEmpleados.php?error=captcha_invalido&usuario={$user_encoded}");
    exit();
}

// Limpiar el código después de una verificación exitosa (se usa una sola vez)
unset($_SESSION['captcha_code']); 



// 2. CONEXIÓN A LA BASE DE DATOS (Asumimos que usa PDO)
try {
    $con = new ConexionBDUsuarios(); 
    $conexion = $con->getConexion(); 
} catch (Exception $e) {
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit();
}


// 3. VERIFICACIÓN Y AUTENTICACIÓN SEGURA EN AMBAS TABLAS (Empleados y Clientes)
$sql = "
    (
        SELECT 
            u.ID_Usuario, 
            u.Nombre, 
            u.Perfil, 
            u.ID_Usuario AS ID_Referencia, 
            'empleado' AS Tipo_Usuario
        FROM usuarios u
        WHERE u.Usuario = ? AND u.Password = ?
    )
    UNION ALL
    (
        SELECT 
            uc.idUsuario AS ID_Usuario, 
            uc.Email AS Nombre,
            'cliente' AS Perfil, 
            uc.idCliente_Potencial AS ID_Referencia, 
            'cliente' AS Tipo_Usuario
        FROM usuario_cliente uc
        WHERE uc.Email = ? AND uc.Password = ?
    )
";

// 3a. Preparar la consulta
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    error_log("Error al preparar la consulta de login: " . print_r($conexion->errorInfo(), true));
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit(); 
}

// 3b. Ejecutar la consulta pasando el array de parámetros
$parametros = [$u, $p_cifrado, $u, $p_cifrado];
$stmt->execute($parametros);

// 4. MANEJO DE RESULTADOS
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($resultados) === 1) {
    // ÉXITO: Autenticación completa
    $usuario_data = $resultados[0];
    
    // Almacenamos datos esenciales en la sesión
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['nombre_usuario'] = $usuario_data['Nombre'];
    $_SESSION['perfil'] = $usuario_data['Perfil'];
    $_SESSION['idReferencia'] = $usuario_data['ID_Referencia']; 
    $_SESSION['tipo_usuario'] = $usuario_data['Tipo_Usuario'];

    $perfil_usuario = $usuario_data['Perfil'];
    if ($perfil_usuario === 'vendedor') {
        $_SESSION['idVendedor'] = $usuario_data['ID_Referencia']; 
    }
    
    // 5. Redirección según el tipo de usuario/perfil
    if ($usuario_data['Tipo_Usuario'] === 'empleado') {
        if ($perfil_usuario === 'administrador') {
            header('Location: ../../pages/Empleado_Administrador/menuPrincipal_EA.html');
        } elseif ($perfil_usuario === 'dueno') {
            header('location:../../pages/Empleado_Dueño/menuPrincipal_ED.php');
        } elseif ($perfil_usuario === 'vendedor') {
            header('location:../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
        } 
    } elseif ($usuario_data['Tipo_Usuario'] === 'cliente') {
        header('location:../../pages/ClientePotencial/catalogo.html'); 
    }

    exit(); 
} else {
    // FALLO: Credenciales inválidas
    header("location:../../pages/login/loginEmpleados.php?error=credenciales_invalidas&usuario={$user_encoded}");
    exit();
}
?>