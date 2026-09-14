<?php
include_once(__DIR__.'/../database/conexion_bd_usuarios.php'); 
session_start();

$u = $_POST['usuario'] ?? null;
$p = $_POST['password'] ?? null;

$p_cifrado = hash('sha256', $p);
$user_encoded = urlencode($u ?? '');

if (empty($u) || empty($p) ) {
    header("location:../../pages/login/loginEmpleados.php?error=campos_vacios&usuario={$user_encoded}");
    exit();
}

try {
    $con = ConexionBDUsuarios::getInstancia(); 
    $conexion = $con->getConexion();

    $sql = "
    (       
        SELECT u.idUsuario AS ID_Usuario, u.nombre_completo AS Nombre, u.rol AS Perfil, u.idUsuario AS ID_Referencia, 'empleado' AS Tipo_Usuario
        FROM usuarios u
        WHERE u.usuario_o_email = ? AND u.password = ?
    )
    UNION ALL
    (
        SELECT uc.idUsuario AS ID_Usuario, uc.Email AS Nombre, 'cliente' AS Perfil, uc.idCliente_Potencial AS ID_Referencia, 'cliente' AS Tipo_Usuario
        FROM usuario_cliente uc
        WHERE uc.Email = ? AND uc.Password = ?
    )
";

    $stmt = $conexion->prepare($sql);

    // CAMBIO CLAVE: En PDO no se usa bind_param("ssss", ...). Se pasan los datos en execute()
    $stmt->execute([$u, $p_cifrado, $u, $p_cifrado]);
    
    // CAMBIO CLAVE: En PDO usamos fetchAll para obtener los resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) === 1) {
        $usuario_data = $resultados[0];
        
        $_SESSION['usuario_autenticado'] = true;
        $_SESSION['nombre_usuario'] = $usuario_data['Nombre'];
        $_SESSION['perfil'] = $usuario_data['Perfil'];
        $_SESSION['idReferencia'] = $usuario_data['ID_Referencia']; 
        $_SESSION['tipo_usuario'] = $usuario_data['Tipo_Usuario'];

        $perfil_usuario = $usuario_data['Perfil'];
        
        if ($usuario_data['Tipo_Usuario'] === 'empleado') {
            if ($perfil_usuario === 'administrador') {
                header('Location: ../../pages/Empleado_Administrador/menuPrincipal_EA.html');
            } elseif ($perfil_usuario === 'dueno') {
                header('Location: ../../pages/Empleado_Dueño/menuPrincipal_ED.php');
            } elseif ($perfil_usuario === 'vendedor') {
                header('Location: ../../pages/Empleado_Vendedor/menuPrincipal_EV.php'); 
            } 
        } elseif ($usuario_data['Tipo_Usuario'] === 'cliente') {
            header('Location: ../../pages/ClientePotencial/catalogo.html'); 
        }
        exit(); 
    } else {
        header("location:../../pages/login/loginEmpleados.php?error=credenciales_invalidas&usuario={$user_encoded}");
        exit();
    }


} catch (Exception $e) {
    // COMENTA ESTA LÍNEA TEMPORALMENTE:
    // header("location:../../pages/login/loginEmpleados.php?error=db_error&usuario={$user_encoded}");
    
    // AGREGA ESTO PARA VER EL ERROR:
    echo "<h1>Error de Conexión Detectado</h1>";
    echo "Detalle técnico: " . $e->getMessage();
    exit();
}
?>