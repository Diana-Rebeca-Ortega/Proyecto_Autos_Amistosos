<?php
session_start();

// 1. Inclusión del DAO (Ajusta la ruta si es necesario)
include_once('../../controllers/ABCC_Automovil/AutomovilDAO.php'); 
// La inclusión del ReporteDAO es opcional aquí, pero no hace daño.
include_once('../../controllers/Reportes/ReporteDAO.php'); 

$mensaje = '';
$exito = false;
$idAutomovil = $_POST['idAutomovil'] ?? null;

// Solo procedemos si la solicitud es POST y tenemos el ID del auto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idAutomovil) {
    
    // 2. Recolección de datos (AJUSTADO A TU ESQUEMA SIN 'Marca')
    // Debes incluir todos los campos que tu sentencia UPDATE en el DAO espera.
   $datosAuto = [
        'idAutomovil' => $idAutomovil,      
        'Modelo' => $_POST['modelo'] ?? '', 
        'Precio_Lista' => $_POST['precio_lista'] ?? 0.00, 
        'FechaFabricacion' => $_POST['fecha_fabricacion'] ?? '', 
        'Color' => $_POST['color'] ?? '', 
        'Kilometraje_Entrega' => $_POST['kilometraje_entrega'] ?? 0,         
        'Condicion' => $_POST['condicion'] ?? '',           
        'Tipo_Carroceria' => $_POST['tipo_carroceria'] ?? '',         
        'Estado' => $_POST['estado'] ?? '',
    ];

    try {
        $automovilDAO = new AutomovilDAO();
        
        // 3. Ejecución del UPDATE
        // Si el precio cambió, esta acción disparará automáticamente el Trigger SQL.
        $exito = $automovilDAO->actualizarAutomovil($datosAuto);
        
        if ($exito) {
            $mensaje = "✅ ¡Automóvil actualizado con éxito! El cambio de precio fue registrado por el **TRIGGER**.";
        } else {
            // Esto captura un fallo en la ejecución del SQL
            $mensaje = "❌ Error: No se pudo completar la actualización. Es posible que el ID no exista o falten datos.";
        }
    } catch (Exception $e) {
        // Esto captura un error grave de PHP o conexión
        $mensaje = "❌ Error de sistema: " . $e->getMessage();
    }
} else {
    $mensaje = "Advertencia: Acceso no autorizado o datos incompletos.";
}

// 4. Redirección al formulario para mostrar el resultado
// Se redirige con el ID del auto y el mensaje codificado en la URL.
$urlRedireccion = "formulario_cambiosAutomovil.php?id=" . urlencode($idAutomovil) . "&msg=" . urlencode($mensaje);
header("Location: " . $urlRedireccion);
exit;

?>