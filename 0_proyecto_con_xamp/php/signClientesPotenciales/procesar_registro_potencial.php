<?php
// 1. Configuración y Clases Necesarias

session_start();

// Rutas de Inclusión: AJUSTA ESTAS RUTAS SEGÚN TU ESTRUCTURA DE CARPETAS REAL
// Asumo que UsuarioDAO está en el mismo directorio que ClientePotencialDAO.
include_once('ClientePotencialDAO.php'); 
include_once('UsuarioDAO.php'); // Asegúrate de que este archivo exista y contenga la clase UsuarioDAO

$cliente_potencial_dao = new ClientePotencialDAO();
$usuario_dao = new UsuarioDAO(); 

// 2. Captura de Datos POST
$accion = $_POST['accion'] ?? null; 
$nombre = $_POST['nombre'] ?? '';
$apellido1 = $_POST['apellido1'] ?? '';
$apellido2 = $_POST['apellido2'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$email = $_POST['email'] ?? '';
$fuente = $_POST['fuente'] ?? ''; 
$password = $_POST['password'] ?? '';
$confirmar_password = $_POST['confirmar_password'] ?? '';

// Variables para pasar los datos antiguos en caso de error
$redirect_data = 
    "&nombre=" . urlencode($nombre) .
    "&apellido1=" . urlencode($apellido1) .
    "&email=" . urlencode($email);

if ($accion === 'insertar') {
    
    // 3. VALIDACIÓN DEL LADO DEL SERVIDOR (Campos Requeridos)
    if (empty($nombre) || empty($apellido1) || empty($email) || empty($fuente)) {
        header("Location: paginaPrincipal_ClientePotencial.html?status=campos_vacios" . $redirect_data);
        exit;
    }

    // 4. VALIDACIÓN DE CONTRASEÑAS
    if (empty($password) || $password !== $confirmar_password) {
        // Podrías crear un status='error_password' en el HTML para un mensaje más específico
        header("Location: paginaPrincipal_ClientePotencial.html?status=registro_fallido" . $redirect_data); 
        exit;
    }
    
    $password_hashed = sha1($password);

    // 5. INSERCIÓN TRANSACCIONAL (Doble Inserción)

    // A. Insertar datos de contacto en Cliente_Potencial
    // La función insertar AHORA devuelve el ID insertado o FALSE si falla
    $id_cliente_potencial = $cliente_potencial_dao->insertar(
        $nombre, $apellido1, $apellido2, $direccion, $email, $fuente
    );

    if ($id_cliente_potencial !== false) {
        // B. Si la inserción del Cliente Potencial fue exitosa (recibimos un ID)
        
        // Insertar el usuario con credenciales y vincularlo al ID del cliente potencial
        $res_usuario = $usuario_dao->insertarUsuario(
            $email, $password_hashed, $id_cliente_potencial
        );
        
        if ($res_usuario) {
            // Éxito Total: Ambos registros se crearon.
            // **IMPORTANTE**: Aquí deberías iniciar la sesión del usuario ($email)
            // y redirigirlo a su Dashboard/Catálogo.
            // Por simplicidad, usamos el mensaje de éxito original:
            header('Location: paginaPrincipal_ClientePotencial.html?status=registro_exitoso'); 
            exit;
        } else {
            // Fallo en Usuario: Se registró el potencial, pero falló la cuenta.
            // En un sistema real, se debería hacer un ROLLBACK y eliminar el potencial creado.
            // Por ahora, solo reportamos el error:
            // Nota: Podrías querer eliminar $cliente_potencial_dao->eliminar($id_cliente_potencial); aquí
            header('Location: paginaPrincipal_ClientePotencial.html?status=registro_fallido' . $redirect_data);
            exit;
        }

    } else {
        // Fallo en Cliente Potencial (ej. error de base de datos)
        header('Location: paginaPrincipal_ClientePotencial.html?status=registro_fallido' . $redirect_data);
        exit;
    }
} 

// Redirección por defecto si no se especificó la acción o si la lógica de arriba se completa
header('Location: paginaPrincipal_ClientePotencial.html');
exit();
?>