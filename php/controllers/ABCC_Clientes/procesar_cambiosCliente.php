<?php
include_once('./clienteDAO.php');
$cliente_obj = new clienteDAO();

// 1. **Definir la ACCIÓN** (puede ser 'eliminar' o 'actualizar').
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

// 2. Definir el ID. El ID para eliminar viene por GET. El ID para actualizar (y el resto de campos) vienen por POST.
$id = $_POST['id'] ?? $_GET['id'] ?? null; 

// 3. Procesamiento de la acción
switch ($accion) {
        case 'actualizar':
        // ==========================================
        // Lógica de CAMBIOS (ACTUALIZAR)
        // ==========================================
        // 4. Capturar el resto de datos por POST
        $nombre = $_POST['nombre'] ?? '';
        $apellido1 = $_POST['apellido1'] ?? '';
        $apellido2 = $_POST['apellido2'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $email = $_POST['email'] ?? '';

        // 5. Validar que al menos el ID y el Nombre/Apellido no estén vacíos
        if ($id && !empty($nombre) && !empty($apellido1)) {
            
            // 6. Llamar al método modificar del DAO, pasando todos los datos
            $res = $cliente_obj->actualizar(
                $id, $nombre, $apellido1, $apellido2, 
                $direccion, $telefono, $email
            ); 
            if ($res) {
                // Redirigir al listado con mensaje de éxito de actualización
                header('Location: ./formularios/formulario_cambiosCliente.php?status=modificacion_ok');
            } else {
                // Redirigir al listado con mensaje de error de base de datos
                header('Location: ./formularios/formulario_cambiosCliente.php?status=modificacion_error'); 
            }
        } else {
            // Error si faltan campos esenciales
            header('Location: ./formularios/formulario_cambiosCliente.php?status=campos_vacios_mod');
        }
break; // Fin del case 'actualizar'
default:
// Si no se especifica ninguna acción válida
header('Location: ./formularios/formulario_cambiosCliente.php?status=error_accion');
break;
}
exit(); 
?>