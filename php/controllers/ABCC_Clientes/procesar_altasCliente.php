<?php
session_start();

// Redirige si el usuario no está autenticado
if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ../cerrar_sesion.php");
    exit;
}

// Incluir el DAO del cliente
include_once('./clienteDAO.php'); 

// ---------------------------------------------------------------------------------
// FUNCIÓN DE LIMPIEZA
// ---------------------------------------------------------------------------------
function limpiar_entrada($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // Aplicar htmlspecialchars para prevenir XSS
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); 
    return $data;
}

// ---------------------------------------------------------------------------------
// CAPTURA, LIMPIEZA Y VALIDACIÓN
// ---------------------------------------------------------------------------------
$accion = $_POST['accion'] ?? null; 
$errores = [];

// 1. Captura y limpieza de datos
$nombre = limpiar_entrada($_POST['nombre'] ?? '');
$apellido1 = limpiar_entrada($_POST['apellido1'] ?? '');
$apellido2 = limpiar_entrada($_POST['apellido2'] ?? '');
$direccion = limpiar_entrada($_POST['direccion'] ?? '');
$telefono = limpiar_entrada($_POST['telefono'] ?? '');
$email = limpiar_entrada($_POST['email'] ?? '');

// 2. Reglas de Validación

// Campos obligatorios (Nombre, Apellido1, Email, Dirección, Teléfono)
if (empty($nombre)) {
    $errores[] = "El nombre es obligatorio.";
}
if (empty($apellido1)) {
    $errores[] = "El primer apellido es obligatorio.";
}
if (empty($email)) {
    $errores[] = "El email es obligatorio.";
}
if (empty($direccion)) {
    $errores[] = "La dirección es obligatoria.";
}
if (empty($telefono)) {
    $errores[] = "El teléfono es obligatorio.";
}

// Validar formato de Email (si se proporcionó)
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato de email no es válido.";
}

// Validar Teléfono: Solo números, entre 8 y 15 dígitos (ajusta el rango según tu BD)
if (!empty($telefono) && !preg_match('/^[0-9]{8,15}$/', $telefono)) {
    $errores[] = "El teléfono debe contener solo entre 8 y 15 dígitos (solo números).";
}


// ---------------------------------------------------------------------------------
// MANEJO DE ERRORES DE VALIDACIÓN
// ---------------------------------------------------------------------------------
if (!empty($errores)) {
    // 3. Si hay errores: guardar errores y datos temporales, y redirigir
    $_SESSION['errores_cliente'] = $errores;
    // Guardar todos los datos POST para precargar el formulario
    $_SESSION['datos_cliente_temp'] = $_POST; 
    
    // Asumo que el formulario se llama 'formulario_registrarCliente.php'
    header('Location: ./formularios/formulario_registrarCliente.php?status=validacion_error');
    exit();
}


// Inicializa el objeto de la clase Cliente
$cliente_obj = new clienteDAO();

// Lógica de inserción (Alta)
if ($accion === 'insertar') {
    
    // Llama al método de inserción de la clase Cliente
    $res = $cliente_obj->insertar($nombre, $apellido1, $apellido2, $direccion, $telefono, $email);
    
    if ($res) {
        // Éxito: Redireccionar al listado de clientes (o al menú principal)
        header('Location: ../../pages/Empleado_Vendedor/menuPrincipal_EV.php?status=cliente_alta_ok'); 
        exit();
    } else {
        // Fallo en la base de datos: Guardar error y redirigir
        $_SESSION['errores_cliente'] = ["Error en la base de datos: No se pudo registrar al cliente. Verifica la conexión o las restricciones de la BD."];
        $_SESSION['datos_cliente_temp'] = $_POST; 
        header('Location: ./formularios/formulario_registrarCliente.php?status=bd_error');
        exit();
    }
} else {
    // Si la acción no es 'insertar'
    header('Location: ../../pages/Empleado_Vendedor/menuPrincipal_EV.php?status=error_accion'); 
    exit();
}

?>