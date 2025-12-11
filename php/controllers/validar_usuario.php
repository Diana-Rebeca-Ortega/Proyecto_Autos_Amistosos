<?php
// Incluimos la conexión a la base de datos de usuarios (donde asumimos que están AMBAS tablas)
include_once(__DIR__.'/../database/conexion_bd_usuarios.php'); 
session_start();

// 1. CAPTURA Y SANITIZACIÓN
$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;

// Si la tabla usa SHA1 (inseguro, pero replicando tu código original)
$p_cifrado = sha1($p); 
$user_encoded = urlencode($u ?? '');

if (empty($u) || empty($p) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

// 2. CONEXIÓN A LA BASE DE DATOS (Asumimos que usa PDO)
try {
    $con = new ConexionBDUsuarios(); // Esta clase ahora maneja el die() si falla la conexión
    $conexion = $con->getConexion(); // Devuelve el objeto PDO
} catch (Exception $e) {
    // Esto es un fallback, la clase ya debería manejar el error
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
// Los signos de interrogación (?) se mapean a los valores en el array
$parametros = [$u, $p_cifrado, $u, $p_cifrado];

// El método execute() de PDO recibe el array de parámetros directamente
$stmt->execute($parametros);

// 4. MANEJO DE RESULTADOS
// Usamos fetchAll para obtener todas las filas y poder contarlas
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4a. Contar filas (reemplazo de $res->num_rows)
if (count($resultados) === 1) {
    // ÉXITO: Autenticación completa
    $usuario_data = $resultados[0]; // Obtenemos la única fila de resultados
    
    // ... (El resto del código de la sesión y redirección es idéntico) ...
    
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