<?php
// Incluimos la conexión a la base de datos de usuarios (donde asumimos que están AMBAS tablas)
include_once(__DIR__.'/../database/conexion_bd_usuarios.php'); 
session_start();

// 1. CAPTURA Y SANITIZACIÓN
$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;

// Si la tabla usa SHA1 (inseguro, pero replicando tu código original)
$p_cifrado = sha1($p); 
// Si usas password_hash(), aquí deberías usar password_verify()
$user_encoded = urlencode($u ?? '');

if (empty($u) || empty($p) ) {
    // Si la página de login es genérica o solo para empleados, ajusta la URL
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

// 2. CONEXIÓN A LA BASE DE DATOS
// Asumimos que la clase fue corregida (ConexionBDUsuarios)
$con = new ConexionBDUsuarios();
$conexion = $con->getConexion();

if (!$conexion) {
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
            u.ID_Puesto AS ID_Referencia, 
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

$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    error_log("Error al preparar la consulta de login: " . $conexion->error);
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit(); 
}

// Los parámetros se ligan CUATRO VECES: Usuario/Email, Password, Usuario/Email, Password
$stmt->bind_param("ssss", $u, $p_cifrado, $u, $p_cifrado);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close(); // Cierra el statement

// 4. MANEJO DE RESULTADOS
if ($res->num_rows == 1) {
    // ÉXITO: Autenticación completa
    $usuario_data = $res->fetch_assoc();
    
    // Almacenamos datos esenciales en la sesión
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['nombre_usuario'] = $usuario_data['Nombre'];
    $_SESSION['perfil'] = $usuario_data['Perfil'];
    $_SESSION['idReferencia'] = $usuario_data['ID_Referencia']; 
    $_SESSION['tipo_usuario'] = $usuario_data['Tipo_Usuario']; // Nuevo: 'empleado' o 'cliente'

    $perfil_usuario = $usuario_data['Perfil'];
    
    // 5. Redirección según el tipo de usuario/perfil
    if ($usuario_data['Tipo_Usuario'] === 'empleado') {
        // Redirecciones de Empleados
        if ($perfil_usuario === 'administrador') {
            header('Location: ../../pages/Empleado_Administrador/menuPrincipal_EA.html');
        } elseif ($perfil_usuario === 'dueno') {
            header('location:../../pages/Empleado_Dueño/menuPrincipal_ED.php');
        } elseif ($perfil_usuario === 'vendedor') {
            header('location:../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
        } 
    } elseif ($usuario_data['Tipo_Usuario'] === 'cliente') {
        // Redirección para Clientes
        header('location:../../pages/ClientePotencial/catalogo.html'); 
    }

    exit(); 
} else {
    // FALLO: Credenciales inválidas
    header("location:../../pages/login/loginEmpleados.php?error=credenciales_invalidas&usuario={$user_encoded}");
    exit();
}
?>