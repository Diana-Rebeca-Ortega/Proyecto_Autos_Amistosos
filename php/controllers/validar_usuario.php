<?php
include_once(__DIR__.'/../database/conexion_bd_usuarios.php');

session_start();
//1. Recepcion de parametros 
$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;

//1.5cifrar la contraseña 
$p_cifrado= sha1($p);

$user_encoded = urlencode($u ?? '');

// 2. Verificación básica de que los campos estén presentes
if (empty($u) || empty($p) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

// 3. CONEXIÓN A LA BASE DE DATOS
// -----------------------------------------------------------------
$con = new ConexionBDUsuarios();
$conexion = $con->getConexion();

if (!$conexion) { // Verifica si la conexión falló
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit();
}

// 4. VERIFICACIÓN Y AUTENTICACIÓN SEGURA (Consulta completa)
// ---------------------------------------------------------------------
$u_cifrado = ($u);
$p_cifrado = sha1($p);

// 4a. Primera consulta: ¿Coinciden Usuario, Contraseña?
$sql = "SELECT ID_Usuario, Nombre, Perfil FROM usuarios WHERE Usuario = ? AND Password = ? ";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit(); 
}

$stmt->bind_param("ss", $u_cifrado, $p_cifrado);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close(); // Cierra el primer statement

// 5. MANEJO DE RESULTADOS DETALLADO
// ---------------------------------------------------------------------

if ($res->num_rows == 1) {
    // ÉXITO: Autenticación completa
    $usuario_data = $res->fetch_assoc();
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['nombre_usuario'] = $usuario_data['Nombre']; 
    $_SESSION['perfil'] = $usuario_data['Perfil']; // <-- Capturamos el perfil en la sesión
    // 1. Obtenemos el perfil del usuario
    $perfil_usuario = $usuario_data['Perfil'];
    
    // 2. Validamos el perfil para direccionar
    if ($perfil_usuario === 'administrador') {
        // Redireccionar al administrador
        header('Location: ../../pages/Empleado_Administrador/menuPrincipal_EA.html');
    } elseif ($perfil_usuario === 'dueno') {
        // Redireccionar al dueño (usando tu ruta original)
        header('location:../../pages/Empleado_Dueño/menuPrincipal_ED.php');
    } elseif ($perfil_usuario === 'vendedor') {
        // Redireccionar al vendedor (usando tu ruta original)
        header('location:../../pages/Empleado_Vendedor/menuPrincipal_EV.html');
    } 

    exit(); // ¡Siempre importante llamar a exit() después de un header Location!
} else {
    // FALLO: Hacer verificaciones más detalladas para dar una alerta específica

    // --- 5a. Verificar si el usuario existe (Ignorando la contraseña y el perfil) ---
    $sql_user_only = "SELECT ID_Usuario FROM usuarios WHERE Usuario = ?";
    $stmt_user = $conexion->prepare($sql_user_only);
    $stmt_user->bind_param("s", $u_cifrado);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    $stmt_user->close(); // Cierra el segundo statement
    
    if ($res_user->num_rows == 0) {
        // ERROR ESPECÍFICO: Usuario no existe
        header("location:../../pages/login/loginEmpleados.php?error=usuario_invalido&usuario={$user_encoded}");
        exit();
    }

    // --- 5b. Verificar si la contraseña es incorrecta (Ya sabemos que el usuario existe) ---
    
    $sql_user_pass = "SELECT ID_Usuario FROM usuarios WHERE Usuario = ? AND Password = ?";
    $stmt_up = $conexion->prepare($sql_user_pass);
    $stmt_up->bind_param("ss", $u_cifrado, $p_cifrado);
    $stmt_up->execute();
    $res_up = $stmt_up->get_result();
    $stmt_up->close(); // Cierra el tercer statement

    if ($res_up->num_rows == 0) {
        // ERROR ESPECÍFICO: Contraseña INCORRECTA
        header("location:../../pages/login/loginEmpleados.php?error=password_invalida&usuario={$user_encoded}&");
        exit();
    }
    
   
}
// La conexión se cierra automáticamente al final del script si no se usa mysqli::close()
?>