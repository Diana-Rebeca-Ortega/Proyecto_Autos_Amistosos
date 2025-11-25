<?php
include_once('./clienteDAO.php');  
$cliente_obj = new clienteDAO(); 
// 2. Captura la acción y el ID. La acción puede venir por POST (Alta) o GET (Baja)
$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null; // El ID para eliminar o actualizar viene por GET o POST
// 3. Procesamiento de la acción
switch ($accion) {
    case 'eliminar':
        // ==========================================
        // Lógica de BAJA (ELIMINAR)
        // ==========================================
        if ($id) {
            $res = $cliente_obj->eliminar($id); // Llama al método que creamos en Cliente.php
            
            if ($res) {
                 header('Location: ./formularios/formulario_eliminarCliente.php?status=baja_ok');
            } else {
                 // Si falla, podría ser porque tiene ventas asociadas (Foreign Key)
                 header('Location:./formularios/formulario_eliminarCliente.php?status=baja_error'); 
            }
        } else {
            header('Location: ./formularios/formulario_eliminarCliente.php?status=error_id');
        }
        break;

    default:
        // Si no se especifica ninguna acción válida
        header('Location: ./formularios/formulario_eliminarCliente.php?status=error_accion');
        break;
}

exit(); 
?>