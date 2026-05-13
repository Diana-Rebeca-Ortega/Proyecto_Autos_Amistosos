<?php
include_once(__DIR__.'/../database/conexion_bd_usuarios.php'); 
session_start();

$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;
$captcha_ingresado = $_POST['captcha_input'] ?? null;

// NOTA IMPORTANTE: sha1() es obsoleto y NO se recomienda. 
// Para un proyecto final, es mucho mejor usar password_hash() y password_verify().
//$p_cifrado = sha1($p); 
$p_cifrado = $p;
$user_encoded = urlencode($u ?? '');

// 1. Validar campos vacíos
if (empty($u) || empty($p) || empty($captcha_ingresado) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

// 2. CORRECCIÓN CLAVE: El bloque del CAPTCHA ahora es accesible
$captcha_sesion = $_SESSION['captcha_code'] ?? null;
if (strtolower($captcha_ingresado) !== strtolower($captcha_sesion)) {
    unset($_SESSION['captcha_code']); 
    header("location:../../pages/login/loginEmpleados.php?error=captcha_invalido&usuario={$user_encoded}");
    exit();
}
// Limpiar la variable de sesión del CAPTCHA después de una validación exitosa
unset($_SESSION['captcha_code']); 

// 3. Uso del Patrón SINGLETON (Punto 8)
try {
    // LLAMADA CLAVE: Usar el método estático para obtener la única instancia
    $con = ConexionBDUsuarios::getInstancia(); 
    $conexion = $con->getConexion(); 
} catch (Exception $e) {
    // Captura la excepción si la conexión falla (lanzada desde el constructor Singleton)
    error_log("Error de conexión a la BD: " . $e->getMessage());
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit();
}

// Lógica de Consulta corregida para que coincida con tu tabla real
$sql = "
    SELECT 
        u.idUsuario AS ID_Usuario, 
        u.nombre_completo AS Nombre, 
        u.rol AS Perfil, 
        u.idUsuario AS ID_Referencia, 
        'empleado' AS Tipo_Usuario
    FROM usuarios u
    WHERE u.usuario_o_email = ? AND u.password = ?
";

$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    error_log("Error al preparar la consulta: " . print_r($conexion->errorInfo(), true));
    header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    exit(); 
}

// IMPORTANTE: Solo pasamos 2 parámetros porque solo hay 2 '?' en el SQL de arriba
$parametros = [$u, $p_cifrado]; 
$stmt->execute($parametros);

$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica de Redirección (Sin cambios, ya estaba bien)
if (count($resultados) === 1) {
    $usuario_data = $resultados[0];
    $_SESSION['usuario_autenticado'] = true;
    $_SESSION['nombre_usuario'] = $usuario_data['Nombre'];
    $_SESSION['perfil'] = $usuario_data['Perfil'];
    $_SESSION['idReferencia'] = $usuario_data['ID_Referencia']; 
    $_SESSION['tipo_usuario'] = $usuario_data['Tipo_Usuario'];

    $perfil_usuario = $usuario_data['Perfil'];
    if ($perfil_usuario === 'vendedor') {
        $_SESSION['idVendedor'] = $usuario_data['ID_Referencia']; 
    }
    if ($usuario_data['Tipo_Usuario'] === 'empleado') {
        if ($perfil_usuario === 'administrador') {
            header('Location: /PROYECTO/admin/principal');
        } elseif ($perfil_usuario === 'dueno') {
            header('Location: /PROYECTO/dueño/principal');
        } elseif ($perfil_usuario === 'vendedor') {
            header('Location: /PROYECTO/vendedor/principal');
        } 
    } elseif ($usuario_data['Tipo_Usuario'] === 'cliente') {
        header('Location: /PROYECTO/cliente/catalogo');
    }

    exit(); 
} else {
    header("location:../../pages/login/loginEmpleados.php?error=credenciales_invalidas&usuario={$user_encoded}");
    exit();
}
?>